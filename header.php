<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<?php
$is_mobile = wp_is_mobile();
$is_front_page = is_front_page();

if ($is_mobile) {
    $GLOBALS['is_green_layout'] = false;
} else {
    if ($is_front_page) {
        $GLOBALS['is_green_layout'] = false;
    } else {
        $GLOBALS['is_green_layout'] = true;
    }
}
?>

<body <?php body_class(); ?>>
    
    <header class="site-header">
        <div class="container">
            <div class="date-temperature<?php echo (!empty($GLOBALS['is_green_layout']) ? ' green' : ''); ?>">
                <p class="current-date">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/<?php echo (!empty($GLOBALS['is_green_layout']) ? 'calendar-icon-black.svg' : 'calendar-icon.svg'); ?>" alt="calendar icon">
                    <?php setlocale(LC_TIME, 'de_DE.UTF-8'); echo strftime('%A %d.%B %Y'); ?>
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
                <a href="<?php echo esc_url(home_url('/')); ?>"class="site-logo <?php echo (!empty($GLOBALS['is_green_layout']) ? ' green' : ''); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/<?php echo (!empty($GLOBALS['is_green_layout']) ? 'freda-logo-green.svg' : 'freda-logo.svg'); ?>" alt="freda-logo">
                </a>
            <div class="search-social <?php echo (!empty($GLOBALS['is_green_layout']) ? ' green' : ''); ?>">
                <form method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-form">
                    <input type="text" name="s" placeholder="Suche" class="search-input" value="<?php echo get_search_query(); ?>">
                    <button type="submit" class="search-button"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/<?php echo (!empty($GLOBALS['is_green_layout']) ? 'search-icon-black.svg' : 'search-icon.svg'); ?>" alt="search icon"></button>
                </form>
                <div class="social-icons">
                    <a href="<?php echo get_field('instagram', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/<?php echo (!empty($GLOBALS['is_green_layout']) ? 'instagram-green-icon.svg' : 'instagram-icon.svg'); ?>" alt="Instagram"></a>
                    <a href="<?php echo get_field('facebook', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/<?php echo (!empty($GLOBALS['is_green_layout']) ? 'facebook-green.svg' : 'facebook-icon.svg'); ?>" alt="Facebook"></a>
                    <a href="<?php echo get_field('tiktok', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/<?php echo (!empty($GLOBALS['is_green_layout']) ? 'tiktok-green.svg' : 'tiktok-icon.svg'); ?>" alt="Tiktok"></a>
                </div>
            </div>
        </div>
        <nav class="main-navigation <?php echo (!empty($GLOBALS['is_green_layout']) ? ' green' : ''); ?>">
            <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'nav-menu',
                ));
            ?>
        </nav>
    </header>
    <div class="header-mobile <?php echo (!empty($GLOBALS['is_green_layout']) ? 'green' : ''); ?>">
        <div class="container">
            <div class="mobile-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>"class="site-logo <?php echo (!empty($GLOBALS['is_green_layout']) ? 'green' : ''); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/<?php echo (!empty($GLOBALS['is_green_layout']) ? 'freda-logo-green.svg' : 'freda-logo.svg'); ?>" alt="freda-logo">
                </a>
            </div>
            <div class="menu-button <?php echo (!empty($GLOBALS['is_green_layout']) ? ' green' : ''); ?>">
                <button id="menu-toggle" class="menu-toggle">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>
            </div>
            <div class="mobile-menu">
                <div class="mobile-logo__green">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo green">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/freda-logo-green.svg" alt="freda logo" />
                    </a>
                </div>
                <form method="post" action="<?php echo esc_url(home_url('/')); ?>" class="search-form">
                    <input type="text" name="s" placeholder="Suche" class="search-input">
                    <button type="submit" class="search-button"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/search-icon-black.svg" alt="search icon"></button>
                </form>
               
                <?php
                $menu_locations = get_nav_menu_locations();
                if (isset($menu_locations['primary'])) {
                    $menu_items = wp_get_nav_menu_items($menu_locations['primary']);

                    if ($menu_items) {
                        echo '<ul class="mobile-nav-menu">';
                        foreach ($menu_items as $item) {
                            $slug = sanitize_title($item->title);
                            $category = get_term_by('slug', $slug, 'category');

                            // Defaults
                            $category_background = '';
                            $icon_html = '';

                            if ($category instanceof WP_Term) {
                                $category_background = get_field('background_color', 'category_' . $category->term_id);
                                $icon_border = get_field('icon_border', 'category_' . $category->term_id);
                                $icon_field = get_field('icon', 'category_' . $category->term_id);

                                if ($icon_field) {
                                    $icon_html = '<figure class="category-icon-wrapper" style="border: 1px solid '. esc_attr($icon_border) .'; background-color: ' . esc_attr($category_background) . ';">';
                                    $icon_html .= '<img src="' . esc_url($icon_field) . '" alt="' . esc_attr($item->title) . ' icon" class="category-icon" />';
                                    $icon_html .= '</figure>';
                                } else {
                                    $icon_html = '<figure class="category-icon-wrapper" style="background-color: ' . esc_attr($category_background) . ';">';
                                    $icon_html .= '</figure>';
                                }
                            }

                            echo '<li>';
                            echo '<a href="' . esc_url($item->url) . '" class="menu-item-link">';
                            echo '<span class="item-title" style="color: ' . esc_attr($category_background) . ';">' . esc_html($item->title) . '</span>';
                            echo $icon_html;
                            echo '</a>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
                }
                ?>



                <div class="mobile-social-icons">
                    <a href="<?php echo get_field('instagram', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/instagram-green-icon.svg" alt="Instagram"></a>
                    <a href="<?php echo get_field('facebook', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/facebook-green.svg" alt="Facebook"></a>
                    <a href="<?php echo get_field('x', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/x-green.svg" alt="X"></a>
                    <a href="<?php echo get_field('tiktok', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/tiktok-green.svg" alt="Tiktok"></a>
                    <a href="<?php echo get_field('youtube', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/youtube-green.svg" alt="Youtube"></a>
                </div>
            </div>
        </div>
    </div>

    <main>