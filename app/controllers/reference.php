<?php

class Reference extends Controller {

	public function __construct() {
		parent::__construct();
		
		// Interrupt if password reset is required
		if (isset($_SESSION['force_password_reset']) AND $_SESSION['force_password_reset'] == true) {
			header('Location: '.URL.'user/setPassword');
		}

		// Page parent for navigation
		$this->view->cur_page = "ref";
	}

	// Reference section home page
	public function index() {
		$referenceModel = $this->loadModel('referenceModel');
		
		// Get buildings
		$buildingList = $referenceModel->getBuildingList(1);
		$this->view->building_list = $buildingList;

		// Get traps
		$trapList = $referenceModel->getBuildingList(2);
		$this->view->trap_list = $trapList;

		// Get heroes
		$heroList = $referenceModel->getBuildingList(3);
		$this->view->hero_list = $heroList;

		// Get defenses
		$defenseList = $referenceModel->getBuildingList(4);
		$this->view->defense_list = $defenseList;

		// Get troops
		$troopList = $referenceModel->getTroopList(1);
		$this->view->troop_list = $troopList;

		// Get dark troops
		$darkTroopList = $referenceModel->getTroopList(2);
		$this->view->dark_troop_list = $darkTroopList;

		// Get spells
		$spellList = $referenceModel->getTroopList(3);
		$this->view->spell_list = $spellList;

		// Render view
		$this->view->render('reference/index');
	}

	// Building detail page
	public function building($buildingid) {
		$referenceModel = $this->loadModel('referenceModel');
		
		// Get building info
		$buildingInfo = $referenceModel->getBuilding($buildingid);
		$this->view->building_info = $buildingInfo;

		// Get building level details
		$buildingLevels = $referenceModel->getBuildingLevels($buildingid);
		$this->view->building_levels = $buildingLevels;

		// Get TH requirements
		$thReqs = $referenceModel->getBuildingTHReqs($buildingid);
		$this->view->th_reqs = $thReqs;

		// Render view
		$this->view->render('reference/building');
	}

	// Troop detail page
	public function troop($troopid) {
		$referenceModel = $this->loadModel('referenceModel');
		
		// Get troop info
		$troopInfo = $referenceModel->getTroop($troopid);
		$this->view->troop_info = $troopInfo;

		// Get troop level details
		$troopLevels = $referenceModel->getTroopLevels($troopid);
		$this->view->troop_levels = $troopLevels;

		// Render view
		$this->view->render('reference/troop');
	}

}

?>