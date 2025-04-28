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

const inputElement = document.querySelector('header input');
const siteLogo = document.querySelector('.site-logo');

if (inputElement && siteLogo) {
    inputElement.addEventListener('click', () => {
        siteLogo.style.setProperty('--after-width', '142px');
        inputElement.style.setProperty('--input-width', '170px');
    });

    document.addEventListener('click', (event) => {
        if (!inputElement.contains(event.target)) {
            siteLogo.style.removeProperty('--after-width');
            inputElement.style.removeProperty('--input-width');
        }
    });
}

const postShareButtonsWrapper = document.querySelector('.post-share-sticky-btns');

if (postShareButtonsWrapper) {
    postShareButtonsWrapper.addEventListener('mouseenter', () => {
        postShareButtonsWrapper.classList.toggle('sticky-btns-hover');
    });
    
    postShareButtonsWrapper.addEventListener('mouseleave', () => {
        postShareButtonsWrapper.classList.remove('sticky-btns-hover');
    });
}

