document.addEventListener('DOMContentLoaded', () => {
    const mySwiper = new Swiper('.swiper-companies', {
        direction: 'horizontal',
        loop: true,
        autoplay: {
            delay: 3000
        },
        navigation: {
            nextEl: '.swiper-button-next-company_pc',
            prevEl: '.swiper-button-prev-company_pc'
        },
        breakpoints: {
            400: {
                slidesPerView: 1,
                spaceBetween: 20
            },
            600: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            900: {
                slidesPerView: 4,
                spaceBetween: 30
            },
            1200: {
                slidesPerView: 4,
                spaceBetween: 40
            }
        }
    });

    const newSwiper = new Swiper('.mySwiper', {
        // Optional parameters
        direction: 'horizontal',
        loop: true,
        autoplay: {
            delay: 3000
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        }
    });
});

// PRODUCT SLIDER

document.addEventListener('DOMContentLoaded', () => {
    const productDiv = document.querySelector('.woocommerce-loop-product__link');

    const productSlider = document.querySelector('.products__related-products');
    const btnArrowLeft = document.querySelector('.product-arrow-left');
    const btnArrowRight = document.querySelector('.product-arrow-right');

    if (!btnArrowLeft) return;

    btnArrowLeft.addEventListener('click', () => {
        productSlider.scrollBy({
            behavior: 'smooth',
            left: -productDiv.offsetWidth - 70
        });
    });

    btnArrowRight.addEventListener('click', () => {
        productSlider.scrollBy({
            behavior: 'smooth',
            left: +productDiv.offsetWidth + 70
        });
    });
});
