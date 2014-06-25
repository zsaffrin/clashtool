<?php

	// Load primary config settings
	require './app/lib/config.php';

	// Load base Controller class
	require './app/controllers/controller.php';

	// Load and initialize controller Router
	require './app/lib/router.php';
	$router = new Router();

?>
