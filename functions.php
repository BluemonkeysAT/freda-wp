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
}
add_action('wp_enqueue_scripts', 'freda_magazine_enqueue_styles');

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