<?php
/**
 * Plugin Name: Regional Pages (Paired)
 * Description: Regional support with US/EU pairing, hreflang, and smart routing.
 * Version: 4.0
 */

if (!defined('ABSPATH')) exit;

class RegionalPagesPaired {

    public function __construct() {

        // Meta boxes
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta']);

        // Admin column
        add_filter('manage_posts_columns', [$this, 'add_region_column']);
        add_filter('manage_pages_columns', [$this, 'add_region_column']);

        add_action('manage_posts_custom_column', [$this, 'render_region_column'], 10, 2);
        add_action('manage_pages_custom_column', [$this, 'render_region_column'], 10, 2);

        // SEO
        add_action('wp_head', [$this, 'add_hreflang'], 20);

        // Helpers
        add_filter('body_class', [$this, 'body_class']);

        // Redirects
        add_action('template_redirect', [$this, 'root_redirect']);
        add_filter('redirect_canonical', [$this, 'disable_region_canonical'], 10, 2);

        // JS sync
        add_action('wp_footer', [$this, 'inject_js_data']);
    }

    // -------------------------
    // META BOXES
    // -------------------------
    public function add_meta_boxes() {

        add_meta_box('region_meta', 'Region', [$this, 'region_box'], ['page','post'], 'side');
        add_meta_box('region_pair', 'Region Pairing', [$this, 'pair_box'], ['page','post'], 'side');
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

    public function pair_box($post) {
        $paired = get_post_meta($post->ID, '_paired_page', true);
        ?>
        <p>Select equivalent page in other region:</p>
        <select name="paired_page">
            <option value="">— None —</option>
            <?php
            $pages = get_posts(['post_type' => ['page','post'], 'numberposts' => -1]);
            foreach ($pages as $p) {
                echo '<option value="'.$p->ID.'" '.selected($paired, $p->ID, false).'>'.$p->post_title.'</option>';
            }
            ?>
        </select>
        <?php
    }

    public function save_meta($post_id) {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if (isset($_POST['region'])) {
            update_post_meta($post_id, '_region', sanitize_text_field($_POST['region']));
        }

        if (isset($_POST['paired_page'])) {
            update_post_meta($post_id, '_paired_page', intval($_POST['paired_page']));
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

        if (strpos($_SERVER['REQUEST_URI'], '/eu') === 0) return 'eu';

        return 'us';
    }

    // -------------------------
    // HREFLANG (PERFECT PAIRING)
    // -------------------------
    public function add_hreflang() {

        if (!is_singular()) return;

        global $post;

        $paired_id = get_post_meta($post->ID, '_paired_page', true);

        if ($paired_id) {

            $current_region = self::get_region();
            $paired_url = get_permalink($paired_id);
            $current_url = get_permalink($post->ID);

            if ($current_region === 'us') {
                echo '<link rel="alternate" hreflang="en-us" href="'.$current_url.'" />'."\n";
                echo '<link rel="alternate" hreflang="en-gb" href="'.$paired_url.'" />'."\n";
            } else {
                echo '<link rel="alternate" hreflang="en-gb" href="'.$current_url.'" />'."\n";
                echo '<link rel="alternate" hreflang="en-us" href="'.$paired_url.'" />'."\n";
            }
        }
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

        if (preg_match('#/(us|eu)(/)?$#', $requested)) {
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

    // -------------------------
    // JS DATA (PAIRING SUPPORT)
    // -------------------------
    public function inject_js_data() {

        if (!is_singular()) return;

        global $post;

        $paired_id = get_post_meta($post->ID, '_paired_page', true);
        $paired_url = $paired_id ? get_permalink($paired_id) : '';

        ?>
        <script>
            window.CURRENT_REGION = "<?php echo self::get_region(); ?>";
            window.PAIRED_URL = "<?php echo esc_url($paired_url); ?>";
        </script>
        <?php
    }
}

// Init
new RegionalPagesPaired();

// Global helper
function get_current_region() {
    return RegionalPagesPaired::get_region();
}


function get_region_url($region) {
	$path = $_SERVER['REQUEST_URI'];

	// Remove existing region prefix (prevents /us/us/ bug)
	$path = preg_replace('#^/(us|eu|anz)#', '', $path);

	return home_url('/' . $region . $path);
}

function add_region_meta() {

	$us  = get_region_url('us');
	$eu  = get_region_url('eu');
	$anz = get_region_url('anz');

	// ✅ Hreflang tags
	echo '
	<link rel="alternate" hreflang="en-US" href="' . esc_url($us) . '" />
	<link rel="alternate" hreflang="en-GB" href="' . esc_url($eu) . '" />
	<link rel="alternate" hreflang="en-AU" href="' . esc_url($anz) . '" />
	<link rel="alternate" hreflang="en-NZ" href="' . esc_url($anz) . '" />
	<link rel="alternate" hreflang="x-default" href="' . esc_url($us) . '" />
	';

	// ✅ JS object for your switcher
	echo "<script>
		window.REGION_URLS = {
			us: '" . esc_url($us) . "',
			eu: '" . esc_url($eu) . "',
			anz: '" . esc_url($anz) . "'
		};
	</script>";
}
add_action('wp_head', 'add_region_meta');