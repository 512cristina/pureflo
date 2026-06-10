<?php
/**
 * Plugin Name: Resource Library
 * Description: Custom | Resource Library (PDFs, Docs, Videos)
 * Version: 1.0
*  Author: PureFlo
 */

if (!defined('ABSPATH')) exit;

// =====================================================
// RESOURCE LIBRARY
// MULTI-LANGUAGE VERSION
// =====================================================

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
        'supports' => [ 'title',  'editor' ],
        'has_archive' => true,
        'show_in_rest' => true,

    ]);
}
add_action('init', 'rl_register_post_type');


// -------------------------
// REGISTER TAXONOMIES
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
// REMOVE DEFAULT LANGUAGE BOX
// -------------------------

add_action('add_meta_boxes', function () {
    remove_meta_box(  'resource_languagediv',  'resource', 'side' );
});


// -------------------------
// CUSTOM LANGUAGE BOX
// -------------------------

add_action('add_meta_boxes', function () {
    add_meta_box( 'rl_languages', 'Languages', 'rl_languages_meta_box', 'resource', 'normal', 'high' );
});

function rl_languages_meta_box($post) {

    $taxonomy = 'resource_language';
    $terms = get_terms([ 'taxonomy' => $taxonomy,  'hide_empty' => false,  ]);
    $selected_terms = wp_get_post_terms( $post->ID,  $taxonomy,  ['fields' => 'ids' ]  );

    echo '<div id="rl-language-selector">';

    foreach ($terms as $term) {
        $checked = in_array( $term->term_id, $selected_terms ) ? 'checked' : '';
        ?>

        <p><label><input  type="checkbox" name="rl_languages[]" value="<?php echo esc_attr($term->term_id); ?>" 
            data-slug="<?php echo esc_attr($term->slug); ?>" <?php echo $checked; ?>  > <?php echo esc_html($term->name); ?>
            </label>
        </p>

        <?php
    }
    echo '</div>';
}


// -------------------------
// SAVE LANGUAGE TERMS
// -------------------------

function rl_save_languages($post_id) {

    if ( defined('DOING_AUTOSAVE')  && DOING_AUTOSAVE ) { return; }
    if ( get_post_type($post_id)  !== 'resource' ) { return; }
    if (!isset($_POST['rl_languages'])) { return; }

    $term_ids = array_map( 'intval', $_POST['rl_languages'] );
    wp_set_post_terms( $post_id, $term_ids,  'resource_language' );
}
add_action( 'save_post', 'rl_save_languages' );

// -------------------------
// RESOURCE DETAILS META BOX
// -------------------------

function rl_add_meta_boxes() {
    add_meta_box( 'rl_details', 'Resource Details', 'rl_meta_box_callback', 'resource',  'normal', 'default' );
}
add_action('add_meta_boxes', 'rl_add_meta_boxes');


function rl_meta_box_callback($post) {

    wp_nonce_field(  'rl_save_meta_nonce', 'rl_meta_nonce' );

    $languages = [
        'en' => 'English',
        'da' => 'Danish',
        'fr' => 'French',
        'de' => 'German',
        'it' => 'Italian',
        'es' => 'Spanish',
        'nl' => 'Dutch',
    ];
    ?>

    <div id="rl-language-files-wrapper">
        <?php $source_type = get_post_meta( $post->ID,  '_rl_source_type', true );
            if (!$source_type) { $source_type = 'upload'; }
        ?>

        <p><label><strong>Resource Source</strong></label><br>

            <select name="rl_source_type" id="rl_source_type">
                <option value="upload" <?php selected($source_type,'upload'); ?>>
                    Uploaded File
                </option>

                <option value="external" <?php selected($source_type,'external'); ?>>
                    External URL
                </option>
            </select>
        </p>

        <h3>Resource Files</h3>
        <p>Upload a file for each selected language.  </p>

        <?php foreach ($languages as $slug => $label) { 
            $file_id = get_post_meta( $post->ID, '_rl_file_' . $slug, true  );
        ?>

            <div class="rl-language-file-row" data-language="<?php echo esc_attr($slug); ?>"
                style="display:none; border:1px solid #ddd; padding:15px; margin-bottom:10px;">

                <h4><?php echo esc_html($label); ?> File</h4>

                <input type="hidden" id="rl_file_<?php echo esc_attr($slug); ?>"
                    name="rl_file_<?php echo esc_attr($slug); ?>" value="<?php echo esc_attr($file_id); ?>" >

                <button type="button" class="button rl-upload-file" data-target="<?php echo esc_attr($slug); ?>" >
                    Upload / Select File
                </button>

                <button type="button" class="button rl-remove-file" data-target="<?php echo esc_attr($slug); ?>" >
                    Remove
                </button>

                <div id="rl_file_preview_<?php echo esc_attr($slug); ?>" style="margin-top:10px;" >
                    <?php
                        if ($file_id) { echo '<p>';
                            echo esc_html( basename( get_attached_file($file_id) )  );
                            echo '</p>';
                        }
                    ?>
                </div>
            </div>

            <?php
        }
        ?>
    </div>

    <hr>

    <div id="rl-external-url-wrapper">

        <h3>External Resource URL</h3>

        <?php $external_url =  get_post_meta( $post->ID, '_rl_external_url', true ); ?>

        <input type="url" id="rl_external_url" name="rl_external_url" value="<?php echo esc_attr($external_url); ?>"
            style="width:100%;" placeholder="https://example.com/someurl-here/">
    </div>

    <hr>

    <?php $video = get_post_meta( $post->ID, '_rl_video', true  );  ?>

    <p><label><strong> Video URL (YouTube / Vimeo) </strong></label><br>
        <input type="text" id="rl_video" name="rl_video" value="<?php echo esc_attr($video); ?>" style="width:100%;">
    </p>

    <?php  $date = get_post_meta( $post->ID,  '_rl_date', true ); ?>

    <p><label><strong>Resource Date</strong></label><br>
        <input type="date" name="rl_date" value="<?php echo esc_attr($date); ?>" >
    </p>

    <?php
}

