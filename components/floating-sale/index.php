<?php
function floating_sale_enqueue_custom_style()
{
    $directory = get_template_directory_uri();
    wp_enqueue_style('custom-style-floating-sale', $directory . '/components/floating-sale/style.css');
    wp_enqueue_script('floating-sale-script', get_template_directory_uri() . '/components/floating-sale/script.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'floating_sale_enqueue_custom_style');
?>

<?php

function floating_sale_component()
{
    $category_permalink = get_term_link(42, 'product_cat');

    ob_start();
?>
    <?php if (is_active_sidebar('sale-floating')) : ?>
        <a style="color:black" href="<?php echo $category_permalink  ?>">
            <div class="ya-floating-sale">
                <div class="ya-floating-sale__container">
                    <div id="floating-sale-sidebar" class="floating-sale-sidebar widget-area" role="complementary">
                        <?php dynamic_sidebar('sale-floating'); ?>
                    </div>
        </a>
        <div class="ya-floating-sale__content_container">
            <?php dynamic_sidebar('sale-floating-image'); ?>
        </div>
        <div class="ya-floating-sale-exit"></div>
        </div>
        </div>
    <?php endif; ?>
<?php

    $html_content = ob_get_clean();
    return $html_content;
}

?>