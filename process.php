<?php
	require(__DIR__ . "/config/config.php");
	require(APP_ROOT . "/classes/class.interactor.php");
	
	if (isset($_POST) && !empty($_POST)) {
		$interactor	=	new interactor();
		if ($interactor->set($_POST)) {
			header("Location: " . DOMAIN_URI_ROOT . "?created");
		} else {
			header("Location: " . DOMAIN_URI_ROOT . "?notCreated");
		}
	} else if (isset($_GET) && !empty($_GET)) {
		$interactor	=	new interactor();
		if ($interactor->remove($_GET['delete'])) {
			header("Location: " . DOMAIN_URI_ROOT . "?removed");
		} else {
			header("Location: " . DOMAIN_URI_ROOT . "?notRemoved");
		}
	} else {
		header("Location: " . DOMAIN_URI_ROOT . "?badRequest");
	}
