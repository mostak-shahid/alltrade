<?php
function textIconContainer($title, $icon)
{
?>
    <div class="choose-us-container__card">
        <?php echo file_get_contents(get_template_directory_uri() . "/assets/icons/$icon.svg"); ?>
        <h3> <?php echo $title ?></h3>

    </div>
<?php
}
?>