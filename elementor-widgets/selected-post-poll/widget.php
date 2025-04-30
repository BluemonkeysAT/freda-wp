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
            'poll_notice',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="padding:10px;background:#c5c5c5;border-left:4px solid #ffba00;font-weight:300; color: #0d0d0d; font-style: italic;">Notice: Only posts with poll data will be shown in the list below.</div>',
                'content_classes' => 'elementor-control-field',
            ]
        );

        $this->add_control(
            'post',
            [
                'label' => __('Select Post', 'freda'),
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
            echo '<p>Bitte einen Post/Artikel für den Poll auswählen</p>';
            return;
        }
        
        global $post;
        $post = get_post($post_id);
        if (!$post) {
            echo '<p>Post nicht gefunden.</p>';
            return;
        }

        setup_postdata($post);

        echo '<div class="selected-post-poll">';
        $post_thumbnail = get_the_post_thumbnail($post->ID, 'full');
        if ($post_thumbnail) {
            echo '<a href="' . get_permalink() . '" class="post-thumbnail">' . $post_thumbnail . '</a>';
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
            echo '<a href="' . get_permalink() . '">';
            echo '<h3 class="post-title">' . get_the_title() . '</h3>';
            echo '<p class="post-excerpt">' . get_the_excerpt() . '</p>';
            echo '</a>';
            echo '<a href="' . get_permalink() . '" class="post-action">';
                echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/icons/arrow-right-black.svg') . '" alt="Arrow Right" />';
            echo '</a>';
            echo '</div>';
            echo '<div class="post-poll">';
            
            $poll_data = get_post_meta($post->ID, 'freda_poll_data', true);

            $blocks = parse_blocks($post->post_content);
            foreach ($blocks as $block) {
                if ($block['blockName'] === 'freda-custom-widgets/freda-post-poll') {
                    echo render_block($block);
                    break;
                }
            }
            
            echo '</div>';
        echo '</div>'; 
        echo '</div>'; 
    wp_reset_postdata();
}
}