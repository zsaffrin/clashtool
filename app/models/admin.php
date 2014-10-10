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

		require HELPERS_PATH.'PHPMailer/PHPMailerAutoload.php';
		$this->mail = new PHPMailer;

		$this->mail->IsSMTP();
		$this->mail->SMTPDebug = PHPMAILER_DEBUG_MODE;
		$this->mail->SMTPAuth = EMAIL_SMTP_AUTH;
		$this->mail->Host = EMAIL_SMTP_HOST;
		$this->mail->Username = EMAIL_SMTP_USERNAME;
		$this->mail->Password = EMAIL_SMTP_PASSWORD;
		$this->mail->Port = EMAIL_SMTP_PORT;
		$this->mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;
		$this->mail->IsHTML(true);
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
						user_status,
						force_password_reset 
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
	 * 	Change user status
	 * 	@param int $userid 	Target User ID
	 * 	@param int $status 	Status value to apply
	 */
	public function setUserStatus($userid, $status) {
		$sql = 'UPDATE 	users
				SET 	user_status = :status 
				WHERE 	user_id = :userid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':userid' => $userid, ':status' => $status));
		return true;
	}

	/**
	 * 	Verify User Email
	 * 	@param int $userid 		Target user ID
	 * 	@param int $unlock 		Auto-unlock account after email verified - 0 = No (Default); 1 = Yes
	 */
	public function verifyUserEmail($userid, $unlock=0) {
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

		// Send verification email
		$this->mail->AddReplyTo(EMAIL_VERIFICATION_FROM_EMAIL, EMAIL_VERIFICATION_FROM_NAME);
		$this->mail->From = EMAIL_VERIFICATION_FROM_EMAIL;
		$this->mail->FromName = EMAIL_VERIFICATION_FROM_NAME;
		$this->mail->AddAddress($user->user_email);
		$this->mail->Subject = EMAIL_VERIFICATION_SUBJECT;
		$this->mail->Body = EMAIL_VERIFICATION_CONTENT;
		$this->mail->Body .= URL.'login/verify_email/'.urlencode($user->user_id).'/'.urlencode($verifyCode);
		if ($unlock == 1) { $this->mail->Body .= '/1'; }
		$this->mail->Body .= EMAIL_VERIFICATION_CONTENT_CLOSE;

		if ($this->mail->Send()) {
			$_SESSION["messages"][] = array("success", SUCCESS_EMAIL_VERIFICATION_TRIGGERED);
			return true;
		} else {
			$_SESSION["messages"][] = array("error", ERROR_EMAIL_SEND_FAILED.' - '.$this->mail->ErrorInfo);
			return false;
		}
	}

	/**
	 * 	Delete user
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

		$_SESSION["messages"][] = array("success", SUCCESS_USER_DELETED);
		return true;

	}

	/**
	 * 	Trigger flag to force user to choose a new password upon next login
	 */
	public function forcePasswordReset($userid) {
		$user = $this->getUser($userid);

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
			$_SESSION["messages"][] = array("error", ERROR_DB_UPDATE_FAILED);
			return false;
		} else  {
			return true;
		}
	}

	/**
	 * 	Create new user record
	 */
	public function createUser() {
		// Check email availability
		$query = $this->db->prepare("SELECT user_id FROM users WHERE user_email = :email");
		$query->execute(array(':email' => $_POST['email']));
		if ($query->rowCount() >= 1) {
			$_SESSION["messages"][] = array("error", ERROR_EMAIL_TAKEN);
			return false;
		}

		// Generate random new password
		$newpass = substr(md5(microtime()),rand(0,26),6);

		// Create new code and hash new password
		$newCode = sha1(time());
		$hpass = hash_hmac('sha512', $newpass, $newCode);

		// Insert user record
		$sql = 'INSERT INTO users 	(user_email, user_email_verified, user_code, user_status, user_password, force_password_reset, user_created)
				VALUES 				(:email, :verif, :code, :status, :pass, :reset, :created)';
		$query = $this->db->prepare($sql);
		$query->execute(array(
						':email' => $_POST['email'],
						':verif' => 1,
						':code' => $newCode,
						':status' => 1,
						':pass' => $hpass,
						':reset' => 1,
						':created' => date('Y-m-d H:i:s')));
		if ($query->rowCount() != 1) {
			$_SESSION["messages"][] = array("error", ERROR_USER_CREATION_FAILED);
			return false;
		}
		$newUserID = $this->db->lastInsertID();

		// Send verification email
		$this->mail->AddReplyTo(EMAIL_ADMIN_INVITE_FROM_EMAIL, EMAIL_ADMIN_INVITE_FROM_NAME);
		$this->mail->From = EMAIL_ADMIN_INVITE_FROM_EMAIL;
		$this->mail->FromName = EMAIL_ADMIN_INVITE_FROM_NAME;
		$this->mail->AddAddress($_POST['email']);
		$this->mail->Subject = EMAIL_ADMIN_INVITE_SUBJECT;
		$this->mail->Body = EMAIL_ADMIN_INVITE_CONTENT_A.$_POST['email'].EMAIL_ADMIN_INVITE_CONTENT_B.$newpass.EMAIL_ADMIN_INVITE_CONTENT_C;
		$this->mail->Send();

		// Return success
		$_SESSION["messages"][] = array("success", SUCCESS_USER_CREATED);
		return true;
	}

}

?>