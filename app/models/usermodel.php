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

}

?>