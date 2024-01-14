<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package npcoding
 */

get_header();
?>

<main id="primary" class="site-main">

	<section class="error-404 not-found">

		<h2>שגיאת 404, דף זה לא נמצא</h2>
		<a href="<?php echo home_url() ?>">חזרה לדף הבית</a>

	</section>

</main>

<?php
get_footer();
