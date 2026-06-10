<?php /* Template Name: Resources */ 

get_header();  
while (have_posts()) : the_post();  the_content(); endwhile;
?>

<form method="GET" id="rsFilterForm"> 
<section class="pt-5 pb-0">
	<div class="container">
		
        <div class="d-flex flex-wrap justify-content-center align-items-center mb-5">
			<div class="fw-bolder my-0 me-3">SEARCH RESOURCES:</div>
            <div class="resource-search d-flex gap-2">
                <input type="text" name="search" class="form-control" 
                    value="<?php echo isset($_GET['search']) ? esc_attr($_GET['search']) : ''; ?>"  >
                <button type="submit" class="btn btn-primary btn-sm">Search</button>
            </div>
        </div>    


		<div class="d-flex flex-wrap justify-content-center align-items-center my-5 resource-filters">
			<div class="rf-title">Filter Resources:</div>
   
            <?php
            $selected_type  = $_GET['resource_type'] ?? '';
            $selected_topic = $_GET['resource_topic'] ?? '';
            $selected_language = $_GET['resource_language'] ?? '';

            $types  = get_terms(['taxonomy' => 'resource_type', 'hide_empty' => true]);
            $topics = get_terms(['taxonomy' => 'resource_topic', 'hide_empty' => true]);
            $languages = get_terms(['taxonomy' => 'resource_language', 'hide_empty' => true]);
            ?>

            <select name="resource_type">
                <option value="">All Types</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?php echo esc_attr($type->slug); ?>"
                        <?php selected($selected_type, $type->slug); ?>>
                        <?php echo esc_html($type->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="resource_topic">
                <option value="">All Topics</option>
                <?php foreach ($topics as $topic): ?>
                    <option value="<?php echo esc_attr($topic->slug); ?>"
                        <?php selected($selected_topic, $topic->slug); ?>>
                        <?php echo esc_html($topic->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="resource_language">
                <option value="">All Languages</option>
                <?php foreach ($languages as $language): ?>
                    <option value="<?php echo esc_attr($language->slug); ?>"
                        <?php selected($selected_language, $language->slug); ?>>
                        <?php echo esc_html($language->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <a href="<?php echo get_permalink(); ?>#rsFilterForm" class="btn btn-white btn-sm d-inline-block" title="Reset Filters"><i class="bi bi-arrow-clockwise"></i> Reset</a>
        </div>

	</div>
</section>

<section class="bkg-light resource-list">
	<div class="container">
		<div class="row justify-content-between gx-3 gy-4" id="rsList">

        <?php
            $tax_query = [];

            if (!empty($selected_type)) {
                $tax_query[] = ['taxonomy' => 'resource_type', 'field'    => 'slug', 'terms'    => $selected_type, ];
            }

            if (!empty($selected_topic)) {
                $tax_query[] = ['taxonomy' => 'resource_topic', 'field'    => 'slug', 'terms'    => $selected_topic, ];
            }
            
            if (!empty($selected_language)) {
                $tax_query[] = [ 'taxonomy' => 'resource_language', 'field'    => 'slug', 'terms'    => $selected_language, ];
            }
            
            if (count($tax_query) > 1) { $tax_query['relation'] = 'AND'; }

            $search_term = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

            $args = [ 'post_type' => 'resource', 'posts_per_page' => -1, 'post_status' => 'publish', ];

            if (!empty($search_term)) { $args['s'] = $search_term; }

            if (!empty($tax_query)) { $args['tax_query'] = $tax_query; }

            $query = new WP_Query($args);
            if ($query->have_posts()) : 

                while ($query->have_posts()) : $query->the_post();

                    $post_id = get_the_ID();

                    $video_url = get_post_meta( $post_id,  '_rl_video', true );
                    $source_type = get_post_meta( $post_id,  '_rl_source_type',  true );
                    $external_url = get_post_meta( $post_id, '_rl_external_url', true );

                    $languages = [
                        'en' => 'EN',
                        'fr' => 'FR',
                        'it' => 'IT',
                        'es' => 'ES',
                        'de' => 'DE',
                        'da' => 'DA',
                        'nl' => 'NL'
                    ];

                    $resource_files = [];

                    foreach ($languages as $slug => $label) {

                        $file_id = get_post_meta( $post_id,  '_rl_file_' . $slug, true );

                        if ($file_id) {
                            $resource_files[$slug] = [ 'label' => $label, 'url'   => wp_get_attachment_url($file_id) ];
                        }
                    }

                    $file_count = count($resource_files);
                    $file_url = '';

                    if ($file_count === 1) {
                        $first_file = reset($resource_files);
                        $file_url = $first_file['url'];
                    }

                    $image_url = get_the_post_thumbnail_url($post_id, 'full');
                    $image_id = get_post_meta($post_id, '_rl_image', true);
                    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : 'https://placehold.co/350x200/png';

                    // Get resource type
                    $types = get_the_terms($post_id, 'resource_type');
                    
                    $is_video = false;

                    if ($types && !is_wp_error($types)) {
                        foreach ($types as $type) {
                            if (in_array($type->slug, ['video', 'webinar'])) { $is_video = true; break; }
                        }
                    }

                    $is_external = ( !$is_video  && $source_type === 'external' && !empty($external_url) );

                    if ($is_video && $video_url): ?>
                        <!-- VIDEO CARD -->
                        <div class="col-md-6 col-lg-3 d-flex">
                            <div class="card w-100 px-0"> 

                                <figure role="none">
                                    <img src="<?php echo esc_url($image_url); ?>" 
                                        width="350" height="200" class="card-img-top" alt="<?php echo esc_attr(get_the_title()); ?>">

                                    <div class="overlay">
                                        <a href="<?php echo esc_url($video_url); ?>" class="popup-video zoom glightbox" rel="noopener"
                                            title="<?php the_title_attribute(); ?>"><i class="bi bi-play-circle"><span class="d-none">Play video</span></i>
                                        </a>
                                    </div>
                                </figure>

                                <div class="card-body">
                                    <h4 class="card-title"><?php the_title(); ?></h4>
                                    <div class="card-text"><?php echo wp_trim_words(get_the_content(), 20); ?></div>

                                    <a href="<?php echo esc_url($video_url); ?>" class="card-icon glightbox"  rel="noopener">
                                        <i class="bi bi-play-btn-fill"><span class="d-none">Play video</span></i>
                                    </a>
                                </div>

                            </div>
                        </div>

                    <?php elseif ($file_count > 0): ?>
                        <!-- DOCUMENT CARD -->
                        <div class="col-md-6 col-lg-3 d-flex">
                            <div class="card w-100 px-0"> 

                                <a href="<?php echo esc_url($file_url); ?>" target="_blank" class="img-wrapper">
                                    <img src="<?php echo esc_url($image_url); ?>" width="350" height="200" class="card-img-top" 
                                        alt="<?php echo esc_attr(get_the_title()); ?>">
                                </a>

                                <div class="card-body">
                                    <?php if ($file_count <= 1): ?>
                                        <a href="<?php echo esc_url($file_url); ?>" target="_blank">
                                            <h4 class="card-title"><?php the_title(); ?></h4>
                                        </a>

                                    <?php else: ?>
                                        <h4 class="card-title"><?php the_title(); ?></h4>
                                    <?php endif; ?>

                                    <div class="card-text"><?php echo wp_trim_words(get_the_content(), 20); ?></div>

                                    <?php if ($file_count <= 1): ?>

                                        <a href="<?php echo esc_url($file_url); ?>" class="card-icon" target="_blank">
                                            <i class="bi bi-file-earmark-arrow-down-fill"><span class="d-none">Download file</span></i>
                                        </a>

                                    <?php else: ?>

                                        <div class="download-lang">
                                            <div class="card-icon"><i class="bi bi-file-earmark-arrow-down-fill"><span class="d-none">Download file</span></i></div>
                                            <div><b>Download:</b><br>

                                                <?php $links = [];
                                                foreach ($resource_files as $file) {
                                                    $links[] = '<a href="' . esc_url($file['url']) .  '" target="_blank">' . esc_html($file['label']) . '</a>';
                                                }

                                                echo implode(' &#9642; ', $links);
                                                ?> 
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>


                    <?php elseif ($is_external): ?>
                        <!-- EXTERNAL LINK CARD -->
                        <div class="col-md-6 col-lg-3 d-flex">
                            <div class="card w-100 px-0"> 

                                <a href="<?php echo esc_url($external_url); ?>" class="img-wrapper" target="_blank" rel="noopener">
                                    <img src="<?php echo esc_url($image_url); ?>" width="350" height="200" class="card-img-top" 
                                        alt="<?php echo esc_attr(get_the_title()); ?>">
                                </a>

                                <div class="card-body">
                                    <a href="<?php echo esc_url($external_url); ?>" target="_blank" rel="noopener">
                                        <h4 class="card-title"><?php the_title(); ?></h4>
                                    </a>

                                    <div class="card-text"><?php echo wp_trim_words(get_the_content(), 20); ?></div>
                                    <a href="<?php echo esc_url($external_url); ?>" class="card-icon"  target="_blank" rel="noopener"><i class="bi bi-arrow-up-right-square-fill"></i></a>

                                </div>
                            </div>
                        </div>

                    <?php endif; 

                endwhile;  wp_reset_postdata(); 
            endif; 
        ?>

        </div>
    </div>
</section>
</form>

<?php get_footer(); ?>
