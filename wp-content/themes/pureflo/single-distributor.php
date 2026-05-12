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
                <?php if (!empty($website)) : ?>
                    <div class="dist-name my-1"><a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener"> <i class="fa-solid fa-globe"></i> <?php echo esc_url($website); ?></a></div>
                <?php endif; ?>    
                
                <?php if (!empty($rep)) : ?>
                    <div class="dist-rep my-1"> <?php echo esc_html($rep); ?> </div>	
                <?php endif; ?>  
                
                <div class="dist-contact">			
                    <?php
                    if (!empty($email)) { echo '<i class="fa-regular fa-envelope"></i> <a href="mailto:' . esc_attr($email) . ' ">' . esc_attr($email) . '</a> '; }
                    if (!empty($phone)) { echo '<br><i class="fa-solid fa-phone"></i> ' . esc_html($phone); }
                    if (!empty($fax)) { echo '<br><i class="fa-solid fa-fax"></i> ' . esc_html($fax); }
                    if (!empty($address)) { echo '<br><i class="fa-solid fa-location-dot"></i> ' . esc_html($address); }
                    ?>
                </div>				
            </div></div>

            <div class="col-12 col-md-6 col-lg-4 card-countries">
                <p class="h4"><i class="fa-solid fa-earth-americas"></i> Countries</p>

              <?php if (!empty($countries_list)) : ?>
                <p><?php echo esc_html(implode(', ', $countries_list)); ?></p>
              <?php endif; ?>

            </div>

		</div>

        <div class="row"><div class="col text-end mt-4 small">
            <a href="/<?php echo get_current_region(); ?>/distributors/"><em>Search distributors <i class="bi bi-arrow-right"></i></em></a>
        </div></div>

	</div>
</section>

<?php endwhile; ?>

<link href="/wp-content/themes/pureflo/assets/css/distributors.css" rel="preload" as="style">
<link href="/wp-content/themes/pureflo/assets/css/distributors.css" rel="stylesheet">

<?php get_footer(); ?>