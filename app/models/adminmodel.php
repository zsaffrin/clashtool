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
						user_email_verified,
						user_status,
						user_firstname,
						user_lastname,
						user_level,
						user_last_login,
						user_failed_logins,
						force_password_reset 
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
						user_email,
						user_status 
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

	/**
	 * 	Trigger flag to force user to choose a new password upon next login
	 */
	public function force_password_reset($userid) {
		$sql = 'SELECT 	user_id,
						force_password_reset 
				FROM 	users 
				WHERE 	user_id = :userid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':userid' => $userid));
		$user = $query->fetch();

		$newValue = 1;
		if ($user->force_password_reset == 1) {
			$newValue = 0;
		} 

		$sql = 'UPDATE 	users
				SET 	force_password_reset = :newvalue 
				WHERE 	user_id = :userid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':userid' => $userid, ':newvalue' => $newValue));
		if ($query->rowCount()!=1) {
			$_SESSION["messages"][] = array("error", ERROR_FLAG_UPDATE_FAILED);
			return false;
		} else  {
			return true;
		}
	}

	/**
	 * 	Trigger email verification process
	 */
	public function trigger_email_verification($userid) {
		// Get User info
		$user = $this->getUser($userid);

		// Generate verification code
		$verifyCode = substr(md5(microtime()),rand(0,26),16);

		// Update database
		$sql = 'UPDATE 	users
				SET 	user_email_verified = 0,
						user_email_verify_code = :vcode 
				WHERE 	user_id = :userid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':userid' => $userid, ':vcode' => $verifyCode));
		if ($query->rowCount()==1) {
			$_SESSION["messages"][] = array("success", SUCCESS_USER_FLAGS_UPDATED);
		}

		// Send verification email
		$mail = new PHPMailer;

		$mail->IsSMTP();
		$mail->SMTPDebug = PHPMAILER_DEBUG_MODE;
		$mail->SMTPAuth = EMAIL_SMTP_AUTH;
		$mail->Host = EMAIL_SMTP_HOST;
		$mail->Username = EMAIL_SMTP_USERNAME;
		$mail->Password = EMAIL_SMTP_PASSWORD;
		$mail->Port = EMAIL_SMTP_PORT;
		$mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;

		$mail->IsHTML(true);
		$mail->AddReplyTo(EMAIL_VERIFICATION_FROM_EMAIL, EMAIL_VERIFICATION_FROM_NAME);
		$mail->From = EMAIL_VERIFICATION_FROM_EMAIL;
		$mail->FromName = EMAIL_VERIFICATION_FROM_NAME;
		$mail->AddAddress($user->user_email);
		
		$mail->Subject = EMAIL_VERIFICATION_SUBJECT;
		$mail->Body = EMAIL_VERIFICATION_CONTENT;
		$mail->Body .= URL.'login/verify_email/'.urlencode($user->user_id).'/'.urlencode($verifyCode);
		$mail->Body .= EMAIL_VERIFICATION_CONTENT_CLOSE;

		if ($mail->Send()) {
			$_SESSION["messages"][] = array("success", SUCCESS_EMAIL_SENT);
			return true;
		} else {
			$_SESSION["messages"][] = array("error", ERROR_EMAIL_SEND_FAILED.' - '.$mail->ErrorInfo);
			return false;
		}
	}

	/**
	 * 	Toggle user activation status
	 */
	public function toggle_user_status($userid) {
		// Get User info
		$user = $this->getUser($userid);

		// Check current lock state, determine new state
		if ($user->user_status == 1) {
			$newStatus = 2;
		} else {
			$newStatus = 1;
		}

		$sql = 'UPDATE 	users
				SET 	user_status = :status 
				WHERE 	user_id = :userid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':userid' => $userid, ':status' => $newStatus));
		if ($query->rowCount()!=1) {
			$_SESSION["messages"][] = array("error", ERROR_FLAG_UPDATE_FAILED);
			return false;
		} else  {
			return true;
		}
	}

	/**
	 * 	Toggle user activation status
	 */
	public function deleteUser($userid) {
		// Delete User buildings
		$sql = 'DELETE FROM user_buildings 
				WHERE 		user_id = :userid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':userid' => $userid));

		// Delete User troops
		$sql = 'DELETE FROM user_troops 
				WHERE 		user_id = :userid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':userid' => $userid));

		// Delete User Record
		$sql = 'DELETE FROM users 
				WHERE 		user_id = :userid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':userid' => $userid));

		return true;

	}

}

?>