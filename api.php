<?php
	require(__DIR__ . "/config/config.php");
	require(APP_ROOT . "/classes/class.DBConnection.php");
	require(APP_ROOT . "/classes/class.api.php");
	
	try {
		if ($_SERVER['REQUEST_METHOD'] == "GET") {
			// GET CODE
			$api	=	new api();
			$limit	=	(isset($_GET['limit']) ? $_GET['limit'] : 100);
			$offset	=	(isset($_GET['offset']) ? $_GET['offset'] : 0);
			
			if ($api->get($limit, $offset)) {
				header("HTTP/2 200 OK");
				header("Content-type: application/json");
				echo $api->data;
			} else {
				header("HTTP/2 204 No Content");
			}
		} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
			// POST CODE
			$api	=	new api();
			if ($api->set($_POST)) {
				header("HTTP/2 201 Created");
			} else {
				header("HTTP/2 500 Internal Server Error");
			}
		} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
			// DELETE CODE
			header("HTTP/2 200 OK");
		} else {
			$error	=	[
				"error_description"	=>	"Invalid Request Method",
				"error_code"		=>	"BAD_REQUEST_METHOD"
			];
			throw new Exception ("Method Not Allowed", 405);
		}
	} catch (Exception $e) {
		error_log($e);
		header("HTTP/2 " .  $e->getCode() . " " . $e->getMessage());
		header("Content-type: application/json");
		echo json_encode($api->error);
	}