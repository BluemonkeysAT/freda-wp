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
            'featured_hero_post',
            [
            'label' => __('Featured Post', 'freda'),
            'type' => Controls_Manager::SELECT2,
            'options' => $this->get_posts_list(),
            'label_block' => true,
            'description' => __('Select a single post to feature as the hero post.', 'freda'),
            'multiple' => false,
            'autocomplete' => [
            'object' => 'post',
            'display' => 'post_title',
            ],
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
            'description' => __('Select multiple posts to display.', 'freda'),
            ]
        );

        $this->end_controls_section();
    }

    private function get_featured_post($post_id) {
        if (!$post_id) {
            return null;
        }

        $post = get_post($post_id);
        if (!$post) {
            return null;
        }

        $categories = get_the_category($post->ID);
        $category = null;
        if (!empty($categories)) {
            $background_color = get_field('background_color', 'category_' . $categories[0]->term_id);
            $text_color = get_field('text_color', 'category_' . $categories[0]->term_id);

            $category = [
            'name' => $categories[0]->name,
            'link' => get_category_link($categories[0]->term_id),
            'background_color' => $background_color ? $background_color : '#fff',
            'text_color' => $text_color ? $text_color : '#000',
            ];
        } else {
            $category = [
            'name' => 'Uncategorized',
            'link' => '#',
            'background_color' => '#000',
            'text_color' => '#fff',
            ];
        }

        $featured_post = [
            'id' => $post->ID,
            'title' => $post->post_title,
            'excerpt' => $post->post_excerpt,
            'permalink' => get_permalink($post->ID),
            'thumbnail' => has_post_thumbnail($post->ID) ? get_the_post_thumbnail($post->ID, 'full') : '',
            'author' => get_the_author_meta('display_name', $post->post_author),
            'date' => get_the_date('F j, Y', $post->ID),
            'category' => $category,
        ];

        return $featured_post;
    }

    private function get_posts_list() {
        $posts = get_posts(['numberposts' => -1, 'orderby' => 'date', 'order' => 'DESC']);
        $options = [];
        foreach ($posts as $post) {
            $options[$post->ID] = $post->post_title . ' (' . get_the_date('F j, Y', $post->ID) . ')';
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_ids = $settings['posts'];
        $featured_post = $settings['featured_hero_post'];

        $featured_query = null;
        $posts_query = null;

        if (!empty($featured_post)) {
            $featured_query = new \WP_Query([
            'post__in' => [$featured_post],
            'orderby' => 'post__in',
            ]);
        }

        if (!empty($post_ids)) {
            $posts_query = new \WP_Query([
            'post__in' => $post_ids,
            'orderby' => 'post__in',
            ]);
        }

        if (!$featured_query && !$posts_query) {
            echo '<p>Please select at least one category or post.</p>';
            return;
        }

        if (($featured_query && $featured_query->have_posts()) ||
            ($posts_query && $posts_query->have_posts())) {
            echo '<div class="featured-posts__hero">';
            if (!empty($featured_post)) {
                $featured_post_data = $this->get_featured_post($featured_post);
                
                if ($featured_post_data) {
                    echo '<figure class="featured-post-img-wrapper">';
                    echo $featured_post_data['thumbnail'];
                    echo '</figure>';

                    echo '<div class="post-info">';

                    $category = $featured_post_data['category'];
                    if (!empty($category)) {
                        echo '<a href="' . esc_url($category['link']) . '" 
                            class="post-category" style="background-color:' . esc_attr($category['background_color']) . '; color:' . esc_attr($category['text_color']) . ';">'
                            . esc_html($category['name']) .
                            '</a>';
                    }

                    echo '<a href="' . esc_url($featured_post_data['permalink']) . '" class="post-link">';
                        echo '<h1>' . esc_html($featured_post_data['title']) . '</h1>';
                        echo '<div class="post-meta">';
                            echo '<p>' . esc_html($featured_post_data['excerpt']) . '</p>';
                            echo '<span class="post-arrow">';
                                echo '<img class="desktop-icon" src="' . get_template_directory_uri() . '/assets/icons/arrow-right.svg" alt="Arrow Right">';
                                echo '<img class="mobile-icon" src="' . get_template_directory_uri() . '/assets/icons/arrow-right-black.svg" alt="Arrow Right" width="40" height="15">';
                            echo '</span>';
                        echo '</div>';
                    echo '</a>';

                echo '</div>';
                }

            }


            echo '<div class="selected-posts">';
            if ($posts_query && $posts_query->have_posts()) {
                while ($posts_query->have_posts()) {
                    $posts_query->the_post();

                    echo '<div class="post-item">';
                    echo '<a href="' . get_permalink() . '" class="thumbnail-wrapper">';
                    if (has_post_thumbnail()) {
                        echo get_the_post_thumbnail(get_the_ID(), 'large');
                    }

                    $categories = get_the_category();
                    if (!empty($categories)) {
                        $categoryBackground = get_field('background_color', 'category_' . $categories[0]->term_id);
                        $categoryTextColor = get_field('text_color', 'category_' . $categories[0]->term_id);

                        echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" 
                            class="post-category" style="background-color:' . esc_attr($categoryBackground) . '; color:' . esc_attr($categoryTextColor) . ';">'
                            . esc_html($categories[0]->name) .
                            '</a>';
                    }
                    echo '</a>';
                    echo '<a href="' . get_permalink() . '">';
                    echo '<h3 class="post-title">' . get_the_title() . '</h3>';
                    echo '<p class="post-excerpt">' . get_the_excerpt() . '</p>';
                    echo '</a>';
                    echo '</div>';
                }
            }
            echo '</div>';
            echo '</div>';

        wp_reset_postdata();
    }
}
}
