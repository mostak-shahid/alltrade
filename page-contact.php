<?php get_header();

$page_title = 'צור קשר';
$is_image = true;

set_query_var('page_title', $page_title);
set_query_var('is_image', $is_image);

get_template_part('content', 'page-header');

?>
<div class="contact-container">
    <div class="form-container">
        <h2 class="form-container__title">צרו איתנו קשר</h2>
        <?php echo do_shortcode('[contact-form-7 id="170" title="Contact form-contact"]') ?>
    </div>

    <div style="background-image: url(	<?php echo wp_get_attachment_image_src(168, 'full')[0]; ?>);" class="contact-deatils">
        <h2>צרו איתנו קשר</h2>
        <?php get_template_part('template-parts/footer-icons') ?>

        <div class="contact-deatils__links">
            <a href="https://allrecycling.co.il">allrecycling.co.il</a>
            <a href="https://allrecycling.co.il">alltrade.co.il</a>
        </div>
    </div>
</div>
<?php

get_footer();
