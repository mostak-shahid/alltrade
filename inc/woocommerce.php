<?php

/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package npcoding
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 * @link https://github.com/woocommerce/woocommerce/wiki/Declaring-WooCommerce-support-in-themes
 *
 * @return void
 */
function alltrade_woocommerce_setup()
{
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 150,
			'single_image_width' => 300,
			'product_grid' => array(
				'default_rows' => 3,
				'min_rows' => 1,
				'default_columns' => 4,
				'min_columns' => 1,
				'max_columns' => 6,
			),
		)
	);
	// add_theme_support('wc-product-gallery-zoom');
	// add_theme_support('wc-product-gallery-lightbox');
	// add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'alltrade_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function alltrade_woocommerce_scripts()
{
	wp_enqueue_style('alltrade-woocommerce-style', get_template_directory_uri() . '/woocommerce.css', array(), _S_VERSION);

	$font_path = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style('alltrade-woocommerce-style', $inline_font);
}
add_action('wp_enqueue_scripts', 'alltrade_woocommerce_scripts');

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function alltrade_woocommerce_active_body_class($classes)
{
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter('body_class', 'alltrade_woocommerce_active_body_class');

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function alltrade_woocommerce_related_products_args($args)
{
	$defaults = array(
		'posts_per_page' => 3,
		'columns' => 3,
	);

	$args = wp_parse_args($defaults, $args);

	return $args;
}
add_filter('woocommerce_output_related_products_args', 'alltrade_woocommerce_related_products_args');

/**
 * Remove default WooCommerce wrapper.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

if (!function_exists('alltrade_woocommerce_wrapper_before')) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function alltrade_woocommerce_wrapper_before()
	{
?>
		<main id="primary" class="site-main">
		<?php
	}
}
add_action('woocommerce_before_main_content', 'alltrade_woocommerce_wrapper_before');

if (!function_exists('alltrade_woocommerce_wrapper_after')) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function alltrade_woocommerce_wrapper_after()
	{
		?>
		</main><!-- #main -->
	<?php
	}
}
add_action('woocommerce_after_main_content', 'alltrade_woocommerce_wrapper_after');

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
<?php
if ( function_exists( 'alltrade_woocommerce_header_cart' ) ) {
alltrade_woocommerce_header_cart();
}
?>
 */

if (!function_exists('alltrade_woocommerce_cart_link_fragment')) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function alltrade_woocommerce_cart_link_fragment($fragments)
	{
		ob_start();
		alltrade_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter('woocommerce_add_to_cart_fragments', 'alltrade_woocommerce_cart_link_fragment');

if (!function_exists('alltrade_woocommerce_cart_link')) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function alltrade_woocommerce_cart_link()
	{
	?>
		<a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'alltrade'); ?>">
			<?php
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n('%d item', '%d items', WC()->cart->get_cart_contents_count(), 'alltrade'),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<span class="amount">
				<?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?>
			</span> <span class="count">
				<?php echo esc_html($item_count_text); ?>
			</span>
		</a>
	<?php
	}
}

if (!function_exists('alltrade_woocommerce_header_cart')) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function alltrade_woocommerce_header_cart()
	{
		if (is_cart()) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
	?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr($class); ?>">
				<?php alltrade_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				$instance = array(
					'title' => '',
				);

				the_widget('WC_Widget_Cart', $instance);
				?>
			</li>
		</ul>
	<?php
	}
}
// Custom add to cart button and more details button

