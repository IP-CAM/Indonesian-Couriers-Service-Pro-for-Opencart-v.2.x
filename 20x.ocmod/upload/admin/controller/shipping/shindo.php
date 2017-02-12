<?php
class ControllerShippingShindo extends Controller {
	private $error = array();
	public function index() {
		$this->load->language('shipping/shindo');
		$this->load->model('shipping/shindo');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$mod = array('igsjne','igstiki');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shindo', $this->request->post);
			foreach ($mod as $m) {
				$this->model_setting_setting->editSetting($m, $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'] . '&type=shipping', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['tab_general'] = $this->language->get('tab_general');

		foreach ($mod as $m) {
			$data['tab_'. $m] = $this->language->get('tab_' . $m);
		}

		$data['entry_apikey'] = $this->language->get('entry_apikey');
		$data['entry_handling'] = $this->language->get('entry_handling');
		$data['entry_handlingmode'] = $this->language->get('entry_handlingmode');
		$data['option_handlingmode1'] = $this->language->get('option_handlingmode1');
		$data['option_handlingmode2'] = $this->language->get('option_handlingmode2');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['entry_province'] = $this->language->get('entry_province');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_weight_class'] =  $this->language->get('entry_weight_class');
		$data['entry_tax_class'] =  $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] =  $this->language->get('entry_geo_zone');
		$data['entry_service'] =  $this->language->get('entry_service');

		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['help_rate'] = $this->language->get('help_rate');
		$data['help_weight_class'] = $this->language->get('help_weight_class');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['apikey'])) {
			$data['error_apikey'] = $this->error['apikey'];
		} else {
			$data['error_apikey'] = '';
		}
		if (isset($this->error['province_id'])) {
			$data['error_province_id'] = $this->error['province_id'];
		} else {
			$data['error_province_id'] = '';
		}

		if (isset($this->error['city_id'])) {
			$data['error_city_id'] = $this->error['city_id'];
		} else {
			$data['error_city_id'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/shindo', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('shipping/shindo', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'] . '&type=shipping', true);


		$data['provinces'] = $this->model_shipping_shindo->getProvinces();
		if (isset($this->request->post['shindo_province_id'])) {
			$data['shindo_province_id'] = $this->request->post['shindo_province_id'];
		} else {
			$data['shindo_province_id'] = $this->config->get('shindo_province_id');
		}

		if (isset($this->request->post['shindo_city_id'])) {
			$data['shindo_city_id'] = $this->request->post['shindo_city_id'];
		} else {
			$data['shindo_city_id'] = $this->config->get('shindo_city_id');
		}

		if (isset($this->request->post['shindo_apikey'])) {
			$data['shindo_apikey'] = $this->request->post['shindo_apikey'];
		} else {
			$data['shindo_apikey'] = $this->config->get('shindo_apikey');
		}
		if (isset($this->request->post['shindo_status'])) {
			$data['shindo_status'] = $this->request->post['shindo_status'];
		} else {
			$data['shindo_status'] = $this->config->get('shindo_status');
		}

		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/tax_class');
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->load->model('localisation/weight_class');
		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		foreach ($mod as $m) {
			if (isset($this->request->post[$m . '_handling'])) {
				$data[$m . '_handling'] = $this->request->post[$m . '_handling'];
			} else {
				$data[$m . '_handling'] = $this->config->get($m .'_handling');
			}
			if (isset($this->request->post[$m . '_status'])) {
				$data[$m . '_status'] = $this->request->post[$m . '_status'];
			} else {
				$data[$m . '_status'] = $this->config->get($m . '_status');
			}
			if (isset($this->request->post[$m . '_handlingmode'])) {
				$data[$m . '_handlingmode'] = $this->request->post[$m . '_handlingmode'];
			} else {
				$data[$m . '_handlingmode'] = $this->config->get($m . '_handlingmode');
			}

			if (isset($this->request->post[$m . '_geo_zone_id'])) {
				$data[$m . '_geo_zone_id'] = $this->request->post[$m . '_geo_zone_id'];
			} else {
				$data[$m . '_geo_zone_id'] = $this->config->get($m . '_geo_zone_id');
			}
			if (isset($this->request->post[$m . '_tax_class_id'])) {
				$data[$m . '_tax_class_id'] = $this->request->post[$m . '_tax_class_id'];
			} else {
				$data[$m . '_tax_class_id'] = $this->config->get($m . '_tax_class_id');
			}

			if (isset($this->request->post[$m . '_weight_class_id'])) {
				$data[$m . '_weight_class_id'] = $this->request->post[$m . '_weight_class_id'];
			} else {
				$data[$m . '_weight_class_id'] = $this->config->get($m . '_weight_class_id');
			}

			if (isset($this->request->post[$m . '_service'])) {
				$data[$m . '_service'] = $this->request->post[$m . '_service'];
			} elseif ($this->config->has($m . '_service')) {
				$data[$m . '_service'] = $this->config->get($m . '_service');
			} else {
				$data[$m . '_service'] = array();
			}

			if (isset($this->request->post[$m .'_sort_order'])) {
				$data[$m .'_sort_order'] = $this->request->post[$m .'_sort_order'];
			} else {
				$data[$m .'_sort_order'] = $this->config->get($m .'_sort_order');
			}


		}
		$data['igsjne_services'] = array();
		$data['igstiki_services'] = array();

