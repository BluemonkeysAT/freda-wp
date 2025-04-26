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

<main>

    <div class="post-featured-image">
        <?php if (has_post_thumbnail()) : ?>
            <img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php the_title_attribute(); ?>" class="featured-image">
        <?php endif; ?>
        <?php
                $categories = get_the_category();
                if (!empty($categories)) {
                    $categoryBackground = get_field('background_color', 'category_' . $categories[0]->term_id);
                    $categoryTextColor = get_field('text_color', 'category_' . $categories[0]->term_id);
                    echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="post-category" style="background-color:' . esc_attr($categoryBackground) . '; color:' . esc_attr($categoryTextColor) . ';">' . esc_html($categories[0]->name) . '</a>';
                }
        ?>
    </div>
    <div class="post-share-sticky-btns">
        <div class="share-buttons">
            <a href="mailto:?subject=<?php echo rawurlencode(get_the_title()); ?>&body=<?php echo rawurlencode(get_permalink()); ?>" target="_blank" class="share-button email">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/email-share-icon.svg" alt="Email">
                <span>Email</span>
            </a>
            <a href="https://www.whatsapp.com/share?text=<?php echo urlencode(get_the_title()); ?> <?php echo urlencode(get_permalink()); ?>" target="_blank" class="share-button whatsapp">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/whatsapp-icon.svg" alt="WhatsApp">
                <span>WhatsApp</span>
            </a>
            <a href="https://bsky.app/share?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="share-button bluesky">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/bluesky-icon.svg" alt="Bluesky">
                <span>Bluesky</span>
            </a>
        </div>
        <h3>ARTIKEL TEILEN</h3>
    </div>
    
    <?php
        $post = get_post();
        setup_postdata($post); 
        
        $authorName = get_the_author();
        $authorID = get_the_author_meta('ID');
        $authorAvatar = get_avatar($authorID, 96);
        $authorDescription = get_the_author_meta('description', $authorID);
        $authorLink = get_author_posts_url($authorID);
        $authorEmail = get_the_author_meta('user_email', $authorID);
    ?>

    <div class="post-content">
        <div class="container">
            <div class="post-content__inner">
            <div class="post-categories">
                <?php
                $categories = get_the_category();
                if (!empty($categories)) {
                    $categoryBackground = get_field('background_color', 'category_' . $categories[0]->term_id);
                    $categoryTextColor = get_field('text_color', 'category_' . $categories[0]->term_id);
                    echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="post-category" style="font-weight: 600; background-color:' . esc_attr($categoryBackground) . '; color:' . esc_attr($categoryTextColor) . ';">' . esc_html($categories[0]->name) . '</a>';
                }
                ?>
            </div>
            <div class="post-info">
                <h1 class="post-title"><?php the_title(); ?></h1>
                <div class="post-meta">
                    <p class="post-author">
                        <?php 
                        echo '<img src="' . get_template_directory_uri() . '/assets/icons/user-icon.svg" alt="author icon" /> ';
                        echo '<a href="' . esc_url($authorLink) . '" class="button"> ' . esc_html($authorName) . '</a>';
                   
                        ?>
                    </p>
                    <p class="post-date">
                        <?php 
                        echo '<img src="' . get_template_directory_uri() . '/assets/icons/calendar-icon-black.svg" alt="calendar icon" /> ' . get_the_date(); 
                        ?>
                    </p>
                    <p class="post-reading-time">
                        <?php
                        $word_count = str_word_count(strip_tags(get_the_content()));
                        $reading_time = ceil($word_count / 200); // Assuming 200 words per minute reading speed
                        echo '<img src="' . get_template_directory_uri() . '/assets/icons/book-icon.svg" alt="author icon" /> ' . $reading_time . ' min';
                        ?>
                    </p>
                </div>
                <div class="post-share-buttons">
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="share-button facebook">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/facebook-icon.svg" alt="Facebook">
                            <span>Facebook</span>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share-button twitter">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/x-icon.svg" alt="Twitter">
                            <span>Twitter</span>
                        </a>
                        <a href="https://www.whatsapp.com/share?text=<?php echo urlencode(get_the_title()); ?> <?php echo urlencode(get_permalink()); ?>" target="_blank" class="share-button linkedin">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/whatsapp-icon.svg" alt="WhatsApp">
                            <span>WhatsApp</span>
                        </a>
                    </div>
                </div>
                
            </div>
            <div class="post-text">
                <?php
                 the_content();
                ?>
            </div>
            </div>
            <div class="post-sticky-wrapper">
                <div class="post-author__inner">
                    <div class="post-author__info">
                        <h3>Über den Autor</h3>
                        <div class="post-author__image">
                            <?php echo get_avatar($authorAvatar); ?>
                        </div>
                        <p class="post-author__name"><?php echo esc_html($authorName); ?></p>
                        <p class="post-author__bio">
                            <?php echo esc_html($authorDescription); ?>
                         </p>
                         <p class="post-author__link">
                            <a href="mailto:<?php echo esc_attr($authorEmail); ?>">
                                <span>Mehr Erfahren</span> 
                                <img src="<?php echo get_template_directory_uri();?>/assets/icons/arrow-right-green.svg" alt="arrow right" />
                            </a>
                        </p>
                    </div>
                    
                </div>
                <div class="post-related-categories">
                    
                    <ul>
                        <?php
                        $related_categories = get_terms(array(
                            'taxonomy' => 'category',
                            'number'   => 4,
                            'orderby'  => 'count',
                            'order'    => 'DESC',
                            'hide_empty' => false,
                        ));

                        if (!empty($related_categories) && !is_wp_error($related_categories)) {
                            foreach ($related_categories as $category) {
                                $featured_image = get_field('featured_image', 'category_' . $category->term_id);
                                $icon = get_field('icon', 'category_' . $category->term_id);
                                $backgroundColor = get_field('background_color', 'category_' . $category->term_id);
                                
                                
                                echo '<li>';
                                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">';
                                if ($featured_image) {
                                    echo '<img src="' . esc_url($featured_image) . '" alt="' . esc_attr($category->name) . '" class="category-featured-image" />';
                                }
                                if ($icon) {
                                    echo '<figure class="category-icon" style="background-color:'. $backgroundColor . '">';
                                    echo '<img src="' . esc_url($icon) . '" alt="' . esc_attr($category->name) . '" class="category-icon" />';
                                    echo '</figure>';
                                }
                                echo '<span class="title">'. esc_html($category->name) . '</span>';
                                echo '</a>';
                                echo '</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="related-posts">
        <div class="container">
            <h2>Das könnte Sie auch interessieren</h2>
            <div class="related-posts__inner">
                <?php
                $related_posts = get_field('related_posts');

                if ($related_posts) {
                    foreach ($related_posts as $related_post) {
                        
                        echo '<div class="post-item">';
                        echo '<a href="' . get_permalink($related_post->ID) . '" class="thumbnail-wrapper">';
                        if (has_post_thumbnail($related_post->ID)) {
                            echo get_the_post_thumbnail($related_post->ID, 'large');
                        }
                        $categories = get_the_category($related_post->ID);
                        if (!empty($categories)) {
                            $categoryBackground = get_field('background_color', 'category_' . $categories[0]->term_id);
                            $categoryTextColor = get_field('text_color', 'category_' . $categories[0]->term_id);
                            
                            echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" 
                            class="post-category" style="background-color:' . esc_attr($categoryBackground) . '; color:' . esc_attr($categoryTextColor) . ';">' 
                            . esc_html($categories[0]->name) . 
                            '</a>';
                        }
                        echo '</a>';
                        echo '<h3 class="post-title"><a href="' . get_permalink($related_post->ID) . '">' . get_the_title($related_post->ID) . '</a></h3>';
                        echo '<p class="post-excerpt">' . get_the_excerpt($related_post->ID) . '</p>';
                        echo '</div>';
                    
                    }
                } else {
                    // Fallback to related posts based on categories if no ACF field is set
                    $related_posts = get_posts(array(
                        'category__in' => wp_get_post_categories($post->ID),
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => 3,
                    ));

                    if ($related_posts) {
                        foreach ($related_posts as $related_post) {
                            setup_postdata($related_post);
                            
                            echo '<div class="post-item">';
                            echo '<a href="' . get_permalink($related_post->ID) . '" class="thumbnail-wrapper">';
                            if (has_post_thumbnail($related_post->ID)) {
                                echo get_the_post_thumbnail($related_post->ID, 'large');
                            }
                            $categories = get_the_category($related_post->ID);
                            if (!empty($categories)) {
                                $categoryBackground = get_field('background_color', 'category_' . $categories[0]->term_id);
                                $categoryTextColor = get_field('text_color', 'category_' . $categories[0]->term_id);
                                
                                echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" 
                                class="post-category" style="background-color:' . esc_attr($categoryBackground) . '; color:' . esc_attr($categoryTextColor) . ';">' 
                                . esc_html($categories[0]->name) . 
                                '</a>';
                            }
                            echo '</a>';
                            echo '<h3 class="post-title"><a href="' . get_permalink($related_post->ID) . '">' . get_the_title($related_post->ID) . '</a></h3>';
                            echo '<p class="post-excerpt">' . get_the_excerpt($related_post->ID) . '</p>';
                            echo '</div>';
                        }
                        wp_reset_postdata();
                    }
                
                }
                ?>
            </div>

            <div class="related-posts__socials">
                <h3>Folge uns auf unseren Social Media Kanälen:</h3>
                <div class="social-icons">
                    <a href="<?php echo get_field('facebook', 'options'); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/facebook-icon.svg" alt="Facebook">
                    </a>
                    <a href="<?php echo get_field('instagram', 'options'); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/instagram-icon.svg" alt="Instagram">
                    </a>
                    <a href="<?php echo get_field('x', 'options'); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/x-icon.svg" alt="X">
                    </a>
                    <a href="<?php echo get_field('tiktok', 'options'); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/tiktok-icon.svg" alt="TikTok">
                    </a>
                    <a href="<?php echo get_field('youtube', 'options'); ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/youtube-icon.svg" alt="YouTube">
                    </a>
                </div>
            </div>
        </div>
<?php get_footer(); ?>