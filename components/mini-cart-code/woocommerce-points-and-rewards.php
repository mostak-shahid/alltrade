<?php
if (!defined('ABSPATH')) {
    exit;
}

if (class_exists('Woo_Pr_Model')) {
    remove_action('woocommerce_before_add_to_cart_button', array($woo_pr_public, 'woo_pr_points_message_before_add_to_cart_button'));
}

class Custom_Woocommerce_Points_And_Rewards {
    private $woo_pr_public;

    public function __construct()
    {
        if (!$this->is_valid()) {
            return;
        }

        global $woo_pr_public;
        $this->woo_pr_public = $woo_pr_public;

        // Remove text on loop product
        remove_action('woocommerce_before_add_to_cart_button', array($this->woo_pr_public, 'woo_pr_points_message_before_add_to_cart_button'));

        // Ajax
        add_action('init', [$this, 'ajax']);
    }

    public function is_valid() {
        return class_exists('Woo_Pr_Model');
    }

    public function get_points() {
        if( ! $this->is_valid() ) {
            return;
        }

        if( !is_user_logged_in() ) {
            return;
        }

        global $current_user;
    
        $points = woo_pr_get_user_points( $current_user->ID );
        return $points;
    }

    public function get_points_html() {
        if( ! $this->is_valid() ) {
            return;
        }

        if( !is_user_logged_in() ) {
            return;
        }

        $points = $this->get_points();

        if( is_numeric($points) ) {
            $points = floor($points);
        }

        $html = '<div class="total-points">';
        
        $zero_points_message    = "עדיין לא צברתם נקודות";
        $current_points_message = "צברתם עד כה {$points} נקודות";
        $current_point_message = "צברתם עד כו נקודה אחת";

        $html .= '<i class="icon far fa-credit-card"></i>';
        $html .= '<p class="text">';
        $html .= $points == 0 ? $zero_points_message : ($points === 1 ? $current_point_message : $current_points_message);
        $html .= '</p>';


        $html .= '</div>';

        return $html;
    }

    public function suggest_use_points_in_checkout_html() {
        $html = '';

        if( !$this->is_valid() || !is_user_logged_in() ) {
            return $html;
        }

        echo '<div class="suggest-use-points">';
        global $woo_pr_public;
        echo $woo_pr_public->woo_pr_redeem_point_markup();
        echo '</div>';
    }

    public function ajax()
    {
        if (!is_user_logged_in()) {
            add_action('wp_ajax_nopriv_get_suggest_use_points', [$this, 'checkout_get_suggest_use_points']);
        } else {
            add_action('wp_ajax_get_suggest_use_points', [$this, 'checkout_get_suggest_use_points']);
        }
    }

    public function checkout_get_suggest_use_points()
    {
        $this->suggest_use_points_in_checkout_html();

        wp_die();
    }
}

$GLOBALS['custom_woocommerce_points_and_rewards'] = new Custom_Woocommerce_Points_And_Rewards;