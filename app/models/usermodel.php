<?php

/**
 * 	USER LEVEL
 * 	1 - Guest/Public (Default)
 * 	2 - Standard User
 * 	3 - Power User
 * 	4 - Administrator	
 */

class userModel {

	/**
	* Shared DB connection must be provided
	* @param object $db A PDO database connection
	*/
	function __construct($db) {
		try {
			$this->db = $db;
		} catch (PDOException $e) {
			exit('Database connection could not be established.');
		}
	}

	/**
	 * Create new user object
	 */
	public function newUser() {
		$user = new stdClass();
		$user->level = 1;
		return $user;
	}

	/**
	 * Authenticate user credentials
	 */
	public function authenticate($username, $password) {
		$sql = 'SELECT * FROM users WHERE user_username like :user';
		$query = $this->db->prepare($sql);
		$query->execute(array(':user' => $username));
		$dbUser = $query->fetch();
		$hpass = hash_hmac('sha512', $password, $dbUser->user_code);
		if ($hpass==$dbUser->user_password) {
			$user = $this->newUser();
			$user->level = 4;
			$user->firstname = $dbUser->user_firstname;
			$user->lastname = $dbUser->user_lastname;
			$user->email = $dbUser->user_email;
			$_SESSION['activeUser'] = $user;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Log out and destroy session
	 */
	public function destroySession() {
		unset($_SESSION["activeUser"]);
		session_destroy();
	}
}

?>