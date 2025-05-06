<?php
/**
 * Template Name: Freda Elementor Default
 */
?>
<?= get_template_part('partials/template-header-green'); ?>
    <div class="author-page">

        <div class="container">
            <section style="margin: 80px 0; display: flex; flex-direction: column; align-items: center" class="not-found">
                <h1>Seite nicht gefunden</h1>
                <p>Die angeforderte Seite konnte leider nicht gefunden werden.</p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary">Zurück zur Startseite</a>
            </section>
        </div>
    </div>

<?php get_footer(); ?>