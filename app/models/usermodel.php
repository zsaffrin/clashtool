<?php

class userModel {

	/**
	 * 	Shared DB connection must be provided
	 * 	@param object $db A PDO database connection
	 */
	function __construct($db) {
		try {
			$this->db = $db;
		} catch (PDOException $e) {
			exit('Database connection could not be established.');
		}
	}

	/**
	 * 	Get User - Fetch user data from DB
	 */
	public function getUser($userid) {
		$sql = 'SELECT 	user_id,
						user_firstname,
						user_lastname,
						user_email,
						user_password 
				FROM 	users 
				WHERE 	user_id = :userid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':userid' => $userid));
		if ($query->rowCount()==1) {
			return $query->fetch();
		} else {
			$_SESSION["msg_errors"][] = ERROR_USER_NOT_FOUND; 
			return false;
		}
	}

	/**
	 * 	Save user information to DB
	 */
	public function saveUserData() {
		$sql = 'UPDATE 	users
				SET 	user_firstname = :fname,
						user_lastname = :lname,
						user_email = :email 
				WHERE 	user_id = :userid';
		$query = $this->db->prepare($sql);
		$query->execute(
			array(
				':fname' => $_POST['firstname'],
				':lname' => $_POST['lastname'],
				':email' => $_POST['email'],
				':userid' => Session::get('user_id')));
		if ($query->rowCount()==1) {
			Session::set('user_firstname', $_POST['firstname']);
			Session::set('user_lastname', $_POST['lastname']);
			Session::set('user_email', $_POST['email']);
			$_SESSION["msg_success"][] = SUCCESS_USER_INFO_UPDATED;
			return true;
		} else {
			$_SESSION["msg_errors"][] = ERROR_USER_UPDATE_FAILED; 
			return false;
		}
	}

	/**
	 * 	Change user password
	 */
	public function changePassword() {
		// Check form fields
		if (empty($_POST['password'])) { 
			$_SESSION["msg_errors"][] = ERROR_PASSWORD_FIELD_EMPTY;
		} elseif (empty($_POST['new_password'])) {
			$_SESSION["msg_errors"][] = ERROR_NEW_PASSWORD_FIELD_EMPTY;
		} elseif (strlen($_POST['new_password']) < 6) {
			$_SESSION["msg_errors"][] = ERROR_NEW_PASSWORD_TOO_SHORT;
		} elseif (empty($_POST['new_password_confirm'])) {
			$_SESSION["msg_errors"][] = ERROR_NEW_PASSWORD_CONFIRM_FIELD_EMPTY;
		} elseif ($_POST['new_password'] !== $_POST['new_password_confirm']) {
			$_SESSION["msg_errors"][] = ERROR_PASSWORD_CONFIRM_WRONG;
		} elseif (!empty($_POST['password']) 
			AND !empty($_POST['new_password']) 
			AND (strlen($_POST['new_password']) >= 6) 
			AND !empty($_POST['new_password_confirm'])
			AND ($_POST['new_password'] == $_POST['new_password_confirm'])) {

			// Validate current password
			$user = $this->getUser(Session::get('user_id'));
			if (!password_verify($_POST['password'], $user->user_password)) {
				$_SESSION["msg_errors"][] = ERROR_PASSWORD_WRONG;
				return false;
			}

			// Hash new password
			$hpass = password_hash($_POST['new_password'], PASSWORD_DEFAULT, ['cost' => HASH_COST_FACTOR]);

			// Store new password
			$sql = 'UPDATE 	users
					SET 	user_password = :hpass 
					WHERE 	user_id = :userid';
			$query = $this->db->prepare($sql);
			$query->execute(array(':hpass' => $hpass, ':userid' => $user->user_id));

			// Check success
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