		$data['igsjne_services'][] = array(
			'text'  => 'Ongkos Kirim Ekonomis',
			'value' => 'OKE'
		);
		$data['igsjne_services'][] = array(
			'text'  => 'Layanan Reguler',
			'value' => 'REG'
		);
		$data['igsjne_services'][] = array(
			'text'  => 'Yakin Esok Sampai',
			'value' => 'YES'
		);
		$data['igsjne_services'][] = array(
			'text'  => 'JNE Trucking',
			'value' => 'JTR'
		);
		$data['igsjne_services'][] = array(
			'text'  => 'Super Speed',
			'value' => 'SPS'
		);
		$data['igsjne_services'][] = array(
			'text'  => 'JNE Trucking',
			'value' => 'JTR<150'
		);
		$data['igsjne_services'][] = array(
			'text'  => 'JNE Trucking',
			'value' => 'JTR>250'
		);
		$data['igsjne_services'][] = array(
			'text'  => 'JNE Trucking',
			'value' => 'JTR250'
		);
		//----
		$data['igstiki_services'][] = array(
			'text'  => 'REGULAR SERVICE',
			'value' => 'REG'
		);
		$data['igstiki_services'][] = array(
			'text'  => 'ECONOMY SERVICE',
			'value' => 'ECO'
		);
		$data['igstiki_services'][] = array(
			'text'  => 'OVER NIGHT SERVICE',
			'value' => 'ONS'
		);
		$data['igstiki_services'][] = array(
			'text'  => 'SAMEDAY SERVICE',
			'value' => 'SDS'
		);
		$data['igstiki_services'][] = array(
			'text'  => 'HOLIDAY SERVICE',
			'value' => 'HDS'
		);


		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/shindo.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/shindo')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['shindo_apikey']) {
			$this->error['apikey'] = $this->language->get('error_apikey');
		}
		/*if (!$this->request->post['shindo_city_id']) {
			$this->error['city_id'] = $this->language->get('error_city_id');
		}

		if (!$this->request->post['shindo_province_id']) {
			$this->error['province_id'] = $this->language->get('error_province_id');
		}*/

		return !$this->error;
	}

	public function cities() {
		$json = array();

		$this->load->model('shipping/shindo');

		$json = $this->model_shipping_shindo->getCities($this->request->get['province_id']);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install() {
		if ($this->user->hasPermission('modify', 'extension/shipping')) {
			$this->load->model('shipping/shindo');

			$this->model_shipping_shindo->install();
		}
	}

	public function uninstall() {
		if ($this->user->hasPermission('modify', 'extension/shipping')) {
			$this->load->model('shipping/shindo');

			$this->model_shipping_shindo->uninstall();
		}
	}

}
