<?php

class loginModel {

	public function __construct($db) {
		$this->db = $db;
	}

	/**
	 * 	Log user in based on authentication of POST variables
	 */
	public function login() {
		$sql = 'SELECT 	user_id, 
						user_username, 
						user_password, 
						user_code, 
						user_firstname, 
						user_lastname, 
						user_email 
				FROM 	users 
				WHERE 	user_username like :user';
		$query = $this->db->prepare($sql);
		$query->execute(array(':user' => $_POST['username']));
		if ($query->rowCount() != 1) {
			Session::set('msg', ERROR_INVALID_LOGIN);
			return false;
		}

		$dbUser = $query->fetch();
		$hpass = hash_hmac('sha512', $_POST['password'], $dbUser->user_code);
		if ($hpass==$dbUser->user_password) {
			Session::set('user_logged_in', true);
			Session::set('user_id', $dbUser->user_id);
			Session::set('user_username', $dbUser->user_username);
			Session::set('user_firstname', $dbUser->user_firstname);
			Session::set('user_lastname', $dbUser->user_lastname);
			Session::set('user_email', $dbUser->user_email);
			Session::set('user_level', 4);
			return true;
		}
	} 

	/**
	 * 	Log out, destroy session
	 */
	public function logout() {
		foreach ($_SESSION as $key) {
			unset($key);
		}
		Session::destroy();
	}

}

?>