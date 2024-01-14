<?php
function enqueue_custom_filter_style()
{
    $directory = get_template_directory_uri();
    wp_enqueue_style('custom-filter-style', $directory . '/components/products-filter/style.css');
    wp_enqueue_script('filter-mobile-script', $directory . '/components/products-filter/script.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_filter_style');
?>




<?php

function simpleHash($str, $num)
{
    $sum = 0;
    for ($i = 0; $i < strlen($str); $i++) {
        $sum += ord($str[$i]);
    }
    return $sum * $num;
}

function filterProductFunction($title, $type, $options)
{
?>
    <div data-type="<?php echo $type ?>" class="inner-filter-container__title expand-btn">
        <h4 class="text text-sm text-black font-medium"><?php echo $title ?></h4>
        <img loading="lazy" alt="chevron-down" class="" src="<?php echo get_template_directory_uri() . '/assets/chevron-down.png' ?>" />
    </div>
    <div class="inner-filter-container__option-container expanded filter-<?php echo $type ?> ">
        <?php

        foreach ($options as $processor_option) {

            $trimmed_filter =
                simpleHash($processor_option->meta_value, 4);

            if (strpos($processor_option->meta_value, '}') || empty($processor_option->meta_value)) {
                continue;
            } else {
                echo "
        <div class=inner-filter-container__option>
          <input type=checkbox id='$trimmed_filter' data-type='$type' data-filter='" . $processor_option->meta_value . "' class=input-filter> </input>
          <label>("  . $processor_option->count   . ") " . $processor_option->meta_value . " </label>
        </div>";
            }
        }
        ?>
    </div>
<?php
}
?>



<?php

function products_filter()
{
    ob_start();

    $filter_list = array(
        array(
            'meta' => 'processor',
            'title' => 'מעבד'
        ),
        array(
            'meta' => 'operation-system',
            'title' => 'מערכות הפעלה'
        ),
        array(
            'meta' => 'ram',
            'title' => 'נפח זיכרון RAM'
        ),
        array(
            'meta' => 'storage',
            'title' => 'נפח דיסק קשיח'
        ),
        array(
            'meta' => 'screen-size',
            'title' => 'גודל מסך'
        ),
        array(
            'meta' => 'gpu',
            'title' => 'כרטיס מסך'
        )
    );

    function getMetaOptions($meta)
    {
        $current_category = get_queried_object();
        $category_id = $current_category->term_id;
        global $wpdb;
        $query = "
            SELECT meta_value, COUNT(*) AS count
            FROM wp_postmeta
            WHERE meta_key = %s";

        $params = [$meta];
        if ($category_id !== null) {
            $query .= "
                AND post_id IN (
                    SELECT object_id
                    FROM wp_term_relationships
                    WHERE term_taxonomy_id = %d
                )";
            $params[] = $category_id;
        }
        $query .= "
            GROUP BY meta_value";
        $prepared_query = $wpdb->prepare($query, ...$params);
        $results = $wpdb->get_results($prepared_query, OBJECT);

        return $results;
    }

?>
    <div class="product-filter-sidebar">
        <div class="product-filter-sidebar__sticky">
            <div class="inner-filter-container">
                <div class="inner-filter-container__header">
                    <h3 class="inner-filter-container__filter_title">סינון</h3>
                    <div class="inner-filter-container__icons">
                        <span class="inner-filter-container__filter_icon"></span>
                        <span class="inner-filter-container__exit_icon"></span>
                    </div>
                </div>
                <?php
                foreach ($filter_list as $filter) {
                    $title = $filter['title'];
                    $meta = $filter['meta'];
                    filterProductFunction($title, $meta, getMetaOptions($meta));
                }
                ?>
                <button class="inner-filter-container__show-results-btn">הצג תוצאות</button>
            </div>
        </div>
    </div>
    <div class="product-filter__filter-values">
    </div>

<?php
    $html_content = ob_get_clean();
    return $html_content;
}

?>