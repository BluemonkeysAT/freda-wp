<?php

use Elementor\Plugin;
use Elementor\Widgets_Manager;

if (!defined('ABSPATH')) exit;

// Register the widgets
function register_custom_elementor_widgets($widgets_manager) {
    // Selected Posts Widget
    require_once get_template_directory() . '/elementor-widgets/selected-posts/selected-posts.php';
    wp_enqueue_style('recent-posts-style', get_template_directory_uri() . '/elementor-widgets/selected-posts/style.css');
    require_once get_template_directory() . '/elementor-widgets/selected-posts/style.css';
    $widgets_manager->register(new \FredaMagazin\SelectPosts());

    // Recent Posts Widget
    require_once get_template_directory() . '/elementor-widgets/recent-posts/recent-posts.php';
    wp_enqueue_style('recent-posts-style', get_template_directory_uri() . '/elementor-widgets/recent-posts/style.css');
    $widgets_manager->register(new \FredaMagazin\RecentPosts());

    // Categories Cards Widget
    require_once get_template_directory() . '/elementor-widgets/categories-cards/categories-cards.php';
    wp_enqueue_style('categories-cards-style', get_template_directory_uri() . '/elementor-widgets/categories-cards/style.css');
    $widgets_manager->register(new \FredaMagazin\CategoriesCards());

    // Selected Post with Poll Widget
    require_once get_template_directory() . '/elementor-widgets/selected-post-poll/widget.php';
    wp_enqueue_style('selected-post-poll-style', get_template_directory_uri() . '/elementor-widgets/selected-post-poll/style.css');
    $widgets_manager->register(new \FredaMagazin\SelectPostPoll());
}
add_action('elementor/widgets/register', 'register_custom_elementor_widgets');
