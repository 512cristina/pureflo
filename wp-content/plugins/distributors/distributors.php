<?php
/*
Plugin Name: Distributor Manager
Description: Custom | Manage distributor listings with validation and enhanced UX.
Version: 1.3
Author: PureFlo
*/

if (!defined('ABSPATH')) exit;

/* ## Load Countries ##  */

function dm_get_countries() {
    static $countries = null;

    if ($countries === null) {
        $file = plugin_dir_path(__FILE__) . 'countries.json';

        if (file_exists($file)) {
            $json = file_get_contents($file);
            $data = json_decode($json, true);

            $countries = [];
            foreach ($data as $country) {
                $countries[$country['code']] = $country['name'];
            }
        } else {
            $countries = [];
        }
    }

    return $countries;
}

/* ##  CPT ##  */

add_action('init', function() {
    register_post_type('distributor', [
        'labels' => [
            'name' => 'Distributors',
            'singular_name' => 'Distributor',
            'add_new_item' => 'Add New Distributor',
            'add_new' => 'Add Distributor',
            'new_item' => 'New Distributor',
            'edit_item' => 'Edit Distributor',
            'view_item' => 'View Distributor',
            'all_items' => 'All Distributors'
        ],
        'public' => true,
        'has_archive' => false,

        'rewrite' => [
            'slug' => 'distributors',
            'with_front' => false
        ],

        'menu_icon' => 'dashicons-groups',
        'supports' => ['title'],
    ]);

});


/* ##  REMOVE / DISABLE DICUSSION & COMMENTS ##  */
add_action('add_meta_boxes', function () {
    remove_meta_box('commentstatusdiv', 'distributor', 'normal'); // Discussion
    remove_meta_box('commentsdiv', 'distributor', 'normal');      // Comments
});
add_filter('comments_open', function ($open, $post_id) { return get_post_type($post_id) === 'distributor' ? false : $open; }, 10, 2);
add_filter('pings_open', function ($open, $post_id) { return get_post_type($post_id) === 'distributor' ? false : $open; }, 10, 2);


/* ##  Taxonomy | Certifications ##  */

add_action('init', function() {

    register_taxonomy('product_certification', 'distributor', [

        'labels' => [
            'name'              => 'Product Certifications',
            'singular_name'     => 'Product Certification',
            'menu_name'         => 'Product Certifications',
            'all_items'         => 'All Certifications',
            'edit_item'         => 'Edit Certification',
            'view_item'         => 'View Certification',
            'update_item'       => 'Update Certification',
            'add_new_item'      => 'Add New Certification',
            'new_item_name'     => 'New Certification',
            'search_items'      => 'Search Certifications',
        ],

        'public'            => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'hierarchical'      => true,

    ]);

});

/* ## Meta Box */

add_action('add_meta_boxes', function() {
    add_meta_box(
        'dm_distributor_details',
        'Distributor Details',
        'dm_render_meta_box',
        'distributor',
        'normal',
        'high'
    );
});

