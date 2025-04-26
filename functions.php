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
    wp_enqueue_script('freda-magazine-scripts', get_template_directory_uri() . '/includes/main.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'freda_magazine_enqueue_styles');

function freda_enqueue_scripts() {
    wp_enqueue_script('load-more', get_template_directory_uri() . '/includes/load-more.js', ['jquery'], null, true);
    wp_localize_script('load-more', 'ajax_params', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}

add_action('wp_enqueue_scripts', 'freda_enqueue_scripts');

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

add_action('elementor/elements/categories_registered', function($elements_manager) {
    $elements_manager->add_category(
        'custom',
        [
            'title' => __('Freda Widgets', 'freda'),
            'icon'  => 'fa fa-plug',
        ]
    );
});

add_action('elementor/widgets/register', function($widgets_manager) {
    // Select Posts Widget
    require_once get_template_directory() . '/elementor-widgets/selected-posts/selected-posts.php';
    wp_enqueue_style('selected-posts-style', get_template_directory_uri() . '/elementor-widgets/selected-posts/style.css');
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
});

// Add support for featured images
add_theme_support('post-thumbnails');

add_action('wp_ajax_load_more_posts', 'load_more_posts_callback');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts_callback');

function load_more_posts_callback() {
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $category_id = isset($_POST['category']) ? intval($_POST['category']) : 0;

    $query = new WP_Query([
        'posts_per_page' => 6,
        'paged' => $paged,
        'cat' => $category_id,
    ]);

    ob_start();

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/recent-post-template', 'item');
        endwhile;
    endif;

    $html = ob_get_clean();

    wp_send_json([
        'html' => $html,
        'max_pages' => $query->max_num_pages,
        'current_page' => $paged,
    ]);
}

function register_all_custom_block_widgets() {
    $blocks_dir = get_template_directory() . '/blocks';

    foreach (glob($blocks_dir . '/*', GLOB_ONLYDIR) as $block_path) {
        $block_name = basename($block_path);
        $block_js = "$block_path/block.js";
        $block_css = "$block_path/style.css";

        if (!file_exists($block_js)) {
            continue;
        }

        // Register script
        wp_register_script(
            "{$block_name}-script",
            get_template_directory_uri() . "/blocks/{$block_name}/block.js",
            ['wp-blocks', 'wp-element', 'wp-editor', 'wp-components'],
            filemtime($block_js),
            true
        );

        // Optional: Register style if exists
        if (file_exists($block_css)) {
            wp_register_style(
                "{$block_name}-style",
                get_template_directory_uri() . "/blocks/{$block_name}/style.css",
                [],
                filemtime($block_css)
            );
        }

        // Register block type
        register_block_type("freda-custom-widgets/{$block_name}", [
            'editor_script' => "{$block_name}-script",
            'editor_style'  => file_exists($block_css) ? "{$block_name}-style" : null,
            'style'         => file_exists($block_css) ? "{$block_name}-style" : null,
        ]);

        $frontend_js_path = $block_path . '/frontend.js';
        wp_register_script(
            'freda-poll-widget-frontend',
            get_template_directory_uri() . '/blocks/poll-block/frontend.js',
            [],
            file_exists($frontend_js_path) ? filemtime($frontend_js_path) : null,
            true
        );
        
        wp_localize_script('freda-poll-widget-frontend', 'fredaPollAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
        ]);
        
        wp_enqueue_script('freda-poll-widget-frontend');
    }
}
add_action('init', 'register_all_custom_block_widgets');

// ===========================
// 1. Handle AJAX Voting
// ===========================
function handle_freda_poll_vote() {
    
    if (!isset($_POST['data'], $_POST['post_id'])) {
        wp_send_json_error(['message' => 'Missing data or post_id']);
    }

    $data = json_decode(stripslashes($_POST['data']), true);
    $poll_id = sanitize_text_field($data['id']);
    $optionIndex = intval($data['optionIndex']);
    $post_id = intval($_POST['post_id']);
    $user_id = get_current_user_id();
    $user_ip = $_SERVER['REMOTE_ADDR'];

    // Load poll meta
    $voters = get_post_meta($post_id, 'freda_poll_voters', true);
    $voted_choice = get_post_meta($post_id, 'freda_poll_vote_choice', true);
    $polls = get_post_meta($post_id, 'freda_poll_data', true);
    if (!is_array($polls)) $polls = [];

    // Fallback: try loading pollData from post content if DB meta is empty or broken
    if (empty($polls)) {
        $post = get_post($post_id);
        if ($post) {
            $blocks = parse_blocks($post->post_content);
            foreach ($blocks as $block) {
                if (
                    $block['blockName'] === 'freda-custom-widgets/freda-post-poll' &&
                    isset($block['attrs']['pollData'])
                ) {
                    $polls = $block['attrs']['pollData'];
                    break;
                }
            }
        }
    }

    if (!is_array($voters)) $voters = [];
    if (!is_array($voted_choice)) $voted_choice = [];
    if (!is_array($polls)) $polls = [];

    $poll_found = false;
    $selected_poll_votes = [];

    foreach ($polls as &$poll) {
        
        if ($poll['id'] === $poll_id) {
             
            $poll_found = true;

            if (isset($voters[$poll_id])) {
                $already = $voters[$poll_id];
                if (!empty($user_id) && in_array($user_id, $already['users'] ?? [])) {
                    wp_send_json_error(['message' => 'You have already voted.']);
                }
                if (in_array($user_ip, $already['ips'] ?? [])) {
                    wp_send_json_error(['message' => 'You have already voted.']);
                }
            }

            if (!isset($poll['votes'][$optionIndex])) {
                $poll['votes'][$optionIndex] = 0;
            }

            $poll['votes'][$optionIndex]++;
            $selected_poll_votes = $poll['votes'];
            break;
        }
    }

    if (!$poll_found) {
        wp_send_json_error(['message' => 'Poll not found.']);
    }

    // Save updated data
    update_post_meta($post_id, 'freda_poll_data', $polls);

    if (!isset($voted_choice[$poll_id])) {
        $voted_choice[$poll_id] = [];
    }
    if ($user_id) {
        $voted_choice[$poll_id]['user_' . $user_id] = $optionIndex;
    } else {
        $voted_choice[$poll_id]['ip_' . $user_ip] = $optionIndex;
    }
    update_post_meta($post_id, 'freda_poll_vote_choice', $voted_choice);

    if (!isset($voters[$poll_id])) {
        $voters[$poll_id] = ['users' => [], 'ips' => []];
    }
    if ($user_id) {
        $voters[$poll_id]['users'][] = $user_id;
    } else {
        $voters[$poll_id]['ips'][] = $user_ip;
    }

    update_post_meta($post_id, 'freda_poll_voters', $voters);
    
    wp_send_json_success([
        'votes' => $selected_poll_votes,
        'votedIndex' => $optionIndex
    ]);
}
add_action('wp_ajax_freda_poll_vote', 'handle_freda_poll_vote');
add_action('wp_ajax_nopriv_freda_poll_vote', 'handle_freda_poll_vote');


// ===========================
// 2. Inject Live Poll Data into Block (Frontend Only)
// ===========================
add_filter('render_block', function ($block_content, $block) {
    if (
        $block['blockName'] !== 'freda-custom-widgets/freda-post-poll' ||
        !is_singular() ||
        !isset($block['attrs']['pollData'])
    ) {
        return $block_content;
    }

    $post_id = get_the_ID();
    $poll_data = $block['attrs']['pollData'];

    $saved_votes = get_post_meta($post_id, 'freda_poll_data', true);
    $voters = get_post_meta($post_id, 'freda_poll_voters', true);
    $voted_choice = get_post_meta($post_id, 'freda_poll_vote_choice', true);

    if (!is_array($saved_votes)) $saved_votes = [];
    if (!is_array($voters)) $voters = [];
    if (!is_array($voted_choice)) $voted_choice = [];

    if (empty($saved_votes)) {
        // Only save valid polls with IDs
        $valid = array_filter($poll_data, fn($p) => isset($p['id']) && !empty($p['id']));
        if (!empty($valid)) {
            update_post_meta($post_id, 'freda_poll_data', $poll_data);
            $saved_votes = $poll_data;
        }
    }
    
    $user_id = get_current_user_id();
    $user_ip = $_SERVER['REMOTE_ADDR'];

    foreach ($poll_data as &$poll) {
        $poll_id = $poll['id'];

        foreach ($saved_votes as $saved) {
            if ($saved['id'] === $poll_id) {
                $poll['votes'] = $saved['votes'];
                break;
            }
        }

        $is_voted = false;
        if (isset($voters[$poll_id])) {
            $entry = $voters[$poll_id];
            if (!empty($user_id) && in_array($user_id, $entry['users'] ?? [])) {
                $is_voted = true;
            }
            if (empty($user_id) && in_array($user_ip, $entry['ips'] ?? [])) {
                $is_voted = true;
            }
        }

        $voted_index = null;
        if ($is_voted && isset($voted_choice[$poll_id])) {
            if (!empty($user_id)) {
                $voted_index = $voted_choice[$poll_id]['user_' . $user_id] ?? null;
            } else {
                $voted_index = $voted_choice[$poll_id]['ip_' . $user_ip] ?? null;
            }
        }

        $poll['voted'] = $is_voted;
        $poll['votedIndex'] = $voted_index;
    }

    $poll_json = esc_attr(json_encode($poll_data));
    
    return preg_replace_callback('/data-poll="([^"]*)"/', function () use ($poll_json, $post_id) {
        return 'data-poll="' . $poll_json . '" data-post-id="' . $post_id . '"';
    }, $block_content);
}, 10, 2);

add_action('save_post', function ($post_id) {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (wp_is_post_revision($post_id)) return;

	$post = get_post($post_id);
	if (!$post || $post->post_type !== 'post') return;

	// Check if poll block is still in the content
	if (has_block('freda-custom-widgets/freda-post-poll', $post->post_content)) {
		return; // Poll block is still present
	}

	// Poll block removed → delete stored meta
	delete_post_meta($post_id, 'freda_poll_data');
	delete_post_meta($post_id, 'freda_poll_vote_choice');
	delete_post_meta($post_id, 'freda_poll_voters');
}, 10);



add_filter('block_categories_all', function ($categories) {
    return array_merge(
        [['slug' => 'freda-custom-widgets', 'title' => __('Freda Custom Widgets', 'freda-magazine')]],
        $categories
    );
});

//Move jquery to the footer
function move_jquery_to_footer() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', includes_url('/js/jquery/jquery.min.js'), false, NULL, true);
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'move_jquery_to_footer');

// Dequeue Dashicons for non-logged-in users
function dequeue_dashicons_everywhere() {
    if (!is_user_logged_in()) {
        wp_dequeue_style('dashicons');
    }
}
add_action('wp_enqueue_scripts', 'dequeue_dashicons_everywhere', 100);