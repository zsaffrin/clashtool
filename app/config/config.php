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
	date_default_timezone_set('America/New_York');

	/**
	 * 	Default root path
	 * 	Set to localhost path while in Development mode
	 */
	define('URL', 'http://localhost:8888/clashtool/');

	/**
	 * 	Administrative Contacts
	 */
	define('SYS_ADMIN_EMAIL_ADDRESS', 'awesomezach@gmail.com');

	/**
	 * 	Folder locations
	 **/
	define('LIBS_PATH', 'app/libs/');
	define('CONTROLLERS_PATH', 'app/controllers/');
	define('MODELS_PATH', 'app/models/');
	define('VIEWS_PATH', 'app/views/');
	define('HELPERS_PATH', 'app/helpers/');
	define('IMAGES_PATH', 'public/img/');

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
	define('EMAIL_NEW_USER_NOTIFICATION_FROM_EMAIL', 'no-reply@zachsaffrin.com');
	define('EMAIL_NEW_USER_NOTIFICATION_FROM_NAME', 'ClashTool');
	define('EMAIL_NEW_USER_NOTIFICATION_SUBJECT', 'New User Signup Notification');
	define('EMAIL_NEW_USER_NOTIFICATION_CONTENT', '<p>A new user signup request has been received for email address ');
	define('EMAIL_NEW_USER_NOTIFICATION_CONTENT_CLOSE', '. <p>Please log in to Activate this account.');

	define('EMAIL_ADMIN_INVITE_FROM_EMAIL', 'no-reply@zachsaffrin.com');
	define('EMAIL_ADMIN_INVITE_FROM_NAME', 'ClashTool');
	define('EMAIL_ADMIN_INVITE_SUBJECT', 'You have been invited to use ClashTool');
	define('EMAIL_ADMIN_INVITE_CONTENT_A', '	<p>I would like to invite you to check out my ClashTool app, a planning toolkit for playing Clash of Clans.
											<p>If you don&apos;t know me or don&apos;t want to use this tool, you can simply ignore this email.
											<p>If you&apos;re interested, though, click this link to log in and get started!<br>'.URL.'
											<p>Log in with email address <b>');
	define('EMAIL_ADMIN_INVITE_CONTENT_B', '</b><br>Your temporary password is: <b>');
	define('EMAIL_ADMIN_INVITE_CONTENT_C', '</b>
												<p>Thanks!
												<p><i>-- Zach</i>');

	define('EMAIL_VERIFICATION_FROM_EMAIL', 'no-reply@zachsaffrin.com');
	define('EMAIL_VERIFICATION_FROM_NAME', 'ClashTool');
	define('EMAIL_VERIFICATION_SUBJECT', 'Email verification for your ClashTool account');
	define('EMAIL_VERIFICATION_CONTENT', '	<p>Hey there
											<p>Terribly sorry to bug you, but we need you to verify the email address for your account to unlock it. 
											This will happen when you sign up for an account, when you change your email address associated with the account, 
											or as required from time to time by an administrator.
											<p>Please click this link to verify your email address: ');
	define('EMAIL_VERIFICATION_CONTENT_CLOSE', '<p>Thanks!
												<p><i>-- Zach</i>');

	define('EMAIL_CHANGE_VERIFICATION_FROM_EMAIL', 'no-reply@zachsaffrin.com');
	define('EMAIL_CHANGE_VERIFICATION_FROM_NAME', 'ClashTool');
	define('EMAIL_CHANGE_VERIFICATION_SUBJECT', 'Email verification for your ClashTool account');
	define('EMAIL_CHANGE_VERIFICATION_CONTENT', '	<p>Hey there
													<p>Looks like you changed the email address for your ClashTool account to this one. 
													<p>Please click this link to verify the change: ');
	define('EMAIL_CHANGE_VERIFICATION_CONTENT_CLOSE', '<p>Thanks!
												<p><i>-- Zach</i>');

	define('EMAIL_PASSWORD_RECOVERY_FROM_EMAIL', 'no-reply@zachsaffrin.com');
	define('EMAIL_PASSWORD_RECOVERY_FROM_NAME', 'ClashTool');
	define('EMAIL_PASSWORD_RECOVERY_SUBJECT', 'Password Recovery for your ClashTool account');
	define('EMAIL_PASSWORD_RECOVERY_CONTENT', '	<p>Hey there
												<p>Word is you lost or forgot your password. That&apos;s a real bummer. Let me help you out.
												<p>Click this link to reset your password and you&apos;ll be good to go:<br>');
	define('EMAIL_PASSWORD_RECOVERY_CONTENT_CLOSE', '	<p>If you didn&apos;t initiate this password recovery or did so in error, or for whatever reason you don&apos;t want to change your password, simply ignore this email.
														<p>Thanks!
														<p><i>-- Zach</i>');

	/**
	 * 	Error messages
	 **/
	define('ERROR_ACCESS_DENIED', 'Access denied');
	define('ERROR_LOGIN_FAILED', 'Login failed');
	define('ERROR_EMAIL_FIELD_EMPTY', 'Email field was empty');
	define('ERROR_EMAIL_FIELD_EMPTY_ALT', 'Gotta at least have a valid email. Life is kinda tough, I know.');
	define('ERROR_PASSWORD_FIELD_EMPTY', 'Password field was empty');
	define('ERROR_SIGNUP_FAILED', 'Signup failed');
	define('ERROR_NEW_PASSWORD_FIELD_EMPTY', 'New Password field was empty');
	define('ERROR_NEW_PASSWORD_CONFIRM_FIELD_EMPTY', 'Please confirm your new Password');
	define('ERROR_PASSWORD_WRONG', 'Incorrect Password');
	define('ERROR_PASSWORD_CONFIRM_WRONG', 'New Password and Confirmation do not match. Please try again.');
	define('ERROR_NEW_PASSWORD_TOO_SHORT', 'New Password too short. Please use at least 6 characters.');
	define('ERROR_PASSWORD_NOT_UNIQUE', 'New Password cannot be the same as the old one. Please try another.');
	define('ERROR_EMAIL_TAKEN', 'An account for this email address already exists');
	define('ERROR_USER_UPDATE_FAILED', 'User record update failed');
	define('ERROR_USER_NOT_FOUND', 'User not found');
	define('ERROR_USER_STATUS_UPDATE_FAILED', 'User status change failed');
	define('ERROR_USER_ACCOUNT_PENDING', 'Signup request is still pending. You will be notified by email once your account is ready.');
	define('ERROR_USER_ACCOUNT_LOCKED', 'Account locked. Please contact me to unlock.');
	define('ERROR_EMAIL_VERIFICATION_FAILED', 'Email verification failed');
	define('ERROR_EMAIL_ALREADY_VERIFIED', 'This email address is already verified for this account');
	define('ERROR_EMAIL_VERIFICATION_INACTIVE', 'This email verification link is no longer active');
	define('ERROR_EMAIL_CODE_MISMATCH', 'Verification failed - This verification email is expired');
	define('ERROR_DB_UPDATE_FAILED', 'Database update failed');
	define('ERROR_RECOVERY_UNVERIFIED_EMAIL', 'Password recovery is not available for this account because the email address is not verified. Please contact me to resolve.');
	define('ERROR_RECOVERY_CODE_INVALID', 'This password recovery link is no longer valid');
	define('ERROR_MAX_WALL_COUNT_EXCEEDED', 'You have entered more walls than you are allowed at your level. Please re-check your numbers.');

	/**
	 * 	Success messages
	 **/
	define('SUCCESS_USER_LOCKED', 'User locked');
	define('SUCCESS_USER_UNLOCKED', 'User unlocked');
	define('SUCCESS_USER_DELETED', 'User deleted');
	define('SUCCESS_USER_CREATED', 'New user created');
	define('SUCCESS_USER_INFO_UPDATED', 'User profile updated');
	define('SUCCESS_USER_BUILDINGS_SAVED', 'Building levels saved');
	define('SUCCESS_USER_TROOPS_SAVED', 'Troop levels saved');
	define('SUCCESS_EMAIL_VERIFICATION_TRIGGERED', 'User email verification triggered');
	define('SUCCESS_EMAIL_VERIFICATION_SENT', 'Email verification link sent successfully.');
	define('SUCCESS_EMAIL_VERIFIED', 'Email verified successfully');
	define('SUCCESS_PASSWORD_UPDATED', 'Password updated');

?>
