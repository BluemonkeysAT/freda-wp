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
            <div class="date-temperature">
                <p class="current-date"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/calendar-icon.svg" alt="calendar icon"><?php echo date('l, F j, Y'); ?></p>
                
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
            <?php if (has_custom_logo()) : ?>
                <div class="site-logo">
                    <?php the_custom_logo(); ?>
                </div>
            <?php endif; ?>
            <div class="search-social">
                <form method="post" action="<?php echo esc_url(home_url('/')); ?>" class="search-form">
                    <input type="text" placeholder="Suche" class="search-input">
                    <button type="submit" class="search-button"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/search-icon.svg" alt="search icon"></button>
                </form>
                <div class="social-icons">
                    <a href="https://www.facebook.com/fredamagazine" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/facebook-icon.svg" alt="Facebook"></a>
                    <a href="https://www.instagram.com/fredamagazine/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/instagram-icon.svg" alt="Instagram"></a>
                    <a href="https://www.pinterest.at/fredamagazine/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/x-icon.svg" alt="X"></a>
                </div>
            </div>
        </div>
        <nav class="main-navigation">
            <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'nav-menu',
                ));
            ?>
        </nav>
    </header>