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
			$_SESSION["messages"][] = array("error", ERROR_EMAIL_FIELD_EMPTY);
		} elseif (empty($_POST['password'])) {
			$_SESSION["messages"][] = array("error", ERROR_PASSWORD_FIELD_EMPTY);
		} elseif (!empty($_POST['email']) 
			AND !empty($_POST['password'])) {
			$sql = 'SELECT 	user_id, 
							user_status, 
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
				$_SESSION["messages"][] = array("error", ERROR_USER_NOT_FOUND);
				return false;
			}
			$dbUser = $query->fetch();
			if (password_verify($_POST['password'], $dbUser->user_password)) {
				
				// Check user account status
				if ($dbUser->user_status == 0) {
					$_SESSION["messages"][] = array("error", ERROR_USER_PENDING);
					return false;
				} 
				if ($dbUser->user_status == 2) {
					$_SESSION["messages"][] = array("error", ERROR_USER_ACCOUNT_LOCKED);
					return false;
				} 
				// Set session data
				Session::set('user_logged_in', true);
				Session::set('user_id', $dbUser->user_id);
				Session::set('user_firstname', $dbUser->user_firstname);
				Session::set('user_lastname', $dbUser->user_lastname);
				Session::set('user_email', $dbUser->user_email);
				Session::set('user_level', $dbUser->user_level);

				// Reset Failed Login count and stamp Last Login time
				$sql = 'UPDATE 	users 
						SET 	user_failed_logins = 0, 
								user_last_login = :now 
						WHERE 	user_id = :userid';
				$query = $this->db->prepare($sql);
				$query->execute(
						array(
							':userid' => $dbUser->user_id, 
							':now' => date('Y-m-d H:i:s')
						)
					);
				if ($query->rowCount()!=1) {
					$_SESSION["messages"][] = array("error", ERROR_FAILED_LOGIN_RESET_FAILED);
					return false;
				}

				// Return success
				return true;

			} else {
				
				// Increment Failed Login count
				$sql = 'UPDATE 	users 
						SET 	user_failed_logins = :failcount, 
								user_last_failed_login = :lastfail 
						WHERE 	user_id = :userid';
				$query = $this->db->prepare($sql);
				$query->execute(
					array(
						':userid' => $dbUser->user_id, 
						':failcount' => abs($dbUser->user_failed_logins + 1), 
						':lastfail' => date('Y-m-d H:i:s')
						)
					);

				$_SESSION["messages"][] = array("error", ERROR_PASSWORD_WRONG);
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