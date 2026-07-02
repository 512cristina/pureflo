<?php /* Template Name: Distributors */ 

get_header(); 
while (have_posts()) : the_post();  the_content();  endwhile; ?>

<section class="pt-4">
	<div class="container">    
        <?php
        $query = new WP_Query([
            'post_type' => 'distributor',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
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

            <div id="distributor-results"></div>

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
    filter.addEventListener('change', function () { fetchDistributors(this.value);  });

});
</script>

<?php get_footer(); ?>
