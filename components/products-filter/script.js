document.addEventListener('DOMContentLoaded', function () {
    window.addEventListener('resize', () => {
        init();
    });

    const init = () => {
        let isClosed = false;

        function isMobile() {
            return window.innerWidth <= 1050;
        }
        if (!isMobile()) return;

        const handleAvoidScrolling = () => {
            document.body.classList.add('mobile__menu-avoid-scrolling');
        };
        const handleAllowScrolling = () => {
            document.body.classList.remove('mobile__menu-avoid-scrolling');
            filterInnerContainer.scrollTop = 0;
        };

        const openFilterElement = document.querySelector('.inner-filter-container__header');
        const filterContainer = document.querySelector('.product-filter-sidebar');
        const showResultsBtn = document.querySelector('.inner-filter-container__show-results-btn');
        const exitFilterBtn = document.querySelector('.inner-filter-container__exit_icon');
        const filterInnerContainer = document.querySelector('.product-filter-sidebar__sticky');

        if (!openFilterElement) return;

        openFilterElement.addEventListener('click', (e) => {
            if (e.target.nodeName === 'SPAN' && isClosed) return;
            if (!isMobile()) return;
            filterContainer.classList.add('product-filter__active');
            window.scroll({
                top: 0,
                left: 0,
                behavior: 'smooth'
            });
            handleAvoidScrolling();
        });

        showResultsBtn.addEventListener('click', () => {
            filterContainer.classList.remove('product-filter__active');
            handleAllowScrolling();
            isClosed = true;
        });

        exitFilterBtn.addEventListener('click', () => {
            filterContainer.classList.toggle('product-filter__active');
            handleAllowScrolling();
            isClosed = true;
        });
    };

    init();
});
