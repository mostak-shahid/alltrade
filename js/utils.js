document.addEventListener('DOMContentLoaded', () => {
    const inputBtn = document.querySelector('.dgwt-wcas-search-submit');
    inputBtn.disabled = true;

    var btn = document.querySelector('.ya__btn-buy-now-btn');
    if (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var form = this.closest('form.cart');
            var input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', 'proceed');
            input.setAttribute('value', 'true');
            form.appendChild(input);
            form.submit();
        });
    }

    // Redirect after contact form 7

    document.addEventListener('wpcf7mailsent', function (event) {
        const status = event.detail.status;
        const { contactFormId } = event.detail;

        const baseUrl = window.location.origin;

        if (status === 'mail_sent' && contactFormId === 154) {
            location = baseUrl + '/alltrade/call-service-success/';
        }
    });

    // opens notify me when in stock form

    document.addEventListener('finish-loading', () => {});

    // Change UI Price when selecting new variation

    const priceElement = document.querySelector('.woocommerce-Price-amount.amount').querySelector('bdi');
    const varitaionForm = document.querySelector('.variations_form.cart');

    const initialPrice = parseFloat(priceElement.innerText.replace('₪', '').replace(',', ''));
    const defaultVariation = document.querySelector('.variations').querySelector('select').value;

    let isInitialRun = true;

    const observer = new MutationObserver((mutationsList, observer) => {
        for (let mutation of mutationsList) {
            const variation = document.querySelector('.variations').querySelector('select');
            const productData = mutation.target.dataset.product_variations;
            const parsedData = JSON.parse(productData);
            parsedData.forEach((attr) => {
                const attribute = attr['attributes'];
                const key = Object.values(attribute)[0];
                const decodedAttributeName = decodeURIComponent(key);

                if (decodedAttributeName === variation.value) {
                    const finalPrice = attr.display_price.toLocaleString();

                    priceElement.innerHTML = `${finalPrice}<span class="woocommerce-Price-currencySymbol">₪</span>`;
                }
            });
        }
    });

    if (varitaionForm !== null) {
        observer.observe(varitaionForm, { attributes: true });
    }
});
