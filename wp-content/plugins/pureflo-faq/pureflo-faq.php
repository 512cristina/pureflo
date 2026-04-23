<?php
/**
 * Plugin Name: PureFlo FAQ
 * Description: Minimal FAQ system (CPT + Taxonomy + Shortcode, no forced styling)
 * Version: 1.0
 * Author: PureFlo
 * Instructions:  in DCT use [pureflo_faq category="general-questions"] to display just one category
 */

// REGISTER FAQ POST TYPE
function pureflo_register_faq() {
    register_post_type('faq', [
        'labels' => [
            'name' => 'FAQs',
            'singular_name' => 'FAQ'
        ],
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-editor-help',
        'supports' => ['title', 'editor', 'page-attributes'],
        'rewrite' => ['slug' => 'faq'],
        'show_in_rest' => true
    ]);
}
add_action('init', 'pureflo_register_faq');


// REGISTER CATEGORY TAXONOMY
function pureflo_register_faq_taxonomy() {
    register_taxonomy('faq_category', 'faq', [
        'labels' => [
            'name' => 'FAQ Categories',
            'singular_name' => 'FAQ Category'
        ],
        'hierarchical' => true,
        'public' => true,
        'rewrite' => ['slug' => 'faq-category'],
        'show_in_rest' => true
    ]);
}
add_action('init', 'pureflo_register_faq_taxonomy');


// SHORTCODE OUTPUT
function pureflo_faq_shortcode($atts) {

    $atts = shortcode_atts([
        'category' => '',
    ], $atts);

    $args = [
        'post_type' => 'faq',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ];

    if (!empty($atts['category'])) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'faq_category',
                'field' => 'slug',
                'terms' => $atts['category']
            ]
        ];
    }

    $faqs = new WP_Query($args);

    if (!$faqs->have_posts()) { return '<p>No FAQs found.</p>'; }

    ob_start();
    ?>

    <div class="accordion" id="FAQs">
    
    <?php $i = 0; while ($faqs->have_posts()) : $faqs->the_post(); $i++; ?>
        <div class="accordion-item">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i; ?>" aria-expanded="false" aria-controls="collapse<?php echo $i; ?>">
                <div class="faq-icon"><i class="bi bi-question-circle"></i></div> 
                <?php the_title(); ?>
            </button>

            <div id="collapse<?php echo $i; ?>" class="accordion-collapse collapse" data-bs-parent="#FAQs">
                <div class="accordion-body">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>    
    <?php endwhile; ?>

    </div> <!-- /Accordion -->


    <?php
        wp_reset_postdata();
        return ob_get_clean();
}
add_shortcode('pureflo_faq', 'pureflo_faq_shortcode');

// ADD CATEGORY FILTER DROPDOWN IN ADMIN
function pureflo_faq_category_filter() {
    global $typenow;

    if ($typenow === 'faq') {
        $taxonomy = 'faq_category';
        $selected = $_GET[$taxonomy] ?? '';
        $info_taxonomy = get_taxonomy($taxonomy);

        wp_dropdown_categories([
            'show_option_all' => "All {$info_taxonomy->label}",
            'taxonomy' => $taxonomy,
            'name' => $taxonomy,
            'orderby' => 'name',
            'selected' => $selected,
            'hierarchical' => true,
            'show_count' => true,
            'hide_empty' => false,
        ]);
    }
}
add_action('restrict_manage_posts', 'pureflo_faq_category_filter');

// FILTER QUERY BY SELECTED CATEGORY
function pureflo_faq_filter_query($query) {
    global $pagenow;

    if (
        $pagenow === 'edit.php' &&
        isset($_GET['faq_category']) &&
        $query->is_main_query()
    ) {
        $term = $_GET['faq_category'];

        if (!empty($term) && $term !== '0') {
            $query->query_vars['tax_query'] = [[
                'taxonomy' => 'faq_category',
                'field' => 'term_id',
                'terms' => $term,
            ]];
        }
    }
}
add_action('pre_get_posts', 'pureflo_faq_filter_query');

// ENABLE SORTABLE UI
function pureflo_faq_sortable() {
    add_post_type_support('faq', 'page-attributes');
}
add_action('init', 'pureflo_faq_sortable');

// ADD CATEGORY COLUMN
function pureflo_faq_columns($columns) {
    $columns['faq_category'] = 'Category';
    return $columns;
}
add_filter('manage_faq_posts_columns', 'pureflo_faq_columns');

// POPULATE COLUMN
function pureflo_faq_column_content($column, $post_id) {
    if ($column === 'faq_category') {
        $terms = get_the_terms($post_id, 'faq_category');
        if ($terms) { foreach ($terms as $term) { echo $term->name . '<br>'; } }
    }
}
add_action('manage_faq_posts_custom_column', 'pureflo_faq_column_content', 10, 2);

// ADD ORDER COLUMN
function pureflo_add_order_column($columns) {
    $columns['menu_order'] = 'Order';
    return $columns;
}
add_filter('manage_faq_posts_columns', 'pureflo_add_order_column');

// SHOW ORDER VALUE
function pureflo_show_order_column($column, $post_id) {
    if ($column === 'menu_order') { echo get_post_field('menu_order', $post_id);  }
}
add_action('manage_faq_posts_custom_column', 'pureflo_show_order_column', 10, 2);

// ADD CSS TO FIX COLUMN WIDTHS
add_action('admin_head', function() {
    echo '<style>
        .wp-list-table .column-menu_order { width: 80px; text-align: center; }
    </style>';
});


