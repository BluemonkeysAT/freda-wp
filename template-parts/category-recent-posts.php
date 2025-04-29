<div class="category-recent-posts">
    <?php
    $current_category = get_queried_object();
    $paged = 1;

    // Default posts per page
    $posts_per_page = wp_is_mobile() ? 4 : 6;

    $query = new \WP_Query([
        'posts_per_page' => $posts_per_page,
        'cat' => $current_category->term_id,
        'paged' => $paged,
    ]);

    echo '<h2>Neueste Artikel</h2>';
    echo '<div class="selected-posts archive-recents container">';

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $categories = get_the_category();
            $in_current_category = false;

            foreach ($categories as $category) {
                if ($category->term_id == $current_category->term_id) {
                    $in_current_category = true;
                    break;
                }
            }

            if ($in_current_category) {
                get_template_part('template-parts/recent-post-template', 'item');
            }
        endwhile;
    endif;

    echo '</div>';

    wp_reset_postdata();

    if ($query->max_num_pages > 1): ?>
        <button class="single-cat-load-more btn-primary"
                data-page="1"
                data-category="<?php echo esc_attr(get_queried_object_id()); ?>"
                data-posts-per-page="<?php echo esc_attr($posts_per_page); ?>">
            Mehr Laden
        </button>

        <div id="load-more-spinner" style="display:none;">
            <div class="spinner"></div>
        </div>
    <?php endif;
    ?>
</div>