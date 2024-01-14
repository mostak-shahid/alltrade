<?php
function display_related_products($args)
{

?>
    <div class="related-products">


        <h2 class="related-products__title"><?php esc_html_e('מוצרים נוספים שעשויים לעניין אתכם', 'woocommerce'); ?></h2>

        <?php
        $related_products = new WP_Query($args);
        if ($related_products->have_posts()) :
            echo '<ul class="products products__related-products">';
        ?> <button class='btn-arrow-product product-arrow-right'>
                <?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/chevron_right.svg'); ?>
            </button> <?php
                        while ($related_products->have_posts()) : $related_products->the_post();
                            wc_get_template_part('content', 'product');
                        endwhile;
                        ?> <button class='btn-arrow-product product-arrow-left'>
                <?php echo file_get_contents(get_template_directory_uri() . '/assets/icons/chevron_left.svg'); ?>

            </button> <?php
                        echo '</ul>';
                    endif;
                    wp_reset_postdata();
                        ?>

    </div>

<?php

}


?>