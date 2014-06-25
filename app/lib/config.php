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
	define('URL', 'http://10.0.0.2/~zach/clashtool/');

	/**
	 * 	Database credentials
	 */
	define('DB_TYPE', 'mysql');
	define('DB_HOST', 'zsdb.zachsaffrin.com');
	define('DB_NAME', 'clashtool');
	define('DB_USER', 'doodadippity');
	define('DB_PASS', 'NBS81okY9O91wg');

?>