// -------------------------
// SAVE RESOURCE META
// -------------------------

function rl_save_meta($post_id)
{   if ( !isset($_POST['rl_meta_nonce']) || !wp_verify_nonce( $_POST['rl_meta_nonce'], 'rl_save_meta_nonce' ) ) { return; }
    if (  defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) { return; }
    if ( get_post_type($post_id)  !== 'resource'  ) { return; }
    if ( !current_user_can( 'edit_post', $post_id ) ) { return; }

    $languages = [ 'en', 'da', 'fr', 'de', 'it', 'es', 'nl' ];

    foreach ($languages as $slug) {
        if ( isset( $_POST['rl_file_' . $slug] ) ) 
        {  update_post_meta(  $post_id, '_rl_file_' . $slug, intval( $_POST['rl_file_' . $slug] ) );  }
    }

    update_post_meta( $post_id, '_rl_video', sanitize_text_field( $_POST['rl_video'] ?? '' ) );
    update_post_meta( $post_id, '_rl_date', sanitize_text_field( $_POST['rl_date'] ?? '' ) );

    update_post_meta( $post_id, '_rl_source_type', sanitize_text_field( $_POST['rl_source_type'] ?? 'upload') );
    update_post_meta( $post_id, '_rl_external_url', esc_url_raw( $_POST['rl_external_url'] ?? '') );
}
add_action( 'save_post', 'rl_save_meta');


// -------------------------
// RESOURCE IMAGE META BOX
// -------------------------

function rl_add_image_meta_box() {
    add_meta_box( 'rl_resource_image', 'Resource Image (350x200)', 'rl_image_meta_box_callback', 'resource', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'rl_add_image_meta_box' );


function rl_image_meta_box_callback($post)
{
    $image_id = get_post_meta( $post->ID, '_rl_image',  true  );
    $image_url = '';
    if ($image_id) { $image_url = wp_get_attachment_image_url( $image_id, 'medium' ); }
    ?>

    <div style="text-align:center;">
        <img id="rl_image_preview" src="<?php echo esc_url($image_url); ?>"
            style="max-width:100%;height:auto;<?php echo $image_url ? '' : 'display:none;'; ?>" >
    </div>

    <input type="hidden" id="rl_image" name="rl_image"  value="<?php echo esc_attr($image_id); ?>" >

    <p style="text-align:center;">
        <button type="button" class="button" id="rl_image_upload" >
            Select Image
        </button>

        <button type="button" class="button" id="rl_image_remove" style="<?php echo $image_url ? '' : 'display:none;'; ?>" >
            Remove
        </button>
    </p>

    <p><em>Required size: 350x200</em> </p>

    <?php
}

// -------------------------
// SAVE IMAGE
// -------------------------

function rl_save_image_meta($post_id)
{   if ( !isset($_POST['rl_meta_nonce'])  || !wp_verify_nonce( $_POST['rl_meta_nonce'], 'rl_save_meta_nonce' ) ) { return; }
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) { return; }
    if ( get_post_type($post_id) !== 'resource' ) { return; }
    if (  isset($_POST['rl_image']) ) { update_post_meta( $post_id, '_rl_image', intval($_POST['rl_image'])  ); }
}
add_action( 'save_post',  'rl_save_image_meta' );


// -------------------------
// VALIDATE RESOURCE
// -------------------------

