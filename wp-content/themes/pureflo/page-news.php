<?php
/* Template Name: News Single Page */
get_header(); 

?>

<main class="news-list">
  <h1>News</h1>

  <?php
  $query = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => 10
  ]);

  if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post(); ?>
      <article>
        <h2>
          <a href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
          </a>
        </h2>
        <p><?php the_excerpt(); ?></p>
      </article>
    <?php endwhile;

    wp_reset_postdata();
  else :
    echo '<p>No press releases found.</p>';
  endif;
  ?>
</main>

<?php get_footer(); ?>