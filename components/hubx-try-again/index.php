<?php
function hubx_try_again_enqueue_custom_filter_style()
{
    $directory = get_template_directory_uri();
    wp_enqueue_style('custom-hubx_try_again-style', $directory . '/components/hubx-try-again/style.css');
    wp_enqueue_script('hubx_try_again-mobile-script', $directory . '/components/hubx-try-again/script.js', array('jquery'), '1.0.0', true);

    wp_localize_script('hubx_try_again-mobile-script', 'sticky_globals', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('admin_enqueue_scripts', 'hubx_try_again_enqueue_custom_filter_style');


function hubx_try_again()
{
    if (isset($_POST)) {
        $order_id = $_POST['order_id'];
        $product_id = intval($_POST['product_id']);
        $send_to_hubx = new Handler_after_order();
        $order = wc_get_order($order_id);

        foreach ($order->get_items() as $item_id => $item) {
            if ($item->get_id() == $product_id) {
                $response = $send_to_hubx->hubx_post_product_try_again($item, $order);
                wp_send_json_success($response);
            }
        }
    }
}
add_action('wp_ajax_hubx__request', 'hubx_try_again');
add_action('wp_ajax_nopriv_hubx__request', 'hubx_try_again');


add_filter('manage_edit-shop_order_columns', 'add_custom_shop_order_column', 20);
function add_custom_shop_order_column($columns)
{
    $new_columns = $columns;

    $new_columns = array_slice($columns, 0, -2, true) +
        array('new_line' => __('פירוט', 'woocommerce')) +
        array_slice($columns, -2, null, true);

    $new_columns = array_slice($columns, 0, -3, true) +
        array('new_line_1' => __('סטטוס Hubx', 'woocommerce')) +
        array_slice($new_columns, -3, null, true);

    $new_columns = array_slice($columns, 0, -4, true) +
        array('new_line_2' => __('סטטוס SAP', 'woocommerce')) +
        array_slice($new_columns, -4, null, true);


    return $new_columns;
}

add_action('manage_shop_order_posts_custom_column', 'custom_order_list_column_content');
function custom_order_list_column_content($column)
{
    global $post;

    if ('new_line' === $column) {
        // Get order object
        $order = wc_get_order($post->ID);
        $items = $order->get_items();
        $error_list = [];

        foreach ($items as $item) {
            $is_hubx_success = $item->get_meta('is_hubx_success');
            $meta_data = get_post_meta($item->get_product_id());

            if (isset($meta_data["product_source"])) {
                if ($meta_data["product_source"][0] != 'hubx') continue;
            }

            if (!$is_hubx_success) array_push($error_list, $is_hubx_success);
        };
        $output = "מוצרים (" . count($items) . ')' . " שגיאות (" . count($error_list) . ')';
        $order->update_meta_data('sap_order_status', 'HEY');
        $order->save();


        echo $output;
    }

    if ('new_line_1' === $column) {
        $order = wc_get_order($post->ID);
        $items = $order->get_items();
        $error_list = [];

        $is_one_hubx = false;

        foreach ($items as $item) {
            $is_hubx_success = $item->get_meta('is_hubx_success');
            $meta_data = get_post_meta($item->get_product_id());

            if (isset($meta_data["product_source"])) {
                if ($meta_data["product_source"][0] != 'hubx') continue;
            }

            $is_one_hubx = true;

            $is_hubx_success = $item->get_meta('is_hubx_success');
            if (!$is_hubx_success) array_push($error_list, $is_hubx_success);
        };

        if ($is_one_hubx) {
            $output = !count($error_list) ? 'Hubx Success' : 'Hubx Faild';
            $outpul_class = !count($error_list) ? 'hubx-success' : 'hubx-faild';
        } else {
            $output = 'No hubx product';
            $outpul_class = 'hubx-none';
        }

        $order->save();

        echo "<p class='$outpul_class'>";
        echo $output;
        echo "</p>";
    }

    if ('new_line_2' === $column) {

        $order = wc_get_order($post->ID);
        $meta = get_post_meta($post->ID, 'is_sap_success') ? get_post_meta($post->ID, 'is_sap_success')[0] : '';

        $output = $meta == 'ok' ? 'SAP Success' : 'SAP Faild';
        $outpul_class = $meta == 'ok' ? 'hubx-success' : 'hubx-faild';
        $order->save();
        echo "<p class='$outpul_class'>";
        echo $output;
        echo "</p>";
    }
}
