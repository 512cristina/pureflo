<?php
/**
 * Plugin Name: Resource Library
 * Description: Custom Resource Library (PDFs, Docs, Videos)
 * Version: 1.0
*  Author: PureFlo
 */

if (!defined('ABSPATH')) exit;


// -------------------------
// REGISTER POST TYPE
// -------------------------
function rl_register_post_type() {

    register_post_type('resource', [
        'labels' => [
            'name' => 'Resources',
            'singular_name' => 'Resource',
            'add_new_item' => 'Add New Resource',
        ],
        'public' => true,
        'menu_icon' => 'dashicons-media-document',
        'supports' => ['title', 'editor', 'thumbnail'],
        'has_archive' => true,
        'show_in_rest' => true,
    ]);
}
add_action('init', 'rl_register_post_type');

// -------------------------
// TAXONOMIES
// -------------------------
function rl_register_taxonomies() {

    register_taxonomy('resource_type', 'resource', [
        'label' => 'Type',
        'hierarchical' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
    ]);

    register_taxonomy('resource_topic', 'resource', [
        'label' => 'Topic',
        'hierarchical' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
    ]);

    register_taxonomy('resource_language', 'resource', [
        'label' => 'Language',
        'hierarchical' => true, 
        'show_admin_column' => true,
        'show_in_rest' => true,
    ]);
}
add_action('init', 'rl_register_taxonomies');


// -------------------------
// MAKE LANGUAGE SELECTION RADIO BOXES
// -------------------------

add_action('add_meta_boxes', function () { remove_meta_box('resource_languagediv', 'resource', 'side'); }); // Remove checkbox

add_action('add_meta_boxes', function () {
    add_meta_box(
        'resource_language_radio',
        'Language',
        'render_resource_language_radio_box',
        'resource',
        'side',
        'default'
    );
});

function render_resource_language_radio_box($post) {
    $taxonomy = 'resource_language';
    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ]);

    $selected_terms = wp_get_post_terms($post->ID, $taxonomy, ['fields' => 'ids']);
    $selected = !empty($selected_terms) ? $selected_terms[0] : 0;

    if (!empty($terms)) {
        foreach ($terms as $term) {
            ?>
            <p>
                <label>
                    <input type="radio" name="resource_language_radio" value="<?php echo esc_attr($term->term_id); ?>"
                        <?php checked($selected, $term->term_id); ?> />
                    <?php echo esc_html($term->name); ?>
                </label>
            </p>
            <?php
        }
    } else {
        echo 'No languages found.';
    }
}




add_action('save_post', function ($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (get_post_type($post_id) !== 'resource') return;

    if (empty($_POST['resource_language_radio'])) {

        remove_action('save_post', __FUNCTION__);

        wp_die(
            'Please select a Language before saving this Resource.',
            'Missing Required Field',
            ['back_link' => true]
        );
    }

    $term_id = intval($_POST['resource_language_radio']);
    wp_set_post_terms($post_id, [$term_id], 'resource_language');

});


// -------------------------
// META BOX
// -------------------------
function rl_add_meta_boxes() {
    add_meta_box('rl_details', 'Resource Details', 'rl_meta_box_callback', 'resource');
}
add_action('add_meta_boxes', 'rl_add_meta_boxes');

function rl_meta_box_callback($post) {

    $file = get_post_meta($post->ID, '_rl_file', true);
    $video = get_post_meta($post->ID, '_rl_video', true);
    $date = get_post_meta($post->ID, '_rl_date', true);

    ?>

    <p>
    <label><strong>Resource File (PDF/DOC)</strong></label><br>

    <input type="hidden" id="rl_file" name="rl_file" value="<?php echo esc_attr($file); ?>">

    <button type="button" class="button" id="rl_upload_button">Upload / Select File</button>
    <button type="button" class="button" id="rl_remove_file" style="<?php echo empty($file) ? 'display:none;' : ''; ?>">Remove</button>

    <div id="rl_file_preview" style="margin-top:10px;">
        <?php
        if ($file) {
            $mime = get_post_mime_type($file);

            if (strpos($mime, 'image') !== false) {
                echo wp_get_attachment_image($file, [150, 100]);
            } else {
                echo '<p>' . basename(get_attached_file($file)) . '</p>';
            }
        }
        ?>
    </div>
    </p>


    <p>
        <label><strong>Video URL (YouTube/Vimeo/eBook)</strong></label><br>
        <input type="text" name="rl_video" value="<?php echo esc_attr($video); ?>" style="width:100%;">
    </p>

    <p>
        <label><strong>Resource Date</strong></label><br>
        <input type="date" name="rl_date" value="<?php echo esc_attr($date); ?>">
    </p>

    <p><em>Recommended image size: 350x200</em></p>
    <?php
}

