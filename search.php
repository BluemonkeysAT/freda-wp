<?php get_template_part('partials/template-header-green') ?>

<div class="search-results-wrapper">
    <div class="container">
    <h1 class="search-title">
        <?php printf( esc_html__( 'Suchergenisse für: %s', 'freda-magazine' ), get_search_query() ); ?>
    </h1>
        <?php 
        
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        $excluded_pages = [
            get_page_by_path('elementor-9')?->ID,
            get_page_by_path('agb')?->ID,
            get_page_by_path('impressum')?->ID,
            get_page_by_path('datenschutz')?->ID,
        ];
        $query = new \WP_Query([
            'posts_per_page' => 6,
            'paged' => $paged,
            's' => get_search_query(),
            'post__not_in' => array_filter($excluded_pages),
        ]);
        ?>
        <?php

        echo '<div class="selected-posts archive-load-more">';
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
               
                get_template_part('template-parts/recent-post-template', 'item');
                
            endwhile;
        echo '</div>';

        if ($query->max_num_pages > 1): ?>
        <button class="single-cat-load-more btn-primary"
            data-page="1"
            data-search="<?php echo esc_attr(get_search_query()); ?>">
            Mehr Laden
        </button>


        <div id="load-more-spinner" style="display:none;">
            <div class="spinner"></div>
        </div>

        <?php endif; ?>
        <?php else : ?>
        <div class="no-results">
            <h2><?php esc_html_e( 'Keine Ergebnisse', 'freda-magazine' ); ?></h2>
            <p><?php esc_html_e( 'Entschuldigung, aber zu deinen Suchbegriffen wurde nichts gefunden. Bitte versuche es mit anderen Stichwörtern.', 'freda-magazine' ); ?></p>
            <?php get_search_form(); ?>
        </div>
    <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>