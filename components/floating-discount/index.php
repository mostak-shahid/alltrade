<?php
function floating_discount_enqueue_custom_style()
{
    $directory = get_template_directory_uri();
    wp_enqueue_style('custom-style-floating-discount', $directory . '/components/floating-discount/style.css');
    wp_enqueue_script('floating-discount-script', get_template_directory_uri() . '/components/floating-discount/script.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'floating_discount_enqueue_custom_style');
?>

<?php

function floating_discount_component()
{
    ob_start();
?>
    <?php if (is_active_sidebar('discount-floating')) : ?>
        <div class="ya-floating-discount">
            <div id="floating-discount-sidebar" class="floating-discount-sidebar widget-area" role="complementary">
                <?php dynamic_sidebar('discount-floating'); ?>
            </div>
            <div class="ya-floating-discount-exit"></div>
        </div>
    <?php endif; ?>
<?php

    $html_content = ob_get_clean();
    return $html_content;
}

?>