add_filter('woocommerce_loop_add_to_cart_link', 'replace_loop_add_to_cart_button', 10, 2);
function replace_loop_add_to_cart_button($button, $product)
{
	$comment_count = get_comment_count($product->get_id());
	$num_reviews = $comment_count['approved'];
	$rating = $num_reviews ? wc_get_rating_html($product->get_average_rating()) . $num_reviews . " ביקורות" : '';

	$is_in_stock = $product->is_in_stock();
	$is_active = $product->get_meta('product_source') == 'hubx' ? $product->get_meta('is_active') === '1' : true;

	$is_not_available = !$is_active || !$is_in_stock;

	if ($is_not_available) {
		return
			'
		<div class=product-buttons>
		<button data-product-id="' . $product->get_id() . '" class="btn-stock-popup"> עדכנו כשיש מלאי </button>
		<a class="btn-product product-buttons__info-btn" href="' . $product->get_permalink() . '">' . __("פרטים נוספים", "woocommerce") . '</a>
		</div>
		<div class="flex ya__product-review"> 
		' . $rating . ' 
		</div>';
	} else {
		return '
		<div class=product-buttons>
		' . $button . '
		<a class="btn-product product-buttons__info-btn" href="' . $product->get_permalink() . '">' . __("פרטים נוספים", "woocommerce") . '</a>
		</div>
		<div class="flex ya__product-review"> 
		' . $rating . ' 
		</div>
		';
	}
}

remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

// Custom function to display the custom field and product thumbnail within the same div
function my_custom_field_and_thumbnail()
{
	global $product;

	$processor = $product->get_meta('processor');
	$ram = $product->get_meta('ram');
	$storage = $product->get_meta('storage');

	preg_match('/Intel Core (i[57])/i', $processor, $processor_matches);
	preg_match('/\b\d+GB\b/', $ram, $ram_matches);
	preg_match('/\b\d+GB\b/', $storage, $storage_matches);

	echo '<div class="product__thumbnail-tech-container">
	';
	echo woocommerce_get_product_thumbnail();
	?>
	<div class="product__tech-details-container">
		<div class="prodcut__tech-item">
			<p class="text text-black"><?php echo isset($processor_matches[1]) ? $processor_matches[1] : '' ?></p>
		</div>
		<div class="prodcut__tech-item">
			<p class="text text-black"><?php echo isset($storage_matches[0]) ? $storage_matches[0] : '' ?></p>
		</div>
		<div class="prodcut__tech-item">
			<p class="text text-black"><?php echo isset($ram_matches[0]) ? $ram_matches[0] : '' ?></p>
		</div>
		<div class="prodcut__tech-item">
			<p class="text text-black"><?php echo substr($product->get_meta('operation-system'), 0, 3) ?></p>
		</div>
	</div>
<?php
	echo '</div>';
}
add_action('woocommerce_before_shop_loop_item_title', 'my_custom_field_and_thumbnail', 5);

remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

// Add quantity dot
function my_custom_title_and_field()
{
	global $product;
	$quantity = $product->get_stock_quantity();
	$is_active = $product->get_meta('product_source') == 'hubx' ? $product->get_meta('is_active') === '1' : true;


	echo '<h2 class="woocommerce-loop-product__title">';
	echo get_the_title();
	echo '   ';


	if ($quantity && $is_active) {
		echo '<span class="prodcut-title__stock stock-true"></span>';
	} else {
		echo '<span class="prodcut-title__stock stock-false"></span>';
	}

	echo '</h2>';
}
add_action('woocommerce_shop_loop_item_title', 'my_custom_title_and_field', 5);

// Remove the default WooCommerce title function
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);



// Remove related products from single product page
add_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 30);



// ----- SINGLE PRODUCT -----


add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 2);

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);



// add custom field after add to cart btn

// Define a custom function to add content after the "Add to cart" button
function my_custom_content_after_add_to_cart_button()
{
?>
	<div class="single-product-icon-container">
		<div class="single-product-icon">
			<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/local_shipping.svg'); ?>
			<p>משלוח עד הבית: 49 ₪</p>
		</div>
		<div class="single-product-icon">
			<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/computer2.svg'); ?>
			<p>ייתכנו סימני שימוש</p>
		</div>
		<div class="single-product-icon">
			<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/sell.svg'); ?>
			<p>המחיר עד גמר המלאי</p>
		</div>
	</div>

	<div class="single-product-choose-us-container-wrapper">
		<h3>למה לקנות אצלנו?</h3>
		<div class="single-product-choose-us-container">
			<div class="single-product-choose-us-container__single-product-icon">
				<?php echo file_get_contents(get_template_directory_uri() . '/assets/big-icons/secure-payment.svg'); ?>
				<p> תשלום מאובטח </p>
			</div>
			<div class="single-product-choose-us-container__single-product-icon">
				<?php echo file_get_contents(get_template_directory_uri() . '/assets/big-icons/payments.svg'); ?>
				<p>24 תשלומים </p>
			</div>
			<div class="single-product-choose-us-container__single-product-icon">
				<?php echo file_get_contents(get_template_directory_uri() . '/assets/big-icons/support.svg'); ?>
				<p>24/7 מענה טלפוני</p>
			</div>
			<div class="single-product-choose-us-container__single-product-icon">
				<?php echo file_get_contents(get_template_directory_uri() . '/assets/big-icons/warranty.svg'); ?>
				<p>24 חודשי אחריות</p>
			</div>
		</div>
	</div>
	<div class="companies-container__youtube">
		<iframe src="https://www.youtube.com/embed/{VIDEO_ID}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</div>


	<?php
}

