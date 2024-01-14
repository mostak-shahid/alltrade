<?php

/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

global $product;

if ($product->is_in_stock()) { ?>
	<div class="woocommerce-variation-add-to-cart variations_button">
		<?php do_action('woocommerce_before_add_to_cart_button'); ?>

		<p class="text text-black text-sm font-medium">כמות</p>

		<div class="product-quantity-container">
			<button class="btn-quantity btn-add-quantity">+</button>

			<?php
			do_action('woocommerce_before_add_to_cart_quantity');

			woocommerce_quantity_input(
				array(
					'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
					'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
					'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
				)
			);

			?>

			<button class="btn-quantity btn-remove-quantity">-</button>
		</div>

		<?php
		do_action('woocommerce_after_add_to_cart_quantity');
		?>

		<button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button ya__single-add-to-cart-btn button alt ya__btn-buy-now-btn<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>">קנה עכשיו</button>
		<button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button ya__single-buy-now-btn button alt<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>

		<?php do_action('woocommerce_after_add_to_cart_button'); ?>

		<input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>" />
		<input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>" />
		<input type="hidden" name="variation_id" class="variation_id" value="0" />
	</div>
<?php } else {
	echo '<p>מוצר זה אזל מהמלאי ולא ניתן להזמנה כרגע </p>';
} ?>