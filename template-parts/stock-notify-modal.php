<?php
function stock_notify_modal()
{
    ob_start();
?>
    <div class="stock-notify__overlay">
        <div class="stock-notify__container">
            <div class="stock-notify__title">
                <h3>הודיעו לי כשהמוצר יחזור למלאי</h3>
            </div>
            <div class="stock-notify__product">
                <div class="stock-notify__product__image-container">
                    <img loading="lazy" alt="product-image" class="stock-notify__product__image" src="" />
                </div>
                <p class="stock-notify__product__title"></p>
            </div>
            <div class="stock-notify__form">
                <?php echo do_shortcode('[contact-form-7 id="3366" title="stock-notify"]') ?>
            </div>
            <button class="stock-notify__btn-close">x</button>
        </div>
    </div>
<?php
    $html_content = ob_get_clean();
    return $html_content;
}


?>