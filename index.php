<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package npcoding
 */

get_header();
?>

<div style="background-image: url(	<?php echo wp_get_attachment_image_src(136, 'full')[0]; ?>);" class="header-banner">
	<?php echo floating_discount_component() ?>

	<div class="hero-banner-container">
		<div class="swiper mySwiper">
			<div class="swiper-wrapper">
				<?php
				$header_banners = get_header_banners('header');

				if ($header_banners->have_posts()) {
					while ($header_banners->have_posts()) {
						$header_banners->the_post();
						$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
				?>
						<div class="swiper-slide"> <img loading="lazy" src="<?php echo $thumbnail_url ?>" alt="<?php get_the_title() ?>;" /></div>
				<?php
					}
					wp_reset_postdata();
				}

				?>
			</div>
			<div class="swiper-pagination"></div>
		</div>
		<div class="header-banner__left-banner">
			<?php
			$header_banners = get_header_banners('header-static');

			if ($header_banners->have_posts()) {
				while ($header_banners->have_posts()) {
					$header_banners->the_post();
					$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
			?>
					<img alt="header-banner" loading="lazy" src="<?php echo $thumbnail_url ?>" />
			<?php
				}
				wp_reset_postdata();
			}

			?>

		</div>
	</div>
	<div class="header-banner__bottom">
		<button data-category="top-10" class="header-banner__bottom__button active category-btn">
			<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/workspace_premium.svg'); ?>
			<p>
				טופ 10
			</p>
		</button>
		<button data-category="sale" class="header-banner__bottom__button category-btn">
			<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/sell.svg'); ?>
			<p>
				מבצעים
			</p>
		</button>
		<button data-category="new" class="header-banner__bottom__button category-btn category-btn__new">
			<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/star.svg'); ?>
		</button>
		<button data-category="top-reviews" class="header-banner__bottom__button category-btn">
			<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/reviews.svg'); ?>
			<p>
				ביקורות
			</p>
		</button>
	</div>
</div>

<div class="products-container">
	<?php if (woocommerce_product_loop()) {
		$args = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'meta_query' => array(),
			'posts_per_page' => '12'

		);

		$query = new WP_Query($args);
		echo '<ul class="products columns-4 grid-column-4 col-1">';
		echo '</ul>';
		echo company_slider_component();
	?>


	<?php

		echo '<ul class="products columns-4 grid-column-4 col-2">';
		echo '</ul>';
	} ?>
	<div class="load-more-btn-container">
		<button class="load-more-btn btn btn-header font-sm">טען מוצרים נוספים</button>
	</div>

</div>

<?php get_template_part('content', 'choose-us'); ?>


<?php get_template_part('content', 'company-carousel'); ?>


<a href="#">
	<div style="background-image: url(	<?php echo wp_get_attachment_image_src(48, 'full')[0]; ?>);" class="bottom-banner">
	</div>
</a>

<a href="#">
	<div class="bottom-video">
		<img alt="video-bottom" loading="lazy" src="<?php echo wp_get_attachment_image_src(161, 'full')[0]; ?>" />

	</div>
</a>



</main>


<!-- #main -->

<?php
get_footer();
