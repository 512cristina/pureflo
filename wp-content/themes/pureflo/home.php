<?php get_header(); ?>


<!-- Hero Section -->
<section class="hero">
	<div class="overlay"></div>

	<div class="container">
		<div class="row align-items-center"><div class="col-lg-7">
			<h1>News</h1>
		</div></div>		
	</div>
</section>

<?php $featured_query = new WP_Query([ 'post_type' => 'post', 'posts_per_page' => 1 ]);
	if ($featured_query->have_posts()) : while ($featured_query->have_posts()) : $featured_query->the_post(); ?>

<section class="latest-news" data-aos="fade-up" data-aos-duration="2000">
	<div class="container">

		<div class="row justify-content-center"><div class="col-10 bg-light-grey p-5 rounded">

			<div class="row align-items-center">
				<div class="col-lg-7 order-2 order-lg-1">
					<div class="eyebrow"><?php echo get_field('news_date'); ?></div>
					<h2 class="mt-3"><?php the_title(); ?></h2>
					<p><?php echo wp_trim_words(get_the_excerpt(), 18, '[...]'); ?></p>

					<a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm mt-4">Read more <i class="bi bi-arrow-right"></i></a>
				</div>

				<div class="col-lg-4 offset-lg-1 order-1 order-lg-2 mb-3 mb-lg-0 text-center">
					<img src="<?php echo get_field('news_image'); ?>" width="400" height="300" class="rounded" alt="<?php the_title(); ?>">
				</div>
			</div>

		</div></div>

	</div>
</section>

<?php endwhile;  wp_reset_postdata();
	endif; ?>

<section class="bkg-light news-list">
	<div class="container">

    <?php $paged = get_query_var('paged') ? get_query_var('paged') : 1;
		$posts_per_page = 9;
		$cards_query = new WP_Query([ 'post_type' => 'post', 'posts_per_page' => $posts_per_page, 'paged' => $paged, 'offset' => ($paged - 1) * $posts_per_page + 1 ]);
 	
	if ($cards_query->have_posts()) : ?>

		<!-- Cards row -->
		<div class="row justify-content-between gx-3 gy-4">

		<?php while ($cards_query->have_posts()) : $cards_query->the_post(); ?>

			<div class="col-md-6 col-lg-4 d-flex">
				<div class="card w-100 px-0"> 				
					<a href="<?php the_permalink(); ?>" class="img-wrapper">
						<img src="<?php echo get_field('news_image'); ?>" width="400" height="300" class="card-img-top" alt="<?php the_title(); ?>">
					</a>
					<div class="card-body d-flex flex-column">
						<div class="date"><?php echo get_field('news_date'); ?></div>
						<a href="<?php the_permalink(); ?>"><h4 class="card-title mt-2 mb-4"><?php the_title(); ?></h4></a>
						<div class="mt-auto"><a href="<?php the_permalink(); ?>" class="btn btn-sm btn-primary">Read more <i class="bi bi-arrow-right"></i></a></div>
					</div>
				</div>
			</div>

		<?php endwhile; ?>

		</div> <!-- /.row -->

		<!-- Pagination -->
		<div class="row"><div class="col-12 text-center mt-4">
			<?php echo paginate_links([ 'total' => $cards_query->max_num_pages,	'current' => $paged, 
				'prev_text' => '« Prev', 'next_text' => 'Next »', 'format' => '?paged=%#%' ]); ?>
		</div></div>

		<?php wp_reset_postdata(); ?>

	<?php else : ?>
		<h3 class="text-center">No additional press releases found.</h3>
	<?php endif; ?>

  </div>
</section>

<link href="/wp-content/themes/pureflo/assets/css/news.css" type="text/css"  rel="preload stylesheet"  as="style">

<?php get_footer(); ?>