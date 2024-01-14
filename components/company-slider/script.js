document.addEventListener('DOMContentLoaded', () => {
    const swiperCategoryCompanies = new Swiper('.swiper-category-companies', {
        loop: true,
        direction: 'horizontal',
        autoplay: {
            delay: 3000
        },
        navigation: {
            prevEl: '.swiper-button-next-company',
            nextEl: '.swiper-button-prev-company'
        },
        breakpoints: {
            0: {
                slidesPerView: 4,
                spaceBetween: 10
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
                slidesPerView: 6
            }
        }
    });

    // Fix flicker bug

    const sliderItem = document.querySelector('.swiper-category-companies');

    if (!sliderItem) return;

    setTimeout(() => {
        sliderItem.classList.add('active__slider');
    }, 5);

    const handleCustomNavgationArrows = (btnClassPrefix) => {
        const btnNext = document.querySelector(`.btn-next-btn-${btnClassPrefix}`);
        const btnNextTrigger = document.querySelector(`.swiper-button-next-${btnClassPrefix}`);

        const btnPrev = document.querySelector(`.btn-prev-btn-${btnClassPrefix}`);
        const btnPrevTrigger = document.querySelector(`.swiper-button-prev-${btnClassPrefix}`);

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
