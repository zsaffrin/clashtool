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
	 * 	Get list of all users
	 */
	public function getAllUsers() {
		$sql = 'SELECT 	user_id,
						user_email 
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
			$_SESSION["messages"][] = array("error", ERROR_USER_NOT_FOUND);
			return false;
		}
	}

	/**
	 * 	Save user information to DB
	 */
	public function saveUserData() {
		// Valid email required
		if (empty($_POST['email'])) {
			$_SESSION["messages"][] = array("error", ERROR_EMAIL_FIELD_EMPTY_ALT);
			return false;
		} 

		// Check if Email changed
		$emailChanged = 0;
		$user = $this->getUser(Session::get('user_id'));
		if ($_POST['email'] != $user->user_email) {
			$emailChanged = 1;
			$verifyCode = substr(md5(microtime()),rand(0,26),16);

			// Unique email required
			$users = $this->getAllUsers();
			foreach ($users as $u) {
				if ($_POST['email'] == $u->user_email) {
					$_SESSION["messages"][] = array("error", ERROR_EMAIL_TAKEN);
					return false;
				}
			}
		}

		// Update info
		$sql = 'UPDATE 	users
				SET 	user_firstname = :fname,
						user_lastname = :lname,
						user_email = :email'; 
		if ($emailChanged == 1) { $sql .= ',user_email_verified = 0,user_email_verify_code = :vcode'; }
		$sql .= ' WHERE 	user_id = :userid';
		$query = $this->db->prepare($sql);
		$args = array(
				':fname' => $_POST['firstname'],
				':lname' => $_POST['lastname'],
				':email' => $_POST['email'],
				':userid' => Session::get('user_id'));
		if ($emailChanged == 1) {
			$args[':vcode'] = $verifyCode;
		}
		$query->execute($args);
		if ($emailChanged == 1) {
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
			$mail->AddReplyTo(EMAIL_CHANGE_VERIFICATION_FROM_EMAIL, EMAIL_CHANGE_VERIFICATION_FROM_NAME);
			$mail->From = EMAIL_CHANGE_VERIFICATION_FROM_EMAIL;
			$mail->FromName = EMAIL_CHANGE_VERIFICATION_FROM_NAME;
			$mail->AddAddress($_POST['email']);
			
			$mail->Subject = EMAIL_CHANGE_VERIFICATION_SUBJECT;
			$mail->Body = EMAIL_CHANGE_VERIFICATION_CONTENT;
			$mail->Body .= URL.'login/verify_email/'.urlencode($user->user_id).'/'.urlencode($verifyCode);
			$mail->Body .= EMAIL_CHANGE_VERIFICATION_CONTENT_CLOSE;

			$mail->Send();
			$_SESSION["messages"][] = array("success", SUCCESS_EMAIL_VERIFICATION_SENT);
		}
		Session::set('user_firstname', $_POST['firstname']);
		Session::set('user_lastname', $_POST['lastname']);
		Session::set('user_email', $_POST['email']);
		$_SESSION["messages"][] = array("success", SUCCESS_USER_INFO_UPDATED);
		return true;
	}

	/**
	 * 	Change user password
	 */
	public function changePassword() {
		// Check form fields
		if (empty($_POST['password'])) { 
			$_SESSION["messages"][] = array("error", ERROR_PASSWORD_FIELD_EMPTY);
		} elseif (empty($_POST['new_password'])) {
			$_SESSION["messages"][] = array("error", ERROR_NEW_PASSWORD_FIELD_EMPTY);
		} elseif (strlen($_POST['new_password']) < 6) {
			$_SESSION["messages"][] = array("error", ERROR_NEW_PASSWORD_TOO_SHORT);
		} elseif (empty($_POST['new_password_confirm'])) {
			$_SESSION["messages"][] = array("error", ERROR_NEW_PASSWORD_CONFIRM_FIELD_EMPTY);
		} elseif ($_POST['new_password'] !== $_POST['new_password_confirm']) {
			$_SESSION["messages"][] = array("error", ERROR_PASSWORD_CONFIRM_WRONG);
		} elseif (!empty($_POST['password']) 
			AND !empty($_POST['new_password']) 
			AND (strlen($_POST['new_password']) >= 6) 
			AND !empty($_POST['new_password_confirm'])
			AND ($_POST['new_password'] == $_POST['new_password_confirm'])) {

			// Validate current password
			$user = $this->getUser(Session::get('user_id'));
			if (!password_verify($_POST['password'], $user->user_password)) {
				$_SESSION["messages"][] = array("error", ERROR_PASSWORD_WRONG);
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
	 * 	Force-set user password
	 */
	public function setPassword() {
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

			// Check if password is new
			$user = $this->getUser($_POST['userid']);
			if (password_verify($_POST['new_password'], $user->user_password)) {
				$_SESSION["messages"][] = array("error", ERROR_PASSWORD_NOT_UNIQUE);
				return false;
			}

			// Store new password
			$sql = 'UPDATE 	users
					SET 	user_password = :hpass,
							force_password_reset = 0 
					WHERE 	user_id = :userid';
			$query = $this->db->prepare($sql);
			$query->execute(array(':hpass' => $hpass, ':userid' => $_POST['userid']));

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