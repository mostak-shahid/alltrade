<?php


class SapOrderFlow
{

    public $sap_warranty_skus = array(
        "pickup_return_1_year" => 'Pickup-&amp;Return- 1yr',
        "pickup_return_2_year" => 'Pickup-&amp;Return- 2yr',
        "pickup_return_3_year" => 'Pickup-&amp;Return- 3yr',
        "get_service_24m" => 'GET-SERVICE-24M',
        "get_service_36m" => 'GET-SERVICE-36M'
    );

    public $SAP_BASE_URL = 'http://62.0.68.60:25001/Services/FACClient.asmx';

    public  function sap_item_error_handle($errors, $order_id)
    {
        $order = new WC_Order($order_id);
        $error_output = '';

        $error_output .= '<ul>';
        foreach ($errors as $key => $value) {
            $error_output .= '<li>';
            $product = wc_get_product($key);
            $error_output .= '<strong>מוצר: </strong> ' . $product->get_title() . '<br>';
            $error_output .= '<strong>שגיאה: </strong>' . $value['message'] . '<br>';
            $error_output .= '<strong>לינק למוצר: </strong> <a href="' . $product->get_permalink()  . '">לינק למוצר</a><br>';
            $error_output .= '</li>';

            $this->update_sap_item_meta_fields($value['item'], 'חלה תקלה', $value['message']);
        };
        $error_output .= '</ul>';

        $this->update_sap_order_meta_fields('חלה תקלה', $error_output, $order, 'faild');

        $to = 'yoni@npcoding.com';
        $subject = ' שגיאה בעת הקמת פריט בסאפ - הזמנה ' . $order_id . '';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $message = '
            <html>
            <head>
              <title>נכשלה הזמנה בעת שליחת פריט לSAP</title>
            </head>
            <body dir="rtl">
              <h1>שגיאה בהקמת פריט בסאפ</h1>
              <p>' . $error_output . ' </p>
              <ul>
                <li><strong>מספר הזמנה:</strong> ' . $order_id . '</li>
                <li><strong>קישור להזמנה:</strong> <a href="' . get_site_url() . '/wp-admin/post.php?post=' . $order_id . '&action=edit">לצפייה בהזמנה</a></li>
              </ul>
            </body>
            </html>
            ';
        wp_mail($to, $subject, $message, $headers);
    }
    public  function sap_send_order_error_handle($error, $order_id)
    {
        $error_output = '';

        $error_output .= '<ul>';
        $error_output .= '<li>';
        $error_output .= '<strong>שגיאה: </strong>' . $error . '<br>';
        $error_output .= '</li>';
        $error_output .= '</ul>';

        $to = 'yoni@npcoding.com';
        $subject = ' שגיאה בעת שליחת הזמנה לסאפ - הזמנה ' . $order_id . '';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $message = '
            <html>
            <head>
              <title>נכשלה הזמנה בעת שליחת פריט לSAP</title>
            </head>
            <body dir="rtl">
              <h1>שגיאה בעת שליחת הזמנה</h1>
              <p>' . $error_output . ' </p>
              <ul>
                <li><strong>מספר הזמנה:</strong> ' . $order_id . '</li>
                <li><strong>קישור להזמנה:</strong> <a href="' . get_site_url() . '/wp-admin/post.php?post=' . $order_id . '&action=edit">לצפייה בהזמנה</a></li>
              </ul>
            </body>
            </html>
            ';
        wp_mail($to, $subject, $message, $headers);
    }


    public function update_sap_order_meta_fields($status, $sap_order_id, $order, $message = 'success')
    {

        $order->update_meta_data('is_sap_success', $status);
        $order->update_meta_data('sap_order_id', $sap_order_id);
        $order->update_meta_data('sap_message', $message);
        $order->update_meta_data('product_source', 'sap');


        $order->save();
    }

    public function update_sap_item_meta_fields($item, $status, $message, $item_id = null)
    {
        $item->update_meta_data('is_sap_item_succed', $status);
        $item->update_meta_data('sap_item_message', $message);
        $item->update_meta_data('sap_item_id', $item_id);
        $item->save();
    }

    function handle_sap_request($iObjectType, $xml_string)
    {
        $args = array(
            'method'      => 'POST',
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            'body' => array(
                'Token' => '1qaz',
                'iObjectType' => $iObjectType,
                'sDocEntry' => -1,
                'bCancel' => 'FALSE',
                'sXml' =>  $xml_string,
            )
        );

        $res = wp_remote_post($this->SAP_BASE_URL . '/SaveDocumentWithoutSapCompany', $args);

        if ($res instanceof WP_Error) {
            return 'error';
        };

        if ($res["response"]["code"] == 200) {
            $xmlstring = $res['body'];
            $xml = simplexml_load_string($xmlstring);
            $sap_xml_body = (string)$xml[0];
            return $sap_xml_body;
        } else {
            return array('status' => 'faild', 'message' => $res["response"]["message"]);
        }
    }

