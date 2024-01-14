jQuery(function ($) {
    const KEY = 'shouldOpenCart';

    function init() {
        if (shouldOpenCart() && !isMobileSize()) {
            setInLocalStorage();
            show();
        } else {
            removeFromLocalStorage();
            hide();
        }

        // When click show cart
        $('#cart').click(function () {
            if (shouldOpenCartByUrl() && !is_404_page()) {
                setInLocalStorage();
                show();
            }
        });

        // When click hide cart
        $('.cart-link').click(function () {
            removeFromLocalStorage();
            hide();
        });

        // When add product to cart - open it after refresh
        $(document).on('click', '.single_add_to_cart_button', function () {
            setInLocalStorage();
        });

        // When remove all products from cart - not show cart after refresh
        $(document).on('click', '.remove_from_cart_button', function () {
            setTimeout(function () {
                if ($('.woocommerce-mini-cart__empty-message').text().includes('אין מוצרים בסל הקניות.')) {
                    removeFromLocalStorage();
                }
            }, 1000 * 1);
        });

        $(document.body).on('open-mini-cart', show);
    }

    function shouldOpenCart() {
        return shouldOpenCartByLocalStorage() && shouldOpenCartByUrl() && !is_404_page();
    }

    function shouldOpenCartByLocalStorage() {
        const shouldCartOpen = window.localStorage.getItem(KEY);

        return shouldCartOpen === 'true';
    }

    function shouldOpenCartByUrl() {
        const pagesShouldNot = ['/cart/', '/checkout/', 'thank-you'];
        for (let i = 0; i < pagesShouldNot.length; i++) {
            if (window.location.href.includes(pagesShouldNot[i])) {
                return false;
            }
        }

        return true;
    }

    function is_404_page() {
        return $('body').hasClass('error404');
    }

    function setInLocalStorage() {
        window.localStorage.setItem(KEY, true);
    }

    function removeFromLocalStorage() {
        window.localStorage.removeItem(KEY);
    }

    function isMobileSize() {
        const width = $(window).width();

        return width <= 999;
    }

    function show() {
        $('#cart-sidebar').addClass('open');
    }

    function hide() {
        $('#cart-sidebar').removeClass('open');
    }

    init();
});
