jQuery(function ($) {
    const form = $('#newsletter-register');
    const spinner = $('.newsletter-form-container__spinner');
    const sucessMessage = $('.newsletter-form-container__sucess-message');
    const btnSubmit = $('.newsletter-form-container__btn');
    const honeyPot = $('.newsletter-form-container__favorite-color');

    form.on('submit', function (e) {
        const registerdEmail = $('#newsletter-email').val();

        if (honeyPot.val()) return;

        e.preventDefault();
        $.ajax({
            url: sticky_globals.ajax_url,
            type: 'POST',
            data: {
                action: 'newsletter_form_request',
                register_email: registerdEmail
            },
            success: function () {
                $(sucessMessage).addClass('newsletter-form-container__spinner-active');
                $(spinner).removeClass('newsletter-form-container__spinner-active');
            },
            beforeSend: function () {
                $(spinner).addClass('newsletter-form-container__spinner-active');
                $(btnSubmit).addClass('ya__hide');
            }
        });
    });
});