// Add the custom function to the woocommerce_after_add_to_cart_button action hook
add_action('woocommerce_after_add_to_cart_button', 'my_custom_content_after_add_to_cart_button');


// Define a custom function to get the related products on the single product page
function my_custom_related_products_query($args, $product_id)
{
	// Modify the query args as needed
	$args['posts_per_page'] = 4;
	$args['orderby'] = 'rand';
	return $args;
}

// Add the custom function to the woocommerce_product_related_posts_query filter hook
add_filter('woocommerce_product_related_posts_query', 'my_custom_related_products_query', 10, 2);


// Remove tab of reviews
add_filter('woocommerce_product_tabs', 'remove_reviews_from_tabs', 98);

// Add custom reviews
function remove_reviews_from_tabs($tabs)
{
	unset($tabs['reviews']);
	unset($tabs['description']);
	unset($tabs['additional_information']);

	return $tabs;
}

// -- Reviews Config -- \\

// remove the image

function remove_review_avatar()
{
	remove_action('woocommerce_review_before', 'woocommerce_review_display_gravatar', 10);
}
add_action('init', 'remove_review_avatar');


// Remove default stock 

add_filter('woocommerce_get_stock_html', 'remove_woocommerce_stock_html');
function remove_woocommerce_stock_html($html)
{
	return '';
}

// move breadcrumbs position 

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

add_action('woocommerce_archive_description', 'woocommerce_breadcrumb', 20);



// Remove sidebar from WooCommerce checkout page
function remove_checkout_sidebar()
{
	if (is_checkout()) {
		remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
	}
}
add_action('wp', 'remove_checkout_sidebar');


// Remove deafult pagination from product archvie 
remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);


// hanlde after order trigger api request

require_once get_template_directory() . '/inc/after-order/trigger-after-order.php';
require_once get_template_directory() . '/cron-jobs/order-stock-update.php';
require_once get_template_directory() . '/cron-jobs/new-product-insert-hubx.php';

// Register new status
function register_wait_call_order_status()
{
	register_post_status('wc-hubx-failed', array(
		'label'                     => 'Hubx Failed',
		'public'                    => true,
		'show_in_admin_status_list' => true,
		'show_in_admin_all_list'    => true,
		'exclude_from_search'       => false,
		'label_count'               => _n_noop('הזמנות שנשלחו לhubx(%s)', 'הזמנות שנשלחו לhubx (%s)'),
		'before'                    => '<mark class="order-status pending-color">',
		'after'                     => '</mark>',
	));

	register_post_status('wc-hubx-succed', array(
		'label'                     => 'Hubx Succed',
		'public'                    => true,
		'show_in_admin_status_list' => true,
		'show_in_admin_all_list'    => true,
		'exclude_from_search'       => false,
		'label_count'               => _n_noop('הזמנות שנשלחו לhubx(%s)', 'הזמנות שנשלחו לhubx (%s)'),
		'before'                    => '<mark class="order-status pending-color">',
		'after'                     => '</mark>',
	));
}

add_action('init', 'register_wait_call_order_status');

function add_wait_call_to_order_statuses($order_statuses)
{
	$new_order_statuses = array();
	foreach ($order_statuses as $key => $status) {
		$new_order_statuses[$key] = $status;
		if ('wc-on-hold' === $key) {
			$new_order_statuses['wc-hubx-failed'] = 'נכשל מhubx';
			$new_order_statuses['wc-hubx-succed'] = 'נשלח לhubx בהצלחה';
		}
	}
	return $new_order_statuses;
}

