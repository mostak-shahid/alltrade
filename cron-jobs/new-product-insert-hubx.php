<?php

require_once get_stylesheet_directory() . '/cron-jobs/utils/get-current-dollar.php';

class Handle_new_product_insert extends Handler_after_order
{
    public $access_token;
    public $max_pages;
    public $current_page = 1;
    public $all_products = array();
    public $is_run_new_products;
    public $shekel_currency;

    public function __construct($is_run_new_products)
    {
        $this->is_run_new_products = $is_run_new_products;
    }

    public function get_ils_rate()
    {
        $currenyClass = new FreeCurrencyAPI();
        $FreeAPiCurrency = $currenyClass->get_ILS_rate();

        $init = new SapApiCurrency();
        $sapCurrencyRes = $init->handle_sap_request();

        $final_currency = $FreeAPiCurrency;

        if ($sapCurrencyRes['status'] == 'succed') {
            $final_currency =  (float)$sapCurrencyRes['message'] > $FreeAPiCurrency ?  (float)$sapCurrencyRes['message'] : $FreeAPiCurrency;
        }


        $this->shekel_currency = $final_currency;
    }

    public function get_products_from_hubx($page_number)
    {
        $current_page = !$this->is_run_new_products ? "?pageSize=1000&searchQuery=JUST RELEASED&pageNumber=" . $page_number : '?pageNumber' . $page_number;

        $response = wp_remote_post(
            $this->base_url . '/api/products' . $current_page,
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
        if (!$this->shekel_currency) $this->get_ils_rate();

        $ils_currency = $this->shekel_currency ? $this->shekel_currency : 1;

        $product_price = $product["unitPrice"] * $ils_currency;

        $product_price = number_format($product_price, 2, '.', '');

        $meta_data_arr = array_map(function ($attribute) {
            return array('key' => $attribute["name"], 'value' => $attribute["value"]);
        }, $product["attributes"]);

        array_push($meta_data_arr, array('key' => 'is_active', 'value' => $product['isActive']));
        array_push($meta_data_arr, array('key' => 'hubx_id', 'value' => $product['id']));
        array_push($meta_data_arr, array('key' => 'exw', 'value' => $product['exw']));
        array_push($meta_data_arr, array('key' => 'product_source', 'value' => 'hubx'));
        array_push($meta_data_arr, array('key' => 'hubx_original_price', 'value' => $product["unitPrice"]));
        array_push($meta_data_arr, array('key' => 'hubx_original_price_shekel', 'value' => $product_price));
        array_push($meta_data_arr, array('key' => 'original_title', 'value' => $product["description"]));
        array_push($meta_data_arr, array('key' => 'initial_product_price', 'value' => $product_price));

        return array(
            'sku' => $product["mpn"],
            'price' => $product_price,
            'meta_data' =>  $meta_data_arr,
            'name' => $product["description"],
            'stock' => $product["availability"],
            'is_active' => $product['isActive'],
            'moq' => $product['moq'],
        );
    }

    public function create_product_variation($product_id, $parent_product, $attr_name, $option, $price)
    {

        $variation = new WC_Product_Variation();
        $variation->set_parent_id($product_id);
        $variation->set_attributes(array($attr_name => $option));
        $variation->set_regular_price($price);
        $variation->save();
    }

    public function insert_product($product_data, $product_exist)
    {
        $moq = $product_data['moq'];

        if ($product_data['moq'] > 1) return;

        $attribute_name = "הרחבת אחריות";

        $taxonomy_name = wc_attribute_taxonomy_name("warranty_expand");
        $terms = get_terms($taxonomy_name, ['hide_empty' => false]);
        $term_names = wp_list_pluck($terms, 'name');
        $term_slugs = wp_list_pluck($terms, 'slug');

        $product = $product_exist ? $product_exist : new WC_Product_Variable();

        $product->set_props([
            'name' => $product_data['name'],
            'status' => 'publish',
            'price' => $product_data['price'],
            'sku' => $product_data['sku'],
        ]);

        $attribute = new WC_Product_Attribute();
        $attribute->set_name($taxonomy_name);
        $attribute->set_id(1);
        $attribute->set_options($term_names);
        $attribute->set_position(0);
        $attribute->set_visible(1);
        $attribute->set_variation(1);
        $attrs[$taxonomy_name] = $attribute;

        $product->set_attributes($attrs);
        $product->set_manage_stock(true);
        $product->set_stock_quantity($product_data['stock']);

        foreach ($product_data['meta_data'] as $product_meta) {
            $product->update_meta_data($product_meta['key'], $product_meta['value']);
        }

        $product->save();

        foreach ($term_slugs as $option) {
            $this->create_product_variation($product->get_id(), $product, $taxonomy_name, $option, $product_data['price']);
        };
        $product->save();
    }

    public function init()
    {
        $this->access_token = $this->get_hubux_token();
        $this->get_products_from_hubx(1);

        // HARD LIMIT : 20 PRODUCTS

        for ($x = 1; $x <= 2; $x++) {
            $products =  $this->get_products_from_hubx($x);
            $this->all_products = [...$this->all_products, ...$products];
        }

        // HARD LIMIT : 20 PRODUCTS

        for ($y = 1; $y <= 20 - 1; $y++) {
            $product = $this->all_products[$y];
            $product_data = $this->extract_product_info($product);
            $product_in_web = $this->get_product_by_id($product['id']);

            //if ($product_in_web) return;
            $this->insert_product($product_data, $product_in_web);
        };
    }
}

function run_new_products_only()
{
    $new_product_insert = new Handle_new_product_insert(false);
    $new_product_insert->init();
};

function run_all_products()
{
    $new_product_insert = new Handle_new_product_insert(true);
    $new_product_insert->init();
}


add_action('run_new_products_only', 'run_new_products_only');

add_action('run_all_products', 'run_all_products');
