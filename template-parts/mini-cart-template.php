<?php
global $woocommerce;
$issidebar = is_active_sidebar('cart-sidebar');
if ($issidebar) {
?>
    <div id="cart-sidebar">
        <div class="content">

            <?php
            dynamic_sidebar('cart-sidebar');
            ?>
        </div>
    </div>

<?php
}
