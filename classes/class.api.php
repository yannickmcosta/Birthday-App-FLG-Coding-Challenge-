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
		
		/*
			api::get
			- limit		(optional) (int)	=	Defines the limit to the number of records to retrieve
			- offset	(optional) (int)	=	Defines the offset record number to start on
		*/
		
		public function get($limit = 100, $offset = 0) {
			try {
				// Check first to see if these have been set to empty by the calling script,
				// and then change them back to the defaults if so
				if (empty($records) || !isset($records)) {
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
					
					// Do some final bits of sanitisation to the numbers to ensure they aren't floats
					$limit	=	filter_var($limit, FILTER_SANITIZE_NUMBER_INT);
					$offset	=	filter_var($offset, FILTER_SANITIZE_NUMBER_INT);
				}
				
				// Request the data from the SQL database, using the defined limits and offsets
				$results	=	$this->dbHandler->query("SELECT `id`, `user_name`, `user_dob`, `added`, `is_public` FROM `birthdays` LIMIT ?, ?", $limit, $offset);
				
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
						$line['id']			=	$row[0];
						$line['user_name']	=	$row[1];
						$line['user_dob']	=	$row[2];
						$line['added']		=	$row[3];
						$line['is_public']	=	$row[4];
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
				
				$postData['user_name']	=	preg_replace('/[^A-Za-z0-9\ \-\.\']/','',$postData['user_name'];
				$postData['user_dob']	=	preg_replace('/[^0-9\-\/]/','',$postData['user_dob'];
				
				$postData['user_dob']	=	date("Y-m-d", strtotime($postData['user_dob']));
				
				if ($postData['user_dob'] == "1970-01-01") {
					// If the user's DOB at this stage is the unix epoch,
					// for development purposes, flag it as it could well
					// be a bad date validation and it's been defaulted by PHP
					
					// Those with birthdays on this date, my god have you upset some DBAs
				}
				
				
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
	}