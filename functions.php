<?php

function freda_magazine_custom_logo_setup() {
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array('site-title', 'site-description'),
    ));
}
add_action('after_setup_theme', 'freda_magazine_custom_logo_setup');

// Allow SVG file uploads
function freda_magazine_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'freda_magazine_mime_types');

// Enqueue style.css
function freda_magazine_enqueue_styles() {
    wp_enqueue_style('freda-magazine-style', get_stylesheet_uri());
    wp_enqueue_script('freda-magazine-scripts', get_template_directory_uri() . '/includes/main.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'freda_magazine_enqueue_styles');

function freda_enqueue_scripts() {
    wp_enqueue_script('load-more', get_template_directory_uri() . '/includes/load-more.js', ['jquery'], null, true);
    wp_localize_script('load-more', 'ajax_params', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}

add_action('wp_enqueue_scripts', 'freda_enqueue_scripts');

// Register navigation menus
function freda_magazine_register_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'freda-magazine'),
        'footer'  => __('Footer Menu', 'freda-magazine'),
        'policies' => __('Policies Menu', 'freda-magazine'),
    ));
}
add_action('init', 'freda_magazine_register_menus');

// Register Gutenberg blocks
require_once get_template_directory() . '/blocks/init.php';

// Only run if Action Scheduler is available
add_action('after_setup_theme', function () {
    if (function_exists('as_schedule_recurring_action') && !as_next_scheduled_action('swd_update_weather')) {
        as_schedule_recurring_action(time(), HOUR_IN_SECONDS, 'swd_update_weather');
    }
});

// Action hook to fetch weather
add_action('swd_update_weather', 'swd_fetch_and_store_weather');

// Register Weather API Call
require_once get_template_directory() . '/includes/weather.php';

add_action('elementor/elements/categories_registered', function($elements_manager) {
    $elements_manager->add_category(
        'custom',
        [
            'title' => __('Freda Widgets', 'freda'),
            'icon'  => 'fa fa-plug',
        ]
    );
});

add_action('elementor/widgets/register', function($widgets_manager) {
    // Select Posts Widget
    require_once get_template_directory() . '/elementor-widgets/selected-posts/selected-posts.php';
    wp_enqueue_style('selected-posts-style', get_template_directory_uri() . '/elementor-widgets/selected-posts/style.css');
    $widgets_manager->register(new \FredaMagazin\SelectPosts());
    
    // Recent Posts Widget
    require_once get_template_directory() . '/elementor-widgets/recent-posts/recent-posts.php';
    wp_enqueue_style('recent-posts-style', get_template_directory_uri() . '/elementor-widgets/recent-posts/style.css');
    $widgets_manager->register(new \FredaMagazin\RecentPosts());

    // Categories Cards Widget
    require_once get_template_directory() . '/elementor-widgets/categories-cards/categories-cards.php';
    wp_enqueue_style('categories-cards-style', get_template_directory_uri() . '/elementor-widgets/categories-cards/style.css');
    $widgets_manager->register(new \FredaMagazin\CategoriesCards());
});

// Add support for featured images
add_theme_support('post-thumbnails');

add_action('wp_ajax_load_more_posts', 'load_more_posts_callback');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts_callback');

function load_more_posts_callback() {
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $category_id = isset($_POST['category']) ? intval($_POST['category']) : 0;

    $query = new WP_Query([
        'posts_per_page' => 6,
        'paged' => $paged,
        'cat' => $category_id,
    ]);

    ob_start();

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/recent-post-template', 'item');
        endwhile;
    endif;

    $html = ob_get_clean();

    wp_send_json([
        'html' => $html,
        'max_pages' => $query->max_num_pages,
        'current_page' => $paged,
    ]);
}



