<?php

/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined('ABSPATH') || exit;

?>
<div id="mini-cart">
	<div class="top">
		<h2 class="title-mini-cart">ההזמנה שלי</h2>
		<p class="total">סה"כ <?php echo getNumberOfItemsInTheCart(); ?> מוצרים</p>
	</div>

	<?php if (!WC()->cart->is_empty()) : ?>

		<ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr($args['list_class']); ?>">
			<?php
			do_action('woocommerce_before_mini_cart_contents');

			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
				$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

				if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
					$productName = getVariationName($_product, $cart_item, $cart_item_key);
					$variation_name = $productName->variation_name;

					$thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
					$product_price     = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
					$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
			?>
					<li class="woocommerce-mini-cart-item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">
						<?php
						echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							'woocommerce_cart_item_remove_link',
							sprintf(
								'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
								esc_url(wc_get_cart_remove_url($cart_item_key)),
								esc_attr__('Remove this item', 'woocommerce'),
								esc_attr($product_id),
								esc_attr($cart_item_key),
								esc_attr($_product->get_sku())
							),
							$cart_item_key
						);
						?>
						<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
						?>

						<div class="details">
							<h3 class="title-product-mini-cart"><?php echo wp_kses_post($productName->only_product_name); ?></h3>
							<p class="price-product-mini-cart"><?php echo wc_get_price_by_format($cart_item['line_subtotal'], false); ?> | <?php echo $cart_item['quantity'] . ' ' . $variation_name; ?> </p>
						</div>

						<?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
						?>
						<?php /* echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key );*/ // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
						?>
						<?php
						if ($_product->is_sold_individually()) {
							echo sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_key);
						}
						?>

					</li>
			<?php
				}
			}

			do_action('woocommerce_mini_cart_contents');
			?>
		</ul>

		<div class="bottom-mini-cart">
			<h4 class="total-title"> </h4>
			<p class="total-price"><?php echo WC()->cart->get_cart_subtotal(); ?></p>
			<?php echo '<a class="to-checkout-btn" href="' . esc_url(wc_get_checkout_url()) . '" class="button checkout wc-forward">' . 'המשך לקופה' . '</a>'; ?>
		</div>

		<p class="note">*סכום סל הינו משוערך, הסכום לחיוב יקבע לאחר שקילת המוצרים וחשבונית סופית תוצר למשלוח.</p>

	<?php else : ?>

		<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e('No products in the cart.', 'woocommerce'); ?></p>

	<?php endif; ?>

	<?php do_action('woocommerce_after_mini_cart'); ?>

</div>