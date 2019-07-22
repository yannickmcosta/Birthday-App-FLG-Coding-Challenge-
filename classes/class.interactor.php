<?php
	class interactor {
		public static function betweenDates($date1, $date2, $format = "%m months, %d days") {
			$date1	=	date("jS F", strtotime($date1));
			$date2	=	date("jS F", strtotime($date2));
			
			if ($date1 == $date2) {
				return "Happy Birthday! 🎂";
			} else {
				$date1 = new DateTime($date1);
				$date2 = new DateTime($date2);
				
				$interval = $date2->diff($date1);
				return $interval->format($format);
			}
		}
		
		public function get() {
			try {

				$curlRes = curl_init();
				curl_setopt($curlRes, CURLOPT_URL, API_ENDPOINT);
				curl_setopt($curlRes, CURLOPT_CUSTOMREQUEST, 'GET');
				curl_setopt($curlRes, CURLOPT_RETURNTRANSFER, 1);

				$response = curl_exec($curlRes);
				
				if (!$response) {
					error_log("Interactor Error Occurred: Code: " . curl_errno($curlRes) . " Error: " . curl_error($curlRes));
					curl_close($curlRes);
					throw new Exception ("Error occurred retrieving data from API, please check the logs", 500);
				} else {
					return $response;
				}

			} catch (Exception $e) {
				throw $e;
			}
		}
	}