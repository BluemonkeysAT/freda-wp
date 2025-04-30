<?php

namespace FredaMagazin;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

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

        $repeater = new Repeater();

        $repeater->add_control(
            'category_id',
            [
                'label' => __('Category', 'freda'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_categories_list(),
            ]
        );

        $this->add_control(
            'categories_ordered',
            [
                'label' => __('Categories (Ordered)', 'freda'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => 'Box {{ WP_RepeatIndex + 1 }}',
                'default' => [
                    [ 'category_id' => '4' ],
                    [ 'category_id' => '1771' ],
                    [ 'category_id' => '1772' ],
                    [ 'category_id' => '8' ],
                ],
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
        $ordered_items = $settings['categories_ordered'];

        if (!empty($ordered_items)) {
            echo '<div class="categories-cards">';

            foreach ($ordered_items as $item) {
                $term_id = intval($item['category_id']);
                $category = get_category($term_id);

                if (!$category) continue;

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
            echo '<p>Please add at least one category.</p>';
        }

        wp_reset_postdata();
    }
}