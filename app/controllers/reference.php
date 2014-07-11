<?php

class Reference extends Controller {

	public function __construct() {
		parent::__construct();
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

}

?>