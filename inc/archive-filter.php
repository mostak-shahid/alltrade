<?php

function get_sale_args($page)
{
    $sale_args = array(
        'post_type'      => 'product_variation',
        'posts_per_page' => 20,
        'paged' => $page,
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'key'     => '_sale_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'numeric'
            ),
            array(
                'key'     => '_price',
                'value'   => array('', 0),
                'compare' => 'NOT IN'
            )
        )
    );
    return $sale_args;
}

function get_new_args($page)
{
    $new_args = array(
        'post_type'      => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 20,
        'paged' => $page,
        'date_query'     => array(
            array(
                'after' => '10 days ago',
            )
        ),
    );
    return $new_args;
}

function get_reviews_args($page)
{
    global $wpdb;
    $product_ids_with_reviews = $wpdb->get_col("
    SELECT p.ID
    FROM $wpdb->posts p
    WHERE p.post_type = 'product' 
    AND p.comment_count > 1
    ORDER BY p.comment_count DESC
");


    $args = array(
        'post_type'      => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 20,
        'paged' => $page,
        'post__in' => $product_ids_with_reviews
    );
    return  $args;
}

function get_top_ten_products($page, $category)
{

    $args = array(
        'post_type'      => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 10,
        'paged' => $page,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => 42,
                'operator' => 'IN',
            ),
        )
    );
    return  $args;
}

function archive_filter()
{
    $output = '';
    $output_last = '';


    if (isset($_POST)) {
        $filter_object = $_POST['filter__data'];
        $current_category = $_POST['category__page'];
        $current_page = $_POST['page__number'];
        $is_home = boolval($_POST['is_home']);
        $is_initial = boolval($_POST['is_initial']);
        $special_filter = ($_POST['special__filter']);
        $is_special = !empty($special_filter);

        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'meta_query' => array(),
            'paged' => $current_page,
            'posts_per_page' => '20'

        );

        if (!empty($current_category)) {
            $args['tax_query'][] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $current_category,
                    'operator' => 'IN',
                ),
            );
        }

        foreach ($filter_object as $sortItem) {

            $values = array_values($sortItem)[0];
            if (strpos($values, ',')) {
                $values = explode(',', $values);
            };
            $args['meta_query'][] = array(
                'key' => array_keys($sortItem)[0],
                'value' => $values,
                'compare' => is_array($values)  ? 'IN' : 'LIKE',
            );
        }


        if ($is_special) {
            if ($special_filter === 'sale') {
                $query = new WP_Query(get_sale_args($current_page));
            }
            if ($special_filter === 'new') {
                $query = new WP_Query(get_new_args($current_page));
            }
            if ($special_filter === 'top-reviews') {
                $query = new WP_Query(get_reviews_args($current_page));
            }
            if ($special_filter === 'top-10') {
                $query = new WP_Query(get_top_ten_products($current_page, $special_filter));
            }
        } else {
            $query = new WP_Query($args);
        }
        $count = 0;


        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                ob_start();
                wc_get_template_part('content', 'product');
                if ($is_home && $is_initial) {
                    if (
                        $count >= 8
                    ) {
                        $output_last .= ob_get_clean();
                    } else {
                        $output .= ob_get_clean();
                    }
                } else {
                    $output .= ob_get_clean();
                }
                $count += 1;
            }
        } else {
            $output = '<p class="products-filter__no-products-found">לא נמצאו מוצרים מתאימים לחיפוש זה </p>';
        }

        $count = 0;

        wp_reset_postdata();

        $remaining_products = $query->found_posts - ($current_page * $args['posts_per_page']);

        $company_slider = company_slider_component();

        echo json_encode(
            array(
                'output_first' => $output,
                'output_last' => $output_last,
                'slider' => $company_slider,
                'remaining_products' => $remaining_products,
                'post_count' =>  $query->post_count
            )
        );
        exit;
    }
}

add_action('wp_ajax_filter__data', 'archive_filter');
add_action('wp_ajax_nopriv_filter__data', 'archive_filter');
