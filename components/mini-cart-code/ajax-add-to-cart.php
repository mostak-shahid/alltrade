<?php
if (!defined('ABSPATH')) {
    exit;
}

class Add_To_Cart
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'load_js'], 99);
        $this->permissions();
    }

    public function load_js()
    {
        $localize_script = array(
            'ajax_url'  => admin_url('admin-ajax.php'),
        );

        wp_enqueue_script('woocommerce-ajax-add-to-cart', get_template_directory_uri() . '/assets/js/ajax-add-to-cart.js', array('jquery'), '1.0.2', true);
        wp_localize_script('woocommerce-ajax-add-to-cart', 'np', $localize_script);
    }

    private function permissions()
    {
        add_action('wp_ajax_woocommerce_ajax_add_to_cart', [$this, 'callback']);
        add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', [$this, 'callback']);
    }

    public function callback()
    {
        $product_id = $_POST['product_id'];
        $variation_id = $_POST['variation_id'];
        $quantity = $_POST['quantity'];

        $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($product_id));
        $quantity = empty($quantity) ? 1 : wc_stock_amount($quantity);
        $variation_id = absint($variation_id);
        $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        $product_status = get_post_status($variation_id);

        if ($variation_id && $variation_id !== 0 && $passed_validation && 'publish' === $product_status && WC()->cart->add_to_cart($product_id, $quantity, $variation_id)) {

            do_action('woocommerce_ajax_added_to_cart', $product_id);

            if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
                wc_add_to_cart_message(array($product_id => $quantity), true);
            }

            $this->new_data_cart();
        } else {

            $data = array(
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
            );

            echo wp_send_json($data);
        }

        wp_die();
    }

    private function new_data_cart()
    {
        ob_start();

        // woocommerce_mini_cart();

        $mini_cart = ob_get_clean();

        $data = array(
            'fragments' => apply_filters(
                'woocommerce_add_to_cart_fragments',
                array(
                    'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
                )
            ),
            'cart_hash' => WC()->cart->get_cart_hash(),
            'total_quantity' => WC()->cart->get_cart_contents_count(),
        );

        wp_send_json($data);
    }
}

new Add_To_Cart;
