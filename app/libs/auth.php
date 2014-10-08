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
			header('Location: '.URL.'login');
		}
	}

	/**
	 * Check user level. Redirects to home URL if user is below minimum level
	 * @param string $level 	The minimum level for access to the given path
     * @return boolean
	 */
	public static function checkLevel($level) {
		// Start the session
		Session::start();

		// Return true if user is at or above required level
		if (isset($_SESSION['user_level'])
			AND $_SESSION['user_level'] >= $level) {
			return true;
		} else {
			header('Location: '.URL);
			return false;
		}
	}

}

?>