<?php
get_header();

while (have_posts()) : the_post();

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

    $all_countries = function_exists('dm_get_countries') ? dm_get_countries() : [];

    $countries_list = [];

    if (is_array($countries_served)) {
        foreach ($countries_served as $code) {
            if (isset($all_countries[$code])) {
                $countries_list[] = $all_countries[$code];
            }
        }
    }

    $address = implode(', ', array_filter([$street, $city, $state, $postal, $country]));
?>

<section class="hero default">
	<div class="container">
		<div class="row align-items-center"><div class="col">
			<div class="eyebrow">Distributor</div>
			<h1><?php the_title(); ?></h1>
		</div></div>		
	</div>
</section>


<section>
	<div class="container">
		<div class="row justify-content-between mobile-list">

            <div class="col-12 col-md-6"><div class="info-card single h-100">

                <div class="dist-contact">			
                    <?php
                    if (!empty($rep)) { echo '<i class="bi bi-person-fill"></i> <span class="fw-500">' . esc_html($rep) . '</span><br>'; }
                    if (!empty($email)) { echo '<i class="bi bi-envelope-at"></i> <a href="mailto:' . esc_attr($email) . ' ">' . esc_attr($email) . '</a><br> '; }
                    if (!empty($phone)) { echo '<i class="bi bi-telephone-fill"></i> ' . esc_html($phone) . '<br>'; }
                    if (!empty($fax)) { echo '<i class="bi bi-printer-fill"></i> ' . esc_html($fax) . '<br>'; }
                    if (!empty($address)) { echo '<i class="bi bi-geo-alt-fill"></i> ' . esc_html($address); }
                    ?>
                </div>		
                <?php if (!empty($website)) : ?>
                    <div class="my-1"><a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener"> <i class="bi bi-globe2"></i> <?php echo esc_url($website); ?></a></div>
                <?php endif; ?>  		
            </div></div>

            <div class="col-12 col-md-6 col-lg-4 card-countries">
                <p class="h4"><i class="bi bi-globe-americas-fill"></i> Countries</p>

              <?php if (!empty($countries_list)) : ?>
                <p><?php echo esc_html(implode(', ', $countries_list)); ?></p>
              <?php endif; ?>

            </div>

		</div>

        <div class="row"><div class="col text-end mt-4 small">
            <a href="/<?php echo get_current_region(); ?>/distributors/"><em>Search all distributors <i class="bi bi-arrow-right"></i></em></a>
        </div></div>

	</div>
</section>

<?php endwhile; ?>

<link href="/wp-content/themes/pureflo/assets/css/distributors.css" rel="preload" as="style">
<link href="/wp-content/themes/pureflo/assets/css/distributors.css" rel="stylesheet">

<?php get_footer(); ?>