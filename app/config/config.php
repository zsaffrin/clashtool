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
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'clashtool');
	define('DB_USER', 'root');
	define('DB_PASS', 'mysql');

	/**
	 * 	Error messages
	 **/
	define('ERROR_LOGIN_FIELDS_EMPTY', 'Missing Email or Password');
	define('ERROR_INVALID_LOGIN', 'Login failed - Email or Password incorrect');
	define('ERROR_USER_NOT_FOUND', 'User not found');
	define('ERROR_USER_UPDATE_FAILED', 'User record update failed');
	define('ERROR_EMAIL_FIELD_EMPTY', 'Email field was empty');
	define('ERROR_PASSWORD_FIELD_EMPTY', 'Password field was empty');
	define('ERROR_NEW_PASSWORD_FIELD_EMPTY', 'New Password field was empty');
	define('ERROR_NEW_PASSWORD_CONFIRM_FIELD_EMPTY', 'Please confirm your new Password');
	define('ERROR_PASSWORD_CONFIRM_WRONG', 'New Password and Confirmation do not match. Please try again.');
	define('ERROR_NEW_PASSWORD_TOO_SHORT', 'New Password too short. Please use at least 6 characters.');
	define('ERROR_PASSWORD_WRONG', 'Incorrect Password');
	define('ERROR_PASSWORD_UPDATE_FAILED', 'Password update failed');
	define('ERROR_FAILED_LOGIN_RESET_FAILED', 'Failed Login count reset failed');
	define('ERROR_ARGUMENT_MISSING', 'Argument Missing - ');
	define('ERROR_NO_USERS_FOUND', 'No users found');
	define('ERROR_FIRST_NAME_FIELD_EMPTY', 'First Name field empty');
	define('ERROR_LAST_NAME_FIELD_EMPTY', 'Last Name field empty');
	define('ERROR_EMAIL_TAKEN', 'An account for this email address already exists');
	define('ERROR_USER_CREATION_FAILED', 'User creation failed');
	define('ERROR_NO_BUILDINGS_FOUND', 'No buildings found');
	define('ERROR_NO_TROOPS_FOUND', 'No troops found');

	/**
	 * 	Success messages
	 **/
	define('SUCCESS_USER_INFO_UPDATED', 'User profile updated');
	define('SUCCESS_PASSWORD_UPDATED', 'Password updated');
	define('SUCCESS_USER_CREATED', 'New user created successfully');


?>
