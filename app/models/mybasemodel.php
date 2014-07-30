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
	 * 	Get user troops
	 */
	public function getUserTroops($userid) {
		$sql = 'SELECT 	entry_id,
						user_id,
						troop_id,
						troop_level 
				FROM 	user_troops 
				WHERE 	user_id = :userid 
				ORDER BY troop_id ASC';
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
	 * 	Get list of all troops
	 */
	public function getTroopList() {
		$sql = 'SELECT 	troop_id,
						troop_type,
						troop_name,
						troop_space,
						train_time,
						barracks_req 
				FROM 	troops 
				ORDER BY troop_id ASC';
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
	 * 	Get Laboratory Requirement mappings
	 */
	public function getLabReqs() {
		$sql = 'SELECT 	lab_req_map_id,
						troop_id,
						lab_level,
						max_level 
				FROM 	lab_req_map';
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
					$building->item_class = 1;
					$building->item_id = $r->building_id;
					$building->level = 0;
					$building->max_level = $r->max_level;
					$building->building_num = $i;

					// Set friendly name and type
					foreach ($buildingList as $b) {
						if ($r->building_id == $b->building_id) {
							$building->name = $b->building_name;
							$building->type = $b->building_type;
							$building->subtype = $b->building_subtype;
						}
					}

					// Set user level for this building if present
					foreach ($userBuildings as $u) {
						if ($u->building_id == $r->building_id AND $u->building_num == $i) {
							$building->level = $u->building_level;
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

	/**
	 * 	Get user's allowed troops (by lab and barracks levels) and current troop levels
	 */
	public function getTroopSet($userid) {
		// Get data
		$troopList = $this->getTroopList();
		$userTroopLevels = $this->getUserTroops($userid);
		$labReqs = $this->getLabReqs();
		$buildings = $this->getBuildingSet($userid);

		// Find key building levels
		$levels = array("lab" => 0, "splf" => 0, "brks" => 0, "dbrks" => 0);
		foreach ($buildings as $b) {
			if ($b->item_id == 21) { $levels["splf"] = $b->level; } // Spell Factory
			if ($b->item_id == 22) { $levels["lab"] = $b->level; } // Laboratory
			if (($b->item_id == 20) && ($b->level > $levels["dbrks"])) { $levels["dbrks"] = $b->level; } // Dark Barracks
			if (($b->item_id == 19) && ($b->level > $levels["brks"])) { $levels["brks"] = $b->level; } // Barracks
		}

		// Assemble User Troop set
		$userTroops = array();
		foreach ($userTroopLevels as $u) {
			$userTroops[$u->troop_id] = $u->troop_level;
		}

		// Create allowed troop set based on applicable building levels
		$troopSet = array();
		foreach ($troopList as $t) {
			if ((($t->troop_type == 1) AND ($t->barracks_req <= $levels["brks"]))
				OR (($t->troop_type == 2) AND ($t->barracks_req <= $levels["dbrks"]))
				OR (($t->troop_type == 3) AND ($t->barracks_req <= $levels["splf"]))) {

				$troop = new stdClass();
				$troop->item_class = 2;
				$troop->item_id = $t->troop_id;
				$troop->level = 1;
				$troop->type = $t->troop_type;
				$troop->name = $t->troop_name;

				// Find max level based on Lab level
				foreach ($labReqs as $l) {
					if (($t->troop_id == $l->troop_id) AND ($l->lab_level == $levels["lab"])) {
						$troop->max_level = $l->max_level;
					}
				}

				// Set user level for this troop if present
				if (isset($userTroops[$t->troop_id])) {
					$troop->level = $userTroops[$t->troop_id];
				}

				// Add troop to set
				$troopSet[] = $troop;
			}
		}

		// Return troop set
		return $troopSet;

	}

}

?>