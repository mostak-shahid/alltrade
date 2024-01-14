jQuery(function ($) {
    const currentPage = $('body').attr('class');

    const handleStockModak = () => {
        const stockBtn = $('.btn-stock-popup');
        const closeModalBtn = $('.stock-notify__btn-close');
        const modalContainer = $('.stock-notify__overlay');

        const stockModalProductTitle = $('.stock-notify__product__title');
        const stockModalProductImage = $('.stock-notify__product__image');

        const productIdHiddenField = $('.ya__stock-product-id');

        const imageContainer = $('.stock-notify__product__image-container');

        const skeleton = `
        <div class="skeleton-text"></div>
        `;

        closeModalBtn.on('click', () => {
            modalContainer.removeClass('stock-notify__overlay-active');
        });

        stockBtn.on('click', (e) => {
            const productId = $(e.target).data('product-id');

            modalContainer.addClass('stock-notify__overlay-active');

            $.ajax({
                url: sticky_globals.ajax_url,
                type: 'POST',
                data: {
                    action: 'stock__modal',
                    product_id: productId
                },
                success: function (product_data) {
                    const { data } = product_data;
                    stockModalProductImage.attr('src', data.product_image);
                    stockModalProductTitle.text(data.product_title);
                    productIdHiddenField.attr('value', data.product_link);
                },
                beforeSend: function () {
                    stockModalProductTitle.html(skeleton);
                }
            });
        });
    };
    const isSingle = currentPage.includes('single-product');

    if (isSingle) {
        $(document).ready(function (e) {
            console.log('hey');
            const stockBtn = $('.btn-stock-popup');
            console.log(stockBtn);
            handleStockModak();
        });
    }

    $(document).on('finish-loading', function (e) {
        handleStockModak();
    });
});