function rl_validate_resource($post_id)
{   if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) { return; }
    if ( wp_is_post_revision($post_id) ) { return; }
    if ( get_post_type($post_id) !== 'resource' ) { return; }
    if ( !isset($_POST['rl_meta_nonce']) ) { return; }
    if ( !wp_verify_nonce( $_POST['rl_meta_nonce'],  'rl_save_meta_nonce' ) ) { return;}

    // -------------------------
    // IMAGE REQUIRED
    // -------------------------

    if ( empty($_POST['rl_image']) ) {  wp_die( 'Please upload a Resource Image.', 'Missing Image', [ 'back_link' => true ]  ); }

    // -------------------------
    // LANGUAGE REQUIRED
    // -------------------------

    if ( empty($_POST['rl_languages']) ) { wp_die( 'Please select at least one Language.', 'Missing Language', [ 'back_link' => true ] ); }

    // -------------------------
    // TOPIC REQUIRED
    // -------------------------

    if ( empty($_POST['tax_input']['resource_topic']) ) {
        wp_die( 'Please select at least one Topic.', 'Missing Topic',  [ 'back_link' => true ] );
    }


    // -------------------------
    // DETERMINE RESOURCE TYPE
    // -------------------------

    $is_video = false;
    $type_terms = wp_get_post_terms( $post_id, 'resource_type' );

    if ( !is_wp_error($type_terms) && !empty($type_terms) ) {
        foreach ($type_terms as $term) { if ($term->slug === 'video') { $is_video = true; break; } }
    }

    // -------------------------
    // VIDEO VALIDATION
    // -------------------------

    if ($is_video) {
        if ( empty($_POST['rl_video']) ) {
            wp_die( 'Video resources require a Video URL.', 'Missing Video URL', [ 'back_link' => true ]  );
        }
    }

    $selected_languages = array_map( 'intval',  $_POST['rl_languages'] );

    $language_map = [
        'en' => 'English',
        'da' => 'Danish',
        'fr' => 'French',
        'de' => 'German',
        'it' => 'Italian',
        'es' => 'Spanish',
        'nl' => 'Dutch',
    ];

    $source_type = sanitize_text_field( $_POST['rl_source_type'] ?? 'upload');

    if (!$is_video && $source_type === 'upload') {

        foreach ($selected_languages as $term_id) {

            $term = get_term( $term_id, 'resource_language' );
            if ( !$term || is_wp_error($term) ) { continue; }

            $slug = $term->slug;
            if (!isset($language_map[$slug])) { continue; }

            $field_name = 'rl_file_' . $slug;

            if ( empty($_POST[$field_name]) ) 
            {   wp_die( $language_map[$slug] . ' language selected but no file uploaded.', 'Missing File', [ 'back_link' => true ] );  }
        }
    }

    if ( !$is_video && $source_type === 'external') {

        if (empty($_POST['rl_external_url'])) {
            wp_die('External resources require a URL.', 'Missing URL', ['back_link' => true] );
        }
    }

}
add_action( 'save_post', 'rl_validate_resource', 5 );

// -------------------------
// ADMIN FILTERS
// -------------------------

function rl_admin_filters()
{   global $typenow;

    if ($typenow !== 'resource') { return; }
    $taxonomies = [ 'resource_type', 'resource_topic',  'resource_language'  ];

    foreach ($taxonomies as $tax) 
    {   $taxonomy = get_taxonomy($tax);

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
add_action( 'restrict_manage_posts', 'rl_admin_filters' );


// -------------------------
// FILTER QUERY
// -------------------------

function rl_filter_query($query)
{   global $pagenow;

    if ( $pagenow !== 'edit.php' ) { return; }
    if ( !isset($query->query_vars['post_type']) ) { return; }
    if ( $query->query_vars['post_type']  !== 'resource' ) { return; }

    $tax_query = [];

    foreach ( [ 'resource_type', 'resource_topic', 'resource_language' ]  as $tax  ) 
    { if ( !empty($_GET[$tax]) ) { $tax_query[] = [ 'taxonomy' => $tax, 'field' => 'term_id', 'terms' => $_GET[$tax],  ]; } }

    if (!empty($tax_query)) {  $query->set(  'tax_query',  $tax_query ); }
}
add_filter( 'parse_query', 'rl_filter_query' );

// -------------------------
// ADMIN SCRIPTS
// -------------------------

function rl_admin_scripts($hook)
{   global $post;

    if (
        ($hook === 'post.php'
        || $hook === 'post-new.php')
        &&  isset($post->post_type)
        &&  $post->post_type === 'resource'
    ) 
    {   wp_enqueue_media();
        wp_enqueue_script( 'rl-admin-js', plugin_dir_url(__FILE__)  . 'rl-admin.js',  ['jquery'], time(), true );
    }
}
add_action( 'admin_enqueue_scripts',  'rl_admin_scripts' );

// -------------------------
// REMOVE FEATURED IMAGE BOX
// -------------------------

add_action('add_meta_boxes', function () { remove_meta_box( 'postimagediv', 'resource', 'side' ); } );

