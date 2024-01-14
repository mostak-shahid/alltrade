<?php


class CheckSapOrderStatus
{
    public $SAP_BASE_URL = 'http://62.0.68.60:25001/Services/FACClient.asmx';

    function get_order_by_sap_id($sap_order_id)
    {
        global $wpdb;

        $sql = "SELECT post_id FROM wp_postmeta as pm
                WHERE meta_key = 'sap_order_id'
                AND meta_value = %s";
        $prepared_sql = $wpdb->prepare($sql, $sap_order_id);

        $result = $wpdb->get_results($prepared_sql);

        return empty($result) ? false : $result[0]->post_id;
    }

    function update_order_status($order_id)
    {
        $order_woo = wc_get_order($order_id);

        $order_woo->update_status('completed');

        $order_woo->save();
    }

    function handle_sap_request()
    {
        $args = array(
            'method'      => 'POST',
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            'body' => array(
                'Token' => '1qaz',
                'UserName' => '',
                'CardCode' => '',
                'EmpId' => '',
                'Date1' =>  '',
                'Date2' =>  '',
                'RepCode' =>  'GetClosedOrders',
            )
        );

        $res = wp_remote_post($this->SAP_BASE_URL . '/GetReportDataWithoutSapCompany', $args);

        if ($res instanceof WP_Error) {
            return 'error';
        };

        if ($res["response"]["code"] == 200) {
            $xmlstring = $res['body'];
            $xml = new SimpleXMLElement($xmlstring);

            $diffgram = $xml->children('diffgr', true)->diffgram;
            $documentElement = (array)$diffgram->children()->DocumentElement;
            $reports_arr = array_values($documentElement)[0];

            $doc_arr = [];

            foreach ($reports_arr as $dataReport) {
                $doc_entry = (string) $dataReport->DocEntry;
                array_push($doc_arr, $doc_entry);
            }

            return array('status' => 'succed', 'message' => $doc_arr);
        } else {
            return array('status' => 'faild', 'message' => $res["response"]["message"]);
        }
    }

    function init()
    {
        $sap_req = $this->handle_sap_request();

        if ($sap_req['status'] == 'succed') {
            $closed_orders = $sap_req['message'];

            foreach ($closed_orders as $closed_order_id) {
                $order_id = $this->get_order_by_sap_id($closed_order_id);
                if (!$order_id) continue;
                $this->update_order_status($order_id);
            }
        }
    }
}

function run_that_cron()
{
    $init = new CheckSapOrderStatus();
    $init->init();
}

add_action('cron_run_validate_sap_orders', 'run_that_cron');