// -------------------------
// SAVE META
// -------------------------
function rl_save_meta($post_id) {

    //  NONCE CHECK (CORRECT PLACE)
    if (!isset($_POST['rl_meta_nonce']) || 
        !wp_verify_nonce($_POST['rl_meta_nonce'], 'rl_save_meta_nonce')) {
        return;
    }

    //  autosave / permissions
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (!isset($_POST['post_type']) || $_POST['post_type'] !== 'resource') return;

    if (!current_user_can('edit_post', $post_id)) return;

    //  SAVE FILE
    if (isset($_POST['rl_file'])) {
        update_post_meta($post_id, '_rl_file', intval($_POST['rl_file']));
    }

    //  SAVE IMAGE
    if (isset($_POST['rl_image'])) {
        update_post_meta($post_id, '_rl_image', intval($_POST['rl_image']));
    }

    //  SAVE VIDEO
    if (isset($_POST['rl_video'])) {
        update_post_meta($post_id, '_rl_video', sanitize_text_field($_POST['rl_video']));
    }

    //  SAVE DATE
    if (isset($_POST['rl_date'])) {
        update_post_meta($post_id, '_rl_date', sanitize_text_field($_POST['rl_date']));
    }
}
add_action('save_post', 'rl_save_meta');

// -------------------------
// ADMIN FILTER DROPDOWNS
// -------------------------
function rl_admin_filters() {
    global $typenow;

    if ($typenow == 'resource') {

        $taxonomies = ['resource_type', 'resource_topic', 'resource_language'];

        foreach ($taxonomies as $tax) {

            $taxonomy = get_taxonomy($tax);
            wp_dropdown_categories([
                'show_option_all' => "All {$taxonomy->label}",
                'taxonomy' => $tax,
                'name' => $tax,
                'orderby' => 'name',
                'selected' => $_GET[$tax] ?? '',
                'hierarchical' => true,
                'show_count' => true,
                'hide_empty' => false,
            ]);
        }
    }
}
add_action('restrict_manage_posts', 'rl_admin_filters');

// -------------------------
// FILTER QUERY
// -------------------------
function rl_filter_query($query) {
    global $pagenow;

    if ($pagenow == 'edit.php' && isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'resource') {

        $tax_query = [];

        foreach (['resource_type', 'resource_topic', 'resource_language'] as $tax) {
            if (!empty($_GET[$tax])) {
                $tax_query[] = [
                    'taxonomy' => $tax,
                    'field' => 'term_id',
                    'terms' => $_GET[$tax],
                ];
            }
        }

        if (!empty($tax_query)) {
            $query->set('tax_query', $tax_query);
        }
    }
}
add_filter('parse_query', 'rl_filter_query');

function rl_admin_scripts($hook) {
    global $post;

    if (($hook == 'post-new.php' || $hook == 'post.php') && $post->post_type === 'resource') {
        wp_enqueue_media();

        wp_enqueue_script('rl-admin-js', plugin_dir_url(__FILE__) . 'rl-admin.js', ['jquery'], null, true);
    }
}
add_action('admin_enqueue_scripts', 'rl_admin_scripts');

function rl_add_image_meta_box() {
    add_meta_box(
        'rl_resource_image',
        'Resource Image (350x200)',
        'rl_image_meta_box_callback',
        'resource',
        'side', // 
        'high'
    );
}
add_action('add_meta_boxes', 'rl_add_image_meta_box');

function rl_image_meta_box_callback($post) {
    $image_id = get_post_meta($post->ID, '_rl_image', true);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : '';
    wp_nonce_field('rl_save_meta_nonce', 'rl_meta_nonce');
    ?>

    <div style="text-align:center;">
        <img id="rl_image_preview" 
             src="<?php echo esc_url($image_url); ?>" 
             style="max-width:100%; height:auto; <?php echo $image_url ? '' : 'display:none;'; ?>" />
    </div>

    <input type="hidden" id="rl_image" name="rl_image" value="<?php echo esc_attr($image_id); ?>">

    <p style="text-align:center;">
        <button type="button" class="button" id="rl_image_upload">Select Image</button>
        <button type="button" class="button" id="rl_image_remove" style="<?php echo $image_url ? '' : 'display:none;'; ?>">Remove</button>
    </p>

    <p><em>Required size: 350x200</em></p>

    <?php
}

function rl_validate_resource($post_id) {
    if (get_post_type($post_id) !== 'resource') return;

    if (empty($_POST['rl_image'])) {
        add_filter('redirect_post_location', function($location) {
            return add_query_arg('rl_image_error', '1', $location);
        });
    }
}
add_action('save_post', 'rl_validate_resource');

// -------------------------
// REMOVE DEFAULT FEATURED IMAGE BLOCK
// -------------------------
add_action('add_meta_boxes', function () {remove_meta_box('postimagediv', 'resource', 'side'); });




