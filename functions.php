<?php

/**
 * npcoding functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package npcoding
 */

use function Crontrol\Event\add;

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function alltrade_setup()
{
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on npcoding, use a find and replace
	 * to change 'alltrade' to the name of your theme in all the template files.
	 */
	load_theme_textdomain('alltrade', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primary', 'alltrade'),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'alltrade_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height' => 250,
			'width' => 250,
			'flex-width' => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'alltrade_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function alltrade_content_width()
{
	$GLOBALS['content_width'] = apply_filters('alltrade_content_width', 640);
}
add_action('after_setup_theme', 'alltrade_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function alltrade_widgets_init()
{
	register_sidebar(
		array(
			'name' => esc_html__('Sidebar', 'alltrade'),
			'id' => 'sidebar-1',
			'description' => esc_html__('Add widgets here.', 'alltrade'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name' => esc_html__('עגלה', 'alltrade'),
			'id' => 'cart-sidebar',
			'description' => esc_html__('Add widgets here.', 'alltrade'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name' => esc_html__('מבצע צף כותרת', 'alltrade'),
			'id' => 'sale-floating',
			'description' => esc_html__('Add widgets here.', 'alltrade'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name' => esc_html__('מבצע צף תמונה', 'alltrade'),
			'id' => 'sale-floating-image',
			'description' => esc_html__('Add widgets here.', 'alltrade'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name' => esc_html__('הנחה צף', 'alltrade'),
			'id' => 'discount-floating',
			'description' => esc_html__('Add widgets here.', 'alltrade'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		)
	);
}
add_action('widgets_init', 'alltrade_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function alltrade_scripts()
{
	wp_enqueue_style('alltrade-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_style_add_data('alltrade-style', 'rtl', 'replace');
	wp_enqueue_script('alltrade-sticky-filter', get_template_directory_uri() . '/js/sticky-filter.js', array('jquery'), '1.0', true);

	wp_localize_script('alltrade-sticky-filter', 'sticky_globals', [
		'ajax_url' => admin_url('admin-ajax.php'),
	]);

	wp_enqueue_script('alltrade-sticky-effect', get_template_directory_uri() . '/js/sticky-effect.js', array('jquery'), '1.0', true);
	wp_enqueue_script('alltrade-carousel', get_template_directory_uri() . '/js/carousel.js', array(), _S_VERSION, true);
	wp_enqueue_script('utils-script', get_template_directory_uri() . '/js/utils.js', array(), _S_VERSION, true);
	wp_enqueue_script('alltrade-slider', get_template_directory_uri() . '/js/slider.js', array(), _S_VERSION, true);
	wp_enqueue_script('alltrade-quantity-btns', get_template_directory_uri() . '/js/products-quantity-btns.js', array(), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	wp_enqueue_style('custom-production-style', get_template_directory_uri() . '/production-custom-style.css');
}
add_action('wp_enqueue_scripts', 'alltrade_scripts');

function make_scripts_modules($tag, $handle, $src)
{

	if ('alltrade-carousel' !== $handle) {
		return $tag;
	}

	$id = $handle . '-js';

	$parts = explode('</script>', $tag); // Break up our string

	foreach ($parts as $key => $part) {
		if (false !== strpos($part, $src)) { // Make sure we're only altering the tag for our module script.
			$parts[$key] = '<script type="module" src="' . esc_url($src) . '" id="' . esc_attr($id) . '">';
		}
	}

	$tags = implode('</script>', $parts); // Bring everything back together

	return $tags;
}

add_filter('script_loader_tag', 'make_scripts_modules', 10, 3);


/**
 * Implement the Custom Header feature.
 */
require_once get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require_once get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require_once get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require_once get_template_directory() . '/inc/customizer.php';

/**
 * Require Filter products
 */
require_once get_template_directory() . '/inc/archive-filter.php';

/**
 * Require Custom Badge
 */
require_once get_template_directory() . '/inc/custom-badge.php';

// Filter Content

require_once get_template_directory() . '/inc/content-archive-filter.php';

require_once get_template_directory() . '/template-parts/content-text-icon.php';

require_once get_template_directory() . '/template-parts/content-text-icon.php';

// Related products

require_once get_template_directory() . '/inc/related-products.php';

require_once get_template_directory() . '/template-parts/stock-notify-modal.php';


/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require_once get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
	require_once get_template_directory() . '/inc/woocommerce.php';
}


/**
 *Allow SVG Files
 */

function cc_mime_types($mimes)
{
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');



function register_new_menus()
{
	register_nav_menu('menu-footer-1', __('menu footer 1'));
	register_nav_menu('menu-footer-2', __('menu footer 2'));
	register_nav_menu('menu-footer-3', __('menu footer 3'));
}
add_action('after_setup_theme', 'register_new_menus');


// New Widget

// Include the custom widget file
require_once get_template_directory() . '/title_image_widget.php';

// Register the custom widget
function register_title_image_widget()
{
	register_widget('Title_Image_Widget');
}
add_action('widgets_init', 'register_title_image_widget');


// Get Header Banners 

function get_header_banners($banner_type)
{
	$args = array(
		'post_type' => 'banner',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'banner_type',
				'field' => 'slug',
				'terms' => $banner_type,
			),
		),
	);

	$header_banners = new WP_Query($args);

	return $header_banners;
}


// load custom widget 

require_once get_stylesheet_directory() . '/custom-widget.php';

// get components

require_once get_template_directory() . '/components/components.php';

// ADD CUSTOM META FIELD TO ORDER ADMIN

function wp177780_order_item_view_link($item_id, $item, $_product)
{
	if (!$_product) return;

	$all_meta_data = get_metadata('order_item', $item_id);
	$product_parent = $_product->get_parent_id();
	$product_source = get_post_meta($product_parent, 'product_source');
	$order_id = $item->get_order_id();


	if ($product_source[0] === 'hubx') {
		if (!isset($all_meta_data['is_hubx_success'])) return;
		$is_hubx_success = $all_meta_data['is_hubx_success'][0] === '1' ? 'נשלח בהצלחה' : 'חלה תקלה';
		$error_message = $all_meta_data['hubx_message'][0];

		$button = $all_meta_data['is_hubx_success'][0] ? '' : "<button class='ya__btn-hubx-try-again' data-order_id='$order_id' data-product_id='$item_id'>ניסיון שליחה חוזר</button>";
?>
		<div class='ya__order_data_column' style='float: right; width: 100%;'>
			<h4>Hubx</h4>
			<table>
				<tr>
					<th><strong>סטטוס</strong></th>
					<th><strong>הודעה</strong></th>
				</tr>
				<tr>
					<td>
						<p style='margin:0; padding:0'><?php echo $is_hubx_success ?></p>
					</td>
					<td>
						<p style='margin:0; padding:0'><?php echo $error_message ?></p>
					</td>
				</tr>
			</table>
			<?php echo $button ?>
		</div>
	<?php
	}
	$sap_button = "<button class='ya__btn-sap-try-again' data-order_id='$order_id' data-product_id='$item_id'>ניסיון שליחה חוזר</button>";
	$is_sap_succed = $all_meta_data['is_sap_item_succed'][0];
	$sap_item_message = $all_meta_data['sap_item_message'][0];
	$sap_item_id = isset($all_meta_data['sap_item_id']) ? $all_meta_data['sap_item_id'][0]	: '';
	?>
	<div class='ya__order_data_column' style='float: right; width: 100%;'>
		<h4>SAP ITEM</h4>
		<table>
			<tr>
				<th><strong>סטטוס</strong></th>
				<th><strong>הודעה</strong></th>
				<?php if ($sap_item_id) { ?>
					<th><strong>מספר פריט בסאפ</strong></th>
				<?php } ?>
			</tr>
			<tr>
				<td>
					<p style='margin:0; padding:0'><?php echo $is_sap_succed ?></p>
				</td>
				<td>
					<p style='margin:0; padding:0'><?php echo $sap_item_message ?></p>
				</td>
				<?php if ($sap_item_id) { ?>
					<td>
						<p style='margin:0; padding:0'><?php echo $sap_item_id ?></p>
					</td>
				<?php } ?>
			</tr>
		</table>
		<?php if (empty($sap_item_id)) { ?>
			<?php echo $sap_button ?>
		<?php } ?>
	</div>

<?php
}

add_action('woocommerce_after_order_itemmeta', 'wp177780_order_item_view_link', 10, 3);


function op_register_menu_meta_box()
{

	add_meta_box(
		'Some identifier of your custom box',
		esc_html__('סטטוס SAP', 'text-domain'),
		'render_meta_box',
		'shop_order', // shop_order is the post type of the admin order page
		'normדal', // change to 'side' to move box to side column 
		'high' // priority (where on page to put the box)
	);
}

add_action('add_meta_boxes', 'op_register_menu_meta_box');

function render_meta_box()
{
	if (!isset($_GET['post'])) return;
	$order_id = $_GET['post'];

	$post_meta = get_post_meta($order_id);

	$is_sap_success = $post_meta['is_sap_success'][0] === 'ok' ? 'נשלח בהצלחה' : 'חלה תקלה';
	$error_message = $post_meta['sap_message'][0];
	$sap_order_id = isset($post_meta['sap_order_id']) ?  $post_meta['sap_order_id'][0] : '';

	$button = "<button class='ya__btn-sap-order-try-again' data-order_id='$order_id' >ניסיון שליחה חוזר</button>";
?>
	<div class='ya__order_data_column'>
		<h4>SAP</h4>
		<table>
			<tr>
				<th style=' text-align:start;'><strong>סטטוס</strong></th>
				<th style=' text-align:start;'><strong>הודעה</strong></th>
				<th style=' text-align:start;'><strong>מספר הזמנה בסאפ</strong></th>
			</tr>
			<tr>
				<td>
					<p style=' margin:0; padding:0'><?php echo $is_sap_success ?></p>
				</td>
				<td>
					<p style='margin:0; padding:0'><?php echo  $error_message ?></p>
				</td>
				<td>
					<p style='margin:0; padding:0'><?php echo  $sap_order_id ?></p>
				</td>
			</tr>
		</table>
		<?php if (empty($sap_order_id)) { ?>
			<?php echo $button ?>
		<?php } ?>
	</div>
<?php
}


// REMOVE META FIELD FROM UI
function exclude_item_meta($formatted_meta, $item)
{
	$excluded_arr = ['is_hubx_success', 'hubx_message', 'sap_order_id', 'sap_message', 'product_source', 'is_sap_success'];


	foreach ($formatted_meta as $key => $meta) {
		if (in_array($meta->key, $excluded_arr)) {
			unset($formatted_meta[$key]);
		}
	}

	return $formatted_meta;
}

add_filter('woocommerce_order_item_get_formatted_meta_data', 'exclude_item_meta', 10, 2);

// Form submission handler
function submit_product_review()
{
	if (isset($_POST['reviewer_name']) && isset($_POST['rating']) && isset($_POST['comments'])) {
		$product_id = $_POST['product_id'];
		$reviewer_name = sanitize_text_field($_POST['reviewer_name']);
		$rating = intval($_POST['rating']);
		$comments = sanitize_textarea_field($_POST['comments']);

		$data = array(
			'comment_post_ID' => $product_id, // product id you want to add review
			'comment_author' => $reviewer_name, // author name
			'comment_content' => $comments,
			'comment_type' => 'review', // add type review
			'comment_approved' => 0, // use 1 for approved
		);
		$comment_id = wp_insert_comment($data); // insert comment
		update_comment_meta($comment_id, 'rating', $rating);

		wp_redirect(home_url('/review-success/'));
	}
}
add_action('admin_post_nopriv_submit_product_review', 'submit_product_review');
add_action('admin_post_submit_product_review', 'submit_product_review');

function wp_kama_woocommerce_variation_option_name_filter($value, $unused, $taxonomy, $product)
{
	$varaitions = $product->get_children();
	if (!empty($varaitions)) {
		$parent_price = $product->get_price();
		if (!$parent_price) return $value;
		$var_price = '';

		foreach ($varaitions as $variation) {
			$var_product = wc_get_product($variation);
			$current_attr = array_values($var_product->get_attributes())[0];

			if ($unused->slug === $current_attr) {
				$var_price = $var_product->get_price();
			}
		}

		$current_var_price = $var_price - $parent_price;
		$current_price_shekel = $current_var_price > 0 ? '₪' . $current_var_price   : $current_var_price;

		return $value . ' - ' . $current_price_shekel;
	}
	return $value;
}
add_filter('woocommerce_variation_option_name', 'wp_kama_woocommerce_variation_option_name_filter', 10, 4);

// function enqueue_fancybox_script()
// {
// 	wp_enqueue_style('fancybox-css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css', array(), '3.5.7');
// 	wp_enqueue_script('fancybox-js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', array('jquery'), '3.5.7', true);
// }
// add_action('wp_enqueue_scripts', 'enqueue_fancybox_script');
