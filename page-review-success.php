<?php get_header();

$page_title = 'תודה, הביקורת שלכם נשלחה';
$page_subtitle = '';
$is_image = false;

set_query_var('page_title', $page_title);
set_query_var('page_subtitle', $page_subtitle);
set_query_var('is_image', $is_image);

get_template_part('content', 'page-header');

?>

<div class="related-product-success-page-container">

    <?php
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 8,
        'orderby' => 'rand',
        'post__not_in' => array(get_the_ID()),
        'meta_query' => WC()->query->get_meta_query(),
        'tax_query' => WC()->query->get_tax_query(),
    );

    echo related_products_slider_component($args, 'מוצרים נוספים שעשויים לעניין אתכם')

    ?>

    <button class="related-product-success-page__show-more-btn">מעבר לכל המוצרים</button>
</div>

<?php

get_footer();
