<?php

/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

defined('ABSPATH') || exit;

global $product;

if (!comments_open()) {
    return;
}

?>
<div id="reviews" class="woocommerce-Reviews">
    <div id="comments">
        <h2 class="woocommerce-Reviews-title">
            <?php
            $count = $product->get_review_count();
            if ($count && wc_review_ratings_enabled()) {
                /* translators: 1: reviews count 2: product name */
                echo 'חוות דעת';
            } else {
                esc_html_e('Reviews', 'woocommerce');
            }
            ?>
        </h2>

        <div class="woocommerce-product-rating">
            <p>
                <?php echo $count ?> חוות דעת
            </p>
            <p>
                <?php woocommerce_template_loop_rating(); ?>
            </p>
        </div>


        <?php if (have_comments()) : ?>
            <ul class="commentlist">
                <?php wp_list_comments(apply_filters('woocommerce_product_review_list_args', array('callback' => 'woocommerce_comments'))); ?>
            </ul>

            <?php
            if (get_comment_pages_count() > 1 && get_option('page_comments')) :
                echo '<nav class="woocommerce-pagination">';
                paginate_comments_links(
                    apply_filters(
                        'woocommerce_comment_pagination_args',
                        array(
                            'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
                            'next_text' => is_rtl() ? '&larr;' : '&rarr;',
                            'type'      => 'list',
                        )
                    )
                );
                echo '</nav>';
            endif;
            ?>
        <?php else : ?>
            <p class="woocommerce-noreviews"><?php esc_html_e('There are no reviews yet.', 'woocommerce'); ?></p>
        <?php endif; ?>
    </div>


    <div class="clear"></div>
</div>















//// old carousel backup

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