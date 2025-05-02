
<?php get_template_part('partials/template-header-green') ?>

<div class="author-page">
    <div class="container">

        <?php
        $author = get_query_var('author');
        $curauth = get_userdata($author);
        ?>
        <div class="author-hero">
            <figure class="author-img-wrapper">
                <?php
                if (function_exists('get_avatar')) {
                    echo get_avatar($curauth->ID, 200);
                }
                ?>
            </figure>
            <div class="author-info">
                <h2><?php echo $curauth->nickname; ?></h2>
                <p><?php echo $curauth->user_description; ?></p>
                <div class="author-social">
                    <?php
                    $facebook = get_user_meta($curauth->ID, 'facebook', true);
                    $instagram = get_user_meta($curauth->ID, 'instagram', true);
                    $tiktok = get_user_meta($curauth->ID, 'tiktok', true);
                    $x = get_user_meta($curauth->ID, 'x', true);
                    $youtube = get_user_meta($curauth->ID, 'youtube', true);
                    $whatsapp = get_user_meta($curauth->ID, 'whatsapp', true);
                    $bluesky = get_user_meta($curauth->ID, 'blue_sky', true);
                    $email = $curauth->user_email;
                    ?>
                    <?php if ($instagram) { ?>
                        <a href="<?php echo $instagram; ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/instagram-green-icon.svg" alt="Instagram"></a>
                    <?php } ?>
                    <?php if ($facebook) { ?>
                        <a href="<?php echo $facebook; ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/facebook-green.svg" alt="Facebook"></a>
                    <?php } ?>
                    <?php if ($tiktok) { ?>
                        <a href="<?php echo $tiktok; ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/tiktok-green.svg" alt="TikTok"></a>
                    <?php } ?>
                    <?php if ($x) { ?>
                        <a href="<?php echo $x; ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/x-green.svg" alt="X"></a>
                    <?php } ?>
                    <?php if ($youtube) { ?>
                        <a href="<?php echo $youtube; ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/youtube-green.svg" alt="Youtube"></a>
                    <?php } ?>
                    <?php if ($whatsapp) { ?>
                        <a href="<?php echo $whatsapp; ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/whatsapp-icon.svg" alt="Whatsapp"></a>
                    <?php } ?>
                    <?php if ($bluesky) { ?>
                        <a href="<?php echo $bluesky; ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/bluesky-icon.svg" alt="Bluesky"></a>
                    <?php } ?>
                    <?php if ($email) { ?>
                        <a href="mailto:<?php echo $email; ?>" style="background: #30472f;" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/email-share-icon.svg" alt="E-Mail"></a>
                    <?php } ?>


                    <a href="<?php echo get_user_meta($curauth->ID, 'x', true); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/x-green.svg" alt="X"></a>
                    <a href="<?php echo get_user_meta($curauth->ID, 'tiktok', true); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/tiktok-green.svg" alt="Tiktok"></a>
                    <a href="<?php echo get_user_meta($curauth->ID, 'youtube', true); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/youtube-green.svg" alt="Youtube"></a>
                </div>
            </div>
        </div>
        
        <div class="author-posts">
        <?php
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;

            $query = new \WP_Query([
            'author' => $curauth->ID,
            'posts_per_page' => 6,
            'paged' => $paged,
            ]);

            echo '<h2>Artikel</h2>';
            echo '<div class="selected-posts archive-load-more">';

            if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
               
                get_template_part('template-parts/recent-post-template', 'item');
                
            endwhile;
            endif;

            echo '</div>';

            wp_reset_postdata();

        if ($query->max_num_pages > 1): ?>
            <button class="single-cat-load-more btn-primary"
                data-page="1"
                data-author-id="<?php echo $curauth->ID; ?>">
                Mehr Laden
            </button>


            <div id="load-more-spinner" style="display:none;">
                <div class="spinner"></div>
            </div>
            <?php endif; 
            
        ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>