<?php
function sap_order_try_again_enqueue_custom_filter_style()
{
    $directory = get_template_directory_uri();
    wp_enqueue_style('custom-sap_order_try_again-style', $directory . '/components/sap-order-try-again/style.css');
    wp_enqueue_script('sap_order_try_again-mobile-script', $directory . '/components/sap-order-try-again/script.js', array('jquery'), '1.0.0', true);

    wp_localize_script('sap_order_try_again-mobile-script', 'sticky_globals', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('admin_enqueue_scripts', 'sap_order_try_again_enqueue_custom_filter_style');


function sap_order_try_again_handle($order_items_code_arr, $order)
{
    $sap_handler = new SapOrderFlow();

    $is_empty = false;
    foreach ($order_items_code_arr as $item) {
        if ($item == '') {
            $is_empty = true;
        }
    }
    if ($is_empty) {
        $sap_handler->update_sap_order_meta_fields('faild', '', $order, 'למוצר אחד או יותר חסר קוד סאפ');
        return ['status' => 'faild', 'body' => 'למוצר אחד או יותר חסר קוד סאפ'];
    }

    $order_sum = $sap_handler->create_customer_order($order_items_code_arr, $order);

    if ($order_sum['status'] == 'ok') {
        $sap_handler->update_sap_order_meta_fields($order_sum['status'], $order_sum['body'], $order);
    } else {
        $sap_handler->update_sap_order_meta_fields($order_sum['status'], '', $order, $order_sum['body']);
    }
    return $order_sum;
}

function sap_order_try_again()
{
    if (isset($_POST)) {
        $order_id = $_POST['order_id'];
        $order = wc_get_order($order_id);

        $order_items = $order->get_items();
        $order_items_code_arr = [];

        foreach ($order_items as $item_id => $item) {
            $product_id = $item->get_product_id();
            $item_sap_code = $item->get_meta('sap_item_id');
            $order_items_code_arr[$product_id] = $item_sap_code;
        }

        $response = sap_order_try_again_handle($order_items_code_arr, $order);
        wp_send_json_success($response);
    }
}
add_action('wp_ajax_sap_order__request', 'sap_order_try_again');
add_action('wp_ajax_nopriv_sap_order__request', 'sap_order_try_again');
