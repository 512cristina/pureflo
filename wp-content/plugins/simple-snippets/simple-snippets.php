<?php
/**
 * Plugin Name: Simple Snippets Shortcode
 * Description: Create reusable snippets (HTML, CSS, JS, PHP) and display via shortcode.
 * Version: 1.1
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
        'supports' => ['title', 'editor'],
    ]);
}
add_action('init', 'sss_register_snippets_cpt');

// Add shortcode column to admin list
function sss_add_shortcode_column($columns) {
    $columns['shortcode'] = 'Shortcode';
    return $columns;
}
add_filter('manage_sss_snippet_posts_columns', 'sss_add_shortcode_column');

// Populate shortcode column
function sss_render_shortcode_column($column, $post_id) {
    if ($column === 'shortcode') {
        $slug = get_post_field('post_name', $post_id);
        echo '<code>[snippet id="' . esc_attr($slug) . '"]</code>';
    }
}
add_action('manage_sss_snippet_posts_custom_column', 'sss_render_shortcode_column', 10, 2);

// Add shortcode metabox (right sidebar)
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

// Render shortcode metabox content
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