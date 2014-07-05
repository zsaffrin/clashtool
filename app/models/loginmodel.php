<?php

class loginModel {

	public function __construct($db) {
		$this->db = $db;
	}

	/**
	 * 	Log user in based on authentication of POST variables
	 */
	public function login() {
		if (empty($_POST['email'])) {
			$_SESSION["msg_errors"][] = ERROR_EMAIL_FIELD_EMPTY;
		} elseif (empty($_POST['password'])) {
			$_SESSION["msg_errors"][] = ERROR_PASSWORD_FIELD_EMPTY;
		} elseif (!empty($_POST['email']) 
			AND !empty($_POST['password'])) {
			$sql = 'SELECT 	user_id, 
							user_email, 
							user_firstname, 
							user_lastname, 
							user_password, 
							user_code, 
							user_level, 
							user_failed_logins 
					FROM 	users 
					WHERE 	user_email like :email';
			$query = $this->db->prepare($sql);
			$query->execute(array(':email' => $_POST['email']));
			if ($query->rowCount() != 1) {
				$_SESSION["msg_errors"][] = ERROR_USER_NOT_FOUND;
				return false;
			}
			$dbUser = $query->fetch();
			$hpass = hash_hmac('sha512', $_POST['password'], $dbUser->user_code);
			if ($hpass==$dbUser->user_password) {
				Session::set('user_logged_in', true);
				Session::set('user_id', $dbUser->user_id);
				Session::set('user_firstname', $dbUser->user_firstname);
				Session::set('user_lastname', $dbUser->user_lastname);
				Session::set('user_email', $dbUser->user_email);
				Session::set('user_level', $dbUser->user_level);
				return true;
			} else {
				$_SESSION["msg_errors"][] = ERROR_PASSWORD_WRONG;
				return false;
			}

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