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
	 * 	Get single building info
	 */
	public function getBuilding($buildingid) {
		$sql = 'SELECT 	building_id,
						building_name,
						building_type  
				FROM 	buildings 
				WHERE 	building_id = :buildingid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':buildingid' => $buildingid));
		return $query->fetch();
	}

	/**
	 * 	Get building level info
	 */
	public function getBuildingLevels($buildingid) {
		$sql = 'SELECT 	stats_entryid,
						building_id,
						building_level,
						build_time,
						build_cost,
						build_cost_type,
						build_armcost,
						build_armcost_type,
						production,
						capacity 
				FROM 	building_levels 
				WHERE 	building_id = :buildingid 
				ORDER BY building_level ASC';
		$query = $this->db->prepare($sql);
		$query->execute(array(':buildingid' => $buildingid));
		return $query->fetchAll();
	}

	/**
	 * 	Get building TH requirements
	 */
	public function getBuildingTHReqs($buildingid) {
		$sql = 'SELECT 	th_req_map_id,
						building_id,
						th_level,
						max_count,
						max_level 
				FROM 	th_req_map 
				WHERE 	building_id = :buildingid 
				ORDER BY th_level ASC';
		$query = $this->db->prepare($sql);
		$query->execute(array(':buildingid' => $buildingid));
		return $query->fetchAll();
	}

	/**
	 * 	Get list of all troops
	 */
	public function getTroopList($type) {
		$sql = 'SELECT 	troop_id,
						troop_name 
				FROM 	troops ';
		if ($type) { $sql .= 'WHERE troop_type = :type '; }
		$sql .= 'ORDER BY troop_id ASC';
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

	/**
	 * 	Get single troop info
	 */
	public function getTroop($troopid) {
		$sql = 'SELECT 	troop_id,
						troop_type,
						troop_name,
						troop_space,
						train_time,
						barracks_req,
						target,
						attack_type 
				FROM 	troops 
				WHERE 	troop_id = :troopid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':troopid' => $troopid));
		return $query->fetch();
	}

	/**
	 * 	Get troop level info
	 */
	public function getTroopLevels($troopid) {
		$sql = 'SELECT 	entry_id,
						troop_id,
						troop_level,
						lab_level_required,
						research_time,
						research_cost,
						train_cost 
				FROM 	troop_levels  
				WHERE 	troop_id = :troopid 
				ORDER BY troop_level ASC';
		$query = $this->db->prepare($sql);
		$query->execute(array(':troopid' => $troopid));
		return $query->fetchAll();
	}

}

?>