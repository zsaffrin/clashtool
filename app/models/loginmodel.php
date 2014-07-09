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
			if (password_verify($_POST['password'], $dbUser->user_password)) {
				
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
					$_SESSION["msg_errors"][] = ERROR_FAILED_LOGIN_RESET_FAILED;
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

	/**
	 * 	Get user info for identification confirmation
	 */
	public function getUserInfo($userid) {
		$sql = 'SELECT 	user_firstname,
						user_lastname 
				FROM 	users 
				WHERE 	user_id = :userid';
		$query = $this->db->prepare($sql);
		$query->execute(
				array(':userid' => $userid)
			);
		if ($query->rowCount()!=1) {
			$_SESSION["msg_errors"][] = ERROR_USER_NOT_FOUND;
			return false;
		}
		$user = $query->fetch();

		// Return user info
		return $user;
	}

	/**
	 * 	Reset user password
	 */
	public function resetPassword() {
		if (!isset($_POST['userid'])) {
			$_SESSION["msg_errors"][] = ERROR_ARGUMENT_MISSING.'POST: userid';
		} elseif (!isset($_POST['new_password'])) {
			$_SESSION["msg_errors"][] = ERROR_NEW_PASSWORD_FIELD_EMPTY;
		} elseif (!isset($_POST['new_password_confirm'])) {
			$_SESSION["msg_errors"][] = ERROR_NEW_PASSWORD_CONFIRM_FIELD_EMPTY;
		} elseif ($_POST['new_password'] !== $_POST['new_password_confirm']) {
			$_SESSION["msg_errors"][] = ERROR_PASSWORD_CONFIRM_WRONG;
		} elseif (isset($_POST['userid'])
			AND isset($_POST['new_password'])
			AND isset($_POST['new_password_confirm'])
			AND ($_POST['new_password'] !== $_POST['new_password_confirm'])) {
			
			// Hash new password
			$hpass = password_hash($_POST['new_password'], PASSWORD_DEFAULT, ['cost' => HASH_COST_FACTOR]);

			// Store new password
			$sql = 'UPDATE 	users
					SET 	user_password = :hpass 
					WHERE 	user_id = :userid';
			$query = $this->db->prepare($sql);
			$query->execute(array(
				array(
					':hpass' => $hpass, 
					':userid' => $_POST['userid'])
				)
			);

			if ($query->rowCount()!=1) {
				$_SESSION["msg_errors"][] = ERROR_PASSWORD_UPDATE_FAILED;
				return false;
			}

			// Return success
			$_SESSION["msg_success"][] = SUCCESS_PASSWORD_UPDATED;
			return true;

		}

		// Default return
		return false;

	}

}

?>