function dm_render_meta_box($post) {

    wp_nonce_field('dm_save_meta', 'dm_meta_nonce');

    $fields = [
        'representative','email','representative2','email2','phone','fax',
        'website','street','city','state','postal','country','countries_served'
    ];

    $values = [];
    foreach ($fields as $field) {
        $values[$field] = get_post_meta($post->ID, $field, true);
        
        if ( $field === 'countries_served' && !is_array($values[$field]) && !empty($values[$field]) ) 
           {  $values[$field] = array_map('trim', explode(',', $values[$field])); }

    }

    $countries = dm_get_countries();
    ?>

    <style>
        .dm-field { margin-bottom: 12px; }
        .dm-field label { font-weight: bold; display:block; }
        .dm-field input, .dm-field select { width:100%; }
        .dm-required { color:#dc3232; }
    </style>

    <?php foreach ($values as $key => $value):
        if (in_array($key, ['country','countries_served'])) continue;
    ?>
        <div class="dm-field">
            <label><?php echo ucwords(str_replace('_',' ',$key)); ?></label>
            <input type="<?php echo (strpos($key,'email') !== false) ? 'email' : 'text'; ?>"
                   name="<?php echo esc_attr($key); ?>"
                   value="<?php echo esc_attr($value); ?>">
        </div>
    <?php endforeach; ?>

    <div class="dm-field">
        <label>Country</label>
        <select name="country">
            <option value="" >Select</option>
            <?php foreach ($countries as $code => $name): ?>
                <option value="<?php echo esc_attr($code); ?>" <?php selected($values['country'], $code); ?>>
                    <?php echo esc_html($name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="dm-field">
        <label>Countries Served <span class="dm-required">*</span></label>
        <select name="countries_served[]" multiple style="width:100%;">
            <?php foreach ($countries as $code => $name): ?>
                <option value="<?php echo esc_attr($code); ?>"
                    <?php echo (is_array($values['countries_served']) && in_array($code, $values['countries_served'])) ? 'selected' : ''; ?>>
                    <?php echo esc_html($name); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p id="countries-served-error" style="display:none;color:#dc3232;">Please select at least one Country Served.</p>
    </div>

<?php }

/* ## Save Meta (DO NOT BLOCK SAVE) ## */

add_action('save_post', function($post_id) {

    if (!isset($_POST['dm_meta_nonce']) || !wp_verify_nonce($_POST['dm_meta_nonce'], 'dm_save_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Save everything normally
    $fields = ['representative','email','representative2','email2','phone','fax','street','city','state','postal','country'];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    if (isset($_POST['website'])) {
        update_post_meta($post_id, 'website', esc_url_raw($_POST['website']));
    }

    if (isset($_POST['countries_served'])) {
        $countries = $_POST['countries_served'];

        // If comma-separated string, turn it back into an array.
        if (!is_array($countries)) {  $countries = array_map('trim', explode(',', $countries)); }

        $countries = array_map('sanitize_text_field', $countries);
        update_post_meta($post_id, 'countries_served', $countries);
    }

});

/* ## Admin Scripts (ONE clean script) ##  */

add_action('admin_enqueue_scripts', function($hook){

    global $post_type;
    if ($post_type !== 'distributor') return;

    wp_enqueue_style('select2-css','https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
    wp_enqueue_script('select2-js','https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',['jquery'],null,true);

    wp_add_inline_script('select2-js', "
    jQuery(document).ready(function($){

        var field = $('select[name=\"countries_served[]\"]');
        var error = $('#countries-served-error');
        var button = $('#publish');

        field.select2({
            placeholder: 'Select countries served',
            width: '100%'
        });

        function validate(){
            var val = field.val();
            if (!val || val.length === 0){
                error.show();
                field.next('.select2-container').find('.select2-selection').css('border','2px solid #dc3232');
                button.prop('disabled', true);

                $('html, body').animate({
                    scrollTop: field.offset().top - 150
                }, 300);

                return false;
            } else {
                error.hide();
                field.next('.select2-container').find('.select2-selection').css('border','');
                button.prop('disabled', false);
                return true;
            }
        }

        // Initial state
        validate();

        // On change
        field.on('change', validate);

    });
    ");
});

function dm_filter_distributors() {

    $selected_country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : '';

    $args = [
        'post_type' => 'distributor',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC',
    ];

    if (!empty($selected_country)) {
        $args['meta_query'] = [
            [
                'key' => 'countries_served',
                'value' => '"' . $selected_country . '"',
                'compare' => 'LIKE'
            ]
        ];
    }

    $query = new WP_Query($args);
    $all_countries = function_exists('dm_get_countries') ? dm_get_countries() : [];

    ob_start();

    if ($query->have_posts()) : ?>

        <!-- DESKTOP -->
        <div class="dist-list d-none d-lg-block">
            <div class="row dist-header mb-2">
                <div class="col-3">Distributor Name</div>
                <div class="col-2">Contact</div>
                <div class="col-4">Contact Info</div>
                <div class="col-3">Countries</div>
            </div>

            <?php while ($query->have_posts()) : $query->the_post();

                $id = get_the_ID();

                $website = get_post_meta($id, 'website', true);
                $rep = get_post_meta($id, 'representative', true);
                $email = get_post_meta($id, 'email', true);
                $phone = get_post_meta($id, 'phone', true);
                $fax = get_post_meta($id, 'fax', true);

                $street = get_post_meta($id, 'street', true);
                $city = get_post_meta($id, 'city', true);
                $state = get_post_meta($id, 'state', true);
                $postal = get_post_meta($id, 'postal', true);
                $country = get_post_meta($id, 'country', true);

                $countries_served = get_post_meta($id, 'countries_served', true);

                // Support comma-separated values
                if (!is_array($countries_served) && !empty($countries_served)) {
                    $countries_served = array_map('trim', explode(',', $countries_served));
                }

                $countries_list = [];

                if (is_array($countries_served)) {
                    foreach ($countries_served as $code) {
                        if (isset($all_countries[$code])) {
                            $countries_list[] = $all_countries[$code];
                        }
                    }
                }

                $address = implode(', ', array_filter([$street, $city, $state, $postal, $country]));
                $distributor_url = get_permalink();
            ?>

            <div class="row">
                <div class="col-12 col-lg-3 dist-name">
                    <a href="<?php echo esc_url($distributor_url); ?>"><?php the_title(); ?></a>
                    <?php if ($website): ?>
                        <a href="<?php echo esc_url($website); ?>" target="_blank">
                            <i class="bi bi-globe2"></i>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-lg-2 dist-rep">
                    <?php
                    if (empty($rep) && !empty($email)) {
                        echo '<em>email</em>';
                    } else {
                        echo esc_html($rep);
                    }

                    if ($email) {
                        echo ' <a href="mailto:' . esc_attr($email) . '"><i class="bi bi-envelope-at"></i></a>';
                    }
                    ?>
                </div>

                <div class="col-12 col-lg-4 dist-contact">
                    <?php if ($phone) echo '<i class="bi bi-telephone-fill"></i> ' . esc_html($phone); ?>
                    <?php if ($fax) echo '<br><i class="bi bi-printer-fill"></i> ' . esc_html($fax); ?>
                    <?php if ($address) echo '<br><i class="bi bi-geo-alt-fill"></i> ' . esc_html($address); ?>
                </div>

                <div class="col-12 col-lg-3 dist-countries">
                    <?php echo esc_html(implode(', ', $countries_list)); ?>
                </div>
            </div>

            <?php endwhile; ?>
        </div>

        <?php $query->rewind_posts(); ?>

        <!-- MOBILE -->
        <div class="mobile-list row g-3 d-lg-none">
            <?php while ($query->have_posts()) : $query->the_post();

                $id = get_the_ID();

                $website = get_post_meta($id, 'website', true);
                $rep = get_post_meta($id, 'representative', true);
                $email = get_post_meta($id, 'email', true);
                $phone = get_post_meta($id, 'phone', true);
                $fax = get_post_meta($id, 'fax', true);

                $street = get_post_meta($id, 'street', true);
                $city = get_post_meta($id, 'city', true);
                $state = get_post_meta($id, 'state', true);
                $postal = get_post_meta($id, 'postal', true);
                $country = get_post_meta($id, 'country', true);

                $countries_served = get_post_meta($id, 'countries_served', true);

                // Support comma-separated values
                if (!is_array($countries_served) && !empty($countries_served)) {
                    $countries_served = array_map('trim', explode(',', $countries_served));
                }

                $countries_list = [];

                if (is_array($countries_served)) {
                    foreach ($countries_served as $code) {
                        if (isset($all_countries[$code])) {
                            $countries_list[] = $all_countries[$code];
                        }
                    }
                }

                $address = implode(', ', array_filter([$street, $city, $state, $postal, $country])); 
                $distributor_url = get_permalink();
            ?>

            <div class="col-12 col-md-6">
                <div class="info-card h-100">
                    <div class="dist-name">
                        <a href="<?php echo esc_url($distributor_url); ?>"><?php the_title(); ?></a>
                        <?php if ($website): ?>
                            <a href="<?php echo esc_url($website); ?>" target="_blank">
                                <i class="bi bi-globe2"></i>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="dist-rep">
                        <?php
                        if (empty($rep) && !empty($email)) {
                            echo '<em>email</em>';
                        } else {
                            echo esc_html($rep);
                        }

                        if ($email) {
                            echo ' <a href="mailto:' . esc_attr($email) . '"><i class="bi bi-envelope-at"></i></a>';
                        }
                        ?>
                    </div>

                    <div class="dist-contact">
                        <?php if ($phone) echo '<i class="bi bi-telephone-fill"></i> ' . esc_html($phone); ?>
                        <?php if ($fax) echo '<br><i class="bi bi-printer-fill"></i> ' . esc_html($fax); ?>
                        <?php if ($address) echo '<br><i class="bi bi-geo-alt-fill"></i> ' . esc_html($address); ?>
                    </div>

                    <div class="dist-countries">
                        <i class="bi bi-globe-americas-fill"></i>
                        <?php echo esc_html(implode(', ', $countries_list)); ?>
                    </div>
                </div>
            </div>

            <?php endwhile; ?>
        </div>

    <?php else:
        echo '<p>No distributors found.</p>';
    endif;

    wp_reset_postdata();

    echo ob_get_clean();
    wp_die();
}
add_action('wp_ajax_filter_distributors', 'dm_filter_distributors');
add_action('wp_ajax_nopriv_filter_distributors', 'dm_filter_distributors');

/* ## Add Export button ##  */

add_action('restrict_manage_posts', function () {

    global $typenow;
    if ($typenow !== 'distributor') {return;}

    ?>
    <a href="<?php echo esc_url(
        wp_nonce_url( admin_url('edit.php?post_type=distributor&dm_export=csv'), 'dm_export_csv'  )
    ); ?>" class="button button-primary"> Export CSV
    </a>  <?php
});

add_action('admin_init', function () {

    if ( !isset($_GET['dm_export']) ||  $_GET['dm_export'] !== 'csv') { return; }
    if (!current_user_can('manage_options')) { return; }
    check_admin_referer('dm_export_csv');

    $countries = dm_get_countries();

    $query = new WP_Query([
        'post_type' => 'distributor',
        'posts_per_page' => -1,
        'post_status' => ['publish','draft'],
        'orderby' => 'title',
        'order' => 'ASC'
    ]);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=distributors-' . date('Y-m-d') . '.csv');

    $output = fopen('php://output', 'w');

    fputcsv($output, [
        'ID',
        'Distributor Name',
        'Representative', 'Representative 2',
        'Email', 'Email 2',
        'Phone',
        'Fax',
        'Website',
        'Countries Served', 'Country Codes',
        'Product Certifications',
        'Street', 'City', 'State', 'Postal', 'Country',
        'Status'
    ]);

    while ($query->have_posts()) {

        $query->the_post();
        $id = get_the_ID();
        $codes = get_post_meta($id, 'countries_served', true);

        if (!is_array($codes)) { $codes = []; }

        $names = [];

        foreach ($codes as $code) { if (isset($countries[$code])) { $names[] = $countries[$code]; } }

        $certification_terms = get_the_terms($id, 'product_certification');
        $certifications = '';

        if (!is_wp_error($certification_terms) && !empty($certification_terms)) {
            $certifications = implode(', ', wp_list_pluck($certification_terms, 'name'));
        }

        fputcsv($output, [

            $id,
            get_the_title(),
            get_post_meta($id,'representative',true),
            get_post_meta($id,'representative2',true),
            get_post_meta($id,'email',true),
            get_post_meta($id,'email2',true),
            get_post_meta($id,'phone',true),
            get_post_meta($id,'fax',true),
            get_post_meta($id,'website',true),
            implode(', ', $names),
            implode(', ', $codes),
            $certifications,
            get_post_meta($id,'street',true),
            get_post_meta($id,'city',true),
            get_post_meta($id,'state',true),
            get_post_meta($id,'postal',true),
            get_post_meta($id,'country',true),
            get_post_status($id)
        ]);
    }

    wp_reset_postdata();

    fclose($output);

    exit;

});