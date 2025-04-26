<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php $GLOBALS['is_green_layout'] = true; ?>
<?php get_header(); ?>

<div class="search-results-wrapper">
    <div class="container">
    <h1 class="search-title">
        <?php printf( esc_html__( 'Search Results for: %s', 'freda-magazine' ), get_search_query() ); ?>
    </h1>

    <?php if ( have_posts() ) : ?>
        <div class="selected-posts">
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-item" <?php post_class(); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'medium' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <h2 class="post-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                   
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        $categoryBackground = get_field('background_color', 'category_' . $categories[0]->term_id);
                        $categoryTextColor = get_field('text_color', 'category_' . $categories[0]->term_id);
                        echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="post-category" style="font-weight: 600; background-color:' . esc_attr($categoryBackground) . '; color:' . esc_attr($categoryTextColor) . ';">' . esc_html($categories[0]->name) . '</a>';
                    }
                    ?>
                    
                    <div class="post-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <div class="pagination">
            <?php
            the_posts_pagination( array(
                'prev_text' => esc_html__( 'Previous', 'freda-magazine' ),
                'next_text' => esc_html__( 'Next', 'freda-magazine' ),
            ) );
            ?>
        </div>
    <?php else : ?>
        <div class="no-results">
            <h2><?php esc_html_e( 'Nothing Found', 'freda-magazine' ); ?></h2>
            <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'freda-magazine' ); ?></p>
            <?php get_search_form(); ?>
        </div>
    <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>