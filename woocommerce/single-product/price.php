<?php

/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $product;
$is_active = $product->get_meta('product_source') == 'hubx' ? $product->get_meta('is_active') === '1' : true;

?>
<p class="<?php echo esc_attr(apply_filters('woocommerce_product_price_class', 'price')); ?>"><?php echo $product->get_price_html(); ?></p>
<?php
$quantity = $product->get_stock_quantity();

if ($quantity > 0 && $is_active) {
	echo "<p class='text text-black price__stock' >נותרו עוד $quantity מוצרים במלאי</p>";
} elseif ($quantity == null  || !$is_active) {
	echo "";
} else {
	echo "<p class='text text-black price__stock' >לא נותרו עוד מוצרים במלאי</p>";
}

?>