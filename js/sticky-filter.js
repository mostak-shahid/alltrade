jQuery(function ($) {
    $('.woocommerce-product-gallery__image a').on('click', function (e) {
        e.preventDefault();
    });
    const $elements = $('.products.columns-4');

    if (!$elements.length) return;

    // State
    let timeOut;
    let isNoMoreProducts;
    let currentPage = 1;
    let = 1;
    let disableNewQuery = false;

    deactiveObserver = false;

    // Elements

    const inputFilter = $('.input-filter');
    const productList1 = $('.products.columns-4.col-1');
    const ProductList2 = $('.products.columns-4.col-2');
    const expandBtn = $('.expand-btn');
    const categoryBtn = $('.category-btn');
    const loadMoreBtn = $('.load-more-btn');
    const cleanerBtn = $('.inner-filter-container__filter_icon');
    const allCheckValues = $('.input-filter');
    const stickyFilterContainer = $('.product-filter-sidebar__sticky');
    const filteredElementInsert = $('.product-filter__filter-values');
    const productGrid = $('.products');

    // CREATE NEW EVENT
    let finishLoadingEvent = new Event('finishLoadingEvent', { bubbles: true, cancelable: true });

    // UTILS

    // Create custom event when finish loading
    const finishLoadingContentEvent = new CustomEvent('finish-loading', {
        bubbles: true,
        cancelable: true
    });

    // Check if page is archive
    function checkIfPageIsArchive() {
        if ($('body').hasClass('archive')) {
            return true;
        } else {
            return false;
        }
    }

    // Check if page is home
    function checkIfPageIsHome() {
        if ($('body').hasClass('home')) {
            return true;
        } else {
            return false;
        }
    }

    // Get current category ID
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

    // Create skeleton
    function createSkeleton() {
        const element = `
		<li class="ya__skeleton ya__skeleton-wrapper">
		<span class="skeleton-div "></span>
		<div class="ya__skeleton-content">
			<span class="skeleton-mini-long "></span>
			<span class="skeleton-mini "></span>
			<span class="skeleton-mini-medium "></span>
		</div>
		</li>
		`;
        let n;

        const screenWidth = window.innerWidth;

        if (screenWidth >= 1300 && !checkIfPageIsArchive()) {
            n = 9;
        } else if (screenWidth < 1300) {
            n = 7;
        } else if (screenWidth < 1000) {
            n = 2;
        } else if (screenWidth < 700) {
            n = 2;
        } else {
            n = 5;
        }

        const elements = new Array(n);

        for (let i = 0; i < n; i++) {
            elements[i] = element;
        }

        const skeleton = elements.join('').toString();

        return `${skeleton}`;
    }

    // END OF UTILS

    // MAIN FILTER - Speaks with PHP file
    const handleFilter = (filterObject, isInitial = false, isNewQuery = false, category, specialFilter = null) => {
        let newFilterObject = filterObject;
        const isPageExist = filterObject.findIndex((param) => Object.keys(param)[0] === 'page');
        let pageNumber = filterObject[isPageExist] ? filterObject[isPageExist]['page'] : 1;

        if (isNoMoreProducts && !isInitial && !isNewQuery) return;
        if (isInitial || isNewQuery) {
            isNoMoreProducts = false;
            currentPage = 1;
        }
        if (isPageExist !== -1) newFilterObject = filterObject.filter((_, index) => index !== isPageExist);

        if (isInitial || isNewQuery) {
            pageNumber = 1;
            if (!checkIfPageIsHome) {
                handleParams('page', 1);
            }
        }

        $.ajax({
            url: sticky_globals.ajax_url,
            beforeSend: () => {
                if (isPageExist === -1 || isInitial || isNewQuery) {
                    productList1.html(`
					${createSkeleton()}
					`);
                    ProductList2.html('');
                } else {
                    if (checkIfPageIsHome() && !isNewQuery) {
                        ProductList2.append(`	
						${createSkeleton()}
						`);
                    } else {
                        productList1.append(`	
						${createSkeleton()}
						`);
                    }
                    deactiveObserver = true;
                    setTimeout(() => {
                        deactiveObserver = false;
                    }, 1000);
                }
            },
            data: {
                action: 'filter__data',
                filter__data: newFilterObject,
                category__page: category || getCurrentCategoryID(),
                special__filter: specialFilter,
                page__number: pageNumber,
                is_home: checkIfPageIsHome(),
                is_initial: isInitial
            },
            error: function (param) {
                stickyFilterContainer.removeClass('fixed__active');
                stickyFilterContainer.removeClass('stick__bottom');
            },

            success: function (result) {
                if (!checkIfPageIsHome() && !isInitial && isNewQuery) {
                    disableNewQuery = true;
                    setTimeout(() => {
                        disableNewQuery = false;
                    }, 1000);

                    productGrid[0].scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'nearest' });
                }
                const data = JSON.parse(result);

                if (data.remaining_products <= 0) {
                    isNoMoreProducts = true;
                    loadMoreBtn.addClass('hide');
                } else {
                    loadMoreBtn.removeClass('hide');
                }

                if (checkIfPageIsHome()) {
                    if (isInitial || isNewQuery) {
                        productList1.html(data.output_first);
                        ProductList2.html(data.output_last);
                    } else {
                        if (data.output_first.includes('לא נמצאו מוצרים')) {
                            isNoMoreProducts = true;
                            $('.ya__skeleton').remove();
                            return;
                        }
                        ProductList2.append(data.output_first);
                        $('.ya__skeleton').remove();
                    }
                } else {
                    if (data.post_count === 0) {
                        if (disableNewQuery) return;
                        isNoMoreProducts = true;
                        stickyFilterContainer.removeClass('fixed__active');
                        stickyFilterContainer.removeClass('stick__bottom');
                        $('.ya__skeleton').remove();
                        productList1.append(data.output_first);
                        return;
                    }
                    if (isPageExist === -1 || isNewQuery || isInitial) {
                        productList1.html(data.output_first);
                        productList1.append(data.output_last);
                    } else {
                        productList1.append(data.output_first);
                        $('.ya__skeleton').remove();
                    }
                }
                document.dispatchEvent(finishLoadingContentEvent);
            },
            complete: function () {
                window.dispatchEvent(finishLoadingEvent);
                stickyFilterContainer.removeClass('fixed__active');
            },
            type: 'POST'
        });
    };

    // END OF MAIN FILTER

    // GET PARAMS FROM URL
    const handleGetParams = () => {
        const searchParams = new URLSearchParams(window.location.search);
        const paramsList = Array.from(searchParams.entries());
        const paramsObjectList = paramsList.reduce(function (result, item) {
            const [key, value] = item;
            result.push({ [key]: value });
            return result;
        }, []);

        return paramsObjectList;
    };

    // HANLDE PARAMS IN URL
    const handleParams = (paramKey, paramValue) => {
        const params = handleGetParams();

        if (params.length === 0) {
            const newUrl = `${window.location.pathname}?${paramKey}=${paramValue}`;
            window.history.pushState({}, '', newUrl);
        }

        if (params.length !== 0) {
            const isCurrentParamExist = params.find((param) => Object.keys(param)[0] === paramKey);

            if (isCurrentParamExist) {
                const newParams = params
                    .map((param) => {
                        let key = Object.keys(param)[0];
                        let value = Object.values(param)[0];
                        if (key === paramKey) value = paramValue;
                        return `${key}=${value}`;
                    })
                    .join('&')
                    .toString();
                const newUrl = `${window.location.pathname}?${newParams}`;
                window.history.pushState({}, '', newUrl);
            }

            if (!isCurrentParamExist) {
                params.push({ [paramKey]: paramValue });
                const newestParams = params
                    .map((param) => {
                        let key = Object.keys(param)[0];
                        let value = Object.values(param)[0];
                        return `${key}=${value}`;
                    })
                    .join('&')
                    .toString();
                const newUrl = `${window.location.pathname}?${newestParams}`;
                window.history.pushState({}, '', newUrl);
            }
        }
    };

    // HANDLE STICKY FILTER ON MOVMENT
    const handleScrollingPagination = () => {
        const siteFooter = document.querySelector('.site-footer');
        const handleObserver = (entries, observer) => {
            entries.forEach((entry) => {
                if (isNoMoreProducts || deactiveObserver) return;
                if (entry.isIntersecting) {
                    currentPage += 1;
                    handleParams('page', currentPage);
                    const newParams = handleGetParams();
                    handleFilter(newParams);
                }
            });
        };

        const options = {
            root: null,
            rootMargin: '0px',
            threshold: 0.5
        };

        const observer = new IntersectionObserver(handleObserver, options);
        observer.observe(siteFooter);
    };

    // HANDLE NEXT PAGE BUTTON
    function handleNextPageButton() {
        const currentFilterCategy = $('.active');
        const specialFilter = currentFilterCategy.data('category');

        currentPage += 1;

        handleParams('page', currentPage);
        const newParams = handleGetParams();
        handleFilter(newParams, false, false, undefined, specialFilter);
    }

    // START INITIAL RUN
    if (checkIfPageIsArchive()) {
        handleScrollingPagination();
    }

    const handleRemoveFilterSelection = () => {
        handleFilter([], true);
        allCheckValues.each(function () {
            $(this).prop('checked', false);
        });
        if (checkIfPageIsHome()) return;
        filteredElementInsert.html('');

        window.history.pushState({}, '', '?page=1');
    };

    cleanerBtn.on('click', function () {
        handleRemoveFilterSelection();
    });

    function simpleHash(str, num) {
        let sum = 0;
        for (let i = 0; i < str.length; i++) {
            sum += str.charCodeAt(i);
        }
        return sum * num;
    }

    function removeFilterFromString(paramString, filter) {
        const filters = paramString.split(',');
        const filterIndex = filters.indexOf(filter);
        if (filterIndex !== -1) filters.splice(filterIndex, 1);
        return filters.join(',');
    }

    inputFilter.on('change', function () {
        const filter = $(this).data('filter');
        const type = $(this).data('type');
        const isChecked = $(this).prop('checked');
        const uniqueClass = `${simpleHash(filter, 5)}`;
        const trimmedFilterId = `${simpleHash(filter, 4)}`;

        if (isChecked) {
            filteredElementInsert.append(
                `<p style="cursor:pointer;" class="${uniqueClass} filtered_item " id="${trimmedFilterId}">${filter} X</p>`
            );
            $(`.${uniqueClass}`).on('click', function () {
                const elemID = $(this).attr('id');
                $(`#${elemID}`).prop('checked', false);
                $(`#${elemID}`).trigger('change');
            });
            isEventListeningOnFilter = true;
        } else {
            $(`.${uniqueClass}`).remove();
        }

        const queryParams = handleGetParams();

        const currentTypeInParamsIndex = queryParams.findIndex((param) => Object.keys(param)[0] === type);

        const existingParam = queryParams[currentTypeInParamsIndex];

        if (currentTypeInParamsIndex !== -1) {
            if (existingParam[type].includes(',')) {
                existingParam[type] = isChecked ? `${existingParam[type]},${filter}` : removeFilterFromString(existingParam[type], filter);
            } else {
                if (!isChecked) queryParams.splice(currentTypeInParamsIndex, 1);
                else if (existingParam[type] !== filter) existingParam[type] = `${existingParam[type]},${filter}`;
            }
        } else queryParams.push({ [type]: filter });

        const newQuery = queryParams.map((param) => `${Object.keys(param)[0]}=${param[Object.keys(param)[0]]}`).join('&');

        const newUrl = `${window.location.pathname}?${newQuery}`;

        clearTimeout(timeOut);
        timeOut = setTimeout(() => {
            handleFilter(queryParams, false, true);
        }, 0);

        window.history.pushState({}, '', newUrl);
    });

    const handleCheckValuesInDom = (values) => {
        values.forEach((value) => {
            const elements = $(`[data-filter="${value}"]`);
            elements.prop('checked', true);
            elements.trigger('change');
        });
    };

    expandBtn.on('click', function () {
        const type = $(this).data('type');
        const filterDiv = $(`.filter-${type}`);
        filterDiv.toggleClass('expanded');
    });

    categoryBtn.on('click', function () {
        categoryBtn.removeClass('active');
        $(this).addClass('active');
        const category = $(this).data('category');
        handleFilter(paramsList, false, true, undefined, category);
        loadMoreBtn.removeClass('hide');
    });

    loadMoreBtn.on('click', function () {
        if (isNoMoreProducts) {
            $(this).addClass('hide');
        }
        handleNextPageButton();
    });

    const companySliderRightBtn = $('.companies-container__arrow-right');
    const companySliderLeftBtn = $('.companies-container__arrow-left');
    const companyContainer = $('.companies-container__companies');
    const companyIcon = $('.company-icon-1');

    let current = 0;
    companySliderRightBtn.on('click', function () {
        current -= 300;

        companyContainer.css({
            transform: `translateX(${current}px)`
        });
    });
    companySliderLeftBtn.on('click', function () {
        const width = companyIcon.width();
        current += 300;
        companyContainer.css({
            transform: `translateX(${current}px)`
        });
    });

    const paramsList = handleGetParams();

    paramsList.forEach((params) => {
        const values = Object.values(params)[0].split(',');
        handleCheckValuesInDom(values);
    });

    if (paramsList === -1) {
    } else {
        if (checkIfPageIsHome()) {
            handleFilter(paramsList, true, false, '', 'top-10');
        } else {
            handleFilter(paramsList, true);
        }
    }
});
