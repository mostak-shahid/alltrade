<?php
function getCategoryPageOfProduct($idProduct) {
    if( isset($idProduct) && !empty($idProduct) && is_product() ) {
        $term_list  = wp_get_post_terms($idProduct,'product_cat',array('fields'=>'ids'));

        foreach( $term_list as $categroy ) {
            $term       = get_term ((int)$categroy, 'product_cat');
    
            $menu_name = "gategories";
            if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
                $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
                $menu = wp_get_nav_menu_items($menu->term_id);
    
                foreach( $menu as $item ) {
                    if( $item->title == $term->name) {
                        $obj = new \stdClass;
                        $obj->id    = $term->term_id;
                        $obj->title = $term->name;
                        return $obj;
                    }
                }
            }
        }

        
        return '';
    }

    return '';
}

function getNumberOfItemsInTheCart() {
    global $woocommerce;
    return $woocommerce->cart->cart_contents_count;
}

// For each product in loop
function is_not_single_product() {
    $idPage = isset(get_queried_object()->ID) ? get_queried_object()->ID : null; // not each product

    // Shop page
    if( $idPage === null ) 
    {
        return true;
    }

    global $product;
    $idProduct = $product->id; // not each product

    $isProduct = wc_get_product($idProduct) !== false;
    $andNotCurrentProduct = $idProduct !== $idPage && $isProduct;
    
    return $andNotCurrentProduct;
}


function is_single_product() {
    return !is_not_single_product();
}

function is_registration_page() {
	$page = "/registration/";
	$currentUrl = home_url($_SERVER['REQUEST_URI']);

	return strpos($currentUrl, $page) !== false;
}

function get_rate_tax() {
    $taxes =  array_values(WC_Tax::get_rates());
    $tax = !empty($taxes) ? $taxes[0] : null;

    if( wc_prices_include_tax() && isset( $tax['rate'] ) ) {
        return $tax['rate'];
    }
    else {
        return null;
    }
}

function add_tax_to_price($price) {
    $rate = get_rate_tax();

    if( !is_null($rate) ) {
        return $price * ( ($rate * 0.01) + 1 );
    }

    return $price;
}

function wc_get_price_by_format($price, $to_add_tax = true, $html = true) {

    if( $to_add_tax ) {
        $price = add_tax_to_price($price);
    }

    $priceWithDecimals = number_format($price, wc_get_price_decimals());

    if( $html ) {
        $html = '<span class="woocommerce-Price-amount amount"><bdi>';
        $html .= '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol() . '</span>';
        $html .= $priceWithDecimals;
        $html .= '</bdi></span>';
        return $html;
    }
    else {
        return $priceWithDecimals;
    }
}

function wc_price_without_html( $price, $args = array() ) {
	$args = apply_filters(
		'wc_price_args',
		wp_parse_args(
			$args,
			array(
				'ex_tax_label'       => false,
				'currency'           => '',
				'decimal_separator'  => wc_get_price_decimal_separator(),
				'thousand_separator' => wc_get_price_thousand_separator(),
				'decimals'           => wc_get_price_decimals(),
				'price_format'       => get_woocommerce_price_format(),
			)
		)
	);

	$original_price = $price;

	// Convert to float to avoid issues on PHP 8.
	$price = (float) $price;

	$unformatted_price = $price;
	$negative          = $price < 0;

	/**
	 * Filter raw price.
	 *
	 * @param float        $raw_price      Raw price.
	 * @param float|string $original_price Original price as float, or empty string. Since 5.0.0.
	 */
	$price = apply_filters( 'raw_woocommerce_price', $negative ? $price * -1 : $price, $original_price );

	/**
	 * Filter formatted price.
	 *
	 * @param float        $formatted_price    Formatted price.
	 * @param float        $price              Unformatted price.
	 * @param int          $decimals           Number of decimals.
	 * @param string       $decimal_separator  Decimal separator.
	 * @param string       $thousand_separator Thousand separator.
	 * @param float|string $original_price     Original price as float, or empty string. Since 5.0.0.
	 */
	$price = apply_filters( 'formatted_woocommerce_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'], $original_price );

	if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
		$price = wc_trim_zeros( $price );
	}

	/**
	 * Filters the string of price markup.
	 *
	 * @param string       $return            Price HTML markup.
	 * @param string       $price             Formatted price.
	 * @param array        $args              Pass on the args.
	 * @param float        $unformatted_price Price as float to allow plugins custom formatting. Since 3.2.0.
	 * @param float|string $original_price    Original price as float, or empty string. Since 5.0.0.
	 */
	return apply_filters( 'wc_price', $price, $price, $args, $unformatted_price, $original_price );
}

