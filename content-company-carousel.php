<div class="companies-container">
    <div class="companies-container__wrapper">
        <button class="swiper-button-next-company_pc"></button>
        <button class="btn-next-btn-company_pc btn-arrow">
            <?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/chevron_right.svg'); ?>
        </button>
        <div class="swiper swiper-companies">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img alt="image-slider" loading="lazy" src="<?php echo wp_get_attachment_image_src(51, 'full')[0]; ?>" />
                </div>
                <div class="swiper-slide">
                    <img alt="image-slider" loading="lazy" src="<?php echo wp_get_attachment_image_src(53, 'full')[0]; ?>" />
                </div>
                <div class="swiper-slide">
                    <img alt="image-slider" loading="lazy" src="<?php echo wp_get_attachment_image_src(54, 'full')[0]; ?>" />
                </div>
                <div class="swiper-slide">
                    <img alt="image-slider" loading="lazy" src="<?php echo wp_get_attachment_image_src(52, 'full')[0]; ?>" />
                </div>
                <div class="swiper-slide">
                    <img alt="image-slider" loading="lazy" src="<?php echo wp_get_attachment_image_src(51, 'full')[0]; ?>" />
                </div>
                <div class="swiper-slide">
                    <img alt="image-slider" loading="lazy" src="<?php echo wp_get_attachment_image_src(53, 'full')[0]; ?>" />
                </div>
                <div class="swiper-slide">
                    <img alt="image-slider" loading="lazy" src="<?php echo wp_get_attachment_image_src(54, 'full')[0]; ?>" />
                </div>
                <div class="swiper-slide">
                    <img alt="image-slider" loading="lazy" src="<?php echo wp_get_attachment_image_src(52, 'full')[0]; ?>" />
                </div>
            </div>
        </div>
        <button class="swiper-button-prev-company_pc"></button>
        <button class="btn-prev-btn-company_pc btn-arrow">
            <?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/chevron_left.svg'); ?>
        </button>
    </div>
</div>