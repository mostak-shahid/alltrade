<?php
function enqueue_custom_style()
{
    $directory = get_template_directory_uri();
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/components/company-slider/style.css');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_style');
?>

<?php

function company_slider_component()
{
?>
    <div class="company-category-slider">
        <button class="swiper-button-next"></button>
        <button class="btn-next-btn btn-arrow btn-next-company">
            <?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/chevron_right.svg'); ?>
        </button>
        <div class="swiper swiper-category-companies">

            <div class="swiper-wrapper">
                <?php
                $header_banners = get_header_banners('company-icon');

                if ($header_banners->have_posts()) {
                    while ($header_banners->have_posts()) {
                        $header_banners->the_post();
                        $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        $category_link = get_post_meta(get_the_ID(), 'category-link', true);

                ?>
                        <div class="swiper-slide">
                            <a href="<?php echo $category_link ?>">

                                <div class="company-category-slider__slider-container">
                                    <img loading="lazy" src="<?php echo $thumbnail_url ?>" alt="<?php get_the_title() ?>;" />
                                    <p><?php echo get_the_title() ?></p>
                                </div>
                            </a>

                        </div>
                <?php
                    }
                    wp_reset_postdata();
                }

                ?>
            </div>
        </div>
        <button class="swiper-button-prev"></button>
        <button class="btn-prev-btn btn-arrow btn-prev-company">
            <?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/chevron_left.svg'); ?>
        </button>
    </div>


<?php

}

?>