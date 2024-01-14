<?php

class Hanlde_sap_product_insert
{
    public $TOKEN = '';
    public $BASE_URL = 'http://62.0.68.60:25001/Services/FACClient.asmx';


    public function create_product_variation($product_id, $parent_product, $attr_name, $option)
    {

        $variation = new WC_Product_Variation();
        $variation->set_parent_id($product_id);
        $variation->set_attributes(array($attr_name => $option));
        $variation->set_manage_stock(false);
        // $variation->set_stock_quantity($parent_product->get_stock_quantity());
        $variation->set_regular_price($parent_product->get_price());
        $variation->save();
    }

    function category_exists($cat_name)
    {
        $term = get_term_by('name', $cat_name, 'product_cat');
        return ($term == false) ? false : true;
    }

    function create_product_category($cat_name)
    {
        if (!$this->category_exists($cat_name)) {
            $result = wp_insert_term(
                $cat_name,   // the term 
                'product_cat', // the taxonomy
                array(
                    'description' => '',
                    'slug' => sanitize_title($cat_name),
                )
            );
            if (is_wp_error($result)) {
                // Log the error and return
                error_log($result->get_error_message());
                return null;
            }
            // Return the term ID
            return $result['term_id'];
        } else {
            // Get the existing category's ID
            return get_term_by('name', $cat_name, 'product_cat')->term_id;
        }
    }

    function get_product_by_title($product_title)
    {

        $args = array(
            'post_type' => 'product',
            'post_status' => array('publish', 'draft'),
            'posts_per_page' => 1,
            'no_found_rows' => true,
            'ignore_sticky_posts' => true,
            'title' => $product_title,
        );

        $products = get_posts($args);

        // Return the product if found, else return false
        if ($products) {
            return $products[0]->ID;
        } else {
            return false;
        }
    }

    public function insert_product($product_data)
    {
        $product_name = empty($product_data['product']['name']) ? $product_data['product']['name2'] : $product_data['product']['name'];

        $product_id = $product = $this->get_product_by_title($product_name);

        if ($product_id) {
            $product = wc_get_product($product_id);
        } else {
            $product = new WC_Product_Variable();

            $product->set_props([
                'name' => $product_name,
                'status' => 'publish',
                'price' => $product_data['product']['price'],
                'sku' => $product_data['product']['sku'],
            ]);

            $attribute_name = "הרחבת אחריות";

            $taxonomy_name = wc_attribute_taxonomy_name("warranty_expand_gat");
            $terms = get_terms($taxonomy_name, ['hide_empty' => false]);
            $term_names = wp_list_pluck($terms, 'name');
            $term_slugs = wp_list_pluck($terms, 'slug');

            $attribute = new WC_Product_Attribute();
            $attribute->set_name($taxonomy_name);
            $attribute->set_id(1);
            $attribute->set_options($term_names);
            $attribute->set_position(0);
            $attribute->set_visible(1);
            $attribute->set_variation(1);
            $attrs[$taxonomy_name] = $attribute;

            $product->set_attributes($attrs);
            $product->save();

            foreach ($term_slugs as $option) {
                $this->create_product_variation($product->get_id(), $product, $taxonomy_name, $option);
            };
        }

        $product->set_manage_stock(true); // to enable managing stock on variation level
        $product->set_stock_quantity($product_data['product']['stock']); //

        foreach ($product_data['meta'] as $key => $value) {
            $product->update_meta_data($key, $value);
        }

        $cat_id = '';
        if (!empty($product_data['product']['category'])) {
            $cat_id = $this->create_product_category($product_data['product']['category']);
        }

        if (!empty($cat_id)) {
            $product->set_category_ids(array($cat_id));
        }

        $product->save();
    }

    function sap_get_items()
    {
        $args = array(
            'method'      => 'POST',
            'timeout'     => 45,
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
            ),
            'body' => array(
                'TOKEN' => '1qaz',
                'RepCode' => 'GetItems',
                'UserName' => '',
                'CardCode' => '',
                'EmpId' => '',
                'Date1' => '',
                'Date2' => ''
            )
        );

        $res = wp_remote_post($this->BASE_URL . '/GetReportDataJsonWithoutSapCompany', $args);
        $xmlstring = $res['body'];

        $xml = simplexml_load_string($xmlstring);

        $dataArray = json_decode((string) $xml, true);

        return $dataArray;
    }

    function sap_fields_arrange($product_from_api)
    {
        $api_to_value = [
            "Brand" => "brand",
            "Model" => "model",
            "Processor" => "processor",
            "Memory" => "ram",
            "HardDisk" => "storage",
            "OperatingSystem" => "operation-system",
            "GraphicCard" => "gpu",
            "Camera" => "camera",
            "Screen" => "screen",
            "Touch" => "touch",
            "Size" => "size",
            "Type" => "screen_type",
            "PhisicalData" => "",
            "Weight" => "weight",
            "Color" => "color",
            "Measurement" => "Measurement",
            "HardDrive" => "",
            "Exit" => "exits",
            "GraphicCardRead" => "",
            "CategoryNum" => "",
            "U_ItemName" => "original_title",
            "Upload" => "",
            "FrgnName" => "",
            "Pic1" => "",
            "Pic2" => "",
            "Pic3" => "",
            "Pic4" => "",
            "upload1" => "",
            "Price" => "original_price_shekel",
            "Price" => "initial_product_price"
        ];

        $meta_values_list = [];
        foreach ($api_to_value as $key => $value) {
            if (empty($value)) continue;
            $meta_values_list[$value] = $product_from_api[$key];
        }

        $product_fields = [
            "OnHand" => "stock",
            "Price" => "price",
            "Category" => "category",
            "U_Itemcategory" => "",
            "U_ItemName" => "name",
            "ItemName" => "name2",
            "ItemCode" => "sku",
        ];

        $regular_fields = [];
        foreach ($product_fields as $key => $value) {
            if (empty($value)) continue;
            $regular_fields[$value] = $product_from_api[$key];
        }

        $meta_values_list["product_source"] = 'sap';

        return array(
            'meta' => $meta_values_list,
            'product' => $regular_fields
        );
    }

    function run()
    {
        $items = $this->sap_get_items();

        for ($x = 0; $x < 50; $x++) {

            if ($items[$x]['Price'] == 0) {
                continue;
            } else {
                $fields = $this->sap_fields_arrange($items[$x]);
                $this->insert_product($fields);
            }
        }
    }
}

function get_products_from_sap()
{
    $new_product_insert = new Hanlde_sap_product_insert();
    $new_product_insert->run();
}


add_action('get_products_from_sap', 'get_products_from_sap');
