<?php

if (!defined('_CATEGORY_MENU_VERSION')) define('_CATEGORY_MENU_VERSION', '1.0.27');

function load_menu_category_dependecies()
{
    wp_enqueue_style('menu-category-style', get_template_directory_uri() . '/components/npcategory-menu/menu-category.css', array(), _CATEGORY_MENU_VERSION);
    wp_enqueue_style('menu-category-mobile', get_template_directory_uri() . '/components/npcategory-menu/media-mc.css', array(), _CATEGORY_MENU_VERSION);
    wp_enqueue_script('menu-category-script', get_template_directory_uri() . '/components/npcategory-menu/menu-category.js', array(), _CATEGORY_MENU_VERSION, true);
}
add_action('wp_enqueue_scripts', 'load_menu_category_dependecies');

class YA_Custom_Walker extends Walker_Nav_Menu
{

    private $category_image = '';

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $link_class_name = $depth == 1 ? 'class="ya__link-child-with-childs"' : '';

        $output .= $indent . '<li' . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        if ($depth === 0) $output .= '<div class="ya__menu-item-title">';

        $item_output = $args->before;
        $item_output .= '<a ' . $link_class_name . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        if ($depth === 0) $item_output .= '</div>';

        $menu_icon_id = get_post_meta($item->ID, 'menu-icon', true);
        $menu_icon_src = wp_get_attachment_url($menu_icon_id);
        $menu_icon_svg = !empty($menu_icon_src) ? file_get_contents($menu_icon_src) : '';
        if (!empty($menu_icon_svg) && $depth == 0) {
            $output .= $menu_icon_svg;
        }

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $category_id = 0;
        $this->category_image = '';

        if (in_array('menu-item-object-product_cat', $classes)) {
            $category_id = $item->object_id;
            $image_id = get_term_meta($category_id, 'thumbnail_id', true);
            if ($image_id) {
                $this->category_image = wp_get_attachment_url($image_id);
            }
        }
    }
    function start_lvl(&$output, $depth = 0, $args = array())
    {
        if ($depth == 1) {
            $output .= "<ul class='sub-menu__child'>\n";
        } elseif ($depth == 2) {
            return;
        } else {
            $output .= "<div class='sub-menu-container'>";
            $output .= "<div class='sub-menu-container-image'>
            <img loading='lazy' aly='menu-image' class='sub-menu-image' src='$this->category_image' />
            </div>";
            $output .= "<ul class='sub-menu'>\n";
        }
    }
    function end_lvl(&$output, $depth = 0, $args = array())
    {
        if ($depth == 1) {
            $output .= "</ul>\n";
        } elseif ($depth == 2) {
            return;
        } else {
            $output .= "</ul>\n";
            $output .= "</div>";
        }
    }
}


function category_menu_shortcode($atts)
{
    $current_cat = get_queried_object();

    $category_parent = $current_cat->parent === 0 ? $current_cat->term_id : $current_cat->parent;
    $category_parent_item = get_term($category_parent, 'product_cat');

    $args = array(
        'taxonomy' => 'product_cat',
        'parent' => $category_parent,
        'hide_empty' => 0,
    );

    $sub_categories = get_terms($args);
    $expand_more = file_get_contents(get_template_directory_uri() . '/components/npcategory-menu/icons/expand_more.svg');
    $expand_less = file_get_contents(get_template_directory_uri() . '/components/npcategory-menu/icons/expand_less.svg');

    echo '<div class="product-category-menu__container">';
    echo '<button class="product-category-menu__button">
        <p> בחירת קטגוריה </p>
        <span class="expand-more-icon expand-icon">' .  $expand_more . '</span>
        <span class="expand-less-icon expand-icon">' . $expand_less . '</span>
    </button>';

    array_unshift($sub_categories, $category_parent_item);


    if ($sub_categories) {
        echo '<ul class="product-category-menu">';
        $last_item = end($sub_categories);
        foreach ($sub_categories as $sub_category) {
            $current_active_class = $sub_category->term_id === $current_cat->term_id ? 'menu-category-active' : '';
            $parent_category = $sub_category->term_id === $category_parent ? 'הצג הכל' : $sub_category->name;

            if (!is_wp_error($sub_category)) {
                echo '<li class="product-category-menu__item ' . $current_active_class . '"><a href="' . get_term_link($sub_category->term_id, 'product_cat') . '">' . $parent_category . '</a></li>';
            }

            if ($sub_category === $last_item) {
                echo '';
            } else {
                echo '<li class="product-category-menu__seperator">|</li>';
            }
        }
        echo '</ul>';
    }
    echo '</div>';
}
add_shortcode('category_menu', 'category_menu_shortcode');


function menu_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'menu' => 'קטגוריות',
    ), $atts);


    $menu = wp_get_nav_menu_object($atts['menu']);

    if (!$menu) {
        return '';
    }


    $args = array(
        'menu' => $menu->term_id,
        'walker' => new YA_Custom_Walker()

    );

    $menu_html = wp_nav_menu($args);

    echo $menu_html;
}

add_shortcode('ya_main_menu', 'menu_shortcode');
