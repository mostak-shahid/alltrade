<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

	<div class="summary entry-summary">

		<div class="right-side-product">

			<?php
			/**
			 * Hook: woocommerce_before_single_product_summary.
			 *
			 * @hooked woocommerce_show_product_sale_flash - 10
			 * @hooked woocommerce_show_product_images - 20
			 */
			do_action('woocommerce_before_single_product_summary');
			?>

			<div class="custom-reviews custom-reviews__desktop">
				<?php
				if (comments_open()) {
					comments_template('woocommerce/single-product-reviews');
				}
				?>
				<div class="custom-reivews__social-share">
					<div class="custom-reivews__social-share-title">
						<h3>שיתוף ברשתות חברתיות</h3>
					</div>
					<div class="custom-reivews__social-share-icons">
						<a class="custom-reivews__social-share-icon" href="">
							<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/facebook_white.svg'); ?>
						</a>
						<a class="custom-reivews__social-share-icon" href="">
							<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/whatsapp.svg'); ?>

						</a>
						<a class="custom-reivews__social-share-icon" href="">
							<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/facebook_white.svg'); ?>

						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="single-product-container-header">

			<?php
			/**
			 * Hook: woocommerce_single_product_summary.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */
			do_action('woocommerce_single_product_summary');
			?>
		</div>
	</div>
	<?php
	$custom_field_list = array(
		array(
			'title' => 'אחריות',
			'meta' => 'Warranty'
		),
		array(
			'title' => 'מצב',
			'meta' => 'Condition'
		),
		array(
			'title' => 'אריזה',
			'meta' => 'Packaging'
		),
		array(
			'title' => 'מדינת שילוח',
			'meta' => 'exw'
		),
		array(
			'title' => 'דגם מעבד',
			'meta' => 'processor'
		),
		array(
			'title' => 'נפח זיכרון ראם',
			'meta' => 'ram'
		),
		array(
			'title' => 'נפח דיסק קשיח',
			'meta' => 'storage'
		),
		array(
			'title' => 'מערכת הפעלה',
			'meta' => 'operation-system'
		),
		array(
			'title' => 'גודל מסך',
			'meta' => 'screen-size'
		),
		array(
			'title' => 'כרטיס מסך',
			'meta' => 'gpu'
		),
		array(
			'title' => 'ברנד',
			'meta' => 'brand'
		),
		array(
			'title' => 'מודל',
			'meta' => 'model'
		),
		array(
			'title' => 'משקל',
			'meta' => 'weight'
		),
		array(
			'title' => 'צבע',
			'meta' => 'color'
		),
		array(
			'title' => 'מידות',
			'meta' => 'Measurement'
		),
		array(
			'title' => 'יציאות',
			'meta' => 'exits'
		),
		array(
			'title' => 'מצלמה',
			'meta' => 'camera'
		),
		array(
			'title' => 'מסך',
			'meta' => 'screen'
		),
		array(
			'title' => 'טאצ׳',
			'meta' => 'touch'
		),
		array(
			'title' => 'גודל',
			'meta' => 'size'
		),
		array(
			'title' => 'סוג מסך',
			'meta' => 'screen_type'
		),
	);
	?>
	<div class="technical-container__wrapper">
		<div class="technical-container">
			<div class="technical-container__table-header">
				<h2>סוג מוצר</h2>
			</div>
			<div class="technical-container__table">
				<?php
				foreach ($custom_field_list as $key => $value) {
					if (empty($product->get_meta($value['meta']))) continue;
					else {
				?>
						<div class="<?php echo $key < 6 ? 'technical-container__right-col' : 'technical-container__left-col' ?>">
							<div class="technical-container__data">
								<p class="technical-container___data-property"><?php echo $value['title'] ?></p>
								<p class="technical-container___data-value"><?php echo $product->get_meta($value['meta'])  ?></p>
							</div>
						</div>
				<?php
					}
				}  ?>
			</div>
		</div>
	</div>

	<div class="custom-reviews custom-reviews__mobile">
		<?php
		if (comments_open()) {
			comments_template('woocommerce/single-product-reviews');
		}
		?>
		<div class="custom-reivews__social-share">
			<div class="custom-reivews__social-share-title">
				<h3>שיתוף ברשתות חברתיות</h3>
			</div>
			<div class="custom-reivews__social-share-icons">
				<a class="custom-reivews__social-share-icon" href="">
					<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/facebook_white.svg'); ?>
				</a>
				<a class="custom-reivews__social-share-icon" href="">
					<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/whatsapp.svg'); ?>

				</a>
				<a class="custom-reivews__social-share-icon" href="">
					<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/facebook_white.svg'); ?>

				</a>
			</div>
		</div>
	</div>


	<?php
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => 8,
		'orderby' => 'rand',
		'post__not_in' => array(get_the_ID()),
		'meta_query' => WC()->query->get_meta_query(),
		'tax_query' => WC()->query->get_tax_query(),
	);



	echo related_products_slider_component($args, 'מוצרים נוספים שעשויים לעניין אתכם');

	?>


	<div class="bottom-rectangle"></div>







	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action('woocommerce_after_single_product_summary');
	?>

</div>





<?php do_action('woocommerce_after_single_product'); ?>