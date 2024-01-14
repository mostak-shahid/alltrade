<?php
/*
Template Name: Review Form
*/

get_header();

$product_id  = '';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
};

$product = wc_get_product($product_id);
$product_name = $product->get_name();
$product_image_id = $product->get_image_id();

?>

<div class="product-review-form">
    <div>
        <h2>
            ביקורת חדשה למוצר
        </h2>
        <p>
            אנו מודים לך על רכישתך בחנותנו. נעריך את שיתוף דעתך לגבי השירות שלנו.
        </p>
        <div class="ya-review__prodcut-image-text">
            <img src="<?php echo wp_get_attachment_image_src($product_image_id, 'full')[0]; ?>" />
            <?php echo $product_name ?></h5>
        </div>
    </div>

    <form id="product-review-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="submit_product_review">
        <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">

        <label for="reviewer-name">שם מלא</label>
        <input type="text" name="reviewer_name" id="reviewer-name" required>

        <label for="rating">דירוג</label>
        <select name="rating" id="rating" required>
            <option value="">בחירת דירוג</option>
            <option value="1">1 כוכב</option>
            <option value="2">2 כוכבים</option>
            <option value="3">3 כוכבים</option>
            <option value="4">4 כוכבים</option>
            <option value="5">5 כוכבים</option>
        </select>

        <label for="comments">ביקורת חדשה</label>
        <textarea name="comments" id="comments" required></textarea>
        <input type="submit" value="צור ביקורת" class="ya__review-btn-submit">
    </form>
</div>


<?php
get_footer();
