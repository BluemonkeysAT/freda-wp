<?php
function freda_register_all_blocks_inline() {
    $blocks_dir = get_template_directory() . '/blocks/';
    $blocks_uri = get_template_directory_uri() . '/blocks/';

    // Loop through all subfolders in /blocks/
    foreach (glob($blocks_dir . '*', GLOB_ONLYDIR) as $block_path) {
        $block_name = basename($block_path);
        $js_file = "$block_path/block.js";
        $css_file = "$block_path/style.css";

        if (file_exists($js_file)) {
            wp_register_script(
                "freda-block-$block_name",
                "$blocks_uri$block_name/block.js",
                array('wp-blocks', 'wp-element', 'wp-editor'),
                filemtime($js_file),
                true
            );
        }

        if (file_exists($css_file)) {
            wp_register_style(
                "freda-block-$block_name-style",
                "$blocks_uri$block_name/style.css",
                array(),
                filemtime($css_file)
            );
        }

        // Default args
        $args = array(
            'editor_script' => "freda-block-$block_name",
            'style'         => file_exists($css_file) ? "freda-block-$block_name-style" : null,
        );

        // Special render logic for dynamic tag-list block
        if ($block_name === 'tag-list-block') {
            $args['render_callback'] = function () {
                $post_id = get_the_ID();
                $tags = get_the_tags($post_id);

                if (!is_array($tags) || empty($tags)) {
                    return '';
                }

                $output = '<div class="freda-tag-list">';
                foreach ($tags as $tag) {
                    $output .= esc_html($tag->name) . ', ';
                }
                $output = rtrim($output, ', ');
                $output .= '</div>';

                return $output;
            };
        }

        register_block_type("freda/$block_name", $args);
    }
}
add_action('init', 'freda_register_all_blocks_inline');