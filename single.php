<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header class="site-header">
        <div class="container">
            <div class="date-temperature green">
                <p class="current-date">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/calendar-icon-black.svg" alt="calendar icon">
                    <?php echo date('l, F j, Y'); ?>
                </p>
                
                    <?php
                    $weather = get_option('swd_weather');

                    if ($weather) {
                        $icon = swd_get_weather_icon($weather['code'], $weather['icon']);
                        $temp = $weather['temp'];
                        $icon_path = get_template_directory() . "/assets/icons/weather/{$icon}.svg";

                        echo '<div class="weather-header">';
                        if (file_exists($icon_path)) {
                            echo '<span class="weather-icon">';
                            echo file_get_contents($icon_path); // inject raw SVG
                            echo '</span>';
                        }
                        echo '<p>Wien - ' . esc_html($temp) . '°C</p>';
                        echo '</div>';
                    }
                    ?>
                
            </div>

            <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo green">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/freda-logo-green.svg" alt="freda logo" />
            </a>
            
            <div class="search-social green">
                <form method="post" action="<?php echo esc_url(home_url('/')); ?>" class="search-form">
                    <input type="text" placeholder="Suche" class="search-input">
                    <button type="submit" class="search-button">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/search-icon-black.svg" alt="search icon">
                    </button>
                </form>
                <div class="social-icons">
                    <a href="<?php echo get_field('instagram', 'options'); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/instagram-green-icon.svg" alt="Instagram">
                    </a>
                    <a href="<?php echo get_field('facebook', 'options'); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/facebook-green.svg" alt="Facebook">
                    </a>
                    <a href="<?php echo get_field('tiktok', 'options'); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/tiktok-green.svg" alt="TikTok">
                    </a>
                </div>
            </div>
        </div>
        <nav class="main-navigation green">
            <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'nav-menu',
                ));
            ?>
        </nav>
    </header>
    <main>

    <div class="post-featured-image">
        <?php if (has_post_thumbnail()) : ?>
            <img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php the_title_attribute(); ?>" class="featured-image">
        <?php endif; ?>
    </div>
    
    <?php
        $post = get_post();
        setup_postdata($post); 
        
        $authorName = get_the_author(); 
    ?>

    <div class="post-content">
        <div class="container">
            <div class="post-content__inner">
            <div class="post-categories">
                <?php
                $categories = get_the_category();
                if (!empty($categories)) {
                    $categoryBackground = get_field('background_color', 'category_' . $categories[0]->term_id);
                    $categoryTextColor = get_field('text_color', 'category_' . $categories[0]->term_id);
                    echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="post-category" style="font-weight: 600; background-color:' . esc_attr($categoryBackground) . '; color:' . esc_attr($categoryTextColor) . ';">' . esc_html($categories[0]->name) . '</a>';
                }
                ?>
            </div>
            <div class="post-info">
                <h1 class="post-title"><?php the_title(); ?></h1>
                <div class="post-meta">
                    <p class="post-author">
                        <?php 
                        echo '<img src="' . get_template_directory_uri() . '/assets/icons/user-icon.svg" alt="author icon" /> ' . $authorName;
                        ?>
                    </p>
                    <p class="post-date">
                        <?php 
                        echo '<img src="' . get_template_directory_uri() . '/assets/icons/calendar-icon-black.svg" alt="calendar icon" /> ' . get_the_date(); 
                        ?>
                    </p>
                    <p class="post-reading-time">
                        <?php
                        $word_count = str_word_count(strip_tags(get_the_content()));
                        $reading_time = ceil($word_count / 200); // Assuming 200 words per minute reading speed
                        echo '<img src="' . get_template_directory_uri() . '/assets/icons/book-icon.svg" alt="author icon" /> ' . $reading_time . ' min';
                        ?>
                    </p>
                </div>
            </div>
            <div class="post-text">
                <?php
                 the_content();
                ?>
            </div>
            </div>
            <div class="post-author">
                <div class="post-author__inner">
                    <div class="post-author__image">
                        <?php echo get_avatar(get_the_author_meta('ID'), 96); ?>
                    </div>
                    <div class="post-author__info">
                        <p class="post-author__name"><?php echo esc_html($authorName); ?></p>
                        <p class="post-author__bio"><?php echo esc_html(get_the_author_meta('description')); ?></p>
                    </div>
                </div>
                <div class="post-related-categories">
                    <h3>Ähnliche Kategorien</h3>
                    <ul>
                        <?php
                        $related_categories = get_the_category();
                        if (!empty($related_categories)) {
                            foreach ($related_categories as $category) {
                                echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="related-posts">
        <div class="container">
            <h2>DAS KÖNNTE SIE AUCH INTERESSIEREN</h2>
            <div class="related-posts__inner">
                <?php
                $related_posts = get_field('related_posts');

                if ($related_posts) {
                    foreach ($related_posts as $related_post) {
                        
                        echo '<div class="post-item">';
                        echo '<a href="' . get_permalink($related_post->ID) . '" class="thumbnail-wrapper">';
                        if (has_post_thumbnail($related_post->ID)) {
                            echo get_the_post_thumbnail($related_post->ID, 'large');
                        }
                        $categories = get_the_category($related_post->ID);
                        if (!empty($categories)) {
                            $categoryBackground = get_field('background_color', 'category_' . $categories[0]->term_id);
                            $categoryTextColor = get_field('text_color', 'category_' . $categories[0]->term_id);
                            
                            echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" 
                            class="post-category" style="background-color:' . esc_attr($categoryBackground) . '; color:' . esc_attr($categoryTextColor) . ';">' 
                            . esc_html($categories[0]->name) . 
                            '</a>';
                        }
                        echo '</a>';
                        echo '<h3 class="post-title"><a href="' . get_permalink($related_post->ID) . '">' . get_the_title($related_post->ID) . '</a></h3>';
                        echo '<p class="post-excerpt">' . get_the_excerpt($related_post->ID) . '</p>';
                        echo '</div>';
                    
                    }
                } else {
                    // Fallback to related posts based on categories if no ACF field is set
                    $related_posts = get_posts(array(
                        'category__in' => wp_get_post_categories($post->ID),
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => 3,
                    ));

                    if ($related_posts) {
                        foreach ($related_posts as $related_post) {
                            setup_postdata($related_post);
                            
                            echo '<div class="post-item">';
                            echo '<a href="' . get_permalink($related_post->ID) . '" class="thumbnail-wrapper">';
                            if (has_post_thumbnail($related_post->ID)) {
                                echo get_the_post_thumbnail($related_post->ID, 'large');
                            }
                            $categories = get_the_category($related_post->ID);
                            if (!empty($categories)) {
                                $categoryBackground = get_field('background_color', 'category_' . $categories[0]->term_id);
                                $categoryTextColor = get_field('text_color', 'category_' . $categories[0]->term_id);
                                
                                echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" 
                                class="post-category" style="background-color:' . esc_attr($categoryBackground) . '; color:' . esc_attr($categoryTextColor) . ';">' 
                                . esc_html($categories[0]->name) . 
                                '</a>';
                            }
                            echo '</a>';
                            echo '<h3 class="post-title"><a href="' . get_permalink($related_post->ID) . '">' . get_the_title($related_post->ID) . '</a></h3>';
                            echo '<p class="post-excerpt">' . get_the_excerpt($related_post->ID) . '</p>';
                            echo '</div>';
                        }
                        wp_reset_postdata();
                    }
                
                }
                ?>
            </div>

            <div class="related-posts__socials">
                <h3>FOLGE UNS AUF UNSEREN SOCIAL MEDIA KANÄLEN:</h3>
                <div class="social-icons">
                    <a href="<?php echo get_field('facebook', 'options'); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/facebook-icon.svg" alt="Facebook">
                    </a>
                    <a href="<?php echo get_field('instagram', 'options'); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/instagram-icon.svg" alt="Instagram">
                    </a>
                    <a href="<?php echo get_field('x', 'options'); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/x-icon.svg" alt="X">
                    </a>
                    <a href="<?php echo get_field('tiktok', 'options'); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/tiktok-icon.svg" alt="TikTok">
                    </a>
                    <a href="<?php echo get_field('youtube', 'options'); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/youtube-icon.svg" alt="YouTube">
                    </a>
                </div>
            </div>
        </div>
<?php get_footer(); ?>