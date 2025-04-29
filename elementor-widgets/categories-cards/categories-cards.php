<?php

namespace FredaMagazin;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class CategoriesCards extends Widget_Base {

    public function get_name() {
        return 'categories_cards';
    }

    public function get_title() {
        return __('Categories Cards', 'freda');
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

    protected function render() {
        $settings = $this->get_settings_for_display();
        $category_ids = $settings['categories'];

        if (!empty($category_ids)) {
            $categories = get_categories([
            'include' => $category_ids,
            'hide_empty' => false,
            ]);

            // Sort categories by the order of selected IDs
            usort($categories, function($a, $b) use ($category_ids) {
            $pos_a = array_search($a->term_id, $category_ids);
            $pos_b = array_search($b->term_id, $category_ids);
            return $pos_a - $pos_b;
            });

            echo '<div class="categories-cards">';
            
            foreach ($categories as $category) {
            echo '<a href="'. esc_url(get_category_link($category->term_id)) .'" class="category-item">';

            $background_color = get_field('background_color', 'category_' . $category->term_id);
            $category_icon = get_field('icon', 'category_' . $category->term_id);
            $featured_image = get_field('featured_image', 'category_' . $category->term_id);
            $icon_border = get_field('icon_border', 'category_' . $category->term_id);
            

            echo '<figure class="category-icon" style="border: 1px solid '. esc_attr($icon_border) .'; background-color:' . esc_attr($background_color) . '">';
                if ($category_icon) {
                    echo '<img class="category-img" src="' . esc_url($category_icon) . '" alt="' . esc_attr($category->name) . '" />';
                }
            echo '</figure>';

            echo '<div class="thumbnail-wrapper">';
            if ($featured_image) {
                echo '<img src="' . esc_url($featured_image) . '" alt="' . esc_attr($category->name) . '">';
            }
            echo '</div>';

            echo '<div class="category-info">';
                echo '<h3 class="category-title">' . esc_html($category->name) . '</h3>';
                echo '<p class="category-description">' . esc_html($category->description) . '</p>';
            echo '</div>';

            echo '</a>';
            }
            echo '</div>';
        } else {
            echo '<p>Please select at least one category.</p>';
        }

        wp_reset_postdata();
    }
}