    function create_sap_item($order_item, $product_id)
    {
        $product = wc_get_product($product_id);
        if (!$product) return;

        $product_source = get_post_meta($product_id, 'product_source');

        $product_code = $product->get_sku();
        $product_title = get_post_meta($product_id, 'original_title')[0];
        $product_firmcode = get_post_meta($product_id, 'sap_firmcode')[0];
        $product_items_group = get_post_meta($product_id, 'sap_itemsgroupcode')[0];

        $xml = '<?xml version="1.0" encoding="UTF-16"?>
                <BOM>
                    <BO>
                    <AdmInfo>
                    <Object>4</Object>
                    </AdmInfo>
                        <OITM>
                            <row>
                            <ItemCode>' . $product_code . '</ItemCode>
                            <ItemName>' .  $product_title  . '</ItemName> 
                            <ItmsGrpCod>' . $product_items_group . '</ItmsGrpCod>
                            <FirmCode>' . $product_firmcode . '</FirmCode>
                            <CardCode>HUBX</CardCode>
                            <U_Curr>1</U_Curr>
                            <U_PurPrice>20</U_PurPrice>
                            <U_SalePrice>25</U_SalePrice>
                            </row>
                        </OITM>
                    </BO>
                </BOM>';

        $res = $this->handle_sap_request(4, $xml);

        $res_arr = []; // Initialize array

        if (strpos($res, 'Sap error') !== false) {
            if (strpos($res, 'already exists') !== false) {
                $res_arr['status'] = 'ok';
                $res_arr['item'] = $product_code;
            } else {
                $res_arr['status'] = 'error';
                $res_arr['message'] = $res;
            }
        } else {
            $res_arr['status'] = 'ok';
            $res_arr['item'] = $product_code;
        }

        return $res_arr;
    }

    function create_customer_order($item_code_array, $order)
    {
        $order_items = $order->get_items();
        $order_row_xml = '';
        $item_counter = 0;
        $order_id = $order->get_id();

        $coupon_codes = $order->get_coupon_codes();
        $coupon_code = !empty($coupon_codes) ? $coupon_codes[0] : ""; // Use first coupon code
        $ship_due_data = getDate21DaysFromNow();

        foreach ($order_items as $order_item) {
            $product_id = $order_item->get_product_id();
            $product_source = get_post_meta($product_id, 'product_source');

            $product = wc_get_product($order_item->get_variation_id());

            if ($product_source === 'hubx') $is_one_hubx_product = true;

            $quantity = $order_item->get_quantity();
            $total = $order_item->get_total();

            $order_row_xml .= '
                    <row>
                    <LineNum>' . $item_counter . '</LineNum>
                    <ItemCode>' . $item_code_array[$product_id] . '</ItemCode>
                    <Quantity>' . $quantity . '</Quantity>
                    <ShipDate>' . $ship_due_data . '</ShipDate>
                    <Currency>₪</Currency>
                    <DiscPrcnt></DiscPrcnt>
                    <LineTotal/>
                    <PriceAfVAT/>
                    <U_WebCouponCode>' . $coupon_code . '</U_WebCouponCode>
                    <WhsCode>808</WhsCode>
                    <PriceBefDi>' . $total .  '</PriceBefDi>
                    <Price/>
                    </row>';

            $item_counter++;

            // Here im adding the warranty for the product to the sap Order
            $current_attr = array_values($product->get_attributes())[0];
            $product_original_price = get_post_meta($product_id, 'initial_product_price')[0];

            $warranty_price = $total - $product_original_price;

            $order_row_xml .= '
                    <row>
                    <LineNum>' . $item_counter . '</LineNum>
                    <ItemCode>' . $this->sap_warranty_skus[$current_attr] . '</ItemCode>
                    <Quantity>' . '1' . '</Quantity>
                    <ShipDate>20230719</ShipDate>
                    <Currency>₪</Currency>
                    <DiscPrcnt></DiscPrcnt>
                     <LineTotal/>
                    <PriceAfVAT/>
                    <WhsCode>808</WhsCode>
                    <U_WebCouponCode/>
                    <Price/>
                    <PriceBefDi>' . $warranty_price .  '</PriceBefDi>
                    </row>';

            $item_counter++;
        }

        $current_date = $order->get_date_created()->date('Ymd');

        $billing_address_1 = $order->get_billing_address_1();
        $billing_email = $order->get_billing_email();
        $billing_first_name = $order->get_billing_first_name();
        $billing_last_name = $order->get_billing_last_name();
        $billing_phone = $order->get_billing_phone();
        $billing_city = $order->get_billing_city();
        $zipcode = $order->get_billing_postcode();

        $docDueDate = $order->get_date_created()->add(new DateInterval('P21D'))->date('Ymd'); // Assuming due date is 2 weeks after order date
        //    <ObjType>17</ObjType>
        $xml = '
        <BOM>
            <BO>
                <AdmInfo>
                <Object>17</Object>
                </AdmInfo>
                <ORDR>
                <row>
                    <ObjType>17</ObjType>
                    <DocStatus>O</DocStatus>
                    <DocDate>' . $current_date . '</DocDate>
                    <DocDueDate>' . $docDueDate . '</DocDueDate>
                    <CardCode>248309</CardCode>
                    <CardName>' . $billing_first_name . ' ' . $billing_last_name . '</CardName>
                    <NumAtCard>' . $order_id  . '</NumAtCard>
                    <VatPercent>17</VatPercent>
                    <DiscPrcnt/>
                    <DocCur>₪</DocCur>
                    <DocTotal/>
                    <TaxDate>' .  $current_date  . '</TaxDate>
                    <U_Street>' . $billing_address_1 . '</U_Street>
                    <U_City>' . $billing_city . '</U_City>
                    <U_Zipcode>' . $zipcode . '</U_Zipcode>
                    <U_email>' . $billing_email . '</U_email>
                    <U_Phone>' . $billing_phone . '</U_Phone>
                    <LicTradNum/>
                    <TrnspCode>2</TrnspCode>
                    <U_smssend>N</U_smssend>
                    <U_SalesPicup>N</U_SalesPicup>
                    <SlpCode/>
                    </row>
                </ORDR>
                <RDR1>
                  ' .  $order_row_xml . '
                </RDR1>
            </BO>
        </BOM>
        ';

        custom_error_logger($xml);

        $res = $this->handle_sap_request(17, $xml);

        $res_arr = [];

        if ((strpos($res, 'Sap error') !== false) || (strpos($res, 'Error') !== false)) {
            $res_arr['status'] = 'error';
            $res_arr['body'] = $res;
        } else {
            $res_arr['status'] = 'ok';
            $res_arr['body'] = $res;
        }

        return $res_arr;
    }

