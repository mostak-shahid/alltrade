<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package npcoding
 */

?>
<!doctype html>
<html lang="he">

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />


	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> dir="rtl">
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<?php echo floating_sale_component() ?>


		<?php echo mobile_menu_component() ?>
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'alltrade'); ?></a>

		<header id="masthead" class="site-header">
			<div class="site-header-container">

				<div class="sale-header">
					<p class="font-xs">לרגל ההשקה 10% על כל האתר! GetDeal</p>
				</div>

				<div class="main-header">

					<div class="menu-icon__toggle">
						<span class="menu-icon__line menu-icon__line-a"></span>
						<span class="menu-icon__line menu-icon__line-b"></span>
						<span class="menu-icon__line menu-icon__line-c"></span>
					</div>

					<div class="header-logo">
						<?php the_custom_logo() ?>
					</div>

					<div class="header-buttons-container">
						<button class="btn btn-header btn-arrow font-sm font-thin">
							<span>מבצעים חמים</span>
							<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/arrow_left.svg'); ?>
						</button>
						<button class="btn btn-header btn-arrow font-sm font-thin">
							<span>מחשבים חדשים</span>
							<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/arrow_left.svg'); ?>
						</button>
					</div>

					<div class="header-input-container">
						<?php echo do_shortcode('[fibosearch]'); ?>
					</div>

					<div class="shipping-item">
						<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/local_shipping.svg'); ?>
						<p class="text">משלוחים לכל הארץ</p>
					</div>

					<div class="header-icons">
						<div class="header-icon-wrapper ya__contact-icon-container">
							<a href="<?php echo get_site_url() . '/contact' ?>" class="header-icon-container ya__icon-contact">
								<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/call.svg'); ?>
							</a>
							<p class="text text-xs">צור קשר</p>
						</div>
						<div class="header-icon-wrapper">
							<a href="<?php echo get_site_url() . '/my-account' ?>" class="header-icon-container">
								<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/account_circle.svg'); ?>
							</a>
							<p class="text text-xs">פרופיל</p>
						</div>
						<div id="cart" class="header-icon-wrapper header-icon__cart-wrapper">
							<a class="header-icon-container">
								<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/shopping_bag.svg'); ?>
							</a>
							<p class="text text-xs">עגלה</p>
							<?php
							$countCart = getNumberOfItemsInTheCart();
							$classes = $countCart > 0 ? "" : "hide";
							?>
							<span class="header-icon-container__cart-counter <?php echo $classes; ?>"><?php echo $countCart; ?></span>
						</div>
					</div>
				</div>
			</div>

			<div class="menu-nav-container">
				<?php
				wp_nav_menu([
					"menu" => 'main',
					"walker" => new YA_Custom_Walker()
				]);

				?>
			</div>




		</header><!-- #masthead -->

		<!-- 
		"browser": "browser-sync start --proxy 'localhost:10063/' --files 'templates/.html, *.php, *.css, */.php, build/.js, build/*.css'" -->