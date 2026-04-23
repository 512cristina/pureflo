<?php /* Template Name: Distributors */ 

get_header(); 
while (have_posts()) : the_post();  the_content();  endwhile; ?>

<section class="pt-4">
	<div class="container">    
        <?php
        $query = new WP_Query([
            'post_type' => 'distributor',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ]);

        if ($query->have_posts()) : ?>
        
            <?php $countries = function_exists('dm_get_countries') ? dm_get_countries() : []; ?>

            <div class="d-flex justify-content-end my-4">
			    <div class="fw-bolder my-0 me-3">FILTER:</div>
                <select id="country-filter">
                    <option value="">All Countries</option>
                    <?php foreach ($countries as $code => $name): ?>
                        <option value="<?php echo esc_attr($code); ?>">
                            <?php echo esc_html($name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="distributor-results">
                <!--   DESKTOP VIEW -->
                <div class="dist-list d-none d-lg-block">
                    <div class="row dist-header mb-2">
                        <div class="col-3">Distributor Name</div>
                        <div class="col-2">Contact</div>
                        <div class="col-4">Contact Info</div>
                        <div class="col-3">Countries</div>
                    </div>


                <?php while ($query->have_posts()) : $query->the_post();

                    $id = get_the_ID();

                    // Meta fields
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

                    // Countries lookup (from your plugin function)
                    $all_countries = function_exists('dm_get_countries') ? dm_get_countries() : [];

                    // Format countries served
                    $countries_list = [];
                    if (is_array($countries_served)) {
                        foreach ($countries_served as $code) {
                            if (isset($all_countries[$code])) {
                                $countries_list[] = $all_countries[$code];
                            }
                        }
                    }

                    // Address formatting
                    $address = implode(', ', array_filter([$street, $city, $state, $postal, $country]));
                ?>
                    <div class="row">			
                        <div class="col-12 col-lg-3 dist-name"><?php the_title(); ?>
                            <?php if (!empty($website)) : ?>
                                <a href="<?php echo esc_url($website); ?>" target="_blank"><i class="fa-solid fa-globe"></i></a>
                            <?php endif; ?>
                        </div>		

                        <div class="col-12 col-lg-2 dist-rep">
                            <?php // Condition: If Representative empty AND email exists → echo "email"
                                if (empty($rep) && !empty($email)) { echo '<em>email</em>'; } else { echo esc_html($rep);}

                            // Email icon (only if email exists)
                            if (!empty($email)) { echo ' <a href="mailto:' . esc_attr($email) . '"><i class="fa-regular fa-envelope"></i></a> ';  }                        
                            ?> 
                        </div>			

                        <div class="col-12 col-lg-4 dist-contact">
                            <?php // Phone icon + number (only if phone exists)
                            if (!empty($phone)) { echo '<i class="fa-solid fa-phone"></i> ' . esc_html($phone); }
                            if (!empty($fax)) { echo '<br><i class="fa-solid fa-fax"></i> ' . esc_html($fax); }
                            if (!empty($address)) { echo '<br><i class="fa-solid fa-location-dot"></i> ' . esc_html($address); }
                            ?>
                        </div>

                        <div class="col-12 col-lg-3 dist-countries"><?php echo esc_html(implode(', ', $countries_list)); ?></div>			
                    </div>

                <?php endwhile; ?>
                </div> <!-- /.Desktop -->


                <!--   MOBILE VIEW  -->
                <div class="mobile-list row g-3 d-lg-none ">

                <?php $query->rewind_posts();

                    $id = get_the_ID();

                    // Meta fields
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

                    // Countries lookup (from your plugin function)
                    $all_countries = function_exists('dm_get_countries') ? dm_get_countries() : [];

                    // Format countries served
                    $countries_list = [];
                    if (is_array($countries_served)) {
                        foreach ($countries_served as $code) {
                            if (isset($all_countries[$code])) {
                                $countries_list[] = $all_countries[$code];
                            }
                        }
                    }

                    // Address formatting
                    $address = implode(', ', array_filter([$street, $city, $state, $postal, $country]));
                ?>
                    
                    <div class="col-12 col-md-6"><div class="info-card h-100">			
                        <div class="dist-name"><?php the_title(); ?>
                            <?php if (!empty($website)) : ?>
                                <a href="<?php echo esc_url($website); ?>" target="_blank"><i class="fa-solid fa-globe"></i></a>
                            <?php endif; ?>
                        </div>

                        <div class="dist-rep">
                            <?php // Condition: If Representative empty AND email exists → echo "email"
                                if (empty($rep) && !empty($email)) { echo '<em>email</em>'; } else { echo esc_html($rep);}

                            // Email icon (only if email exists)
                            if (!empty($email)) { echo ' <a href="mailto:' . esc_attr($email) . '"><i class="fa-regular fa-envelope"></i></a> ';  }                        
                            ?> 
                        </div>			
                        
                        <div class="dist-contact">
                            <?php // Phone icon + number (only if phone exists)
                            if (!empty($phone)) { echo '<i class="fa-solid fa-phone"></i> ' . esc_html($phone); }
                            if (!empty($fax)) { echo '<br><i class="fa-solid fa-fax"></i> ' . esc_html($fax); }
                            if (!empty($address)) { echo '<br><i class="fa-solid fa-location-dot"></i> ' . esc_html($address); }
                            ?>
                        </div>			
                        
                        <div class="dist-countries"><i class="fa-solid fa-earth-americas"></i> <?php echo esc_html(implode(', ', $countries_list)); ?></div>			
                    </div></div>

                </div> <!-- /.Mobile -->
            </div>
<!--
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<div id="map" style="height:400px;"></div>

<script>
var map = L.map('map').setView([37.8, -96], 4);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
}).addTo(map);

// Example marker
L.marker([30.2672, -97.7431]).addTo(map)
    .bindPopup("Distributor Example");
</script>

                            -->


        <?php wp_reset_postdata(); endif; ?>

	</div>
</section>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const filter = document.getElementById('country-filter');

    const results = document.getElementById('distributor-results');

    function fetchDistributors(country = '') {

        const formData = new FormData();
        formData.append('action', 'filter_distributors');
        formData.append('country', country);

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            results.innerHTML = data;
        });
    }

    // Initial load
    fetchDistributors();

    // On change
    filter.addEventListener('change', function () {
        fetchDistributors(this.value);
    });

});
</script>

<?php get_footer(); ?>