add_filter('wc_order_statuses', 'add_wait_call_to_order_statuses');


// change status color 

function hubx_failed_order_status_style()
{
	if (is_admin() && isset($_GET['post_type']) && $_GET['post_type'] == 'shop_order') {
	?>
		<style>
			mark.order-status.status-hubx-failed.tips {
				background-color: #FEA1A1 !important;
				color: black;
			}

			mark.order-status.status-hubx-succed.tips {
				background-color: #A0D8B3 !important;
				color: black;
			}
		</style>
<?php
	}
}
add_action('admin_head', 'hubx_failed_order_status_style');


// Redirect to checkout after buy now button 
function so_woocommerce_buy_now_redirect_to_checkout()
{
	if (isset($_REQUEST['proceed'])) {
		$product_id = $_REQUEST["variation_id"];
		$qty = $_REQUEST['quantity'];
		global $woocommerce;
		$woocommerce->cart->empty_cart();
		$woocommerce->cart->add_to_cart($product_id, $qty);
		wp_safe_redirect(wc_get_checkout_url());
		exit;
	}
}
add_action('template_redirect', 'so_woocommerce_buy_now_redirect_to_checkout');


add_filter('woocommerce_default_address_fields', 'custom_override_default_checkout_fields', 10, 1);
function custom_override_default_checkout_fields($address_fields)
{
	$address_fields['address_2']['placeholder'] = __('', 'woocommerce');
	$address_fields['address_2']['label'] = __('דירה, סוויטה, יחידה', 'woocommerce');
	$address_fields['address_2']['label_class'] = array(); // No label class

	return $address_fields;
}

add_filter('woocommerce_defer_transactional_emails', '__return_true');


// RETURN IMAGE AND TEXT FOR PRODUCT BASED ON ID FOR JS

function get_product_image_and_title()
{
	if (isset($_POST)) {
		$product_id = intval($_POST['product_id']);
		$product  = wc_get_product($product_id);
		$product_image_uri = wp_get_attachment_image_src(intval($product->get_image_id()))[0];

		$product_data = array(
			'product_image' => $product_image_uri,
			'product_title' => $product->get_title(),
			'product_link' => $product->get_permalink(),
		);
		wp_send_json_success($product_data);
	}
}
add_action('wp_ajax_stock__modal', 'get_product_image_and_title');
add_action('wp_ajax_nopriv_stock__modal', 'get_product_image_and_title');


wp_enqueue_script('alltrade-stock-modal', get_template_directory_uri() . '/js/stock-modal.js', array('jquery'), '1.0', true);

wp_localize_script('alltrade-stock-modal', 'sticky_globals', [
	'ajax_url' => admin_url('admin-ajax.php'),
]);

require_once get_template_directory() . '/cron-jobs/new-product-insert-sap.php';
require_once get_template_directory() . '/inc/after-order/sap-handler.php';

// Here's our trusted filter again, ready to reshape reality on your whim.
add_filter('manage_edit-product_columns', 'my_edit_product_columns', 10, 2);

function my_edit_product_columns($columns)
{

	$new_columns = array();

	foreach ($columns as $column_name => $column_info) {

		$new_columns[$column_name] = $column_info;

		if ('product_cat' === $column_name) {
			$new_columns['my_column'] = __('מקור מוצר', 'theme_domain');
		}
	}

	return $new_columns;
}

add_action('manage_product_posts_custom_column', 'my_manage_product_columns', 10, 2);

function my_manage_product_columns($column, $post_id)
{
	global $post;

	switch ($column) {
		case 'my_column':

			$value = get_post_meta($post_id, 'product_source', true);

			if (empty($value))
				echo __('Empty', 'theme_domain');
			else
				echo __($value, 'theme_domain');

			break;

		default:
			break;
	}
}

add_action('admin_enqueue_scripts', 'load_custom_admin_css');

function load_custom_admin_css()
{
	wp_enqueue_style('admin_css', get_template_directory_uri() . '/sass/admin.css'); // Now, 'admin-style.css' should be the actual path to your CSS file.
}

