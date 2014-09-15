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
							user_failed_logins,
							force_password_reset 
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
				if ($dbUser->force_password_reset == 1) { Session::set('force_password_reset', true); }

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

	/**
	 * 	Process signup request
	 */
	public function signup() {
		if (empty($_POST['email'])) {
			$_SESSION["messages"][] = array("error", ERROR_EMAIL_FIELD_EMPTY);
		} elseif (empty($_POST['firstname'])) {
			$_SESSION["messages"][] = array("error", ERROR_FIRST_NAME_FIELD_EMPTY);
		} elseif (empty($_POST['lastname'])) {
			$_SESSION["messages"][] = array("error", ERROR_LAST_NAME_FIELD_EMPTY);
		} elseif (!empty($_POST['email']) 
			AND !empty($_POST['firstname']) 
			AND !empty($_POST['lastname'])) {
			
			// Check if email is available
			$sql = 'SELECT 	user_id,  
							user_email 
					FROM 	users 
					WHERE 	user_email like :email';
			$query = $this->db->prepare($sql);
			$query->execute(array(':email' => $_POST['email']));
			if ($query->rowCount() != 0) {
				$_SESSION["messages"][] = array("error", ERROR_EMAIL_TAKEN);
				return false;
			}

			// Insert user record
			$sql = 'INSERT INTO users 	(user_email, user_email_verified, user_status, user_firstname, user_lastname, user_created)
					VALUES 				(:email, :emailverif, :status, :fname, :lname, :created)';
			$query = $this->db->prepare($sql);
			$query->execute(array(
							':email' => $_POST['email'],
							':emailverif' => 0,
							':status' => 0,
							':fname' => $_POST['firstname'],
							':lname' => $_POST['lastname'],
							':created' => date('Y-m-d H:i:s')));
			if ($query->rowCount() != 1) {
				$_SESSION["messages"][] = array("error", ERROR_USER_CREATION_FAILED);
				return false;
			}

			// Return success
			return true;
		}

		return false;
	}

	/**
	 * 	Process signup request
	 */
	public function verifyEmail($uid, $code) {
		$sql = 'SELECT 	user_id,  
						user_email,
						user_email_verified,
						user_email_verify_code 
				FROM 	users 
				WHERE 	user_id = :uid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':uid' => $uid));
		if ($query->rowCount() != 1) {
			$_SESSION["messages"][] = array("error", ERROR_USER_NOT_FOUND);
			return false;
		}
		$user = $query->fetch();

		// Check verification requirements
		if ($user->user_email_verified != 0) {
			$_SESSION["messages"][] = array("error", ERROR_EMAIL_ALREADY_VERIFIED);
			return false;
		} elseif (is_null($user->user_email_verify_code)) {
			$_SESSION["messages"][] = array("error", ERROR_EMAIL_VERIFICATION_INACTIVE);
			return false;
		} elseif ($user->user_email_verify_code != $code) {
			$_SESSION["messages"][] = array("error", ERROR_EMAIL_CODE_MISMATCH);
			return false;
		} elseif ($user->user_email_verified == 0 
			AND !is_null($user->user_email_verify_code) 
			AND $user->user_email_verify_code == $code
			) {

			// Verify email
			$sql = 'UPDATE 	users 
					SET 	user_email_verified = 1, 
							user_email_verify_code = null 
					WHERE 	user_id = :userid';
			$query = $this->db->prepare($sql);
			$query->execute(array(':userid' => $uid));

			// Return success
			$_SESSION["messages"][] = array("success", SUCCESS_EMAIL_VERIFIED);
			return true;
		}

		// Default return
		$_SESSION["messages"][] = array("error", ERROR_EMAIL_VERIFICATION_FAILED);
		return false;

	}

}

?>