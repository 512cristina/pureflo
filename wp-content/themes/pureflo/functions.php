<?php
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
});

function pureflo_scripts() {
    $uri = get_template_directory_uri();

    wp_enqueue_style('bootstrap', $uri.'/assets/css/bootstrap.min.css');
    wp_enqueue_style('icons', $uri.'/assets/css/bootstrap-icons.css');
    wp_enqueue_style('fontawesome', $uri.'/assets/css/fontawesome.all.min.css');
    wp_enqueue_style('aos', 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css');
    wp_enqueue_style('glightbox', $uri.'/assets/css/glightbox.min.css');
    wp_enqueue_style('global', $uri.'/assets/css/global.css');

    wp_enqueue_script('bootstrap', $uri.'/assets/js/bootstrap.bundle.min.js', [], null, true);
    wp_enqueue_script('aos', 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js', [], null, true);
    wp_enqueue_script('glightbox', $uri.'/assets/js/glightbox.min.js', [], null, true);
    wp_enqueue_script('global', $uri.'/assets/js/global.js', [], null, true);
    wp_enqueue_script('counter', $uri.'/assets/js/stat-counter.js', [], null, true);
}
add_action('wp_enqueue_scripts', 'pureflo_scripts');

add_action('wp_head', function() {
    if (is_page()) { // ACF Header Scripts
        $scripts = get_field('header_scripts');
        if ($scripts) { echo $scripts; }
    }
});

add_action('wp_footer', function() {
    if (is_page()) {
        $footer_scripts = get_field('footer_scripts');
        if ($footer_scripts) { echo $footer_scripts; }
    }
});

function region_url($path = '') {
	return home_url('/' . get_current_region() . '/' . ltrim($path, '/'));
}

// DISABLE ALL EXCESSIVE INLINE CODE NOT BEING USED BY CUSTOM THEME

    // Remove unnecessary <head> output
remove_action( 'wp_head', 'rsd_link' ); // Really Simple Discovery
remove_action( 'wp_head', 'wlwmanifest_link' ); // Windows Live Writer
remove_action( 'wp_head', 'wp_generator' ); // WP version
remove_action( 'wp_head', 'rest_output_link_wp_head' ); // REST API link
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' ); // oEmbed
remove_action( 'wp_head', 'wp_oembed_add_host_js' ); // oEmbed JS
remove_action( 'wp_head', 'feed_links', 2 ); // RSS feeds
remove_action( 'wp_head', 'feed_links_extra', 3 ); // Extra feeds
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' ); // prev/next links
remove_action( 'wp_head', 'wp_resource_hints', 2 ); // DNS prefetch

    // Remove emoji scripts/styles
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

    // Disable Gutenberg (optional but recommended for pure CMS use)
add_filter( 'use_block_editor_for_post', '__return_false' );
add_filter( 'use_block_editor_for_post_type', '__return_false' );
add_filter('wp_img_tag_add_auto_sizes', '__return_false');

    // Remove global styles + block CSS
add_action( 'after_setup_theme', function() {
    remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
    remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
});

add_action( 'wp_enqueue_scripts', function() {
    // Block styles & classic theme styles
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'classic-theme-styles' );
}, 20 );
    

    // Disable auto-paragraphing for Pages only
add_action( 'wp', function() { if ( is_page() ) { remove_filter( 'the_content', 'wpautop' );  } });

add_theme_support('post-thumbnails');
// Ensure it's enabled for both post + page
add_post_type_support('page', 'thumbnail'); 

function my_featured_image_guidance($content, $post_id) {
    $post = get_post($post_id);

    if (in_array($post->post_type, ['post', 'page'])) {
        $note = '<p style="font-size:12px; margin-bottom:8px;">
        <strong>Featured Image:</strong> Recommended 600 x 315 px (16:9 ratio) for best social sharing. </p>';
        return $note . $content;
    }

    return $content;
}
add_filter('admin_post_thumbnail_html', 'my_featured_image_guidance', 10, 2);



