<?php

class Mobile_Menu_Walker extends Walker_Nav_Menu
{


    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = join(' ', apply_filters('mobile__nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $has_chidrens = in_array("menu-item-has-children", $item->classes);

        $no_children_class = !$has_chidrens && $depth === 0 ? ' mobile_ya__no-children' : '';

        if ($depth == 0) $list_item_class_name = ' mobile__ya__list-item__parent';
        if ($depth == 1) $list_item_class_name = ' mobile__ya__list-item__first-child';
        if ($depth == 2) $list_item_class_name = ' mobile__ya__list-item__second-child';

        $class_names = $class_names ? ' class="' . esc_attr($class_names) .  $list_item_class_name . $no_children_class . '"' : '';

        $link_class_name = '';

        if ($depth == 0 && $has_chidrens) $link_class_name = 'class="mobile__ya__link-first_parent"';
        if ($depth == 1 && $has_chidrens) $link_class_name = 'class="mobile__ya__link-child-with-childs"';


        $output .= $indent . '<li' . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';

        if (!$has_chidrens) $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        $item_output = $args->before;
        $item_output .= '<a ' . $link_class_name . $attributes . '>';

        $menu_icon_id = get_post_meta($item->ID, 'menu-icon', true);
        $menu_icon_src = wp_get_attachment_url($menu_icon_id);
        $menu_icon_svg = !empty($menu_icon_src) ? file_get_contents($menu_icon_src) : '';
        if (!empty($menu_icon_svg) && $depth == 0) {
            $item_output .= $menu_icon_svg;
        }

        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;

        if ($has_chidrens && $depth == 0)  $item_output .= '<span class="mobile-menu__toggle-icon close"></span>';

        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'mobile__menu-item-' . $item->ID;
    }
    function start_lvl(&$output, $depth = 0, $args = array())
    {
        if ($depth == 1) {
            $output .= "<ul class='mobile__sub-menu__child'>\n";
        } elseif ($depth == 2) {
            return;
        } else {
            $output .= "<div class='mobile__sub-menu-container'>";
            $output .= "<ul class='mobile__sub-menu'>\n";
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



function enqueue_custom_scripts()
{
    $directory = get_template_directory_uri();
    wp_enqueue_style('custom-style-mobile-menu', $directory . '/components/mobile-menu/style.css');
    wp_enqueue_script('mobile-menu-script', get_template_directory_uri() . '/components/mobile-menu/script.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
?>

<?php

function mobile_menu_component()
{
    ob_start()
?>
    <div class="mobile-menu__container">

        <div class="mobile-menu__header">

            <div class="shipping-item">
                <?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/local_shipping.svg'); ?>
                <p class="text">משלוחים לכל הארץ</p>
            </div>

            <div class="header-input-container">
                <?php echo do_shortcode('[fibosearch]'); ?>
            </div>

            <div class="header-buttons-container">
                <button class="btn btn-header btn-arrow font-sm font-thin">
                    <span>מבצעים חמים</span>
                    <?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/arrow_left.svg'); ?>
                </button>
                <button class="btn btn-header btn-arrow font-sm font-thin">
                    <span>מחשבים חדשים</span>
                    <?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/arrow_left.svg'); ?>
                </button>
            </div>

        </div>

        <div class="mobile-menu__nav">
            <?php wp_nav_menu(array(
                'menu' => 'main',
                'walker' => new Mobile_Menu_Walker()

            )) ?>

        </div>

        <div class="mobile-menu__footer">

        </div>


    </div>



<?php

    $html_content = ob_get_clean(); // Get the buffered content and clean the buffer
    return $html_content;
}
