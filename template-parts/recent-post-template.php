<div class="post-item">
    <a href="<?php the_permalink(); ?>" class="thumbnail-wrapper">
        <?php if (has_post_thumbnail()) {
            the_post_thumbnail('medium');
        } ?>
    </a>

    <div class="post-info">
        <?php
        $categories = get_the_category();
        if (!empty($categories)) {
            $categoryBackground = get_field('background_color', 'category_' . $categories[0]->term_id);
            $categoryTextColor = get_field('text_color', 'category_' . $categories[0]->term_id);
            echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="post-category" style="background-color:' . esc_attr($categoryBackground) . '; color:' . esc_attr($categoryTextColor) . ';">' . esc_html($categories[0]->name) . '</a>';
        }
        ?>
        <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <div class="post-excerpt">
            <a href="<?php the_permalink(); ?>">
                <?php the_excerpt(); ?>
            </a>
        </div>
    </div>
</div>