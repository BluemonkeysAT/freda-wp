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

    <?php get_template_part('template-parts/archive-hero'); ?>
    
    <?php get_template_part('template-parts/category-recent-posts'); ?>

    <div class="archive-content">
        <?php echo do_shortcode('[elementor-template id="167"]'); ?>
    </div>
    

<?php get_footer(); ?>
