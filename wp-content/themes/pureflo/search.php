<?php get_header(); ?>

<section class="hero default">
	<div class="container">
		<div class="row align-items-center"><div class="col-lg-7">
			<div class="eyebrow">PureFlo</div>
			<h1>Search Results</h1>
		</div></div>		
	</div>
</section>

<style>
.form-control:focus { outline: none; box-shadow: none; border-color: inherit; }
.search-field {background-color:var(--almost-white) !important;}
.card .img-wrapper {overflow: hidden;}
.card-text {font-size:1rem; margin: 8px 0 15px 0;}
.card-title {font-size:1.05rem;}
a.read-more {display:block; font-weight: 600; text-transform: uppercase; font-size:0.9rem; margin-top:0.6rem; text-align:right; margin-top:auto;}
</style>

<section>
	<div class="container">        
		<div class="row justify-content-center">
			<div class="col-auto">
				<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>"  class="d-flex align-items-center gap-2">            
					<input type="search" name="s" class="form-control search-field" placeholder="Search..." 
                        value="<?php echo get_search_query(); ?>">
					<button type="submit" class="btn btn-square btn-sm">Search</button>
				</form>
			</div>
		</div>

		<div class="row"><div class="col">
            <p><strong>Results for:</strong> <em><?php echo get_search_query(); ?></em></p>
		</div></div>


    <?php if (have_posts()) : ?>

	    <!-- Cards row -->
		<div class="row gx-3 gy-4">

        <?php while (have_posts()) : the_post(); 
        
            // Get featured image or fallback
            if (has_post_thumbnail()) { $image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            } else {  $image_url = get_template_directory_uri() . '/assets/img/global/pureflo-featured-image.jpg'; }

            // Get excerpt or fallback to content
            if (has_excerpt()) { $text = get_the_excerpt();
            } else { $text = wp_strip_all_tags(get_the_content()); }

            // Trim to 225 characters
            $trimmed = mb_substr($text, 0, 225);
            if (mb_strlen($text) > 225) { $trimmed .= '...'; }
        ?>

            <div class="col-md-6 col-lg-3 d-flex">
                <div class="card w-100 px-0"> 
                    <div class="img-wrapper">
                        <img src="<?php echo esc_url($image_url); ?>"  class="card-img-top" alt="<?php the_title_attribute(); ?>">
                    </div>

                    <div class="card-body d-flex flex-column">
                        <a href="<?php the_permalink(); ?>"><h4 class="card-title"><?php the_title(); ?></h4></a>
                        <div class="card-text">
                            <?php echo esc_html($trimmed); ?>                            
                        </div>
                        <a href="<?php the_permalink(); ?>" class="read-more">read more <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

        <?php endwhile; ?>   
		</div><!-- /.Cards row -->

    <?php else : ?>

        <div class="row"><div class="col">
            <p>No results found. Try a different search.</p> 
        </div></div>

    <?php endif; ?>

	</div>
</section>

<?php get_footer(); ?>
