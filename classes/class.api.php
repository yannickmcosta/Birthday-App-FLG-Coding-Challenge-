<?php
	class api {
		private	$dbHandler	=	NULL;

		public	$error		=	NULL;
		public	$data		=	NULL;
		
		public function __construct() {
			try {
				// Instatiate a database connection when the class is constructed
				$this->dbHandler	=	new DBConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
			} catch (Exception $e) {
				throw $e;
			}
		}

		// Date validation function, taken from PHP.net
		// https://www.php.net/manual/en/function.checkdate.php#113205

		private function validateDate($date, $format = 'Y-m-d H:i:s') {
			$dateTime = DateTime::createFromFormat($format, $date);
			return $dateTime && $dateTime->format($format) == $date;
		}
		
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
		
		/*
			api::get
			- limit		(optional) (int)	=	Defines the limit to the number of records to retrieve
			- offset	(optional) (int)	=	Defines the offset record number to start on
		*/
		
		public function get($limit = 100, $offset = 0, $today = NULL) {
			try {
				// Check first to see if these have been set to empty by the calling script,
				// and then change them back to the defaults if so
				if (empty($limit) || !isset($offset)) {
					$limit	=	100;
					$offset	=	0;
				} else {
					// If the limit is not numeric, the user has made a bad request
					// bad user, go back and try again with a number
					if (!is_numeric($limit)) {
						$this->error	=	[
							"error_description"	=>	"Non numeric limit supplied",
							"error_code"		=>	"NON_NUMERIC_LIMIT"
						];
						throw new Exception ("Bad Request", 400);
					}
					// If the offset is not numeric, the user has made a bad request again
					// very bad user, go back again, and try again, with a number this time
					if (!is_numeric($offset)) {
						$this->error	=	[
							"error_description"	=>	"Non numeric offset supplied",
							"error_code"		=>	"NON_NUMERIC_OFFSET"
						];
						throw new Exception ("Bad Request", 400);
					}
				}
				
				// Do some final bits of sanitisation to the numbers to ensure they aren't floats
				$limit	=	filter_var($limit, FILTER_SANITIZE_NUMBER_INT);
				$offset	=	filter_var($offset, FILTER_SANITIZE_NUMBER_INT);
				
				
				if ($today === TRUE) {
					// Check if the user has requested only today's birthdays
					// Request the data from the SQL database, using the defined limits and offsets
					$results	=	$this->dbHandler->query("SELECT `id`, `user_name`, `user_dob`, `added`, `is_public`, TIMESTAMPDIFF(YEAR,`user_dob`,CURDATE()) AS `age` FROM `birthdays` WHERE MONTH(`user_dob`) = MONTH(CURRENT_TIMESTAMP) AND DAY(`user_dob`) = DAY(CURRENT_TIMESTAMP) LIMIT ?, ?", $offset, $limit);
				} else if ($today === FALSE) {
					// If not, get everyone who isn't today
					// Request the data from the SQL database, using the defined limits and offsets
					$results	=	$this->dbHandler->query("SELECT `id`, `user_name`, `user_dob`, `added`, `is_public`, TIMESTAMPDIFF(YEAR,`user_dob`,CURDATE()) AS `age` FROM `birthdays` WHERE MONTH(user_dob) != MONTH(CURRENT_TIMESTAMP) AND DAY(user_dob) != DAY(CURRENT_TIMESTAMP)LIMIT ?, ?", $offset, $limit);
				} else {
					// If not, get everyone
					// Request the data from the SQL database, using the defined limits and offsets
					$results	=	$this->dbHandler->query("SELECT `id`, `user_name`, `user_dob`, `added`, `is_public`, TIMESTAMPDIFF(YEAR,`user_dob`,CURDATE()) AS `age` FROM `birthdays` WHERE MONTH(user_dob) != MONTH(CURRENT_TIMESTAMP) AND DAY(user_dob) != DAY(CURRENT_TIMESTAMP)LIMIT ?, ?", $offset, $limit);
				}
				
				// If there are no results, this function will return FALSE, and the API
				// will subsequently return an HTTP/2 204 No Content based on this
				if (count($results) == 0) {
					return FALSE;
				} else {
					// As the result list is above 0 count, let's get those results in an array
					$this->data	=	array();
					foreach ($results as $row) {
						// Iterate through each results from the database, assign it to a key
						// then push it into the data array
						$line['id']					=	$row[0];
						$line['user_name']			=	$row[1];
						$line['user_dob']			=	$row[2];
						$line['user_dob_formatted']	=	date("jS F Y", strtotime($row[2]));
						$line['added']				=	$row[3];
						$line['is_public']			=	$row[4];
						$line['time_until']			=	$this->betweenDates($row[2], date("Y-m-d"));
						$line['age']				=	$row[5];
						
						array_push($this->data, $line);
					}
					// Encode the data array as a json string, then return TRUE to the calling function
					// at which point you can be reasonably sure that the data variable will contain a JSON string
					$this->data	=	json_encode($this->data);
					return TRUE;
				}
			} catch (Exception $e) {
				// No fancy exception handling here, just re-throw what we already have
				// You could do some advanced error logging here or return something if that's your thing
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
				
				$postData['user_name']	=	preg_replace('/[^A-Za-z0-9\ \-\.\']/','',$postData['user_name']);
				$postData['user_dob']	=	preg_replace('/[^0-9\-\/]/','',$postData['user_dob']);
				
				if (!$this->validateDate($postData['user_dob'], "Y-m-d")) {
					$this->error	=	[
						"error_description"	=>	"An invalid datestring was supplied",
						"error_code"		=>	"POST_DATA_BAD_DATE_PROVIDED"
					];
					throw new Exception ("Bad Request", 400);
				}
				
				$postData['user_dob']	=	date("Y-m-d", strtotime($postData['user_dob']));
				
				if ($postData['user_dob'] == "1970-01-01") {
					// If the user's DOB at this stage is the unix epoch,
					// for development purposes, flag it as it could well
					// be a bad date validation and it's been defaulted by PHP
					
					// Those with birthdays on this date, my god have you upset some DBAs
				}
				
				// Insert this record into the SQL Database, if the provided data is already
				// there, a unique key exception will be thrown (Code 1062), this can be caught 
				// and handled appropriately, with an error returned to the user for example, in
				// this instance, we will update the existing record.
				$result	=	$this->dbHandler->query("INSERT INTO `birthdays` SET `user_name` = ?, `user_dob` = ? ON DUPLICATE KEY UPDATE `user_name` = VALUES (`user_name`), `user_dob` = VALUES (`user_dob`)", $postData['user_name'], $postData['user_dob']);
				
				
				if (!$result['affectedRows'] >= 1) {
					// Check to see that more than 1 row was affected in the database, if this is
					// not the case, then the SQL didn't execute sucessfully, and this will need
					// to be relayed back to the user
					$this->error	=	[
						"error_description"	=>	"Unable to insert row into database.",
						"error_code"		=>	"DB_AFFECTED_ROWS_NOT_GT1"
					];
					throw new Exception ("Internal Server Error", 500);
				} else {
					// As all has gone well, return TRUE
					return TRUE;
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
				
				// Little bit of sanitisation to make sure the entry_id variable is an int
				$entry_id	=	filter_var($entry_id, FILTER_SANITIZE_NUMBER_INT);
				
				// Run the SQL to delete the record, LIMITing the amount it can do by 1, just for sanity's sake
				$result	=	$this->dbHandler->query("DELETE FROM `birthdays` WHERE `id` = ? LIMIT 1", $entry_id);
				
				if (!$result['affectedRows'] >= 1) {
					// Check to see that more than 1 row was affected in the database, if this is
					// not the case, then the SQL didn't execute sucessfully, and this will need
					// to be relayed back to the user. As we've set a LIMIT 1 (I don't trust deletes!)
					// this should be the case when this query executes sucessfully.
					$this->error	=	[
						"error_description"	=>	"Unable to delete row from database.",
						"error_code"		=>	"DB_AFFECTED_ROWS_NOT_DEL1"
					];
					throw new Exception ("Internal Server Error", 500);
				} else {
					// As all has gone well, return TRUE
					return TRUE;
				}
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
	}