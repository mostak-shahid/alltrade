<?php
function enqueue_custom_style_company()
{
    $directory = get_template_directory_uri();
    wp_enqueue_script('slider-company-script', $directory . '/components/company-slider/script.js', array(), '1.0.1', true);
    wp_enqueue_style('custom-style-company-slider', $directory . '/components/company-slider/style.css');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_style_company');
?>

<?php

function company_slider_component()
{
    ob_start(); // Start output buffering

?>
    <div class="company-category-slider">
        <button class="swiper-button-next-company"></button>
        <button class="btn-next-btn-company btn-arrow btn-next-company">
            <?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/chevron_right.svg'); ?>
        </button>
        <div class="swiper swiper-category-companies">
            <div class="swiper-wrapper">
                <?php
                $header_banners = get_header_banners('company-icon');
                for ($i = 0; $i < 2; $i++) {
                    if ($header_banners->have_posts()) {
                        while ($header_banners->have_posts()) {
                            $header_banners->the_post();
                            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                            $category_link = get_post_meta(get_the_ID(), 'category-link', true);
                ?>
                            <div class="swiper-slide">
                                <a href="<?php echo site_url() . '/product-category/' . get_the_title() ?>">
                                    <div class="company-category-slider__slider-container">
                                        <div class="company-category-slider__image-container">
                                            <img loading="lazy" src="<?php echo $thumbnail_url ?>" alt="<?php get_the_title() ?>;">
                                        </div>
                                        <p><?php echo get_the_title() ?></p>
                                    </div>
                                </a>
                            </div>
                <?php
                        }
                        wp_reset_postdata();
                    }
                }
                ?>
            </div>
        </div>
        <button class="swiper-button-prev-company"></button>
        <button class="btn-prev-btn-company btn-arrow btn-prev-company">
            <?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/chevron_left.svg'); ?>
        </button>
    </div>
<?php

    $html_content = ob_get_clean(); // Get the buffered content and clean the buffer
    return $html_content;
}


?>