<?php
global $woocommerce;
$issidebar = is_active_sidebar('    ');
if (is_active_sidebar('cart-sidebar')) {
?>
    <div id="cart-sidebar">
        <div class="content">

            <?php
            dynamic_sidebar('cart-sidebar');
            ?>
        </div><!--content-->
    </div>
    <!--cart-sidebar-->
<?php
}
