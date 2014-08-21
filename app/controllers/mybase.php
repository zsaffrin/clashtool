<?php

class myBase extends Controller {

	public function __construct() {
		parent::__construct();
		Auth::checkLogin();
		$this->view->cur_page = "mybase";
	}

	// Dashboard - Default landing page
	public function index() {
		$myBaseModel = $this->loadModel('myBaseModel');

		// Pass data
		$this->view->production = $myBaseModel->getUserProduction(Session::get('user_id'));

		// Render view
		$this->view->render('mybase/index');
	}

	// Buildings and Traps page
	public function buildings() {
		$myBaseModel = $this->loadModel('myBaseModel');

		// Get building set
		$buildingSet = $myBaseModel->getBuildingSet(Session::get('user_id'));

		// Pass data and render view
		$this->view->buildings = $buildingSet;
		$this->view->render('mybase/buildings');
	}

	// Save changes to Buildings
	public function buildings_action() {
		$myBaseModel = $this->loadModel('myBaseModel');
		$myBaseModel->saveBuildingLevels(Session::get('user_id'));
		header('Location: '.URL.'mybase/buildings');
	}

	// Troops and Spells page
	public function troops() {
		$myBaseModel = $this->loadModel('myBaseModel');

		// Get troop set
		$troopSet = $myBaseModel->getTroopSet(Session::get('user_id'));

		// Pass data and render view
		$this->view->troops = $troopSet;
		$this->view->render('mybase/troops');
	}

	// Troops and Spells page
	public function troops_action() {
		$myBaseModel = $this->loadModel('myBaseModel');
		$myBaseModel->saveTroopLevels(Session::get('user_id'));
		header('Location: '.URL.'mybase/troops');
	}

}

?>
