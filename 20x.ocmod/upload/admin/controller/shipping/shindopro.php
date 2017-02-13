<?php
class ControllerShippingShindopro extends Controller {
	private $error = array();
	public function index() {
		$this->load->language('shipping/shindopro');
		$this->load->model('shipping/shindopro');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$mod = array('igsjnepro','igspospro','igstikipro', 'igswahanapro', 'igsjntpro');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shindopro', $this->request->post);
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
		$data['entry_subdistrict'] = $this->language->get('entry_subdistrict');
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/shindopro', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('shipping/shindopro', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'] . '&type=shipping', true);


		$data['provinces'] = $this->model_shipping_shindopro->getProvinces();
		if (isset($this->request->post['shindopro_province_id'])) {
			$data['shindopro_province_id'] = $this->request->post['shindopro_province_id'];
		} else {
			$data['shindopro_province_id'] = $this->config->get('shindopro_province_id');
		}

		if (isset($this->request->post['shindopro_city_id'])) {
			$data['shindopro_city_id'] = $this->request->post['shindopro_city_id'];
		} else {
			$data['shindopro_city_id'] = $this->config->get('shindopro_city_id');
		}

		if (isset($this->request->post['shindopro_subdistrict_id'])) {
			$data['shindopro_subdistrict_id'] = $this->request->post['shindopro_subdistrict_id'];
		} else {
			$data['shindopro_subdistrict_id'] = $this->config->get('shindopro_subdistrict_id');
		}

		if (isset($this->request->post['shindopro_apikey'])) {
			$data['shindopro_apikey'] = $this->request->post['shindopro_apikey'];
		} else {
			$data['shindopro_apikey'] = $this->config->get('shindopro_apikey');
		}
		if (isset($this->request->post['shindopro_status'])) {
			$data['shindopro_status'] = $this->request->post['shindopro_status'];
		} else {
			$data['shindopro_status'] = $this->config->get('shindopro_status');
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
		$data['igsjnepro_services'] = array();
		$data['igspospro_services'] = array();
		$data['igstikipro_services'] = array();
		$data['igswahanapro_services'] = array();
		$data['igsjntpro_services'] = array();

		$data['igsjnepro_services'][] = array(
			'text'  => 'Ongkos Kirim Ekonomis',
			'value' => 'OKE'
		);
		$data['igsjnepro_services'][] = array(
			'text'  => 'Layanan Reguler',
			'value' => 'REG'
		);
		$data['igsjnepro_services'][] = array(
			'text'  => 'Yakin Esok Sampai',
			'value' => 'YES'
		);
		$data['igsjnepro_services'][] = array(
			'text'  => 'JNE Trucking',
			'value' => 'JTR'
		);
		$data['igsjnepro_services'][] = array(
			'text'  => 'Super Speed',
			'value' => 'SPS'
		);
		$data['igsjnepro_services'][] = array(
			'text'  => 'JNE Trucking',
			'value' => 'JTR<150'
		);
		$data['igsjnepro_services'][] = array(
			'text'  => 'JNE Trucking',
			'value' => 'JTR>250'
		);
		$data['igsjnepro_services'][] = array(
			'text'  => 'JNE Trucking',
			'value' => 'JTR250'
		);
		//------
		$data['igspospro_services'][] = array(
			'text'  => 'Surat Kilat Khusus',
			'value' => 'Surat Kilat Khusus'
		);

		$data['igspospro_services'][] = array(
			'text'  => 'Express Next Day',
			'value' => 'Express Next Day'
		);

		$data['igspospro_services'][] = array(
			'text'  => 'Paketpos Biasa',
			'value' => 'Paketpos Biasa'
		);
		$data['igspospro_services'][] = array(
			'text'  => 'Paket Kilat Khusus',
			'value' => 'Paket Kilat Khusus'
		);
		$data['igspospro_services'][] = array(
			'text'  => 'Paket Jumbo Ekonomi',
			'value' => 'Paket Jumbo Ekonomi'
		);

		//----
		$data['igstikipro_services'][] = array(
			'text'  => 'REGULAR SERVICE',
			'value' => 'REG'
		);
		$data['igstikipro_services'][] = array(
			'text'  => 'ECONOMY SERVICE',
			'value' => 'ECO'
		);
		$data['igstikipro_services'][] = array(
			'text'  => 'OVER NIGHT SERVICE',
			'value' => 'ONS'
		);
		$data['igstikipro_services'][] = array(
			'text'  => 'SAMEDAY SERVICE',
			'value' => 'SDS'
		);
		$data['igstikipro_services'][] = array(
			'text'  => 'HOLIDAY SERVICE',
			'value' => 'HDS'
		);
		//----
		$data['igswahanapro_services'][] = array(
			'text'  => 'Domestic Express Service',
			'value' => 'DES'
		);
		//----
		$data['igsjntpro_services'][] = array(
			'text'  => 'Regular Service',
			'value' => 'EZ'
		);


		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/shindopro.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/shindopro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->request->post['shindopro_apikey']) {
			$this->error['apikey'] = $this->language->get('error_apikey');
		}

		return !$this->error;
	}

	public function cities() {
		$json = array();

		$this->load->model('shipping/shindopro');

		$json = $this->model_shipping_shindopro->getCities($this->request->get['province_id']);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function subdistricts() {
		$json = array();

		$this->load->model('shipping/shindopro');

		$json = $this->model_shipping_shindopro->getSubdistricts($this->request->get['city_id']);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install() {
		if ($this->user->hasPermission('modify', 'extension/shipping')) {
			$this->load->model('shipping/shindopro');

			$this->model_shipping_shindopro->install();
		}
	}

	public function uninstall() {
		if ($this->user->hasPermission('modify', 'extension/shipping')) {
			$this->load->model('shipping/shindopro');

			$this->model_shipping_shindopro->uninstall();
		}
	}

}
