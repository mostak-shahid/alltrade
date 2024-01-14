<?php get_header();

$page_title = 'קריאת שירות';
$is_image = true;

set_query_var('page_title', $page_title);
set_query_var('is_image', $is_image);

get_template_part('content', 'page-header');

?>

<div class="form-container">
    <?php echo do_shortcode('[contact-form-7 id="154" title="Contact form 1"]') ?>
</div>



<?php

get_footer();
