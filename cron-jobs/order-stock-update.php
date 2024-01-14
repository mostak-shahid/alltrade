<?php

require_once get_stylesheet_directory() . '/cron-jobs/utils/get-current-dollar.php';

class Handle_stock_update extends Handler_after_order
{
    public $access_token;
    public $max_pages;
    public $current_page = 1;
    public $all_products = array();

    public function get_ils_rate()
    {
        $currenyClass = new FreeCurrencyAPI();
        $curreny = $currenyClass->get_ILS_rate();

        return $curreny;
    }

    public function get_products_from_hubx($page_number)
    {
        $response = wp_remote_post(
            $this->base_url . '/api/products?pageNumber=' . $page_number,
            array(
                'method' => 'GET',
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->access_token,
                ),
            )
        );

        $data = json_decode($response['body'], true);
        if (!$this->max_pages) $this->max_pages = $data["pagination"]["totalPages"];

        return $data['products'];
    }

    public function get_product_by_id($product_id)
    {
        $args = array(
            'post_type' => 'product',
            'post_status' => array('publish', 'draft'),
            'meta_query' => array(
                array(
                    'key' => 'hubx_id',
                    'value' => $product_id,
                    'compare' => 'LIKE',
                ),
            ),
        );
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $current_product_id = get_the_ID();
                $_pf = new WC_Product_Factory();
                $product = $_pf->get_product($current_product_id);
                return $product;
            }
            wp_reset_postdata();
        } else {
        }
    }

    public function extract_product_info($product)
    {

        $get_curreny = $this->get_ils_rate();
        $ils_currency = $get_curreny ? $get_curreny : 1;

        return array(
            'id' => $product['id'],
            'stock' => $product["availability"],
            'is_active' => $product['isActive'],
            'price' => $product['unitPrice'] * $ils_currency,
        );
    }

    public function update_product_variation_price($product, $new_price)
    {
        // Get all variations of the product.
        $variations = $product->get_children();

        // Loop through the variations and update their price.
        foreach ($variations as $variation_id) {
            $variation = wc_get_product($variation_id);

            $variation->set_regular_price($new_price);
            $variation->save();
        }

        // Sync the product prices.
        $product->sync();
    }

    public function init()
    {
        custom_error_logger('start');

        $this->access_token = $this->get_hubux_token();
        $this->get_products_from_hubx(1);

        for ($x = 1; $x <= 1; $x++) {
            $products =  $this->get_products_from_hubx($x);
            $this->all_products = [...$this->all_products, ...$products];
        }

        for ($y = 1; $y <= 1; $y++) {
            $product = $this->all_products[$y];
            $info = $this->extract_product_info($product);
            $product_in_web = $this->get_product_by_id($info['id']);
            if (!$product_in_web) continue;

            $this->update_product_variation_price($product_in_web, $info['price']);

            $product_in_web->set_stock_quantity($info['stock']);
            $product_in_web->update_meta_data('is_active', $info['is_active']);
            $product_in_web->save();
        };
        custom_error_logger('end');
    }
}

function update_hubx_stock()
{
    $stock_update = new Handle_stock_update();
    $stock_update->init();
};

add_action('update_hubx_stock', 'update_hubx_stock');