    function create_recipt($item_code_array, $order)
    {

        $xml = '<BOM>
                    <BO>
                        <AdmInfo>
                            <Object>24</Object>
                        </AdmInfo>
                        <ORCT>
                            <row>
                                <DocDate>20230710</DocDate>
                                <CardCode>248309</CardCode>
                                <CounterRef>13380666</CounterRef>
                            </row>
                        </ORCT>
                        <RCT3>
                            <row>
                                <CreditCard>1012</CreditCard>
                                <CreditAcct>1343</CreditAcct>
                                <CrCardNum>6609</CrCardNum>
                                <OwnerIdNum>999999999</OwnerIdNum>
                                <OwnerPhone>0542034798</OwnerPhone>
                                <CardValid>20270828</CardValid>
                                <CrTypeCode>9</CrTypeCode>
                                <CreditSum>839.0</CreditSum>
                                <NumOfPmnts>2</NumOfPmnts>
                                <VoucherNum>001462</VoucherNum>
                                <ConfNum>0392894</ConfNum>
                                <U_IsManual>1</U_IsManual>
                            </row>
                        </RCT3>
                    </BO>
                </BOM>

';

        $res = $this->handle_sap_request(203, $xml);
    }

    function init($order_id)
    {

        $succed_items_array = [];
        $faild_items_array = [];
        $order = new WC_Order($order_id);
        $order_items = $order->get_items();

        // STEP 1 - CREATE ITEM IN SAP
        foreach ($order_items as $order_item) {
            $product_id = $order_item->get_product_id();
            $res = $this->create_sap_item($order_item, $product_id);

            if ($res['status'] == 'ok') {
                $succed_items_array[$product_id] = $res['item'];
                $this->update_sap_item_meta_fields($order_item, 'נשלח בהצלחה', "ok", $res['item']);
            } else {
                $faild_items_array[$product_id]['status'] = 'error';
                $faild_items_array[$product_id]['message'] = $res['message'];
                $faild_items_array[$product_id]['item'] = $order_item;
            }
        }

        // STEP 1 - Error handling CREATE ITEM IN SAP
        if (!empty($faild_items_array)) {
            $this->sap_item_error_handle($faild_items_array, $order_id);
        }

        if (empty($succed_items_array)) {
            return;
        };


        // STEP 2 - CREATE CUSTOMER ORDER
        $order_sum = $this->create_customer_order($succed_items_array, $order);

        if ($order_sum['status'] == 'ok') {
            $this->update_sap_order_meta_fields($order_sum['status'], $order_sum['body'], $order);
        } else {
            $this->update_sap_order_meta_fields($order_sum['status'], '', $order, $order_sum['body']);
            // STEP 2 - Error handling CREATE CUSTOMER ORDER
            $this->sap_send_order_error_handle($order_sum['body'], $order_id);
        }
    }
}

// UTILS

function calculate_percent($total, $number)
{
    if ($total > 0) {
        return round(($number / $total) * 100, 2);
    } else {
        return 0;
    }
}

function getDate21DaysFromNow()
{
    $date = new DateTime();  // Create a DateTime object set to the current date and time
    $date->modify('+21 days');  // Add 21 days to it
    return $date->format('Ymd');  // Format it as "Ymd"
}


// $new_init = new SapOrderFlow();
// $new_init->init(11915);
