<?php 
class ControllerPaymentAndaz extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('payment/andaz');
     //   $this->load->model('payment/andaz');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('andaz', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_authorization'] = $this->language->get('text_authorization');
		$this->data['text_sale'] = $this->language->get('text_sale');
	
		$this->data['entry_client_id'] = $this->language->get('entry_client_id');	
		$this->data['entry_client_username'] = $this->language->get('entry_client_username');
		$this->data['entry_client_password'] = $this->language->get('entry_client_password');
		$this->data['entry_client_token'] = $this->language->get('entry_client_token');
		$this->data['entry_transaction'] = $this->language->get('entry_transaction');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['help_total'] = $this->language->get('help_total');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['client_id'])) {
			$this->data['error_client_id'] = $this->error['client_id'];
		} else {
			$this->data['error_client_id'] = '';
		}

 		if (isset($this->error['client_username'])) {
			$this->data['error_client_username'] = $this->error['client_username'];
		} else {
			$this->data['error_client_username'] = '';
		}
		
 		if (isset($this->error['client_password'])) {
			$this->data['error_client_password'] = $this->error['client_password'];
		} else {
			$this->data['error_client_password'] = '';
		}
		
 		if (isset($this->error['token'])) {
			$this->data['error_client_token'] = $this->error['client_token'];
		} else {
			$this->data['error_client_token'] = '';
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => '::'
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => '::'
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/andaz', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => '::'
   		);
				
		$this->data['action'] = $this->url->link('payment/andaz', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['andaz_client_id'])) {
			$this->data['andaz_client_id'] = $this->request->post['andaz_client_id'];
		} else {
			$this->data['andaz_client_id'] = $this->config->get('andaz_client_id');
		}

		if (isset($this->request->post['andaz_client_username'])) {
			$this->data['andaz_client_username'] = $this->request->post['andaz_client_username'];
		} else {
			$this->data['andaz_client_username'] = $this->config->get('andaz_client_username');
		}
		
		if (isset($this->request->post['andaz_client_password'])) {
			$this->data['andaz_client_password'] = $this->request->post['andaz_client_password'];
		} else {
			$this->data['andaz_client_password'] = $this->config->get('andaz_client_password');
		}
				
		if (isset($this->request->post['andaz_token'])) {
			$this->data['andaz_client_token'] = $this->request->post['andaz_client_token'];
		} else {
			$this->data['andaz_client_token'] = $this->config->get('andaz_client_token');
		}
		
		if (isset($this->request->post['andaz_method'])) {
			$this->data['andaz_method'] = $this->request->post['andaz_method'];
		} else {
			$this->data['andaz_method'] = $this->config->get('andaz_method');
		}
		
		if (isset($this->request->post['andaz_total'])) {
			$this->data['andaz_total'] = $this->request->post['andaz_total'];
		} else {
			$this->data['andaz_total'] = $this->config->get('andaz_total'); 
		} 
				
		if (isset($this->request->post['andaz_order_status_id'])) {
			$this->data['andaz_order_status_id'] = $this->request->post['andaz_order_status_id'];
		} else {
			$this->data['andaz_order_status_id'] = $this->config->get('andaz_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		if (isset($this->request->post['andaz_geo_zone_id'])) {
			$this->data['andaz_geo_zone_id'] = $this->request->post['andaz_geo_zone_id'];
		} else {
			$this->data['andaz_geo_zone_id'] = $this->config->get('andaz_geo_zone_id'); 
		}

		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['andaz_status'])) {
			$this->data['andaz_status'] = $this->request->post['andaz_status'];
		} else {
			$this->data['andaz_status'] = $this->config->get('andaz_status');
		}
		
		if (isset($this->request->post['andaz_sort_order'])) {
			$this->data['andaz_sort_order'] = $this->request->post['andaz_sort_order'];
		} else {
			$this->data['andaz_sort_order'] = $this->config->get('andaz_sort_order');
		}

		$this->template = 'payment/andaz.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

    protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/andaz')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		if (!$this->request->post['andaz_client_id']) {
			$this->error['client_id'] = $this->language->get('error_client_id');
		}
	
		if (!$this->request->post['andaz_client_username']) {
			$this->error['client_username'] = $this->language->get('error_client_username');
		}

		if (!$this->request->post['andaz_client_password']) {
			$this->error['client_password'] = $this->language->get('error_client_password');
		}

		if (!$this->request->post['andaz_client_token']) {
			$this->error['client_token'] = $this->language->get('error_client_token');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

/*    public function install() {
        $this->load->model('payment/andaz');
        $this->load->model('setting/setting');
        $this->model_payment_andaz->install();
        $this->model_setting_setting->editSetting('andaz', $this->settings);
    }

    public function uninstall() {
        $this->load->model('payment/andaz');
        $this->model_payment_andaz->uninstall();
    }
*/
}
?>
