<?php
function filterProductArchive($title, $type, $options)
{
?>
    <div data-type="<?php echo $type ?>" class="inner-filter-container__title expand-btn">
        <h4 class="text text-sm text-black font-medium"><?php echo $title ?></h4>
        <img loading="lazy" alt="chevron down" class="" src="<?php echo get_template_directory_uri() . '/assets/chevron-down.png' ?>" />
    </div>
    <div class="inner-filter-container__option-container filter-<?php echo $type ?>  ">
        <?php

        foreach ($options as $processor_option) {
            echo "
        <div class=inner-filter-container__option>
          <input type=checkbox data-type='$type' data-filter='$processor_option' class=input-filter> </input>
          <label> $processor_option </label>
        </div>";
        }
        ?>
    </div>
<?php
}
?>