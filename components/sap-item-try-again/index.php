<?php
function sap_try_again_enqueue_custom_filter_style()
{
    $directory = get_template_directory_uri();
    wp_enqueue_style('custom-sap_try_again-style', $directory . '/components/sap-item-try-again/style.css');
    wp_enqueue_script('sap_try_again-mobile-script', $directory . '/components/sap-item-try-again/script.js', array('jquery'), '1.0.0', true);

    wp_localize_script('sap_try_again-mobile-script', 'sticky_globals', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('admin_enqueue_scripts', 'sap_try_again_enqueue_custom_filter_style');



function sap_product_try_again($product_id, $order, $order_item_id)
{
    $sap_handler = new SapOrderFlow();
    $res = $sap_handler->create_sap_item($order, $product_id);
    if ($res['status'] == 'ok') {
        $sap_handler->update_sap_item_meta_fields($order->get_item($order_item_id), $res['status'], 'נשלח בהצלחה', $res['item']);
    }
    return $res;
}

function sap_try_again()
{
    if (isset($_POST)) {
        $order_id = $_POST['order_id'];
        $product_id = intval($_POST['product_id']);
        $order = wc_get_order($order_id);

        foreach ($order->get_items() as $item_id => $item) {
            if ($item->get_id() == $product_id) {
                $response = sap_product_try_again($item->get_product_id(), $order, $item_id);
                wp_send_json_success($response);
            }
        }
    }
}
add_action('wp_ajax_sap__request', 'sap_try_again');
add_action('wp_ajax_nopriv_sap__request', 'sap_try_again');
