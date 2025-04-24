const headerMobile = document.querySelector('.header-mobile');
if (headerMobile) {
    const headerMobileButton = headerMobile.querySelector('.menu-button');
    const headerMobileMenu = headerMobile.querySelector('.mobile-menu');
    
    headerMobileButton.addEventListener('click', function () {
        headerMobileMenu.classList.toggle('active');
        headerMobileButton.classList.toggle('active');
        headerMobile.classList.toggle('active');
    });
    

    // document.addEventListener('click', function(event) {
    //     if (!headerMobile.contains(event.target) && headerMobileMenu.classList.contains('active')) {
    //         headerMobileMenu.classList.remove('active');
    //         headerMobileButton.classList.remove('active');
    //     }
    // });
}