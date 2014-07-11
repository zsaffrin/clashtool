<?php

class referenceModel {

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
	 * 	Get list of all buildings
	 */
	public function getBuildingList($type) {
		$sql = 'SELECT 	building_id,
						building_name
				FROM 	buildings ';
		if ($type) { $sql .= 'WHERE building_type = :type '; }
		$sql .= 'ORDER BY building_name ASC';
		$query = $this->db->prepare($sql);
		if ($type) {
			$query->execute(array(':type' => $type));
		} else {
			$query->execute();
		}
		if ($query->rowCount()>=1) {
			return $query->fetchAll();
		} else {
			$_SESSION["msg_errors"][] = ERROR_NO_BUILDINGS_FOUND; 
			return false;
		}
	}

	/**
	 * 	Get list of all buildings
	 */
	public function getTroopList($type) {
		$sql = 'SELECT 	troop_id,
						troop_name 
				FROM 	troops ';
		if ($type) { $sql .= 'WHERE troop_type = :type '; }
		$sql .= 'ORDER BY troop_name ASC';
		$query = $this->db->prepare($sql);
		if ($type) {
			$query->execute(array(':type' => $type));
		} else {
			$query->execute();
		}
		if ($query->rowCount()>=1) {
			return $query->fetchAll();
		} else {
			$_SESSION["msg_errors"][] = ERROR_NO_TROOPS_FOUND; 
			return false;
		}
	}

}

?>