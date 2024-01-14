jQuery(function ($) {
    const tryAgainBtn = $('.ya__btn-sap-try-again');

    const loader = $('<div/>', {
        id: 'ya__loader',
        class: 'ya__loader',
        text: 'This is a div.'
    });

    tryAgainBtn.on('click', function (e) {
        const productId = $(this).data('product_id');
        const orderId = $(this).data('order_id');

        e.preventDefault();
        $.ajax({
            url: sticky_globals.ajax_url,
            type: 'POST',
            data: {
                action: 'sap__request',
                product_id: productId,
                order_id: orderId
            },
            success: function (data) {
                alert(`STATUS: ${data.data.status} \n Item Code : ${data.data.item} `);
                location.reload();
            },
            beforeSend: function () {
                $(this).addClass('ya__btn-hubx-try-again__loading');
                $(this).prop('disabled', true);
            }
        });
    });
});
