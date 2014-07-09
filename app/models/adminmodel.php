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
	public function getUsers() {
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
			$_SESSION["msg_errors"][] = ERROR_USER_UPDATE_FAILED; 
			return false;
		}
	}

	/**
	 * 	Insert new user record
	 */
	public function insertUser() {
		// Check input fields
		if (empty($_POST['firstname'])) {
			$_SESSION["msg_errors"][] = ERROR_FIRST_NAME_FIELD_EMPTY;
		} elseif (empty($_POST['lastname'])) {
			$_SESSION["msg_errors"][] = ERROR_LAST_NAME_FIELD_EMPTY;
		} elseif (empty($_POST['email'])) {
			$_SESSION["msg_errors"][] = ERROR_EMAIL_FIELD_EMPTY;
		} elseif (empty($_POST['new_password'])) {
			$_SESSION["msg_errors"][] = ERROR_NEW_PASSWORD_FIELD_EMPTY;
		} elseif (empty($_POST['new_password_confirm'])) {
			$_SESSION["msg_errors"][] = ERROR_NEW_PASSWORD_CONFIRM_FIELD_EMPTY;
		} elseif ($_POST['new_password'] !== $_POST['new_password_confirm']) {
			$_SESSION["msg_errors"][] = ERROR_PASSWORD_CONFIRM_WRONG;
		} elseif (!empty($_POST['firstname'])
			AND !empty($_POST['lastname'])
			AND !empty($_POST['email'])
			AND !empty($_POST['new_password'])
			AND !empty($_POST['new_password_confirm'])
			AND ($_POST['new_password'] == $_POST['new_password_confirm'])) {

			// Check email availability
			$query = $this->db->prepare("SELECT user_id FROM users WHERE user_email = :email");
			$query->execute(array(':email' => $_POST['email']));
			if ($query->rowCount() >= 1) {
				$_SESSION["msg_errors"][] = ERROR_EMAIL_TAKEN;
				return false;
			}

			// Generate new code key
			$newCode = sha1(time());

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
				$_SESSION["msg_errors"][] = ERROR_USER_CREATION_FAILED;
				return false;
			}

			// Return success
			return true;

		}

		// Default return
		return false;

	}

}

?>