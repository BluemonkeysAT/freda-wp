<div class="archive-hero">
        <?php
            if (is_category()) {
                $current_category = get_queried_object();
                $id = $current_category->term_id;
                $featured_image = get_field('featured_image', 'category_' . $id);
                $featured_post = get_field('featured_archive_post', 'category_' . $id);
                $backgroundColor = get_field('background_color', 'category_' . $id);
                $categoryTextColor = get_field('text_color', 'category_' . $id);
                $posts = get_field('posts', 'category_' . $id);
               
                if ($featured_post) {
                    echo '<figure>';
                    echo '<img src="' . esc_url(get_the_post_thumbnail_url($featured_post->ID, 'large')) . '" alt="' . esc_attr($featured_post->post_title) . '">';
                    echo '</figure>';

                    echo '<div class="archive-hero-content container">';
                    echo '<div class="archive-hero-heading">';
                    echo '<a href="'. esc_url(get_category_link($current_category)) .'" 
                    class="post-category archive-hero-cat" style="background-color:'
                    . $backgroundColor . '; color:' . $categoryTextColor .';">' 
                    . esc_html($current_category->name) . 
                    '</a>';
                    echo '<a href="' .  get_permalink($featured_post->ID) . '" class="post-link">';
                        echo '<h1>' . $featured_post->post_title . '</h1>';
                        echo '<div class="archive-hero-heading__inner">';
                         echo '<p>' . $featured_post->post_excerpt. '</p>';
                         echo '<span class="post-arrow" style="width: 40px">';
                             echo '<img class="desktop-icon" src="' . get_template_directory_uri() . '/assets/icons/arrow-right.svg" alt="arrow right" />';
                             echo '<img class="mobile-icon" src="' . get_template_directory_uri() . '/assets/icons/arrow-right-black.svg" alt="arrow right" />';
                        echo '</span>';
                        echo '</div>';
                        echo '</div>';
                    echo '</a>';
                }
    
                if ($posts) {
                    echo '<div class="selected-posts archive-recents">';
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
                        echo '<a href="'. get_permalink() .'">';
                        echo '<h3 class="post-title">' . get_the_title() . '</h3>';
                        echo '<p class="post-excerpt">' . get_the_excerpt() . '</p>';
                        echo '</a>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                echo '</div>';
            }
        ?>
    </div>