<?php
$page_title = get_query_var('page_title');
$is_image = get_query_var('is_image');;
$page_subtitle = get_query_var('page_subtitle');;


$image_url = wp_get_attachment_url(130);
$image = $is_image ? "background-image: url('$image_url');" : '';
$header_class = $is_image ? "image-active" : "no-image"


?>


<header style="<?php echo $image ?>" class="single-page-header <?php echo $header_class ?>">
    <h1 class="single-page-header__title"><?php echo $page_title ?></h1>
    <?php echo !empty($page_subtitle) ? "<h2>$page_subtitle</h2>" : ''; ?>
</header>




<?php



?>