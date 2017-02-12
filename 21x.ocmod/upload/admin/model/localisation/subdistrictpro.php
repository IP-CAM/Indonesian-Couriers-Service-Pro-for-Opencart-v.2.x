<?php
class ModelLocalisationSubdistrictpro extends Model {
		public function getSubdistricts($city_id) {
			$apikey = $this->config->get('shindopro_apikey');
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'http://pro.rajaongkir.com/api/subdistrict?city=' . $city_id,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "content-type: application/x-www-form-urlencoded",
					"key: ".$apikey
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

		public function getSubdistrict($subdistrict_id) {
				$apikey = $this->config->get('shindopro_apikey');
				$curl = curl_init();
				curl_setopt_array($curl, array(
				CURLOPT_URL => 'http://pro.rajaongkir.com/api/subdistrict?id=' . $subdistrict_id,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "content-type: application/x-www-form-urlencoded",
					"key: ".$apikey
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
