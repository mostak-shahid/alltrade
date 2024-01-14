<?php
session_start();
class Handler_after_order
{
    public $settings = array(
        'client_id' => 'c0cf0eb1954041c0a8920d6ea3c8fbbd',
        'client_secret' => '5CV8nT5O3XjlqDOMFg9Kebx+7ucgQrNYRckvCHShx64=',
        'grant_type' => 'client_credentials'
    );

    public $base_auth_url = 'https://hubx-authenticationapi-dev.azurewebsites.net';
    public $base_url = 'https://hubx-customerapi-staging.azurewebsites.net';

    public $is_order_succed;
    public $order;
    public $items = array();
    public $current_order = array();


    function hubx_post_product_try_again($items, $order)
    {
        $hubux_token = $this->get_hubux_token();

        $this->order = $order;

        $product_id = $items->get_product_id();
        $quantity = $items->get_quantity();
        $total = $items->get_total();
        $custom_fields = get_post_meta($product_id);

        $hubx_product = array(
            'VendorPartNumber' => $custom_fields['hubx_id'][0],
            'Quantity' => $quantity,
            'UnitPrice' => intval($total),
            'UnitOfMeasure' => 'Each',
        );

        $this->items = array($hubx_product);

        $hubx_order_response = $this->send_items_to_hubux($hubux_token);

        $this->update_hubx_product_meta_fields($hubx_order_response, $items);

        return $hubx_order_response;
    }
    public function email_after_checkout()
    {

        $order_item_length = count($this->current_order);
        $error_list = [];
        $order_output = '';

        foreach ($this->current_order as $order_item) {
            if (!$order_item['is_success']) array_push($error_list, $order_item);
            $is_order_succed =  $order_item['is_success'];
            $order_output .= '<h3>מזהה מוצר - ' . $order_item['product_id'] . '</h3>';
            $order_output .= '<li><strong>סטטוס: </strong>';
            $order_output .= ($is_order_succed ? 'נשלח' : 'חלה שגיאה') . '</li>';
            $order_output .= '<li><strong>הודעה: </strong>';
            $order_output .=  $order_item['message'] . '</li>';
        }

        $to = 'testlocalemailforwordpress@gmail.com';
        $subject = 'הזמנה חדשה שודרה ל Hubx - מוצרים (' . $order_item_length . ')' . ' שגיאות (' . count($error_list) . ')';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $order_id = $this->order->get_id();
        $message = '
            <html>
            <head>
              <title>HubX - פרטי הזמנה</title>
            </head>
            <body dir="rtl">
              <h1>HubX הזמנה</h1>
              <p>פרטי הזמנה:</p>
              <ul>
                <li><strong>מספר הזמנה:</strong> ' . $this->order->get_order_number() . '</li>
                <li><strong>קישור להזמנה:</strong> <a href="' . get_site_url() . '/wp-admin/post.php?post=' . $order_id . '&action=edit">לצפייה בהזמנה</a></li>
                ' . $order_output  . '
              </ul>
            </body>
            </html>
            ';
        wp_mail($to, $subject, $message, $headers);
    }
    public function validate_order_status($order_id)
    {
        $order = new WC_Order($order_id);

        if (!$_SESSION['is_hubx']) return;

        if (isset($_SESSION['is_order_succed'])) {
            if ($_SESSION['is_order_succed']) {
                $order->update_status('wc-hubx-succed');
            } else {
                $order->update_status('wc-hubx-failed');
            }
        }
    }

    public function get_hubux_token()
    {
        $response = wp_remote_post(
            $this->base_auth_url . '/connect/token',
            array(
                'method' => 'POST',
                'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
                'body' => $this->settings
            )
        );
        $data = json_decode($response['body'], true);
        return $data['access_token'];
    }

