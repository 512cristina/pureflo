<?php
/**
 * Plugin Name: News
 * Description: Custom Press Releases for PureFlo
 * Version: 1.0
 * Author: PureFlo
 */

if (!defined('ABSPATH')) exit;

// -------------------------------------------------
// REGISTER POST TYPE
// -------------------------------------------------

function pf_register_press_release_cpt() {

    register_post_type('news', [

        'labels' => [
            'name'               => 'Press Releases',
            'singular_name'      => 'Press Release',
            'add_new'            => 'Add Press Release',
            'add_new_item'       => 'Add New Press Release',
            'edit_item'          => 'Edit Press Release',
            'new_item'           => 'New Press Release',
            'view_item'          => 'View Press Release',
            'search_items'       => 'Search Press Releases',
            'not_found'          => 'No Press Releases found',
        ],

        'public' => true,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => ['title', 'editor', 'thumbnail', ],
        'has_archive' => 'news',
        'rewrite' => ['slug' => 'news', 'with_front' => false],
        'show_in_rest' => true,  'menu_position' => 5,

    ]);
}

add_action('init', 'pf_register_press_release_cpt');

// -------------------------------------------------
// PRESS RELEASE META BOX
// -------------------------------------------------

function pf_press_release_meta_box() {
    add_meta_box('pf_press_release_details', 'Press Release Details', 'pf_press_release_meta_callback', 'news', 'side', 'high' );
}

add_action('add_meta_boxes', 'pf_press_release_meta_box');

function pf_press_release_meta_callback($post) {

    wp_nonce_field('pf_press_release_nonce', 'pf_press_release_nonce_field');
    $news_date = get_post_meta($post->ID, '_pf_news_date', true);
    if (!$news_date) { $news_date = current_time('Y-m-d'); }
    ?>

    <p><label><strong>News Date</strong></label><br>
        <input type="date" name="pf_news_date" value="<?php echo esc_attr($news_date); ?>" style="width:100%;" >
    </p>

    <p><em>This date appears publicly on the press release. </em> </p>

    <?php
}

// -------------------------------------------------
// SAVE PRESS RELEASE META
// -------------------------------------------------

function pf_save_press_release_meta($post_id) {

    // Nonce check
    if (
        !isset($_POST['pf_press_release_nonce_field']) ||
        !wp_verify_nonce($_POST['pf_press_release_nonce_field'], 'pf_press_release_nonce')
    ) { return; }

    // Autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return;}

    // Permissions
    if (!current_user_can('edit_post', $post_id)) { return; }

    // Post type
    if (get_post_type($post_id) !== 'news') { return; }

    // Validate News Date
    if (empty($_POST['pf_news_date'])) {

        wp_die(
            'Please enter a News Date.',
            'Missing Required Field',
            ['back_link' => true]
        );
    }

    // Save News Date
    update_post_meta(  $post_id, '_pf_news_date',  sanitize_text_field($_POST['pf_news_date']) );
}

add_action('save_post', 'pf_save_press_release_meta');

// -------------------------------------------------
// FEATURED IMAGE LABEL
// -------------------------------------------------

function pf_press_release_image_guidance($content, $post_id) {

    $post = get_post($post_id);

    if ($post && $post->post_type === 'news') {

        $note = '
        <p style="font-size:12px; margin-bottom:8px;">
            <strong>News Image:</strong>
            Recommended size: Image must be 400 x 200 px
        </p>';

        return $note . $content;
    }
    return $content;
}

add_filter('admin_post_thumbnail_html', 'pf_press_release_image_guidance', 10, 2);
 

// -------------------------------------------------
// OPTIONAL: CUSTOM COLUMNS
// -------------------------------------------------

function pf_press_release_columns($columns) {

    return [
        'cb' => $columns['cb'],
        'thumbnail' => 'Image',
        'title' => 'Title',
        'date' => 'Date',
    ];
}

add_filter('manage_news_posts_columns', 'pf_press_release_columns');


function pf_press_release_column_content($column, $post_id) {

    if ($column === 'thumbnail') {
        if (has_post_thumbnail($post_id)) {
            echo get_the_post_thumbnail($post_id, [80, 80]);
        } else { echo '—'; }
    }
}

add_action( 'manage_news_posts_custom_column', 'pf_press_release_column_content', 10, 2 );

// -------------------------------------------------
// SORT PRESS RELEASES BY NEWS DATE
// -------------------------------------------------

function pf_press_release_archive_sort($query) {

    if (
        !is_admin() &&
        $query->is_main_query() &&
        is_post_type_archive('news')
    ) {
        $query->set('meta_key', '_pf_news_date');
        $query->set('orderby', 'meta_value');
        $query->set('order', 'DESC');
    }
}

add_action('pre_get_posts', 'pf_press_release_archive_sort');


// -------------------------------------------------
// FLUSH REWRITES ON ACTIVATION
// -------------------------------------------------

function pf_press_release_activate() {
    pf_register_press_release_cpt();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'pf_press_release_activate');

function pf_press_release_deactivate() { flush_rewrite_rules(); }
register_deactivation_hook(__FILE__, 'pf_press_release_deactivate');