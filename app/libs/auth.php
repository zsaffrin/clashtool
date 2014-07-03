<?php

class Auth {
	
	/**
	 * 	Check if user is logged in
	 * 	Used to protect controllers/methods from public users
	 */
	public static function checkLogin() {
		// Start the session
		Session::start();

		// If user not logged in, redirect to login page
		if (!isset($_SESSION['user_logged_in'])) {
			Session::destroy();
			header('location: '.URL.'login');
		}
	}
}

?>