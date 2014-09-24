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
							user_password,  
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
			if ($dbUser->user_status == 2) {
				$_SESSION["messages"][] = array("error", ERROR_USER_ACCOUNT_LOCKED);
				return false;
			} elseif (password_verify($_POST['password'], $dbUser->user_password)) {
				
				// Check user account status
				if ($dbUser->user_status == 0) {
					$_SESSION["messages"][] = array("error", ERROR_USER_PENDING);
					return false;
				} 
				
				// Set session data
				Session::set('user_logged_in', true);
				Session::set('user_id', $dbUser->user_id);
				Session::set('user_email', $dbUser->user_email);
				Session::set('user_level', $dbUser->user_level);
				if ($dbUser->force_password_reset == 1) { Session::set('force_password_reset', true); }

				// Reset Failed Login count and stamp Last Login time
				$sql = 'UPDATE 	users 
						SET 	password_recovery_code = null,
								user_failed_logins = 0, 
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
				$failCount = abs($dbUser->user_failed_logins + 1);
				$sql = 'UPDATE 	users 
						SET 	user_failed_logins = :failcount, 
								user_last_failed_login = :lastfail';
				if ($failCount >= 10) { $sql .= ',user_status = 2'; }
				$sql .= ' WHERE user_id = :userid';
				$query = $this->db->prepare($sql);
				$query->execute(
					array(
						':userid' => $dbUser->user_id, 
						':failcount' => $failCount, 
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
		} elseif (empty($_POST['new_password'])) {
			$_SESSION["messages"][] = array("error", ERROR_NEW_PASSWORD_FIELD_EMPTY);
		} elseif (strlen($_POST['new_password']) < 6) {
			$_SESSION["messages"][] = array("error", ERROR_NEW_PASSWORD_TOO_SHORT);
		} elseif (empty($_POST['new_password_confirm'])) {
			$_SESSION["messages"][] = array("error", ERROR_NEW_PASSWORD_CONFIRM_FIELD_EMPTY);
		} elseif ($_POST['new_password'] !== $_POST['new_password_confirm']) {
			$_SESSION["messages"][] = array("error", ERROR_PASSWORD_CONFIRM_WRONG);
		} elseif (!empty($_POST['email']) 
			AND !empty($_POST['new_password']) 
			AND (strlen($_POST['new_password']) >= 6)
			AND !empty($_POST['new_password_confirm']) 
			AND ($_POST['new_password'] == $_POST['new_password_confirm'])) {
			
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

			// Hash new password
			$hpass = password_hash($_POST['new_password'], PASSWORD_DEFAULT, ['cost' => HASH_COST_FACTOR]);

			// Insert user record
			$sql = 'INSERT INTO users 	(user_email, user_email_verified, user_status, user_password, user_created)
					VALUES 				(:email, :emailverif, :status, :newpass, :created)';
			$query = $this->db->prepare($sql);
			$query->execute(array(
							':email' => $_POST['email'],
							':emailverif' => 0,
							':status' => 0,
							':newpass' => $hpass,
							':created' => date('Y-m-d H:i:s')));
			if ($query->rowCount() != 1) {
				$_SESSION["messages"][] = array("error", ERROR_USER_CREATION_FAILED);
				return false;
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
			$mail->AddReplyTo(EMAIL_NEW_USER_NOTIFICATION_FROM_EMAIL, EMAIL_NEW_USER_NOTIFICATION_FROM_NAME);
			$mail->From = EMAIL_NEW_USER_NOTIFICATION_FROM_EMAIL;
			$mail->FromName = EMAIL_NEW_USER_NOTIFICATION_FROM_NAME;
			$mail->AddAddress(SYS_ADMIN_EMAIL_ADDRESS);
			
			$mail->Subject = EMAIL_NEW_USER_NOTIFICATION_SUBJECT;
			$mail->Body = EMAIL_NEW_USER_NOTIFICATION_CONTENT;
			$mail->Body .= $_POST['email'];
			$mail->Body .= EMAIL_NEW_USER_NOTIFICATION_CONTENT_CLOSE;

			$mail->Send();

			// Return success
			return true;
		}

		return false;
	}

	/**
	 * 	Verify user email
	 */
	public function verifyEmail($uid, $code, $unlock) {
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

			// Update DB flags
			$sql = 'UPDATE 	users 
					SET 	user_email_verified = 1, 
							user_email_verify_code = null'; 
			if ($unlock == 1) { $sql .= ', user_status = 1'; }
			$sql .= ' WHERE 	user_id = :userid';
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

	/**
	 * 	Initiate Password recovery
	 */
	public function forgotPassword() {
		// Valid email required
		if (empty($_POST['email'])) {
			$_SESSION["messages"][] = array("error", ERROR_EMAIL_FIELD_EMPTY);
			return false;
		} else {
			$sql = 'SELECT 	user_id,  
							user_email,
							user_email_verified 
					FROM 	users 
					WHERE 	user_email like :email';
			$query = $this->db->prepare($sql);
			$query->execute(array(':email' => $_POST['email']));
			$user = $query->fetch();
			if ($query->rowCount() != 1) {
				$_SESSION["messages"][] = array("error", ERROR_USER_NOT_FOUND);
				return false;
			} elseif ($user->user_email_verified == 0) {
				// Email address must be verified
				$_SESSION["messages"][] = array("error", ERROR_RECOVERY_UNVERIFIED_EMAIL);
				return false;
			} else {
				// Generate recovery response code
				$recoveryCode = substr(md5(microtime()),rand(0,26),32);
				$sql = 'UPDATE 	users
						SET 	password_recovery_code = :code 
						WHERE 	user_id = :userid';
				$query = $this->db->prepare($sql);
				$query->execute(array(':code' => $recoveryCode, ':userid' => $user->user_id));

				// Send recovery email
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
				$mail->AddReplyTo(EMAIL_PASSWORD_RECOVERY_FROM_EMAIL, EMAIL_PASSWORD_RECOVERY_FROM_NAME);
				$mail->From = EMAIL_PASSWORD_RECOVERY_FROM_EMAIL;
				$mail->FromName = EMAIL_PASSWORD_RECOVERY_FROM_NAME;
				$mail->AddAddress($user->user_email);
				
				$mail->Subject = EMAIL_PASSWORD_RECOVERY_SUBJECT;
				$mail->Body = EMAIL_PASSWORD_RECOVERY_CONTENT;
				$mail->Body .= URL.'login/recoverPassword/'.urlencode($user->user_id).'/'.urlencode($recoveryCode);
				$mail->Body .= EMAIL_PASSWORD_RECOVERY_CONTENT_CLOSE;

				$mail->Send();

				// Return success
				$_SESSION["messages"][] = array("success", SUCCESS_PASSWORD_RECOVERY_EMAIL_SENT);
				return true;
			}
		}
	}

	/**
	 * 	Direct login when clicking on Password Recovery email link
	 */
	public function recoveryLogin($userid, $code) {
		$sql = 'SELECT 	user_id,
						user_email,
						password_recovery_code,
						user_status,
						user_level 
				FROM 	users 
				WHERE 	user_id = :uid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':uid' => $userid));
		if ($query->rowCount() != 1) {
			$_SESSION["messages"][] = array("error", ERROR_USER_NOT_FOUND);
			return false;
		} else {
			$user = $query->fetch();
			if (is_null($user->password_recovery_code) OR ($user->password_recovery_code != $code)) {
				$_SESSION["messages"][] = array("error", ERROR_RECOVERY_CODE_INVALID);
				return false;
			} elseif ($user->user_status != 1) {
				$_SESSION["messages"][] = array("error", ERROR_USER_ACCOUNT_LOCKED);
				return false;
			} else {
				// Set session data
				Session::set('user_logged_in', true);
				Session::set('user_id', $user->user_id);
				Session::set('user_email', $user->user_email);
				Session::set('user_level', $user->user_level);

				// Reset recovery code
				$sql = 'UPDATE 	users
						SET 	password_recovery_code = null 
						WHERE 	user_id = :userid';
				$query = $this->db->prepare($sql);
				$query->execute(array(':userid' => $user->user_id));

				return true;
			}
		}

	}

}

?>