function wc_cart_totals_order_total_html_custom() {
	$value = '<strong>' . WC()->cart->get_total() . '</strong> ';

	// If prices are tax inclusive, show taxes here.
	if ( wc_tax_enabled() && WC()->cart->display_prices_including_tax() ) {
		$tax_string_array = array();
		$cart_tax_totals  = WC()->cart->get_tax_totals();

		if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) {
			foreach ( $cart_tax_totals as $code => $tax ) {
				$tax_string_array[] = sprintf( '%s %s', $tax->formatted_amount, $tax->label );
			}
		} elseif ( ! empty( $cart_tax_totals ) ) {
			$tax_string_array[] = sprintf( '%s %s', wc_price( WC()->cart->get_taxes_total( true, true ) ), WC()->countries->tax_or_vat() );
		}

		if ( ! empty( $tax_string_array ) ) {
			$taxable_address = WC()->customer->get_taxable_address();
			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				$country = WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ];
				/* translators: 1: tax amount 2: country name */
				$tax_text = wp_kses_post( sprintf( __( '(includes %1$s estimated for %2$s)', 'woocommerce' ), implode( ', ', $tax_string_array ), $country ) );
			} else {
				/* translators: %s: tax amount */
				$tax_text = wp_kses_post( sprintf( __( '(includes %s)', 'woocommerce' ), implode( ', ', $tax_string_array ) ) );
			}
		}
	}

	echo apply_filters( 'woocommerce_cart_totals_order_total_html', $value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function getVariationName($product, $cart_item, $cart_item_key) {
    $product_name      = apply_filters( 'woocommerce_cart_item_name', $product->get_name(), $cart_item, $cart_item_key );
    $product_name_splite = explode("-", $product_name);
    $only_product_name = trim($product_name_splite[0]);
    $variation_name = trim($product_name_splite[1]);
    $variation_name = str_replace('יחידה', 'יח\'', $variation_name);

    $obj = new \stdClass();
    $obj->only_product_name = $only_product_name;
    $obj->variation_name = $variation_name;

    return $obj;
}

function wc_get_custom_gallery_image_html( $attachment_id, $main_image = false ) {
	$flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
	$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
	$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
	$image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
	$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
	$thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
	$full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
	$alt_text          = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
	$image             = wp_get_attachment_image(
		$attachment_id,
		$image_size,
		false,
		apply_filters(
			'woocommerce_gallery_image_html_attachment_image_params',
			array(
				'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
				'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
				'data-src'                => esc_url( $full_src[0] ),
				'data-large_image'        => esc_url( $full_src[0] ),
				'data-large_image_width'  => esc_attr( $full_src[1] ),
				'data-large_image_height' => esc_attr( $full_src[2] ),
				'class'                   => esc_attr( $main_image ? 'wp-post-image' : '' ),
			),
			$attachment_id,
			$image_size,
			$main_image
		)
	);

	return '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" data-thumb-alt="' . esc_attr( $alt_text ) . '" class="woocommerce-product-gallery__image">' . $image . '</div>';
}

function the_content_only_text($each = true, $tag = "<p>", $closeTag = "</p>") {
    $content = get_the_content();
    $content = apply_filters( 'the_content', $content );
    $content = str_replace( ']]>', ']]&gt;', $content );

	// Remove attributes from html
	$content = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/si",'<$1$2>', $content);

    if( $each ) {
        echo $tag . $content . $closeTag;
    }
    else {
        return $content;
    }
}