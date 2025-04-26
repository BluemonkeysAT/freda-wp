const headerMobile = document.querySelector('.header-mobile');
const headerMobileGreen = document.querySelector('.header-mobile.green');

if (headerMobile) {
    const headerMobileButton = headerMobile.querySelector('.menu-button');
    const headerMobileMenu = headerMobile.querySelector('.mobile-menu');
    
    headerMobileButton.addEventListener('click', function () {
        headerMobileMenu.classList.toggle('active');
        headerMobileButton.classList.toggle('active');
        headerMobile.classList.toggle('active');
    });
}
if (headerMobileGreen) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 0) {
            headerMobileGreen.classList.add('green-scrolled');
        } else {
            headerMobileGreen.classList.remove('green-scrolled');
        }
    });
} else {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 0) {
            headerMobile.classList.add('scrolled');
        } else {
            headerMobile.classList.remove('scrolled');
        }
    });
}
