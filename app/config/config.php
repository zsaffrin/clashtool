<?php

	/**
	 * 	Error reporting
	 * 	Currently showing All Errors while in Development mode
	 */
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	/**
	 * 	Set time zone
	 */
	date_default_timezone_set('Etc/UTC');

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
	define('HELPERS_PATH', 'app/helpers/');

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
	 * 	Email credentials
	 */
	define('PHPMAILER_DEBUG_MODE', 0);
	define('EMAIL_USE_SMTP', true);
	define('EMAIL_SMTP_HOST', 'mail.zachsaffrin.com');
	define('EMAIL_SMTP_AUTH', true);
	define('EMAIL_SMTP_USERNAME', 'zach@zachsaffrin.com');
	define('EMAIL_SMTP_PASSWORD', '&33N32MrKM0y1M');
	define('EMAIL_SMTP_PORT', 587);
	define('EMAIL_SMTP_ENCRYPTION', 'tls');

	/**
	 * 	Email info and content templates
	 */
	define('EMAIL_VERIFICATION_FROM_EMAIL', 'no-reply@zachsaffrin.com');
	define('EMAIL_VERIFICATION_FROM_NAME', 'ClashTool');
	define('EMAIL_VERIFICATION_SUBJECT', 'Email verification for your ClashTool account');
	define('EMAIL_VERIFICATION_CONTENT', '	<p>Hey there
											<p>Terribly sorry to bug you, but we need you to verify the email address for your account to prove you own it. 
											Not to worry, no personal details required, just click the link below to confirm.
											<p>Please click this link to verify your email address: ');
	define('EMAIL_VERIFICATION_CONTENT_CLOSE', '<p>Thanks!
												<p><i>-- Zach</i>');

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
	define('ERROR_USER_BUILDING_CLEAR_FAILED', 'Error while saving Building levels - Database preparation failed');
	define('ERROR_USER_BUILDING_INSERT_FAILED', 'Error while saving Building levels - Building record not saved successfully');
	define('ERROR_USER_TROOP_CLEAR_FAILED', 'Error while saving Troop levels - Database preparation failed');
	define('ERROR_USER_TROOP_INSERT_FAILED', 'Error while saving Troop levels - Troop level record not saved successfully');
	define('ERROR_USER_PENDING', 'Signup request is still pending. You will be notified by email once your account is ready.');
	define('ERROR_USER_ACCOUNT_LOCKED', 'Account locked. Please contact me to unlock.');
	define('ERROR_SIGNUP_REQUEST_FAILED', 'Signup request failed');
	define('ERROR_EMAIL_VERIFICATION_FAILED', 'Email verification failed');
	define('ERROR_EMAIL_ALREADY_VERIFIED', 'This email address is already verified for this account');
	define('ERROR_EMAIL_VERIFICATION_INACTIVE', 'This email verification link is no longer active');
	define('ERROR_EMAIL_MISMATCH', 'Verification failed - This is not the email address currently linked to the account.');
	define('ERROR_EMAIL_CODE_MISMATCH', 'Verification failed - This verification email is expired');
	define('ERROR_FLAG_UPDATE_FAILED', 'Error - the flag in the database was not set successfully');
	define('ERROR_EMAIL_SEND_FAILED', 'Email send failed');

	/**
	 * 	Success messages
	 **/
	define('SUCCESS_USER_INFO_UPDATED', 'User profile updated');
	define('SUCCESS_USER_FLAGS_UPDATED', 'User flags updated');
	define('SUCCESS_PASSWORD_UPDATED', 'Password updated');
	define('SUCCESS_USER_CREATED', 'New user created successfully');
	define('SUCCESS_USER_BUILDINGS_SAVED', 'Building levels saved');
	define('SUCCESS_USER_TROOPS_SAVED', 'Troop levels saved');
	define('SUCCESS_EMAIL_VERIFIED', 'Email verified successfully');
	define('SUCCESS_EMAIL_SENT', 'Email sent successfully');

	/**
	 * 	Debug messages
	 **/
	define('DEBUG_EMAIL_VALID', 'Email is valid');


?>
