</main>
<footer>
    <div class="container">
        <div class="column">
            <h2>Freda Magazin</h2>
            <p class="address">Loquaiplatz 12 / Top 4 <br>1060 Wien</p>
            <div class="contact">
                <a href="tel:+43 (0)1 890 16 80"><span>T</span> +43 (0)1 890 16 80</a>
                <a href="mailto:redaktion@freda.at"><span>M</span> redaktion@freda.at</a>
            </div>
        </div>
        <div class="column">
            <h2>Themen</h2>
            <nav class="footer-menu">
                <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class'     => 'nav-menu',
                    ));
                ?>
            </nav>
        </div>
        <div class="column">
            <h2>Bleiben wir in Kontakt</h2>
            <p>Melde dich für unseren Newsletter an und erhalte regelmäßig spannende Beiträge und News.</p>
            <a href="https://www.freda.at/newsletter/" class="btn-primary">Zur Newsletter Anmeldung</a>
        </div>
    </div>
    <div class="copyright">
        <div class="socials">
            <a href="<?php echo get_field('instagram', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/instagram-green-icon.svg" alt="Instagram"></a>
            <a href="<?php echo get_field('facebook', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/facebook-green.svg" alt="Facebook"></a>
            <a href="<?php echo get_field('tiktok', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/tiktok-green.svg" alt="Tiktok"></a>
            <a href="<?php echo get_field('youtube', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/youtube-green.svg" alt="Youtube"></a>
            <a href="<?php echo get_field('bluesky', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/bluesky-icon-green.svg" alt="Bluesky"></a>
            <a href="<?php echo get_field('threads', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/threads-icon-green.svg" alt="Threads"></a>
            <a href="<?php echo get_field('linkedin', 'options'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/linkedin-icon-green.svg" alt="Linkedin"></a>
        </div>
        <a href="https://freda.at/" target="_blank" class="btn-lightgreen freda-a">
            Zur FREDA Akademie
        </a>
        <nav class="footer-menu">
                <?php
                    wp_nav_menu(array(
                        'theme_location' => 'policies',
                        'menu_class'     => 'nav-menu',
                    ));
                ?>
        </nav>
    </div>
</footer>

</body>
<?php wp_footer(); ?>