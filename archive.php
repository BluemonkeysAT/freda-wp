<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php get_header(); ?>

<main>

    <?php get_template_part('template-parts/archive-hero'); ?>
    
    <?php get_template_part('template-parts/category-recent-posts'); ?>

    <div class="archive-content">
        <?php echo do_shortcode('[elementor-template id="167"]'); ?>
    </div>
    

<?php get_footer(); ?>
