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
					error_log("Interactor Error Occurred (GET): Code: " . curl_errno($curlRes) . " Error: " . curl_error($curlRes));
					curl_close($curlRes);
					throw new Exception ("Error occurred retrieving data from API, please check the logs", 500);
				} else {
					curl_close($curlRes);
					return $response;
				}

			} catch (Exception $e) {
				throw $e;
			}
		}
		
		public function set($postData) {
			try {
				if (!is_array($postData)) {
					// The postData variable should be an array submitted from an HTML Form
					// if it turns out it's not an array, stop here and tell the user that's a bad request
					$this->error	=	[
						"error_description"	=>	"Supplied data is not an array",
						"error_code"		=>	"POST_DATA_NOT_ARRAY"
					];
					throw new Exception ("Bad Request", 400);
				}
				
				if (!isset($postData['user_name']) || empty($postData['user_name'])) {
					// Check that a key for user_name exists and is not empty
					// If not, that's a bad request and we are going to reject that
					$this->error	=	[
						"error_description"	=>	"No user name was supplied",
						"error_code"		=>	"POST_DATA_NO_USER_NAME"
					];
					throw new Exception ("Bad Request", 400);
				}
				if (!isset($postData['user_dob']) || empty($postData['user_dob'])) {
					// Check for a key for user_dob exits and is not empty
					// If not, that's again a bad request and we're going to let the user know that
					$this->error	=	[
						"error_description"	=>	"No user date of birth was supplied",
						"error_code"		=>	"POST_DATA_NO_USER_DOB"
					];
					throw new Exception ("Bad Request", 400);
				}
				
				$ch = curl_init();
				curl_setopt($curlRes, CURLOPT_URL, API_ENDPOINT);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
					'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
				]);
				
				$body = http_build_query($postDatas);
				
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
				
				$response = curl_exec($ch);
				
				if (!$response) {
					error_log("Interactor Error Occurred (SET): Code: " . curl_errno($curlRes) . " Error: " . curl_error($curlRes));
					curl_close($curlRes);
					throw new Exception ("Error occurred retrieving data from API, please check the logs", 500);
				} else {
					$httpResponseCode	=	curl_getinfo($ch, CURLINFO_HTTP_CODE);
					if ($http_response_header == 201) {
						return TRUE;
					} else {
						
						return FALSE;
					}
				}
				
				curl_close($ch);
			} catch (Exception $e) {
				throw $e;
			}
		}
	}