<?php
namespace FredaMagazin;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class SelectPosts extends Widget_Base {

    public function get_name() {
        return 'select_posts';
    }

    public function get_title() {
        return __('Select Posts', 'freda');
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return ['custom'];
    }

    public function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'freda'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'categories',
            [
                'label' => __('Select Categories', 'freda'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_categories_list(),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'posts',
            [
                'label' => __('Select Posts', 'freda'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_posts_list(),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    private function get_categories_list() {
        $categories = get_categories(['hide_empty' => false]);
        $options = [];
        foreach ($categories as $cat) {
            $options[$cat->term_id] = $cat->name;
        }
        return $options;
    }

    private function get_posts_list() {
        $posts = get_posts(['numberposts' => -1]);
        $options = [];
        foreach ($posts as $post) {
            $options[$post->ID] = $post->post_title;
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $category_ids = $settings['categories'];
        $post_ids = $settings['posts'];

        if (!empty($post_ids)) {
            $query = new \WP_Query([
            'post__in' => $post_ids,
            'orderby' => 'post__in',
            ]);
        } elseif (!empty($category_ids)) {
            $query = new \WP_Query([
            'posts_per_page' => 3,
            'category__in' => $category_ids,
            ]);
        } else {
            echo '<p>Please select at least one category or post.</p>';
            return;
        }

        if ($query->have_posts()) {
            echo '<div class="selected-posts">';
            while ($query->have_posts()) {
            $query->the_post();
            echo '<div class="post-item">';
            echo '<a href="'. get_permalink() .'" class="thumbnail-wrapper">';
            if (has_post_thumbnail()) {
                echo get_the_post_thumbnail(get_the_ID(), 'large');
            }
            $categories = get_the_category();
            if (!empty($categories)) {
                $categoryBackground = get_field('background_color', 'category_' . $categories[0] ->term_id);
                $categoryTextColor = get_field('text_color', 'category_' . $categories[0]->term_id);
                
                echo '<a href="'. esc_url(get_category_link($categories[0]->term_id)) .'" 
                class="post-category" style="background-color:'. $categoryBackground . '; color:' . $categoryTextColor .';">' 
                . esc_html($categories[0]->name) . 
                '</a>';
            }
            echo '</a>';
            echo '<h3 class="post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            echo '<p class="post-excerpt">' . get_the_excerpt() . '</p>';
            echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p>No posts found in selected categories or posts.</p>';
        }

        wp_reset_postdata();
    }
}
