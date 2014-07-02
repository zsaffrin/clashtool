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
	public function getUser($userid=null) {
		if ($userid) {
			$sql = 'SELECT 	*
					FROM 	users 
					WHERE 	user_id = :userid';
			$query = $this->db->prepare($sql);
			$query->execute(array(':userid' => $userid));
			if ($query->rowCount() != 1) { 
				$_SESSION["msg_errors"][] = ERROR_USER_NOT_FOUND; 
				exit(ERROR_USER_NOT_FOUND);
			}
			$user = $query->fetch();
			return $user;

		} else {
			$_SESSION["msg_errors"][] = ERROR_USER_NOT_FOUND;
			exit(ERROR_USER_NOT_FOUND);
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
		$count = $query->rowCount();
		if ($query->rowCount()==1) {
			Session::set('user_firstname', $_POST['firstname']);
			Session::set('user_lastname', $_POST['lastname']);
			Session::set('user_email', $_POST['email']);
			return true;
		} else {
			$_SESSION["msg_errors"][] = ERROR_USER_UPDATE_FAILED; 
		}
	}
}

?>