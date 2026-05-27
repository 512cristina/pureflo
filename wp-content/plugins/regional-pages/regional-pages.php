<?php
/**
 * Plugin Name: Regional Pages
 * Description: Custom | Regional support for US/EU/ANZ
 * Author: PureFlo
 * Version: 1.4
 */

if (!defined('ABSPATH')) exit;

class RegionalPagesPaired {

    public function __construct() {

        // Meta boxes
        add_action('add_meta_boxes', [$this, 'add_region_meta_box']);
        add_action('save_post', [$this, 'save_meta']);

        // Admin column
        // Only add Region column to Posts + Pages
        add_filter('manage_post_posts_columns', [$this, 'add_region_column']);
        add_filter('manage_page_posts_columns', [$this, 'add_region_column']);
        add_filter('manage_news_posts_columns', [$this, 'add_region_column']);

        add_action('manage_post_posts_custom_column', [$this, 'render_region_column'], 10, 2);
        add_action('manage_page_posts_custom_column', [$this, 'render_region_column'], 10, 2);
        add_action('manage_news_posts_custom_column', [$this, 'render_region_column'], 10, 2);

        // Helpers
        add_filter('body_class', [$this, 'body_class']);

        // Redirects
        add_action('template_redirect', [$this, 'root_redirect']);
        add_filter('redirect_canonical', [$this, 'disable_region_canonical'], 10, 2);

        // Rewrites needed for Distributor Single to work
        add_action('init', [$this, 'regional_rewrites']);

        // Redirect regional 404s to regional homepage
        add_action('template_redirect', [$this, 'regional_404_redirect']);

    }

    public function add_region_meta_box() {
        add_meta_box('regional_region', 'Region', [$this, 'region_box'], ['page', 'post', 'news'], 'side');
    }

    public function region_box($post) {
        $region = get_post_meta($post->ID, '_region', true) ?: 'us';
        ?>
        <select name="region">
            <option value="us" <?php selected($region, 'us'); ?>>US</option>
            <option value="eu" <?php selected($region, 'eu'); ?>>EU</option>
            <option value="anz" <?php selected($region, 'anz'); ?>>ANZ</option>
        </select>
        <?php
    }

    public function save_meta($post_id) {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if (isset($_POST['region'])) {
            update_post_meta($post_id, '_region', sanitize_text_field($_POST['region']));
        }
    }

    // -------------------------
    // REGION DETECTION
    // -------------------------
    public static function get_region() {

        if (is_singular()) {
            $r = get_post_meta(get_the_ID(), '_region', true);
            if ($r) return $r;
        }

        if (strpos($_SERVER['REQUEST_URI'], '/eu') === 0) {
            return 'eu';
        }

        if (strpos($_SERVER['REQUEST_URI'], '/anz') === 0) {
            return 'anz';
        }

        return 'us';
    }


    // -------------------------
    // ROOT REDIRECT
    // -------------------------
    public function root_redirect() {

        if (is_front_page() && $_SERVER['REQUEST_URI'] === '/') {
            wp_redirect(home_url('/us/'), 301);
            exit;
        }
    }

    // -------------------------
    // FIX REGION CANONICAL
    // -------------------------
    public function disable_region_canonical($redirect, $requested) {

        if (preg_match('#/(us|eu|anz)(/)?$#', $requested)) {
            return false;
        }

        return $redirect;
    }

    // -------------------------
    // ADMIN COLUMN
    // -------------------------
    public function add_region_column($cols) {
        $cols['region'] = 'Region';
        return $cols;
    }

    public function render_region_column($col, $id) {
        if ($col === 'region') {
            echo strtoupper(get_post_meta($id, '_region', true) ?: 'US');
        }
    }

    // -------------------------
    // BODY CLASS
    // -------------------------
    public function body_class($classes) {
        $classes[] = 'region-' . self::get_region();
        return $classes;
    }

    // REWRITE RULES TO ACCOUNT FOR REGIONAL PAGES (Distributors)
    public function regional_rewrites() {

        add_rewrite_rule(
            '^(us|eu|anz)/distributors/([^/]+)/?$',
            'index.php?post_type=distributor&name=$matches[2]',
            'top'
        );
    }

    // Redirect regional 404s to regional homepage
    public function regional_404_redirect() {

        if (is_404()) {

            $region = self::get_region();

            wp_redirect(home_url('/' . $region . '/'), 302);
            exit;
        }
    }

}  // CLOSE Class

// Init
new RegionalPagesPaired();

// Global helper
function get_current_region() {
    return RegionalPagesPaired::get_region();
}

// Generate URLs with region path detected. Example:  <a href=" echo esc_url(get_regional_permalink()); 

function get_regional_permalink($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $region = get_current_region();
    $post = get_post($post_id);

    if (!$post) {  return home_url('/');  }
    $slug = ($post->post_type === 'news') ? 'news' : $post->post_type . 's';
    return home_url('/' . $region . '/' . $slug . '/' . $post->post_name . '/');
}


function get_region_url($region) {

    $path = $_SERVER['REQUEST_URI'];

    // Remove existing region prefix
    $path = preg_replace('#^/(us|eu|anz)#', '', $path);

    $target_path = '/' . $region . $path;
    $target_url  = home_url($target_path);

    // Check if URL resolves
    $request = wp_remote_head($target_url, [
        'timeout' => 2,  'redirection' => 0
    ]);

    // If request succeeded and NOT 404
    if (!is_wp_error($request)) {
        $code = wp_remote_retrieve_response_code($request);
        if ($code >= 200 && $code < 400) { return $target_url;  }
    }

    // Fallback to region homepage
    return home_url('/' . $region . '/');
}

function add_region_meta() {

	$us  = get_region_url('us');
	$eu  = get_region_url('eu');
	$anz = get_region_url('anz');

	// Hreflang tags
	echo '
	<link rel="alternate" hreflang="en-US" href="' . esc_url($us) . '" />
	<link rel="alternate" hreflang="en-GB" href="' . esc_url($eu) . '" />
	<link rel="alternate" hreflang="en-AU" href="' . esc_url($anz) . '" />
	<link rel="alternate" hreflang="en-NZ" href="' . esc_url($anz) . '" />
	<link rel="alternate" hreflang="x-default" href="' . esc_url($us) . '" />
	';

	// JS object for your switcher
	echo "<script>
		window.REGION_URLS = {
			us: '" . esc_url($us) . "',
			eu: '" . esc_url($eu) . "',
			anz: '" . esc_url($anz) . "'
		};
	</script>";
}
add_action('wp_head', 'add_region_meta');

// HTML LANG Fix
add_filter('language_attributes', function($output) {

	$region = get_current_region();

	if ($region === 'eu') {
		return 'lang="en-GB"';
	}

	if ($region === 'anz') {
		return 'lang="en-AU"';
	}

	return 'lang="en-US"';
});

// YOAST FIX FOR REGIONAL SEO
add_filter('wpseo_locale', function($locale) {

	$region = get_current_region();

	if ($region === 'eu') {
		return 'en_GB';
	}

	if ($region === 'anz') {
		return 'en_AU';
	}

	return 'en_US';
});