<?php

// Remove the default WooCommerce sale badge
function remove_default_woocommerce_sale_badge()
{
    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
}
add_action('init', 'remove_default_woocommerce_sale_badge');

function show_custom_badge()
{
    global $product;

    $product_date = strtotime($product->get_date_created());
    $time_limit = strtotime('-7 days');

    $is_recent_product = $product_date >= $time_limit;
    $is_on_sale = $product->is_on_sale();
    $is_stock_less_than_3 = $product->get_stock_quantity() < 3 && $product->get_stock_quantity() !== 0;

    $is_active = $product->get_meta('product_source') == 'hubx' ? $product->get_meta('is_active') === '1' : true;
    $quantity = $product->get_stock_quantity();

    $not_active = !$quantity > 0 || !$is_active;

    $selected_badge = '';

    if ($is_recent_product && !$is_on_sale && !$is_stock_less_than_3 && !$not_active) {
        $selected_badge = get_template_directory_uri() . '/assets/badges/new-badge.png';
    } elseif ($is_on_sale && !$not_active) {
        $selected_badge = get_template_directory_uri() . '/assets/badges/sale-badge.png';
    } elseif ($is_stock_less_than_3 && !$is_on_sale) {
        $selected_badge = get_template_directory_uri() . '/assets/badges/low-stock-badge.png';
    } elseif ($not_active) {
        $selected_badge = get_template_directory_uri() . '/assets/badges/to-be-back.png';
    }

    if ($selected_badge) {
        echo '<div class="custom-badge"><img loading="lazy" src="' . $selected_badge . '" alt="Custom Badge"></div>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'show_custom_badge', 9);
add_action('woocommerce_before_single_product_summary', 'show_custom_badge', 9);