    public function send_items_to_hubux($access_token)
    {
        if (!$this->order || !$this->items)
            return;

        $order_data = array(
            "PurchaseOrderNumber" => $this->order->get_id(),
            "BillingAddress" => array(
                "line1" => $this->order->get_billing_address_1(),
                "line2" => $this->order->get_billing_address_2(),
                "country" => $this->order->get_billing_country(),
                "city" => $this->order->get_billing_city(),
                "state" => $this->order->get_billing_state(),
                "zipCode" => $this->order->get_billing_postcode()
            ),
            "shippingAddress" => array(
                "companyName" => $this->order->get_billing_company(),
                "recipientName" => $this->order->get_billing_first_name(),
                "recipientPhoneNumber" =>  $this->order->get_billing_phone(),
                "line1" => $this->order->get_billing_address_1(),
                "line2" => $this->order->get_billing_address_2(),
                "country" => $this->order->get_billing_country(),
                "city" => $this->order->get_billing_city(),
                "state" => $this->order->get_billing_state(),
                "zipCode" => $this->order->get_billing_postcode()
            ),
            "ShippingCost" => intval($this->order->get_shipping_total()),
            "Details" => $this->items
        );

        $req_body = json_encode($order_data);

        $response = wp_remote_post(
            $this->base_url . '/api/orders',
            array(
                'method' => 'POST',
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token,
                ),
                'body' => $req_body
            )
        );

        $data = json_decode($response['body'], true);
        return $data;
    }

    public function checkout_process($order_id)
    {

        $order = new WC_Order($order_id);
        $items = $order->get_items();
        $this->order = $order;

        $is_one_hubx = false;

        foreach ($items as $order_item) {
            $product_id = $order_item->get_product_id();
            $quantity = $order_item->get_quantity();
            $total = $order_item->get_total();
            $custom_fields = get_post_meta($product_id);
            $product_soruce = $custom_fields['product_source'][0];

            if ($product_soruce === 'hubx') {

                // HUBX ORDER
                $is_one_hubx = true;
                $hubux_token = $this->get_hubux_token();

                $hubx_product = array(
                    'VendorPartNumber' => $custom_fields['hubx_id'][0],
                    'Quantity' => $quantity,
                    'UnitPrice' => intval($total),
                    'UnitOfMeasure' => 'Each',
                );

                $this->items = array($hubx_product);

                $hubx_order_response = $this->send_items_to_hubux($hubux_token);

                $is_success = $hubx_order_response['success'];
                $message = $hubx_order_response["metadata"]["lines"][1][0]["messages"];

                $custom_order_object = array(
                    'product_id' => $product_id,
                    'is_success' => $is_success,
                    'message' =>  $message
                );

                $this->update_hubx_product_meta_fields($hubx_order_response, $order_item);

                array_push($this->current_order, $custom_order_object);

                $_SESSION['is_order_succed'] = $hubx_order_response['success'];
                $_SESSION['is_hubx'] = true;
                $this->email_after_checkout();

                $order->update_meta_data('sap_order_status', 'succed');
                $order->save();
            }
        }

        //!! SAP GOES HERE !!\\

        $sap_handler = new SapOrderFlow();
        $sap_handler->init($order_id);

        if (!$is_one_hubx) {
            $_SESSION['is_hubx'] = false;
        }
    }

    public function update_hubx_product_meta_fields($hubx_response, $product)
    {
        $is_success = $hubx_response['success'];
        $message = $hubx_response["metadata"]["lines"][1][0]["messages"];

        $product->update_meta_data('is_hubx_success', $is_success);
        $product->update_meta_data('hubx_message', $message);

        $product->save();
    }

    public function update_sap_order_meta_fields($status, $sap_order_id, $product, $message = 'success')
    {

        $product->update_meta_data('is_sap_success', $status);
        $product->update_meta_data('sap_order_id', $sap_order_id);
        $product->update_meta_data('sap_message', $message);
        $product->update_meta_data('product_source', 'sap');

        $product->save();
    }

    public function init()
    {
        session_reset();
        add_action('woocommerce_checkout_order_processed', array($this, 'checkout_process'), 1, 1);
        add_action('woocommerce_thankyou', array($this, 'validate_order_status'), 1, 1);
    }
}

$new_order = new Handler_after_order();
$new_order->init();

// TEMP
function custom_error_logger($error_string)
{
    $error_file_path = get_template_directory() . '/error_log.txt';

    $date = new DateTime();
    $date = $date->format('Y-m-d H:i:s');

    $log_message = $date . " - " . $error_string . "\n";

    if (is_writable($error_file_path)) {
        file_put_contents($error_file_path, $log_message, FILE_APPEND | LOCK_EX);
    } else {
        error_log('Could not write to the custom error log file.');
    }
}
