<?php
namespace FredaMagazin;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class SelectPostPoll extends Widget_Base {

    public function get_name() {
        return 'select_post_poll';
    }

    public function get_title() {
        return __('Post with Poll', 'freda');
    }

    public function get_icon() {
        return 'eicon-post-poll';
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
            'post',
            [
                'label' => __('Select Posts', 'freda'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $this->get_posts_list(),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    private function get_posts_list() {
        $posts = get_posts([
            'numberposts' => -1,
            'meta_key'    => 'freda_poll_data',
            'meta_compare'=> 'EXISTS'
        ]);
    
        $options = [];
        foreach ($posts as $post) {
            $options[$post->ID] = $post->post_title;
        }
        return $options;
    }
    

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id = intval($settings['post']);
        
        if (!$post_id) {
            echo '<p>Please select a post with a poll.</p>';
            return;
        }
        
        global $post;
        $post = get_post($post_id);
        if (!$post) {
            echo '<p>Post not found.</p>';
            return;
        }

        setup_postdata($post);

        echo '<div class="selected-post-poll">';
        $post_thumbnail = get_the_post_thumbnail($post->ID, 'full');
        if ($post_thumbnail) {
            echo '<div class="post-thumbnail">' . $post_thumbnail . '</div>';
        }
            echo '<div class="container">';

            
            
            echo '<div class="post-info">';
            
            $categories = get_the_category();
            if (!empty($categories)) {
                $categoryBackground = get_field('background_color', 'category_' . $categories[0] ->term_id);
                $categoryTextColor = get_field('text_color', 'category_' . $categories[0]->term_id);
                
                echo '<a href="'. esc_url(get_category_link($categories[0]->term_id)) .'" 
                class="post-category" style="background-color:'. $categoryBackground . '; color:' . $categoryTextColor .';">' 
                . esc_html($categories[0]->name) . 
                '</a>';
            }
            echo '<h3 class="post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            echo '<p class="post-excerpt">' . get_the_excerpt() . '</p>';
            echo '</div>';
            echo '<div class="post-poll">';
            
            $poll_data = get_post_meta($post->ID, 'freda_poll_data', true);

            $has_poll = has_block('freda-custom-widgets/freda-post-poll', $post->post_content);
            if ($has_poll) {
                $content = apply_filters('the_content', $post->post_content);
                echo do_blocks($content);
            } else {
                echo '<p>No poll block found in this post.</p>';
            } 
            
            echo '</div>';
        echo '</div>'; 
        echo '</div>'; 
    wp_reset_postdata();
}
}