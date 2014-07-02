<?php

	/**
	 * 	Error reporting
	 * 	Currently showing All Errors while in Development mode
	 */
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	/**
	 * 	Default root path
	 * 	Set to localhost path while in Development mode
	 */
	define('URL', 'http://localhost:8888/clashtool/');

	/**
	 * 	Folder locations
	 **/
	define('LIBS_PATH', 'app/libs/');
	define('CONTROLLERS_PATH', 'app/controllers/');
	define('MODELS_PATH', 'app/models/');
	define('VIEWS_PATH', 'app/views/');

	/**
	 * 	Hashing strength for password processing
	 **/
	define('HASH_COST_FACTOR', 10);

	/**
	 * 	Database credentials
	 */
	define('DB_TYPE', 'mysql');
	define('DB_HOST', 'zsdb.zachsaffrin.com');
	define('DB_NAME', 'clashtool');
	define('DB_USER', 'doodadippity');
	define('DB_PASS', 'NBS81okY9O91wg');

	/**
	 * 	Error messages
	 **/
	define('ERROR_LOGIN_FIELDS_EMPTY', 'Missing Username or Password');
	define('ERROR_INVALID_LOGIN', 'Login failed - Username or Password incorrect');

?>
