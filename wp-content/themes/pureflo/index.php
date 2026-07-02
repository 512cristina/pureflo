
<?php 
if ( current_user_can('administrator') ) {
    echo '<pre>';
    echo 'is_archive: ' . (is_archive() ? 'YES' : 'NO') . "\n";
    echo 'is_post_type_archive(distributor): ' . (is_post_type_archive('distributor') ? 'YES' : 'NO') . "\n";
    echo 'is_page: ' . (is_page() ? 'YES' : 'NO') . "\n";
    echo 'is_home: ' . (is_home() ? 'YES' : 'NO') . "\n";
    echo 'is_404: ' . (is_404() ? 'YES' : 'NO') . "\n";
    echo '</pre>';
}


get_header(); ?>


<section class="hero default">
	<div class="container">
		<div class="row align-items-center justify-content-center"><div class="col-lg-7 text-center">
			<h1>Oops! This is the Fallback template!</h1>
		</div></div>		
	</div>
</section>

<section>
    <div class="container"><div class="row justify-content-center"><div class="col-lg-10">
        <h2>We are sorry.</h2>
        <p>The page you requested could not be found. Please use the top navigation or search below for something else.</p>
    </div></div>
</section>



<?php get_footer(); ?>
