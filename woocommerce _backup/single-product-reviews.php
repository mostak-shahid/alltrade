<?php

/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

defined('ABSPATH') || exit;

global $product;

if (!comments_open()) {
	return;
}

?>
<div id="reviews" class="woocommerce-Reviews">
	<div id="comments">
		<div class="review-header-title-container">
			<h2 class="woocommerce-Reviews-title">
				<?php
				$count = $product->get_review_count();
				if ($count && wc_review_ratings_enabled()) {
					/* translators: 1: reviews count 2: product name */
					echo 'חוות דעת';
				} else {
					esc_html_e('Reviews', 'woocommerce');
				}
				?>
			</h2>
		</div>



		<div class="woocommerce-product-rating">
			<p>
				<?php woocommerce_template_loop_rating(); ?>
			</p>
			<p>
				<?php echo $count ?> חוות דעת
			</p>

		</div>


		<?php if (have_comments()) : ?>
			<ul class="commentlist">
				<?php wp_list_comments(apply_filters('woocommerce_product_review_list_args', array('callback' => 'woocommerce_comments'))); ?>
			</ul>

			<?php
			if (get_comment_pages_count() > 1 && get_option('page_comments')) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links(
					apply_filters(
						'woocommerce_comment_pagination_args',
						array(
							'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
							'next_text' => is_rtl() ? '&larr;' : '&rarr;',
							'type'      => 'list',
						)
					)
				);
				echo '</nav>';
			endif;
			?>
		<?php else : ?>
		<?php endif; ?>
	</div>

	<div class="clear"></div>
</div>