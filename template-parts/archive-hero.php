<div class="archive-hero">
        <?php
            if (is_category()) {
                $current_category = get_queried_object();
                $id = $current_category->term_id;
                $featured_image = get_field('featured_image', 'category_' . $id);
                $headline = get_field('headline', 'category_' . $id);
                $headline_desc = get_field('headline_description', 'category_' . $id);
                $headline_src = get_field('headline_link', 'category_' . $id);
                $posts = get_field('posts', 'category_' . $id);
                $backgroundColor = get_field('background_color', 'category_' . $id);
                $categoryTextColor = get_field('text_color', 'category_' . $id);

                if ($featured_image) {
                    echo '<figure>';
                    echo '<img src="' . esc_url($featured_image) . '" alt="' . esc_attr($current_category->name) . '">';
                    echo '</figure>';
                }

                echo '<div class="archive-hero-content container">';
                    echo '<div class="archive-hero-heading">';
                    echo '<h1>' . $headline . '</h1>';
                    echo '<div class="archive-hero-heading__inner">';
                     echo '<p>' . $headline_desc . '</p>';
                     echo '<a href="' . $headline_src . '"><img src="' . get_template_directory_uri() . '/assets/icons/arrow-right.svg" alt="arrow right" /></a>';
                    echo '</div>';
                echo '</div>';
                

                if ($posts) {
                    echo '<div class="selected-posts">';
                    foreach ($posts as $post) {
                        echo '<div class="post-item">';
                        echo '<a href="'. get_permalink() .'" class="thumbnail-wrapper">';
                        if (has_post_thumbnail()) {
                            echo get_the_post_thumbnail(get_the_ID(), 'large');
                        }
                        $categories = get_the_category();
                        if (!empty($categories)) {
                            echo '<a href="'. esc_url(get_category_link($current_category)) .'" 
                            class="post-category" style="background-color:'
                            . $backgroundColor . '; color:' . $categoryTextColor .';">' 
                            . esc_html($current_category->name) . 
                            '</a>';
                        }
                        echo '</a>';
                        echo '<h3 class="post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                        echo '<p class="post-excerpt">' . get_the_excerpt() . '</p>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                echo '</div>';
            }
        ?>
    </div>