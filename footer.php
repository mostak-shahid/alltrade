<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package npcoding
 */

?>

<footer id="colophon" class="site-footer">

	<div class="newsletter-container">
		<div class="newsletter-text">
			<img src="<?php echo get_template_directory_uri() . '/assets/envelope.png' ?>" />
			<div class="inline-text">
				<h3 class="text text-lg font-medium">הירשמו לניוזלטר שלנו</h3>
				<p class="text text-md color-main">כדי להנות ממבצעים, קופונים ומתנות מפנקות במיוחד</p>
			</div>
		</div>

		<?php echo newsletter_form() ?>
	</div>

	<hr class="footer-line" />

	<div class="footer-navigation-container">

		<div class="footer-menu-items-container">
			<?php
			for ($x = 1; $x <= 2; $x++) {
			?>

				<div class="footer-menu-items">
					<h3><?php echo $x === 1 ? 'קטגוריות' : 'עמודים נוספים' ?></h3>
					<?php
					if (has_nav_menu('menu-footer-' . $x)) {
						wp_nav_menu(array(
							'theme_location' => 'menu-footer-' . $x,
							'container' => 'nav',
							'container_class' => 'menu-footer-1-class',
							'items_wrap' => '<ul id="%1$s" class="menu__footer_1">%3$s</ul>',
						));
					}
					?>
				</div>
			<?php
			}
			?>
			<div class="footer-menu-items">
				<h3>צרו קשר</h3>
				<?php get_template_part('template-parts/footer-icons') ?>
			</div>
		</div>


		<div class="footer-icons-container">
			<h3>עקבו אחרינו ברשתות החברתיות </h3>
			<div class="footer-icons">
				<div class="header-icon-wrapper">
					<a target="_blank" href="https://www.youtube.com/channel/UC3c1quap7lYU-aK638xuPEw" class="footer-icon-container">
						<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/mdi_youtube.svg'); ?>
					</a>
				</div>
				<div class="header-icon-wrapper">
					<a target="_blank" href="https://www.instagram.com/get_deal_computers/" class="footer-icon-container">
						<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/mdi_instagram.svg'); ?>
					</a>
				</div>
				<div class="header-icon-wrapper">
					<a target="_blank" href="https://www.facebook.com/getgeal02" class="footer-icon-container">
						<?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/ic_baseline-facebook.svg'); ?>
					</a>
				</div>
			</div>
		</div>
	</div>

	<hr class="footer-line" />


	<div class="rights-container">
		<ul class="company-logo-container">
			<li>
				<img alt="company-american-express" loading="lazy" class="company-logo" src="<?php echo get_template_directory_uri() . '/assets/company-logos/american-express.png' ?>" />
			</li>
			<li>
				<img alt="company-paypal" loading="lazy" class="company-logo" src="<?php echo get_template_directory_uri() . '/assets/company-logos/paypal.png' ?>" />
			</li>
			<li>
				<img alt="company-pci" loading="lazy" class="company-logo" src="<?php echo get_template_directory_uri() . '/assets/company-logos/pci.png' ?>" />
			</li>
			<li>
				<img alt="company-cg" loading="lazy" class="company-logo" src="<?php echo get_template_directory_uri() . '/assets/company-logos/cg.png' ?>" />
			</li>
			<li>
				<img alt="company-visa" loading="lazy" class="company-logo" src="<?php echo get_template_directory_uri() . '/assets/company-logos/visa.png' ?>" />
			</li>
			<li>
				<img alt="company-ssl" loading="lazy" class="company-logo" src="<?php echo get_template_directory_uri() . '/assets/company-logos/ssl.png' ?>" />
			</li>
			<li>
				<img alt="company-diners-club" loading="lazy" class="company-logo" src="<?php echo get_template_directory_uri() . '/assets/company-logos/diners-club.png' ?>" />
			</li>
			<li>
				<img alt="company-mastercard" loading="lazy" class="company-logo" src="<?php echo get_template_directory_uri() . '/assets/company-logos/mastercard-logo.png' ?>" />
			</li>
		</ul>
		<div>
			<p class="text text-xs">
				@כל הזכויות שמורות ל-GetDeal
			</p>

		</div>
	</div>

	<?php get_template_part('template-parts/mini-cart-template') ?>
	<?php echo stock_notify_modal() ?>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>


<!-- <script id="__bs_script__">
	//<![CDATA[
	(function() {
		try {
			var script = document.createElement('script');
			if ('async') {
				script.async = true;
			}
			script.src = 'http://HOST:3000/browser-sync/browser-sync-client.js?v=2.29.1'.replace("HOST", location.hostname);
			if (document.body) {
				document.body.appendChild(script);
			}
		} catch (e) {
			console.error("Browsersync: could not append script tag", e);
		}
	})()
	//]]>
</script> -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<link async defer rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Splide Js -->
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" />

<script id="__bs_script__">//<![CDATA[
  (function() {
    try {
      var script = document.createElement('script');
      if ('async') {
        script.async = true;      
      }
      script.src = 'http://HOST:3000/browser-sync/browser-sync-client.js?v=3.0.2'.replace("HOST", location.hostname);
      if (document.body) {        
        document.body.appendChild(script);
      } else if (document.head) { 
        document.head.appendChild(script);
      }
    } catch (e) {
      console.error("Browsersync: could not append script tag", e); 
    }
  })()
//]]></script>

</body>
<!-- Fancy box -->

<!-- <script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.woocommerce-product-gallery__image .wp-post-image').attr('data-fancybox', 'gallery').fancybox({
			loop: false,
		});
	});
</script> -->


</html>