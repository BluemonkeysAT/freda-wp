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
                    <a href="https://www.facebook.com/fredamagazine" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/facebook-green.svg" alt="Facebook">
                    </a>
                    <a href="https://www.instagram.com/fredamagazine/" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/instagram-green-icon.svg" alt="Instagram">
                    </a>
                    <a href="https://www.pinterest.at/fredamagazine/" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/x-green.svg" alt="X">
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
        setup_postdata($post);    // setting up the post manually
        
        // Then you can fetch/echo the author name
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
                    echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="post-category" style="background-color:' . esc_attr($categoryBackground) . '; color:' . esc_attr($categoryTextColor) . ';">' . esc_html($categories[0]->name) . '</a>';
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
<?php get_footer(); ?>