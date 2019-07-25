<?php
	class interactor {
		public static function betweenDates($date1, $date2, $format = "%m months, %d days") {
			// Remove the year from the date
			$date1	=	date("jS F", strtotime($date1));
			$date2	=	date("jS F", strtotime($date2));
			
			if ($date1 == $date2) {
				// If the date is today, wish the user happy bithday
				return "Happy Birthday! 🎂";
			} else {
				// If not, calculate the difference between the two dates
				$date1 = new DateTime($date1);
				$date2 = new DateTime($date2);
				
				$interval = $date2->diff($date1);
				return $interval->format($format);
			}
		}
		
		public function get() {
			try {

				// Initialise curl
				$curlRes = curl_init();
				
				// Set the API Endpoint
				curl_setopt($curlRes, CURLOPT_URL, API_ENDPOINT);
				
				// Set the request method
				curl_setopt($curlRes, CURLOPT_CUSTOMREQUEST, 'GET');
				
				// Return the raw response
				curl_setopt($curlRes, CURLOPT_RETURNTRANSFER, 1);

				// Execute the curl request and assign it to $response
				$response = curl_exec($curlRes);
				
				if (!$response) {
					// If the response isn't set, throw an error
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
				
				// Initialise curl
				$curlRes = curl_init();
				
				// Set the API Endpoint
				curl_setopt($curlRes, CURLOPT_URL, API_ENDPOINT);
				
				// Set the request method
				curl_setopt($curlRes, CURLOPT_CUSTOMREQUEST, 'POST');
				
				// Return the raw response
				curl_setopt($curlRes, CURLOPT_RETURNTRANSFER, 1);
				
				// Set some HTTP Headers
				curl_setopt($curlRes, CURLOPT_HTTPHEADER, [
					'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
				]);
				
				// Build the HTTP POST query from the post data and set the variables
				$body = http_build_query($postData);
				curl_setopt($curlRes, CURLOPT_POST, 1);
				curl_setopt($curlRes, CURLOPT_POSTFIELDS, $body);
				
				// Execute the curl request and assign the result to $response
				$response = curl_exec($curlRes);
				
				// Check the response code, if it's 201, that was a good request, then return true
				$httpResponseCode	=	curl_getinfo($curlRes, CURLINFO_HTTP_CODE);
				if ($httpResponseCode == 201) {
					// Close the curl resource to free up resources
					curl_close($curlRes);
					return TRUE;
				} else {
					// Close the curl resource to free up resources
					curl_close($curlRes);
					return FALSE;
				}
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		public function remove($entry_id) {
			try {
				if (!isset($entry_id)) {
					// The entry_id variable should be set in order to call this function
					// and in order for the function to operate. If it's not set, tell the
					// user about it
					$this->error	=	[
						"error_description"	=>	"No Entry ID value provided",
						"error_code"		=>	"ENTRY_ID_MISSING"
					];
					throw new Exception ("Bad Request", 400);
				}
				
				if (!is_numeric($entry_id)) {
					// The entry_id variable should always be numeric as it refers to the
					// auto_increment value of the record in the database. In a perfect world,
					// this would be a UUID, as to prevent a user iterating through your API
					// and ruining your day. Something that would be done as an advancement of course
					$this->error	=	[
						"error_description"	=>	"Provided Entry ID is not valid",
						"error_code"		=>	"ENTRY_ID_NON_NUMERIC"
					];
					throw new Exception ("Bad Request", 400);
				}
				
				
				// Initialise curl
				$curlRes = curl_init();
				
				// Set the API Endpoint
				curl_setopt($curlRes, CURLOPT_URL, API_ENDPOINT);
				
				// Set the request method
				curl_setopt($curlRes, CURLOPT_CUSTOMREQUEST, 'DELETE');
				
				// Return the raw response
				curl_setopt($curlRes, CURLOPT_RETURNTRANSFER, 1);
				
				// Set some HTTP Headers
				curl_setopt($curlRes, CURLOPT_HTTPHEADER, [
					'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
				]);
				
				// Build the HTTP query from the post data and set the variables
				$body = [
					'entry_id' => $entry_id,
				];
				$body = http_build_query($body);
				curl_setopt($curlRes, CURLOPT_POST, 1);
				curl_setopt($curlRes, CURLOPT_POSTFIELDS, $body);
				
				// Execute the curl request and assign the response to $response
				$response = curl_exec($curlRes);
				
				// If the API returned a 204, that was a good request
				if (curl_getinfo($curlRes, CURLINFO_HTTP_CODE) == 204) {
					// Close the curl resource to clean up
					curl_close($curlRes);
					return TRUE;
				} else {
					curl_close($curlRes);
					return FALSE;
				}

			} catch (Exception $e) {
				throw $e;
			}
		}
	}