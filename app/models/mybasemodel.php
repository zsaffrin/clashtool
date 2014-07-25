<?php

class myBaseModel {

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
	 * 	Get user buildings
	 */
	public function getUserBuildings($userid) {
		$sql = 'SELECT 	entry_id,
						user_id,
						building_id,
						building_level,
						building_count 
				FROM 	user_buildings 
				WHERE 	user_id = :userid 
				ORDER BY building_id ASC, building_level DESC';
		$query = $this->db->prepare($sql);
		$query->execute(array(':userid' => $userid));
		return $query->fetchAll();
	}

	/**
	 * 	Get list of all buildings
	 */
	public function getBuildingList() {
		$sql = 'SELECT 	building_id,
						building_name,
						building_type,
						building_subtype 
				FROM 	buildings 
				ORDER BY building_name ASC';
		$query = $this->db->prepare($sql);
		$query->execute();
		return $query->fetchAll();
	}

	/**
	 * 	Get TH Requirement mappings
	 */
	public function getTHReqs() {
		$sql = 'SELECT 	th_req_map_id,
						building_id,
						th_level,
						max_count,
						max_level 
				FROM 	th_req_map';
		$query = $this->db->prepare($sql);
		$query->execute();
		return $query->fetchAll();
	}

	/**
	 * 	Get user's allowed buildings (by TH level) and current building levels
	 */
	public function getBuildingSet($userid) {
		// Get data
		$userBuildingCounts = $this->getUserBuildings($userid);
		$buildingList = $this->getBuildingList();
		$thReqs = $this->getTHReqs();
		
		// Assemble user building set
		$userBuildings = array();
		$counts = array();
		foreach ($userBuildingCounts as $c) {
			for ($i=0;$i<$c->building_count;$i++) {
				// Set up building
				$building = new stdClass();
				$building->building_id = $c->building_id;
				$building->building_level = $c->building_level;
				if ($c->building_id == 1) { $thLevel = $c->building_level; }

				// Set building count
				if (isset($counts[$c->building_id])) { 
					$counts[$c->building_id] = abs($counts[$c->building_id]+1);
				} else {
					$counts[$c->building_id] = 1;
				}
				$building->building_num = $counts[$c->building_id];

				// Add building to set
				$userBuildings[] = $building;
			}
		}

		// Create allowed building set based on TH Level
		$buildingSet = array();
		foreach ($thReqs as $r) {
			if ($r->th_level == $thLevel) {
				for ($i=1;$i<=$r->max_count;$i++) {
					// Set up building
					$building = new stdClass();
					$building->building_id = $r->building_id;
					$building->building_level = 0;
					$building->max_level = $r->max_level;
					$building->building_num = $i;

					// Set friendly name and type
					foreach ($buildingList as $b) {
						if ($r->building_id == $b->building_id) {
							$building->building_name = $b->building_name;
							$building->type = $b->building_type;
							$building->subtype = $b->building_subtype;
						}
					}

					// Set user level for this building if present
					foreach ($userBuildings as $u) {
						if ($u->building_id == $r->building_id AND $u->building_num == $i) {
							$building->building_level = $u->building_level;
						}
					}

					// Add building to set
					$buildingSet[] = $building;
				}
			}
		}

		// Return building set
		return $buildingSet;
	}


}

?>