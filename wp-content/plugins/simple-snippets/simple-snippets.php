<?php
/**
 * Plugin Name: Simple Snippets Shortcode
 * Description: Create reusable snippets (HTML, CSS, JS, PHP) and display via shortcode.
 * Version: 1.2
 */

if (!defined('ABSPATH')) exit;

// Register custom post type
function sss_register_snippets_cpt() {
    register_post_type('sss_snippet', [
        'labels' => [
            'name' => 'Snippets',
            'singular_name' => 'Snippet',
            'add_new' => 'Add Snippet',
            'add_new_item' => 'Add New Snippet',
            'edit_item' => 'Edit Snippet',
            'new_item' => 'New Snippet',
            'view_item' => 'View Snippet',
            'search_items' => 'Search Snippets',
            'not_found' => 'No snippets found',
            'menu_name' => 'Snippets'
        ],
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-editor-code',
        'supports' => ['title', 'editor', 'revisions'],
    ]);
}
add_action('init', 'sss_register_snippets_cpt');


// Add admin columns (Shortcode + Last Modified)
function sss_add_columns($columns) {
    $columns['shortcode'] = 'Shortcode';
    $columns['modified'] = 'Last Modified';

    // Remove unwanted Region column if present
    if (isset($columns['region'])) {
        unset($columns['region']);
    }
    if (isset($columns['taxonomy-region'])) {
        unset($columns['taxonomy-region']);
    }

    return $columns;
}
add_filter('manage_sss_snippet_posts_columns', 'sss_add_columns');


// Populate admin columns
function sss_render_columns($column, $post_id) {

    if ($column === 'shortcode') {
        $slug = get_post_field('post_name', $post_id);
        echo '<code>[snippet id="' . esc_attr($slug) . '"]</code>';
    }

    if ($column === 'modified') {
        $modified = get_post_modified_time('Y/m/d g:i a', false, $post_id);
        echo esc_html($modified);
    }
}
add_action('manage_sss_snippet_posts_custom_column', 'sss_render_columns', 10, 2);


// Make Last Modified sortable
function sss_sortable_columns($columns) {
    $columns['modified'] = 'modified';
    return $columns;
}
add_filter('manage_edit-sss_snippet_sortable_columns', 'sss_sortable_columns');


// Default sort by Last Modified
function sss_default_order($query) {
    if (!is_admin() || !$query->is_main_query()) return;

    if ($query->get('post_type') === 'sss_snippet' && !$query->get('orderby')) {
        $query->set('orderby', 'modified');
        $query->set('order', 'DESC');
    }
}
add_action('pre_get_posts', 'sss_default_order');


// Add shortcode metabox
function sss_add_shortcode_metabox() {
    add_meta_box(
        'sss_shortcode_box',
        'Snippet Shortcode',
        'sss_render_shortcode_metabox',
        'sss_snippet',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'sss_add_shortcode_metabox');


// Render shortcode metabox
function sss_render_shortcode_metabox($post) {
    if ($post->post_status === 'auto-draft') {
        echo '<p>Save this snippet to generate a shortcode.</p>';
        return;
    }

    $slug = $post->post_name;
    $shortcode = '[snippet id="' . $slug . '"]';

    echo '<p>Use this shortcode:</p>';
    echo '<input type="text" value="' . esc_attr($shortcode) . '" readonly style="width:100%; font-family:monospace;" onclick="this.select();">';
}


// Shortcode handler
function sss_snippet_shortcode($atts) {
    $atts = shortcode_atts([
        'id' => ''
    ], $atts);

    if (!$atts['id']) return '';

    $snippet = get_page_by_path($atts['id'], OBJECT, 'sss_snippet');

    if (!$snippet) return '';

    $content = $snippet->post_content;

    // Execute PHP only for admins
    if (current_user_can('manage_options')) {
        ob_start();
        eval('?>' . $content);
        return ob_get_clean();
    }

    return do_shortcode($content);
}
add_shortcode('snippet', 'sss_snippet_shortcode');