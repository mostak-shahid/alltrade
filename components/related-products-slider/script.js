document.addEventListener('DOMContentLoaded', () => {
    const swiperRealtedProducts = new Swiper('.swiper-related-products', {
        loop: true,
        direction: 'horizontal',
        autoplay: {
            delay: 3000
        },
        navigation: {
            nextEl: '.swiper-button-next-company',
            prevEl: '.swiper-button-prev-company'
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
                spaceBetween: 49
            },
            600: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            900: {
                slidesPerView: 3,
                spaceBetween: 30
            },
            1200: {
                slidesPerView: 4,
                spaceBetween: 30
            }
        }
    });

    // Fix flicker bug

    const sliderItem = document.querySelector('.related-products-slider');

    if (!sliderItem) return;

    setTimeout(() => {
        sliderItem.classList.add('active__slider');
    }, 5);

    const handleCustomNavgationArrows = (btnClassPrefix) => {
        const btnNext = document.querySelector(`.btn-next-btn-${btnClassPrefix}`);
        const btnNextTrigger = document.querySelector(`.swiper-button-next-${btnClassPrefix}`);

        const btnPrev = document.querySelector(`.btn-prev-btn-${btnClassPrefix}`);
        const btnPrevTrigger = document.querySelector(`.swiper-button-prev-${btnClassPrefix}`);
        if (!btnNext) return;

        btnNext.addEventListener('click', () => {
            btnNextTrigger.click();
        });

        btnPrev.addEventListener('click', () => {
            btnPrevTrigger.click();
        });
    };

    handleCustomNavgationArrows('company');
    handleCustomNavgationArrows('company_pc');
});
