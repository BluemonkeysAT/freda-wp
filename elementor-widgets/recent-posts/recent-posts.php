<?php
namespace FredaMagazin;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class RecentPosts extends Widget_Base {

    private function get_categories_options() {
        $categories = get_categories();
        $options = [];
        foreach ($categories as $category) {
            $options[$category->term_id] = $category->name;
        }
        return $options;
    }

    public function get_name() {
        return 'recent_posts';
    }

    public function get_title() {
        return __('Recent Posts', 'freda');
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
            'label' => __('Categories', 'freda'),
            'type' => Controls_Manager::SELECT2,
            'options' => $this->get_categories_options(),
            'multiple' => true,
            'label_block' => true,
            'default' => array_keys($this->get_categories_options()),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $query = new \WP_Query([
            'posts_per_page' => 5,
        ]);

        $settings = $this->get_settings_for_display();
        $selected_categories = $settings['categories'];

        $query_args = [
            'posts_per_page' => 5,
        ];

        if (!empty($selected_categories)) {
            $query_args['category__in'] = $selected_categories;
        }

        $query = new \WP_Query($query_args);

        if ($query->have_posts()) {
            $query->the_post();
            echo '<div class="recent-posts">';
            
            if ($query->current_post === 0) {
                echo '<div class="featured">';
                echo '<a href="' . get_permalink() . '" class="featured-post-thumbnail">';
                    if (has_post_thumbnail()) {
                    echo get_the_post_thumbnail(get_the_ID(), 'full');
                    }
                    $categories = get_the_category();
                    if (!empty($categories)) {
                    $categoryBackground = get_field('background_color', 'category_' . $categories[0]->term_id);
                    $categoryTextColor = get_field('text_color', 'category_' . $categories[0]->term_id);
                    
                    echo '<a href="'. esc_url(get_category_link($categories[0]->term_id)) .'" 
                    class="post-category" style="background-color:'. $categoryBackground . '; color:' . $categoryTextColor .';">' 
                    . esc_html($categories[0]->name) . 
                    '</a>';
                    }
                    echo '</a>';
                    echo '<div class="post-info">';
                    echo '<h3 class="post-title">' . get_the_title() . '</h3>';
                    echo '<p class="post-excerpt">' . get_the_excerpt() . '</p>';
                    echo '<a href="' . get_permalink() . '" class="post-action">';
                        echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/icons/arrow-right-black.svg') . '" alt="Arrow Right" />';
                    echo '</a>';
                    echo '</div>';
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="post-items">';
            $counter = 0;
            while ($query->have_posts() && $counter < 4) {
                $query->the_post();

                if ($query->current_post === 0) {
                continue;
                }
                echo '<div class="post">';
                echo '<a href="'. get_permalink() .'" class="thumbnail-wrapper">';
                if (has_post_thumbnail()) {
                    echo get_the_post_thumbnail(get_the_ID(), 'medium');
                }
                echo '</a>';

                echo '<div class="post-info">';
                $categories = get_the_category();
                if (!empty($categories)) {
                    $categoryBackground = get_field('background_color', 'category_' . $categories[0]->term_id);
                    $categoryTextColor = get_field('text_color', 'category_' . $categories[0]->term_id);
                    
                    echo '<a href="'. esc_url(get_category_link($categories[0]->term_id)) .'" 
                    class="post-category" style="background-color:'. $categoryBackground . '; color:' . $categoryTextColor .';">' 
                    . esc_html($categories[0]->name) . 
                    '</a>';
                }
                echo '<h3 class="post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                echo '<p class="post-excerpt">' . get_the_excerpt() . '</p>';
                echo '</div>';
                echo '</div>';
                $counter++;
            }
            echo '</div>';
            
            echo '</div>';
        } else {
            echo '<p>No posts found.</p>';
        }

        wp_reset_postdata();
    }
}
