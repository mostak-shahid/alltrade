<?php
function mini_cart_script__new()
{
    wp_enqueue_style('custom-style-mini-cart', get_template_directory_uri() . '/components/mini-cart-code/mini-cart/style.css');

    wp_enqueue_script('mini-cart', get_template_directory_uri() . '/components/mini-cart-code/mini-cart/js/script.js', array('jquery'), '1.0.3', true);
}
add_action('wp_head', 'mini_cart_script__new');

function skip_cart_redirect()
{
    // Redirect to checkout (when cart is not empty)
    if (is_cart()) {
        if (!WC()->cart->is_empty()) {
            wp_safe_redirect(wc_get_checkout_url());
            exit();
        }
        // Redirect to shop if cart is empty
        elseif (WC()->cart->is_empty()) {
            wp_safe_redirect(get_home_url());
            exit();
        }
    }
}
add_action('template_redirect', 'skip_cart_redirect');


class My_Custom_WC_Widget_Cart extends WC_Widget_Cart
{

    public function widget($args, $instance)
    {
        $original_title = $instance['title'];

        echo '<div class="cart-title-wrapper">';
        echo '<h2 class="widgettitle">' . apply_filters('my_custom_wc_cart_title', $original_title) . '</h2>';
        echo '<button class="cart-link">×</button>'; // Add your button text here
        echo '</div>';
        $instance['title'] = ''; // we will manually print the title
        parent::widget($args, $instance);
    }
}

function my_custom_wc_widgets()
{
    unregister_widget('WC_Widget_Cart'); // remove original widget
    register_widget('My_Custom_WC_Widget_Cart'); // register your custom widget
}

add_action('widgets_init', 'my_custom_wc_widgets');

function my_custom_wc_cart_title($title)
{
    return 'סל הקניות'; // replace with your new title
}

add_filter('my_custom_wc_cart_title', 'my_custom_wc_cart_title');
