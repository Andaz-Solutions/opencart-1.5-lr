<?php

class ControllerPaymentAndaz extends Controller
{
    protected function index()
    {
        $this->language->load('payment/andaz');

        $this->data['text_credit_card'] = $this->language->get('text_credit_card');
        $this->data['text_wait'] = $this->language->get('text_wait');

        $this->data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
        $this->data['entry_cc_number'] = $this->language->get('entry_cc_number');
        $this->data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
        $this->data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');

        $this->data['button_confirm'] = $this->language->get('button_confirm');

        $this->data['months'] = array();

        for ($i = 1; $i <= 12; $i++) {
            $this->data['months'][] = array(
                'text' => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)),
                'value' => sprintf('%02d', $i)
            );
        }

        $today = getdate();

        $this->data['year_expire'] = array();

        for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
            $this->data['year_expire'][] = array(
                'text' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
                'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
            );
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/andaz.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/andaz.tpl';
        } else {
            $this->template = 'default/template/payment/andaz.tpl';
        }

        $this->render();
    }

    public function send()
    {
        $url = 'https://secure.andazsolutions.com/post-web-service/process';
        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $data = array();

        $data['client_id'] = $this->config->get('andaz_client_id');
        $data['client_username'] = $this->config->get('andaz_client_username');
        $data['client_password'] = $this->config->get('andaz_client_password');
        $data['client_token'] = $this->config->get('andaz_client_token');

        $data['billing_first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
        $data['billing_last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
        $data['billing_address_line_1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
        $data['billing_city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
        $data['billing_state'] = html_entity_decode($order_info['payment_zone_code'], ENT_QUOTES, 'UTF-8');
        $data['billing_postal_code'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
        $data['billing_country'] = html_entity_decode($order_info['payment_iso_code_2'] , ENT_QUOTES, 'UTF-8');
        $data['billing_phone_number'] = $order_info['telephone'];
        $data['ip_address'] = $this->request->server['REMOTE_ADDR'];
        $data['billing_email_address'] = $order_info['email'];
        $data['initial_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);
        $data['currency'] = $this->currency->getCode();
        $data['processing_type'] = ($this->config->get('andaz_method') == 'capture') ? 'debit' : 'authorize';
        $data['account_number'] = str_replace(' ', '', $this->request->post['cc_number']);
        $data['expiration_month'] = $this->request->post['cc_expire_date_month'];
        $data['expiration_year'] = $this->request->post['cc_expire_date_year'];
        $data['cvv2'] = $this->request->post['cc_cvv2'];

        /* Customer Shipping Address Fields */
        $data['shipping_first_name'] = html_entity_decode($order_info['shipping_firstname'], ENT_QUOTES, 'UTF-8');
        $data['shipping_last_name'] = html_entity_decode($order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');
        $data['shipping_address'] = html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['shipping_address_2'], ENT_QUOTES, 'UTF-8');
        $data['shipping_city'] = html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8');
        $data['shipping_state'] = html_entity_decode($order_info['shipping_zone_code'], ENT_QUOTES, 'UTF-8');
        $data['shipping_postal_code'] = html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8');
        $data['shipping_country'] = html_entity_decode($order_info['shipping_iso_code_2'] , ENT_QUOTES, 'UTF-8');
        $data['pass_through'] = 'order_id:' + $this->session->data['order_id'];
	$data['domain'] = $_SERVER['HTTP_HOST'];

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_PORT, 443);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));

        $response = curl_exec($curl);

        $json = array();

        if (curl_error($curl)) {
            $json['error'] = 'CURL ERROR: (' . $url . ') ' . curl_errno($curl) . '::' . curl_error($curl);

            $this->log->write('ANDAZ CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl));
        } elseif ($response) {
            $i = 1;

            $results = (array)json_decode($response);

            if ($results['status'] == 'approved') {
                $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
                $message = $response;
                $this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('andaz_order_status_id'), $message, false);

                $json['success'] = $this->url->link('checkout/success', '', 'SSL');
            } else {
                preg_match('/<remark>([^<]+)/', $response, $match);
                if (count($match)) {
                    $json['error'] = $match[1];
                } else {
                    $json['error'] = 'Your transaction was declined, please use another card.';
                }
            }
        } else {
            $json['error'] = 'Empty Gateway Response';

            $this->log->write('ANDAZ CURL ERROR: Empty Gateway Response');
        }

        curl_close($curl);

        $this->response->setOutput(json_encode($json));
    }
}

?>
