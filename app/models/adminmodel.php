<?php

class adminModel {

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
	 * 	Get list of all users
	 */
	public function getAllUsers() {
		$sql = 'SELECT 	user_id,
						user_email,
						user_firstname,
						user_lastname,
						user_level,
						user_last_login,
						user_failed_logins 
				FROM 	users 
				ORDER BY user_id ASC';
		$query = $this->db->prepare($sql);
		$query->execute();
		if ($query->rowCount()>=1) {
			return $query->fetchAll();
		} else {
			$_SESSION["messages"][] = array("error", ERROR_USER_UPDATE_FAILED); 
			return false;
		}
	}

	/**
	 * 	Get individual user info
	 */
	public function getUser($userid) {
		$sql = 'SELECT 	user_id,
						user_firstname,
						user_lastname,
						user_email  
				FROM 	users 
				WHERE 	user_id = :userid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':userid' => $userid));
		if ($query->rowCount()==1) {
			return $query->fetch();
		} else {
			$_SESSION["messages"][] = array("error", ERROR_USER_NOT_FOUND);
			return false;
		}
	}

	/**
	 * 	Insert new user record
	 */
	public function insertUser() {
		// Check input fields
		if (empty($_POST['firstname'])) {
			$_SESSION["messages"][] = array("error", ERROR_FIRST_NAME_FIELD_EMPTY);
		} elseif (empty($_POST['lastname'])) {
			$_SESSION["messages"][] = array("error", ERROR_LAST_NAME_FIELD_EMPTY);
		} elseif (empty($_POST['email'])) {
			$_SESSION["messages"][] = array("error", ERROR_EMAIL_FIELD_EMPTY);
		} elseif (empty($_POST['new_password'])) {
			$_SESSION["messages"][] = array("error", ERROR_NEW_PASSWORD_FIELD_EMPTY);
		} elseif (strlen($_POST['new_password']) < 6) {
			$_SESSION["messages"][] = array("error", ERROR_NEW_PASSWORD_TOO_SHORT);
		} elseif (empty($_POST['new_password_confirm'])) {
			$_SESSION["messages"][] = array("error", ERROR_NEW_PASSWORD_CONFIRM_FIELD_EMPTY);
		} elseif ($_POST['new_password'] !== $_POST['new_password_confirm']) {
			$_SESSION["messages"][] = array("error", ERROR_PASSWORD_CONFIRM_WRONG);
		} elseif (!empty($_POST['firstname']) 
			AND !empty($_POST['lastname']) 
			AND !empty($_POST['email']) 
			AND !empty($_POST['new_password']) 
			AND (strlen($_POST['new_password']) >= 6) 
			AND !empty($_POST['new_password_confirm']) 
			AND ($_POST['new_password'] == $_POST['new_password_confirm'])) {

			// Check email availability
			$query = $this->db->prepare("SELECT user_id FROM users WHERE user_email = :email");
			$query->execute(array(':email' => $_POST['email']));
			if ($query->rowCount() >= 1) {
				$_SESSION["messages"][] = array("error", ERROR_EMAIL_TAKEN);
				return false;
			}

			// Generate new code key
			$newCode = sha1(date('Y-m-d H:i:s'));

			// Hash new password
			$hpass = password_hash($_POST['new_password'], PASSWORD_DEFAULT, ['cost' => HASH_COST_FACTOR]);

			// Insert user record
			$sql = 'INSERT INTO users 	(user_email, user_firstname, user_lastname, user_password, user_code, user_created)
					VALUES 				(:email, :fname, :lname, :pass, :code, :created)';
			$query = $this->db->prepare($sql);
			$query->execute(array(
							':email' => $_POST['email'],
							':fname' => $_POST['firstname'],
							':lname' => $_POST['lastname'],
							':pass' => $hpass,
							':code' => $newCode,
							':created' => date('Y-m-d H:i:s')));
			if ($query->rowCount() != 1) {
				$_SESSION["messages"][] = array("error", ERROR_USER_CREATION_FAILED);
				return false;
			}

			// Return success
			$_SESSION["messages"][] = array("success", SUCCESS_USER_CREATED);
			return true;

		}

		// Default return
		return false;

	}

	/**
	 * 	Reset user password
	 */
	public function resetPassword($userid) {
		// Check form fields
		if (empty($_POST['new_password'])) {
			$_SESSION["messages"][] = array("error", ERROR_NEW_PASSWORD_FIELD_EMPTY);
		} elseif (strlen($_POST['new_password']) < 6) {
			$_SESSION["messages"][] = array("error", ERROR_NEW_PASSWORD_TOO_SHORT);
		} elseif (empty($_POST['new_password_confirm'])) {
			$_SESSION["messages"][] = array("error", ERROR_NEW_PASSWORD_CONFIRM_FIELD_EMPTY);
		} elseif ($_POST['new_password'] !== $_POST['new_password_confirm']) {
			$_SESSION["messages"][] = array("error", ERROR_PASSWORD_CONFIRM_WRONG);
		} elseif (!empty($_POST['new_password']) 
			AND (strlen($_POST['new_password']) >= 6) 
			AND !empty($_POST['new_password_confirm'])
			AND ($_POST['new_password'] == $_POST['new_password_confirm'])) {

			// Hash new password
			$hpass = password_hash($_POST['new_password'], PASSWORD_DEFAULT, ['cost' => HASH_COST_FACTOR]);

			// Store new password
			$sql = 'UPDATE 	users
					SET 	user_password = :hpass 
					WHERE 	user_id = :userid';
			$query = $this->db->prepare($sql);
			$query->execute(array(':hpass' => $hpass, ':userid' => $userid));

			// Check success
			if ($query->rowCount()!=1) {
				$_SESSION["messages"][] = array("error", ERROR_PASSWORD_UPDATE_FAILED);
				return false;
			}

			// Return success
			$_SESSION["messages"][] = array("success", SUCCESS_PASSWORD_UPDATED);
			return true;
		}

		// Default return
		return false;
	}

}

?>