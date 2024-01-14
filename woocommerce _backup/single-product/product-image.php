<?php

/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.1
 */

defined('ABSPATH') || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if (!function_exists('wc_get_gallery_image_html')) {
	return;
}

global $product;


$columns           = apply_filters('woocommerce_product_thumbnails_columns', 4);
$post_thumbnail_id = $product->get_image_id();

$is_thumbnails_gallery = $product->get_gallery_image_ids();
$variations = $product->get_available_variations();

$gallery_class = $is_thumbnails_gallery || $variations ? 'gallery-thumbnails__exist' : 'gallery-thumbnails__not-exist';

$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ($post_thumbnail_id ? 'with-images' : 'without-images'),
		'woocommerce-product-gallery--columns-' . absint($columns),
		'images',
	)
);
?>

<div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>" data-columns="<?php echo esc_attr($columns); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
	<figure class="woocommerce-product-gallery__wrapper <?php echo $gallery_class ?>">
		<?php

		$attachment_ids = $product->get_gallery_image_ids();
		$no_more_images = empty($variations) && empty($attachment_ids);

		if ($post_thumbnail_id) {
			echo "<div class='gallery-images-wrapper ya__gallery-have-thumbnails'>";
			echo '<button class="ya-single-product__right-arrow ya__single-product-pagination"></button>';
			// echo '<a href="' . wp_get_attachment_url($post_thumbnail_id) . '" data-fancybox="gallery" data-caption="Single image" class="ya-zoom-image-button">ZOOM</a>';
			echo '<button class="ya-single-product__left-arrow ya__single-product-pagination"></button>';
			$html = wc_get_gallery_image_html($post_thumbnail_id, !$no_more_images);
			$html .= do_action('woocommerce_product_thumbnails');
			echo "</div>";
		} else {
			$image_class =
				$image = "<img src='%s' alt='%s' class='wp-post-image'";
			$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
			$html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src('woocommerce_single')), esc_html__('Awaiting product image', 'woocommerce'));
			$html .= '</div>';
		}

		$processor = $product->get_meta('processor');
		$ram = $product->get_meta('ram');
		$storage = $product->get_meta('storage');

		preg_match('/Intel Core (i[57])/i', $processor, $processor_matches);
		preg_match('/\b\d+GB\b/', $ram, $ram_matches);
		preg_match('/\b\d+GB\b/', $storage, $storage_matches);

		echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
		?>
		<div class="product__tech-details-container single-product__tech-details">
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
	</figure>

	<?php do_action('woocommerce_product_thumbnails'); ?>
</div>