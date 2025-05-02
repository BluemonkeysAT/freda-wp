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

    protected function render() {
        ?>
        <div class="related-posts__socials">
            <h3>Folge uns auf unseren Social Media Kanälen:</h3>
            <div class="social-icons">
                <a href="<?php echo esc_url(get_field('instagram', 'options')); ?>" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/instagram-icon.svg" alt="Instagram">
                </a>
                <a href="<?php echo esc_url(get_field('facebook', 'options')); ?>" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/facebook-icon.svg" alt="Facebook">
                </a>
                <a href="<?php echo esc_url(get_field('tiktok', 'options')); ?>" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/tiktok-icon.svg" alt="TikTok">
                </a>
                <a href="<?php echo esc_url(get_field('youtube', 'options')); ?>" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/youtube-icon.svg" alt="YouTube">
                </a>
                <a href="<?php echo esc_url(get_field('bluesky', 'options')); ?>" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/bluesky-icon.svg" alt="Bluesky">
                </a>
                <a href="<?php echo esc_url(get_field('threads', 'options')); ?>" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/threads-icon.svg" alt="Threads">
                </a>
                <a href="<?php echo esc_url(get_field('linkedin', 'options')); ?>" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/linkedin-icon.svg" alt="Linkedin">
                </a>
            </div>
        </div>
        <?php
    }
}