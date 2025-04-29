document.addEventListener('DOMContentLoaded', function () {
    const loader = document.getElementById('loader');

    barba.init({
        transitions: [{
            name: 'gsap-transition',
            async leave(data) {
                // Zeige weißen Loader
                await gsap.to(loader, { opacity: 1, duration: 0.4 });

                // Alte Seite ausfaden
                await gsap.to(data.current.container, { opacity: 0, duration: 0.5 });
            },
            async enter(data) {
                // Neue Seite einblenden
                await gsap.from(data.next.container, { opacity: 0, duration: 0.5 });

                // Loader wieder ausblenden
                await gsap.to(loader, { opacity: 0, duration: 0.4 });
            }
        }]
    });
});