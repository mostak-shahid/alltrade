<?php

defined('ABSPATH') || exit;


if (!function_exists('wc_get_gallery_image_html')) {
	return;
}

global $product;


$attachment_ids = $product->get_gallery_image_ids();

$variations = $product->get_available_variations();



if ($attachment_ids || $variations) {
	echo '<ul class="thumbnail-list-wrapper">';

	// Get the main product image and add it as the first item in the list
	$main_image_id = $product->get_image_id();
	if ($main_image_id) {
		echo '<li data-id="' . $main_image_id . '">';
		echo apply_filters('woocommerce_single_product_image_thumbnail_html', wp_get_attachment_image($main_image_id, 'woocommerce_thumbnail'), $main_image_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
		echo '</li>';
	}

	// Get gallery images for other product types
	foreach ($attachment_ids as $attachment_id) {
		echo '<li data-id="' . $attachment_id . '">';
		echo apply_filters('woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html($attachment_id), $attachment_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
		echo '</li>';
	}

	// Get variation images for variable products
	if ($product->is_type('variable')) {
		$variations = $product->get_available_variations();
		foreach ($variations as $variation) {
			$variation_image_id = $variation['image_id'];


			if ($variation_image_id === intval($main_image_id)) {
				continue;
			};
			if ($variation_image_id) {
				echo '<li data-id="' . $variation_image_id . '">';
				echo apply_filters('woocommerce_single_product_image_thumbnail_html', wp_get_attachment_image($variation_image_id, 'woocommerce_thumbnail'), $variation_image_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
				echo '</li>';
			}
		}
	}

	echo '</ul>';
}
