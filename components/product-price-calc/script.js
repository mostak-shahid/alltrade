jQuery(function ($) {
    const tryAgainBtn = $('.acf-button-group');

    let searchParams = new URLSearchParams(window.location.search);

    tryAgainBtn.on('click', function (e) {
        if (e.target.nodeName === 'INPUT') {
            const productType = e.target.value;

            let param = searchParams.get('post');
            console.log(param);

            $.ajax({
                url: sticky_globals.ajax_url,
                type: 'POST',
                data: {
                    action: 'product__request',
                    product_id: param,
                    product_type: productType
                },
                success: function (data) {
                    console.log(data);
                    $('#publish').click();
                    alert(`
                    המחיר עודכן בהצלחה
                    מחיר מקורי : ${data.data.initial_price} \n
                    מחיר חדש : ${data.data.new_price}
                    `);
                },
                beforeSend: function () {}
            });
        }
    });
});
