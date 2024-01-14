jQuery(function ($) {
    const tryAgainBtn = $('.ya__btn-hubx-try-again');
    const productId = tryAgainBtn.data('product_id');
    const orderId = tryAgainBtn.data('order_id');

    const loader = $('<div/>', {
        id: 'ya__loader',
        class: 'ya__loader',
        text: 'This is a div.'
    });

    tryAgainBtn.on('click', function (e) {
        const currBtn = this;

        e.preventDefault();
        $.ajax({
            url: sticky_globals.ajax_url,
            type: 'POST',
            data: {
                action: 'hubx__request',
                product_id: productId,
                order_id: orderId
            },
            success: function (data) {
                alert(`STATUS: ${data.data.metadata.error} \nMESSAGE : ${data.data.metadata.lines[1][0].messages} `);
                location.reload();
            },
            beforeSend: function () {
                $(currBtn).addClass('ya__btn-hubx-try-again__loading');
                $(currBtn).prop('disabled', true);
            }
        });
    });
});
