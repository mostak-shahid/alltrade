<?php
function enqueue_custom_style_related_products()
{
    $directory = get_template_directory_uri();
    wp_enqueue_script('slider-related-products-script', $directory . '/components/related-products-slider/script.js', array(), '1.0.1', true);
    wp_enqueue_style('custom-style-related-products', $directory . '/components/related-products-slider/style.css');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_style_related_products');
?>

<?php

function related_products_slider_component($args, $title)
{
    ob_start(); // Start output buffering

?>

    <div class="related-products-slider related-products__container">
        <h3 class="related-products__container__title"><?php echo $title ?></h3>

        <button class="swiper-button-next-company"></button>
        <button class="btn-next-btn-company btn-arrow btn-next-company">
            <?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/chevron_right.svg'); ?>
        </button>
        <div class="swiper swiper-related-products">

            <div class="swiper-wrapper">
                <?php
                $related_products = new WP_Query($args);
                if ($related_products->have_posts()) :
                    while ($related_products->have_posts()) : $related_products->the_post();

                ?>
                        <div class="swiper-slide">
                            <?php

                            wc_get_template_part('content', 'product');

                            ?>
                        </div>
                <?php


                    endwhile;
                endif;
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <button class="swiper-button-prev-company"></button>
        <button class="btn-prev-btn-company btn-arrow btn-prev-company">
            <?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/chevron_left.svg'); ?>
        </button>
    </div>
<?php

    $html_content = ob_get_clean();
    return $html_content;
}


?>