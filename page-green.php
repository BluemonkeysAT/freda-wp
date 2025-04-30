<?php
/**
 * Template Name: Freda Elementor Default
 */
?>
<?= get_template_part('partials/template-header-green'); ?>

    <main id="site-content">
        <?php
        while (have_posts()) :
            the_post();
            the_content();
        endwhile;
        ?>
    </main>

<?php get_footer(); ?>