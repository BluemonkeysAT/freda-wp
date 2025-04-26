<?php
/**
 * Template Name: Green Layout
 */
$GLOBALS['is_green_layout'] = true;
get_header();
?>

    <main id="site-content">
        <?php
        while (have_posts()) :
            the_post();
            the_content();
        endwhile;
        ?>
    </main>

<?php get_footer(); ?>