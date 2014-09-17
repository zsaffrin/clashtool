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
	 * 	Get building level details
	 */
	public function getBuildingLevels() {
		$sql = 'SELECT 	building_id,
						building_level,
						build_time,
						build_cost,
						build_cost_type,
						build_armcost,
						build_armcost_type,
						production,
						capacity 
				FROM 	building_levels';
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
	 * 	Get troop level details
	 */
	public function getTroopLevels() {
		$sql = 'SELECT 	troop_id,
						troop_level,
						lab_level_required,
						research_time,
						research_cost,
						research_cost_type,
						train_cost,
						train_cost_type,
						damage,
						hit_points  
				FROM 	troop_levels';
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
	 * 	Get user's TH level
	 */
	public function getUserTHLevel($userid) {
		$userBuildingCounts = $this->getUserBuildings($userid);
		$thLevel = 1;
		foreach ($userBuildingCounts as $c) {
			if ($c->building_id == 1) { $thLevel = $c->building_level; }
		}
		return $thLevel;
	}

	/**
	 * 	Get user's allowed buildings (by TH level) and current building levels
	 */
	public function getBuildingSet($userid) {
		// Get data
		$userBuildingCounts = $this->getUserBuildings($userid);
		$buildingList = $this->getBuildingList();
		$buildingLevels = $this->getBuildingLevels();
		$thReqs = $this->getTHReqs();
		
		// Assemble user building set
		$userBuildings = array();
		$counts = array();
		$thLevel = 1;
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

		// Create building set
		$buildingSet = array();

		// Town Hall
		$building = new stdClass();
		$building->item_class = 1;
		$building->item_id = 1;
		$building->level = 1;
		$building->max_level = 10;
		$building->building_num = 1;
		$building->name = "Town Hall";
		$building->type = 1;
		$building->subtype = 1;

		foreach ($userBuildings as $u) {
			if ($u->building_id == $building->item_id AND $u->building_num == $building->building_num) {
				$building->level = $u->building_level;
			}
		}

		$buildingSet[] = $building;


		// All other buildings
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

					// Append building level details
					foreach ($buildingLevels as $l) {
						if ($l->building_id == $building->item_id AND $l->building_level == $building->level) {
							$building->production = $l->production;
							$building->capacity = $l->capacity;
							$building->armcost = $l->build_armcost;
							$building->armcost_type = $l->build_armcost_type;
						}
					}

					// Add building to set
					$buildingSet[] = $building;
				}
			}
		}

		// Next level info
		foreach ($buildingSet as $b) {
			if ($b->level < $b->max_level) {
				foreach ($buildingLevels as $l) {
					if ($l->building_id == $b->item_id 
						AND $l->building_level == ($b->level + 1)) {

						$b->next_level_cost = $l->build_cost;
						$b->next_level_cost_type = $l->build_cost_type;
						$b->next_level_build_time = $l->build_time;
					}
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
		$troopLevels = $this->getTroopLevels();
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

		// Next level info
		foreach ($troopSet as $t) {
			if ($t->level < $t->max_level) {
				foreach ($troopLevels as $l) {
					if ($l->troop_id == $t->item_id 
						AND $l->troop_level == ($t->level + 1)) {

						$t->next_level_cost = $l->research_cost;
						$t->next_level_cost_type = $l->research_cost_type;
					}
				}
			}
		}

		// Return troop set
		return $troopSet;
	}

	/**
	 * 	Save building levels
	 */
	public function saveBuildingLevels($userid) {
		// Get data
		$userBuildings = $this->getBuildingSet($userid);
		$buildingList = $this->getBuildingList();
		$buildingLevels = $this->getBuildingLevels();

		$buildingCounts = array();
		foreach ($userBuildings as $u) {
			// Get levels from POST data
			if (isset($_POST[$u->item_id.'-'.$u->building_num])) {
				$u->level = $_POST[$u->item_id.'-'.$u->building_num];
			}

			// Add to building counts
			if (!isset($buildingCounts[$u->item_id][$u->level])) {
				$buildingCounts[$u->item_id][$u->level] = 0;
			}
			$buildingCounts[$u->item_id][$u->level] = abs($buildingCounts[$u->item_id][$u->level] + 1);
		}

		// Clear existing counts
		$sql = 'DELETE FROM user_buildings 
				WHERE 	user_id = :userid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':userid' => $userid));

		// Save new counts
		foreach ($buildingList as $b) {
			$increment = 1;
			for ($l=1;$l<=40;$l++) {
				if (isset($buildingCounts[$b->building_id][$l]) && ($buildingCounts[$b->building_id][$l] > 0)) {
					if ($this->insertUserBuildingCounts($userid, $b->building_id, $increment, $l, $buildingCounts[$b->building_id][$l])) {
						$increment++;
					} else {
						$_SESSION["messages"][] = array("error", ERROR_USER_BUILDING_INSERT_FAILED);
						return false;
					}
				}
			}
		}

		// Return success
		$_SESSION["messages"][] = array("success", SUCCESS_USER_BUILDINGS_SAVED);
		return true;

	}

	/**
	 * 	Insert user building counts
	 */
	public function insertUserBuildingCounts($userid, $buildingid, $increment, $level, $count) {
		$newID = abs(($userid * 1000) + ($buildingid * 10) + $increment);
		$sql = 'INSERT INTO user_buildings 	(entry_id, user_id, building_id, building_level, building_count)
				VALUES 						(:newid, :userid, :buildingid, :level, :count)';
		$query = $this->db->prepare($sql);
		$query->execute(array(
						':newid' => $newID,
						':userid' => $userid,
						':buildingid' => $buildingid,
						':level' => $level,
						':count' => $count));
		if ($query->rowCount() != 1) {
			$_SESSION["messages"][] = array("error", ERROR_USER_BUILDING_INSERT_FAILED);
			return false;
		} 

		// Return success
		return true;
	}

	/**
	 * 	Save troop levels
	 */
	public function saveTroopLevels($userid) {
		// Get data
		$troopList = $this->getTroopSet($userid);

		// Clear existing counts
		$sql = 'DELETE FROM user_troops 
				WHERE 	user_id = :userid';
		$query = $this->db->prepare($sql);
		$query->execute(array(':userid' => $userid));
		if ($query->rowCount() < 1) {
			$_SESSION["messages"][] = array("error", ERROR_USER_TROOP_CLEAR_FAILED);
			return false;
		}

		foreach ($troopList as $t) {
			// Collect data from form
			if (isset($_POST[$t->item_id])) {
				$t->level = $_POST[$t->item_id];
			}

			// Save levels to database
			if (!$this->insertUserTroop($userid, $t->item_id, $t->level)) {
				$_SESSION["messages"][] = array("error", ERROR_USER_TROOP_INSERT_FAILED);
				return false;
			}
		}

		// Return success
		$_SESSION["messages"][] = array("success", SUCCESS_USER_TROOPS_SAVED);
		return true;

	}

	/**
	 * 	Insert user troop level
	 */
	public function insertUserTroop($userid, $troopid, $level) {
		$newID = abs(($userid * 100) + $troopid);
		$sql = 'INSERT INTO user_troops	(entry_id, user_id, troop_id, troop_level) 
				VALUES 					(:newid, :userid, :troopid, :level)';
		$query = $this->db->prepare($sql);
		$query->execute(array(
						':newid' => $newID,
						':userid' => $userid,
						':troopid' => $troopid,
						':level' => $level));
		if ($query->rowCount() != 1) {
			$_SESSION["messages"][] = array("error", ERROR_USER_TROOP_INSERT_FAILED);
			return false;
		} 

		// Return success
		return true;
	}

	/**
	 * 	Calculate user's collector/mine/drill production
	 */
	public function getUserProduction($userid) {
		$buildings = $this->getBuildingSet($userid);
		
		// Set up Production count array
		$production = array();
		$production[1] = array('name' => "Gold", 'count' => 0);
		$production[2] = array('name' => "Elixir", 'count' => 0);
		$production[3] = array('name' => "Dark Elixir", 'count' => 0);

		foreach ($buildings as $b) {
			if (isset($b->production)) {
				if ($b->item_id == 24) { $production[1]['count'] = abs($production[1]['count'] + $b->production); }
				if ($b->item_id == 11) { $production[2]['count'] = abs($production[2]['count'] + $b->production); }
				if ($b->item_id == 26) { $production[3]['count'] = abs($production[3]['count'] + $b->production); }
			}
		}
		return $production;
	}


}

?>