require_once get_template_directory() . '/cron-jobs/get-sap-order-status.php';

// ADD CUSTOM VARIATION FIELD TO ADMIN DASHBOARD

function add_variation_settings_fields($loop, $variation_data, $variation)
{

	woocommerce_wp_text_input(
		array(
			'id'          => '_custom_field_name[' . $variation->ID . ']',
			'label'       => __('תוספת למחיר המקורי', 'woocommerce'),
			'placeholder' => 'כאן מוסיפים מחיר ואראיציה שיתווסף למחיר המקורי',
			'value'       => get_post_meta($variation->ID, '_custom_field_name', true),
			'rows' => 2,
		)
	);
	// echo '<style>.form-field.variable_regular_price_0_field.form-row.form-row-first { display: none !important; }</style>';
}

add_action('woocommerce_variation_options_pricing', 'add_variation_settings_fields', 10, 3);

// WHEN CUSTOM FIELD SAVE CHANGE THE ORIGINAL PRICE OF THE PRODUCT

function save_variation_settings_fields_and_set_price($variation_id)
{
	if (isset($_POST['_custom_field_name'][$variation_id])) {
		$custom_field_value = $_POST['_custom_field_name'][$variation_id];
		if (!empty($custom_field_value)) {
			$product = wc_get_product($variation_id);
			$parent_id = $product->get_parent_id();

			$parent_price = get_post_meta($parent_id, 'initial_product_price')[0];

			$new_price = $custom_field_value + $parent_price;

			update_post_meta($variation_id, '_custom_field_name', esc_attr($custom_field_value));

			update_post_meta($variation_id, '_price', esc_attr($new_price));
			update_post_meta($variation_id, '_regular_price', esc_attr($new_price));
		}
	}
}
add_action('woocommerce_save_product_variation', 'save_variation_settings_fields_and_set_price', 10, 2);


// TEST

add_filter('woocommerce_variable_sale_price_html', 'customize_variable_product_price', 10, 2);
add_filter('woocommerce_variable_price_html', 'customize_variable_product_price', 10, 2);

function customize_variable_product_price($price, $product)
{
	// Get min and max prices
	$prices = array($product->get_variation_price('min', true), $product->get_variation_price('max', true));
	$price = $prices[0] !== $prices[1] ? sprintf(__('%1$s', 'woocommerce'), wc_price($prices[0])) : wc_price($prices[0]);


	return $price;
}



function test()
{
	function create_product_variation($product_id, $parent_product, $attr_name, $option, $price)
	{

		$variation = new WC_Product_Variation();
		$variation->set_parent_id($product_id);
		$variation->set_attributes(array($attr_name => $option));
		$variation->set_regular_price($price);
		$variation->save();
		$parent_product->save();
	}

	$product = wc_get_product(14876);
	$attributes = $product->get_attributes();

	$data = reset($attributes);

	$attribute_name_encoded = '';
	foreach ($attributes as $key => $value) {
		$attribute_name_encoded = $key;
	}

	$option = 'pickup_return_1_year';

	$product = wc_get_product(15276);

	$current_attr = array_values($product->get_attributes())[0];

	$taxonomy_name = wc_attribute_taxonomy_name("warranty_expand");
	$terms = get_terms($taxonomy_name, ['hide_empty' => false]);

	$term_name = 'השירות תיקון במעבדה עם איסוף והחזרה מאתר הלקוח לשנה';

	foreach ($terms as $term) {
		$name = $term->name;

		if ($term->name == $term_name) {
			$dude = $term->slug;
		}
	}

	// create_product_variation($product->get_id(), $product, $attribute_name_encoded, $option, 1000);
}

// add_action('init', 'test');


// Change checkout placeholder 

function uwc_new_address_one_placeholder($fields)
{
	$fields['address_1']['placeholder'] = 'כתובת רחוב ומספר בית';
	$fields['address_1']['label'] = 'כתובת רחוב ומספר בית';

	return $fields;
}
add_filter('woocommerce_default_address_fields', 'uwc_new_address_one_placeholder');
