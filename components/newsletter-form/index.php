<?php


function newsletter_form_enqueue_custom_filter_style()
{
    $directory = get_template_directory_uri();
    wp_enqueue_style('newsletter-form-style', $directory . '/components/newsletter-form/style.css');
    wp_enqueue_script('newsletter-form-script', $directory . '/components/newsletter-form/script.js', array('jquery'), '1.0.1', true);

    wp_localize_script('newsletter-form-script', 'sticky_globals', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'newsletter_form_enqueue_custom_filter_style');


function newsletter_form_request()
{
    if (isset($_POST)) {
        $register_mail = $_POST['register_email'];

        $to = 'testlocalemailforwordpress@gmail.com';
        $subject = 'נרשם חדש לניוזלטר!';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $message = '
		<html>
		<head>
		  <title>נרשם חדש לניוזלטר!</title>
		</head>
		<body dir="rtl">
		  <h3>נרשם חדש לניוזלטר!</h3>
		  <p>מייל הנרשם</p>' . $register_mail . '
		  <ul>
		  </ul>
		</body>
		</html>
		';
        wp_mail($to, $subject, $message, $headers);
    }
}

add_action('wp_ajax_newsletter_form_request', 'newsletter_form_request');
add_action('wp_ajax_nopriv_newsletter_form_request', 'newsletter_form_request');



function newsletter_form()
{
?>
    <div class="newsletter-form-container">
        <form id="newsletter-register">
            <div class="newsletter-form">
                <input oninvalid="this.setCustomValidity('חובה לצרף מייל')" type="email" id="newsletter-email" placeholder="דואר אלקטרוני" required />
                <!-- thats the honeypot -->
                <input class="newsletter-form-container__favorite-color" type="hidden" name="favorite_color" value="" />
                <button class="btn btn-header newsletter-form-container__btn" type="submit">הרשמה</button>
                <img loading="lazy" class="newsletter-form-container__spinner" src="<?php echo get_template_directory_uri() . '/sass/spinner-1s-200px.gif' ?>" />
            </div>
            <div class="newsletter-checkbox">
                <input oninvalid="this.setCustomValidity('חובה לאשר דיוור בשביל להמשיך')" id="news-checkbox" type="checkbox" placeholder="אני מאשר קבלת דיוור מgetDeal" required />
                <label class="text text-xs" id="news-checkbox">אני מאשר קבלת דיוור מgetDeal</label>
            </div>
        </form>
        <p class="newsletter-form-container__sucess-message">הטופס נשלח בההצלחה!</p>
    </div>

<?php

}
