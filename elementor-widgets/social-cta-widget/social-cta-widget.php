<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Social_CTA_Widget extends Widget_Base {

    public function get_name() {
        return 'social_cta';
    }

    public function get_title() {
        return __('Social CTA', 'your-text-domain');
    }

    public function get_icon() {
        return 'eicon-share';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'your-text-domain'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Heading Text Control
        $this->add_control(
            'heading_text',
            [
                'label' => __('Heading Text', 'your-text-domain'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Folge uns auf unseren Social Media Kanälen:', 'your-text-domain'),
            ]
        );

        // Social Icon Visibility Controls
        $this->add_control(
            'show_instagram',
            [
                'label' => __('Show Instagram', 'your-text-domain'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'your-text-domain'),
                'label_off' => __('Hide', 'your-text-domain'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_facebook',
            [
                'label' => __('Show Facebook', 'your-text-domain'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'your-text-domain'),
                'label_off' => __('Hide', 'your-text-domain'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_tiktok',
            [
                'label' => __('Show TikTok', 'your-text-domain'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'your-text-domain'),
                'label_off' => __('Hide', 'your-text-domain'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_youtube',
            [
                'label' => __('Show YouTube', 'your-text-domain'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'your-text-domain'),
                'label_off' => __('Hide', 'your-text-domain'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_bluesky',
            [
                'label' => __('Show Bluesky', 'your-text-domain'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'your-text-domain'),
                'label_off' => __('Hide', 'your-text-domain'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_threads',
            [
                'label' => __('Show Threads', 'your-text-domain'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'your-text-domain'),
                'label_off' => __('Hide', 'your-text-domain'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_linkedin',
            [
                'label' => __('Show LinkedIn', 'your-text-domain'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'your-text-domain'),
                'label_off' => __('Hide', 'your-text-domain'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $heading_text = $settings['heading_text'];
        $social_links = [
            'instagram' => get_field('instagram', 'options'),
            'facebook' => get_field('facebook', 'options'),
            'tiktok' => get_field('tiktok', 'options'),
            'youtube' => get_field('youtube', 'options'),
            'bluesky' => get_field('bluesky', 'options'),
            'threads' => get_field('threads', 'options'),
            'linkedin' => get_field('linkedin', 'options'),
        ];

        $social_icons = [
            'instagram' => 'instagram-icon.svg',
            'facebook' => 'facebook-icon.svg',
            'tiktok' => 'tiktok-icon.svg',
            'youtube' => 'youtube-icon.svg',
            'bluesky' => 'bluesky-icon.svg',
            'threads' => 'threads-icon.svg',
            'linkedin' => 'linkedin-icon.svg',
        ];

        $social_visibility = [
            'instagram' => $settings['show_instagram'],
            'facebook' => $settings['show_facebook'],
            'tiktok' => $settings['show_tiktok'],
            'youtube' => $settings['show_youtube'],
            'bluesky' => $settings['show_bluesky'],
            'threads' => $settings['show_threads'],
            'linkedin' => $settings['show_linkedin'],
        ];
        ?>
        <div class="related-posts__socials">
            <h3><?php echo esc_html($heading_text); ?></h3>
            <div class="social-icons">
                <?php foreach ($social_links as $key => $link) : ?>
                    <?php if ($social_visibility[$key] === 'yes' && $link) : ?>
                        <a href="<?php echo esc_url($link); ?>" target="_blank">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/' . $social_icons[$key]); ?>" alt="<?php echo ucfirst($key); ?>">
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}