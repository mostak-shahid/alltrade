<?php
function np_product_price_calc()
{
    $directory = get_template_directory_uri();
    wp_enqueue_style('product_price_calc-style', $directory . '/components/product-price-calc/style.css');
    wp_enqueue_script('product_price_calc-script', $directory . '/components/product-price-calc/script.js', array('jquery'), '1.0.0', true);
    wp_localize_script('product_price_calc-script', 'sticky_globals', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('admin_enqueue_scripts', 'np_product_price_calc');

function is_value_in_array($arr, $value)
{
    $is_in_arr = false;
    foreach ($arr as $item) {
        if (strpos($value, $item)) {
            $is_in_arr = true;
        }
    }
    return $is_in_arr;
};

function product_price_calculation($product_current_price, $product_condition, $product_type)
{
    $formulas = [
        "laptop" =>
        45,
        "sff_laptop" =>
        100,
        "tower_desktop"
        =>
        150,
    ];

    $product_current_price += $formulas[$product_type];

    $statuses_new = ['New', 'New Factory Sealed'];
    $statuses_renew = ['Used', 'Refurbished', 'Renewed'];

    $is_new = is_value_in_array($statuses_new, $product_condition);
    $is_renew = is_value_in_array($statuses_renew, $product_condition);

    $condition_percent = 5;
    if ($is_new) {
        $condition_percent = 1.25;
    }
    if ($is_renew) {
        $condition_percent = 5;
    };
    $product_current_price = $product_current_price * (1 + ($condition_percent / 100));

    // add profit % for each product
    $profit = 20;

    $last_product_price =
        $product_current_price * (1 + ($profit / 100));

    return  $last_product_price;
}

function np_product_calc()
{

    if (isset($_POST)) {

        $product_id = intval($_POST['product_id']);
        $product_type = $_POST['product_type'];

        if ($product_type == 'custom_pricing') {
        }

        $product = wc_get_product($product_id);
        $variations = $product->get_available_variations();

        $variations_list = array_map(function ($arr) {
            $varaition = wc_get_product($arr['variation_id']);
            return $varaition;
        }, $variations);

        foreach ($variations_list as $variation_product) {
            $initial_price = intval(get_post_meta($variation_product->get_parent_id(), 'original_price_shekel')[0]);
            $condition = get_post_meta($variation_product->get_parent_id(), 'Condition')[0];
            $new_price = product_price_calculation($initial_price, $condition, $product_type);

            $new_price_formatted = number_format($new_price, 2, '.', '');

            $variation_product->set_regular_price($new_price_formatted);
            $variation_product->save();
        };

        $price_obj = [
            'initial_price' => $initial_price,
            'new_price' => $new_price_formatted

        ];

        wp_send_json_success($price_obj);
    }
}
add_action('wp_ajax_product__request', 'np_product_calc');
add_action('wp_ajax_nopriv_product__request', 'np_product_calc');
