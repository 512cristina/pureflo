<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
<section class="hero">
	<div class="overlay"></div>
	<div class="container">

			<div class="row align-items-center news-details-hero" data-aos="fade-left" data-aos-duration="2000">
				<div class="col-lg-7 order-2 order-lg-1">
					<div class="eyebrow"><?php echo get_field('news_date'); ?></div>
					<h2 class="mt-3"><?php the_title(); ?></h2>
				</div>

				<div class="col-lg-4 offset-lg-1 order-1 order-lg-2 mb-3 mb-lg-0 text-center">
					<img src="<?php echo get_field('news_image'); ?>" width="400" height="300" class="rounded" alt="Featured Resource: Myth vs Fact">
				</div>
			</div>
	
	</div>
</section>

<section>
	<div class="container">
		<div class="row"><div class="col-12 news-details">

			<?php the_content(); ?>
		
		</div></div>
	</div>
</section>
<?php endwhile; ?>

<link href="/wp-content/themes/pureflo/assets/css/news.css" type="text/css"  rel="preload stylesheet"  as="style">

<?php get_footer(); ?>