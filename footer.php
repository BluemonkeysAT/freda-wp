</main>
<footer>
    <div class="container">
        <div class="column">
            <h3>FREDA MAGAZIN</h3>
            <p>Loquaiplatz 12 / Top 4 <br>1060 Wien</p>
            <div class="contact">
                <a href="tel:+43 (0)1 890 16 80"><span>T</span> +43 (0)1 890 16 80</a>
                <a href="mailto:redaktion@freda.at"><span>M</span> redaktion@freda.at</a>
            </div>
        </div>
        <div class="column">
            <h3>THEMEN</h3>
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
            <h3>Bleiben wir in Kontakt</h3>
            <p>Melde dich für unseren Newsletter an und erhalte regelmäßig spannende Beiträge und News.</p>
            <a href="https://www.freda.at/newsletter/" class="btn-primary">Zur Newsletter Anmeldung</a>
        </div>
    </div>
    <div class="copyright">
        <div class="socials">
            <a href="<?php echo get_field('facebook', 'options'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/facebook-green.svg" alt="facebook" /></a>
            <a href="<?php echo get_field('instagram', 'options'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/instagram-green-icon.svg" alt="instagram" /></a>
            <a href="<?php echo get_field('tiktok', 'options'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/tiktok-green.svg" alt="tiktok" /></a>
            <a href="<?php echo get_field('x', 'options'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/x-green.svg" alt="x" /></a>
            <a href="<?php echo get_field('youtube', 'options'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/youtube-green.svg" alt="youtube" /></a>
        </div>
        <a href="" class="btn-lightgreen freda-a">
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