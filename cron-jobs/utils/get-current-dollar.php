<?php

class FreeCurrencyAPI
{
    private $api_key = 'fca_live_O55JD19hDHpaYjx5TyzoBFPcz6gcTGjp0qQOaKrb';
    private $url = 'https://api.freecurrencyapi.com/v1/latest?apikey={API_KEY}&currencies=ILS';

    public function get_ILS_rate()
    {
        $endpoint = str_replace('{API_KEY}', $this->api_key, $this->url);
        $response = wp_remote_get($endpoint);

        if (is_wp_error($response)) {
            return null;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        if (!isset($data['data']['ILS'])) {
            return null;
        }

        return $data['data']['ILS'];
    }
}

class SapApiCurrency
{
    public $SAP_BASE_URL = 'http://62.0.68.60:25001/Services/FACClient.asmx';

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
                'RepCode' =>  'GetRate',
            )
        );

        $res = wp_remote_post($this->SAP_BASE_URL . '/GetReportDataWithoutSapCompany', $args);

        if ($res instanceof WP_Error) {
            return 'error';
        };

        if ($res["response"]["code"] == 200) {
            $xmlstring = $res['body'];
            $xml = new SimpleXMLElement($xmlstring);

            // Navigate through the XML structure
            $diffgram = $xml->children('diffgr', true)->diffgram;
            $documentElement = $diffgram->children()->DocumentElement;
            $dataReport = $documentElement->DataReport;

            $rate = (string) $dataReport->Rate;

            return
                array('status' => 'succed', 'message' => $rate);
        } else {
            return array('status' => 'faild', 'message' => $res["response"]["message"]);
        }
    }
}
