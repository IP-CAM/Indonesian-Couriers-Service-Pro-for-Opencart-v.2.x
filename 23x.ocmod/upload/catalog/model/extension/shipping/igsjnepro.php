<?php
ini_set('display_errors',1);
class ModelExtensionShippingIgsjnepro extends Model {
	function getQuote($address) {
		$classname = str_replace('vq2-catalog_model_shipping_', '', basename(__FILE__, '.php'));
		$this->load->language('extension/shipping/' . $classname);
		$title = $this->language->get('text_title');
		$days = $this->language->get('text_days');
		$error_currency = $this->language->get('error_currency');
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('flat_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('flat_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();
			$shipping_weight = $this->cart->getWeight();
			$from = $this->config->get('config_weight_class_id');
			$to = $this->config->get($classname . '_weight_class_id');

			$shipping_weight = str_replace(',','',$this->weight->convert($shipping_weight, $from, $to));
			//weight not allowed 0
			if ($shipping_weight == 0) {
				$shipping_weight = 1;
			}
			$hf = 0;
			if ($this->config->get($classname . '_handling')) {
					$hf = $this->config->get($classname . '_handling');
			}
			//check IDR currency
			$this->load->model('localisation/currency');
			$curr = $this->model_localisation_currency->getCurrencyByCode('IDR');
			if (!$curr) {
				$method_data = array(
					'code'       => $classname,
					'title'      => $title,
					'quote'      => array(),
					'sort_order' => $this->config->get($classname . '_sort_order'),
					'error'      => $error_currency
				);
				return $method_data;
			}
			$origin_id = $this->config->get('shindopro_city_id');
			$destId = $address['district_id'];
			if ($address['subdistrict_id']) {
					$destId = $address['subdistrict_id'];
					$destType = 'subdistrict';
			}
			$key = $this->config->get('shindopro_apikey');
			if (isset($destType)) {
				$json = $this->getCost($origin_id, $destId, $shipping_weight, $key, $destType);
			} else {
				$json = $this->getCost($origin_id, $destId, $shipping_weight, $key);
			}
			$quote_data = array();
			if (isset($json['rajaongkir']) && isset($json['rajaongkir']['results']) && isset($json['rajaongkir']['results'][0]) && isset($json['rajaongkir']['results'][0]['costs'])) {
				foreach ($json['rajaongkir']['results'][0]['costs'] as $res) {
					$stat = false;
					foreach ($this->config->get($classname. '_service') as $s) {
						if ($s == $res['service']) {
							$stat = true;
							//break;
						}
					}
					if ($stat) {
						$cost = $res['cost'][0]['value'];
						if ($this->config->get($classname . '_handlingmode') == 2) {
							$cost = $cost + ($hf * ($shipping_weight/1000));
						} else {
							$cost = $cost + $hf;
						}

						if ($this->config->get('config_currency') <>'IDR') {
							$cost = $cost / $curr['value'];
						}
						$etd = '';
						if ($res['cost'][0]['etd'] <> '') {
							$etd =  ($res['cost'][0]['etd'] === '1-1' ? '1' : $res['cost'][0]['etd']) . ' '. $days . ' ';
						}
						$quote_data[$res['service']] = array(
							'code'         => $classname . '.' . $res['service'],
							'title'        => 'JNE - ' . $res['service'],// . $etd,
							'cost'         => $cost,
							'tax_class_id' => $this->config->get($classname.'_tax_class_id'),
							'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get($classname.'_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']),
							//'etd'					=> $etd
						);
						if ($etd <> '') {
							$quote_data[$res['service']]['etd'] = $etd;
						}
					}
				}
				$method_data = array(
					'code'       => $classname,
					'title'      => $title,
					'quote'      => $quote_data,
					'sort_order' => $this->config->get($classname . '_sort_order'),
					'error'      => false
				);
			} else {
				if (isset($json['rajaongkir']['status']['description']) ) {
					$method_data = array(
						'code'       => $classname,
						'title'      => $title,
						'quote'      => array(),
						'sort_order' => $this->config->get($classname . '_sort_order'),
						'error'      => $json['rajaongkir']['status']['description']
					);

				}
			}
		}
		return $method_data;
	}

	public function getCost($origin, $destination, $weight, $key, $destType='city') {
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://pro.rajaongkir.com/api/cost",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "origin=" . (int)$origin . "&originType=city&destination=" . (int)$destination . "&destinationType=" . $destType . "&weight=" . (int)$weight ."&courier=jne",
		  CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded",
		    "key: " . $key,
		  ),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return "cURL Error #:" . $err;
		} else {
			return json_decode($response, true);
		}
	}
}
