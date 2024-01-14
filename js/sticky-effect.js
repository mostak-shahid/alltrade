const handleScrollingPagination = () => {
    let isSticky;
    let productDiv;
    let mininumStickHeight = 1400;

    productDiv = document.querySelector('.columns-4');

    const triggerSticky = document.querySelector('.company-category-slider');
    const stickyFilter = document.querySelector('.product-filter-sidebar__sticky');

    const footer = document.querySelector('.footer-line');

    const options = {
        root: null,
        rootMargin: '+50px',
        threshold: 0.1
    };

    if (!stickyFilter) return;

    console.log(productDiv.offsetHeight);

    const handleProductsObserver = (entries, observer) => {
        entries.forEach((entry) => {
            if (productDiv.offsetHeight < mininumStickHeight) return;

            if (entry.isIntersecting) {
                stickyFilter.classList.remove('fixed__active');
                isSticky = false;
            } else {
                stickyFilter.classList.add('fixed__active');
                isSticky = true;
            }
        });
    };

    if (triggerSticky !== null) {
        if (productDiv.offsetHeight < mininumStickHeight) return;
        const observer = new IntersectionObserver(handleProductsObserver, options);
        observer.observe(triggerSticky);
    }

    const options2 = {
        root: null,
        rootMargin: '150px',
        threshold: 0.1
    };

    const handleFooterObserver = (entries, observer) => {
        entries.forEach((entry) => {
            if (productDiv.offsetHeight < mininumStickHeight) return;
            if (entry.isIntersecting && isSticky) {
                stickyFilter.classList.remove('fixed__active');
                stickyFilter.classList.add('stick__bottom');
            } else if (isSticky) {
                stickyFilter.classList.add('fixed__active');
                stickyFilter.classList.remove('stick__bottom');
                isSticky = true;
            }
        });
    };

    if (footer !== null && productDiv !== null) {
        if (productDiv.offsetHeight < mininumStickHeight) return;
        const observer2 = new IntersectionObserver(handleFooterObserver, options2);
        observer2.observe(footer);
    }
};

function getCurrentCategoryID() {
    if ($('body').hasClass('tax-product_cat')) {
        const bodyClass = $('body').attr('class');
        const match = bodyClass.match(/term-(\d+)/);
        if (match && match.length > 1) {
            const categoryID = parseInt(match[1], 10);
            return categoryID;
        }
    }
    return null;
}

window.addEventListener('finishLoadingEvent', function () {
    handleScrollingPagination();
});
