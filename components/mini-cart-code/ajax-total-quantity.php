<?php
if (!defined('ABSPATH')) {
    exit;
}

class Ajax_Total_Quantity {
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'load_js'], 99);
        $this->permissions();
    }

    public function load_js() {
        $localize_script = array(
            'ajax_url'  => admin_url('admin-ajax.php'),
        );
        
        wp_enqueue_script('woocommerce-ajax-total-quantity', get_template_directory_uri() . '/assets/js/ajax-total-quantity.js', array('jquery'), '1.0.0', true);
        wp_localize_script('woocommerce-ajax-total-quantity', 'np', $localize_script);
    }

    private function permissions() {
        add_action('wp_ajax_ajax_total_quantity', [$this, 'callback']);
        add_action('wp_ajax_nopriv_ajax_total_quantity', [$this, 'callback']);
    }

    public function callback() {
        $data = array(
            "total" => WC()->cart->get_cart_contents_count()
        );
    
        wp_send_json( $data );

        wp_die();
    }
}

new Ajax_Total_Quantity;