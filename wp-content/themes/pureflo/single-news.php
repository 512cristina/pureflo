<?php get_header(); 
	$news_date = get_post_meta(get_the_ID(), '_pf_news_date', true); ?>

<?php while (have_posts()) : the_post(); ?>
<section class="hero">
	<div class="overlay"></div>
	<div class="container">

			<div class="row align-items-center news-details-hero" data-aos="fade-left" data-aos-duration="2000">
				<div class="col-lg-7 order-2 order-lg-1">
					<div class="eyebrow"><?php if ($news_date) { echo date('j F, Y', strtotime($news_date));} ?></div>
					<h2 class="mt-3"><?php the_title(); ?></h2>
				</div>

				<div class="col-lg-4 offset-lg-1 order-1 order-lg-2 mb-3 mb-lg-0 text-center">
					<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" width="400" height="300" class="rounded" alt="<?php the_title(); ?>">
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

<link href="/wp-content/themes/pureflo/assets/css/news.css" rel="preload" as="style">
<link href="/wp-content/themes/pureflo/assets/css/news.css" rel="stylesheet">

<?php get_footer(); ?>