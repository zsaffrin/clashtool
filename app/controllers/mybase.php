<?php

class myBase extends Controller {

	public function __construct() {
		parent::__construct();
		Auth::checkLogin();
		$this->checkInterrupts();

		// Page parent for navigation
		$this->view->cur_page = "mybase";
	}

	/**
	* 	Default login page
	**/
	public function index() {
		// Render view
		header('Location: '.URL.'mybase/dashboard');
	}

	// Dashboard - Default landing page
	public function dashboard() {
		$myBaseModel = $this->loadModel('mybase');

		// Pass data
		$this->view->th_level = $myBaseModel->getUserTHLevel(Session::get('user_id'));
		$this->view->production = $myBaseModel->getUserProduction(Session::get('user_id'));

		// Render view
		$this->view->render('mybase/dashboard');
	}

	// Buildings and Resources page
	public function buildings() {
		$myBaseModel = $this->loadModel('mybase');

		// Get building set
		$buildingSet = $myBaseModel->getBuildingSet(Session::get('user_id'));

		// Pass data and render view
		$this->view->buildings = $buildingSet;
		$this->view->render('mybase/buildings');
	}

	// Defenses and Traps page
	public function defenses() {
		$myBaseModel = $this->loadModel('mybase');

		// Get building set
		$buildingSet = $myBaseModel->getBuildingSet(Session::get('user_id'));

		// Pass data and render view
		$this->view->buildings = $buildingSet;
		$this->view->render('mybase/defenses');
	}

	// Heroes page
	public function heroes() {
		$myBaseModel = $this->loadModel('mybase');

		// Get building set
		$buildingSet = $myBaseModel->getBuildingSet(Session::get('user_id'));

		// Pass data and render view
		$this->view->buildings = $buildingSet;
		$this->view->render('mybase/heroes');
	}

	// Save changes to Buildings
	public function save_buildings($return_page) {
		$myBaseModel = $this->loadModel('mybase');
		$myBaseModel->saveBuildingLevels(Session::get('user_id'));
		header('Location: '.URL.'mybase/'.$return_page);
	}

	// Troops and Spells page
	public function troops() {
		$myBaseModel = $this->loadModel('mybase');

		// Get troop set
		$troopSet = $myBaseModel->getTroopSet(Session::get('user_id'));

		// Pass data and render view
		$this->view->troops = $troopSet;
		$this->view->render('mybase/troops');
	}

	// Save changes to Troops
	public function save_troops($return_page) {
		$myBaseModel = $this->loadModel('mybase');
		$myBaseModel->saveTroopLevels(Session::get('user_id'));
		header('Location: '.URL.'mybase/'.$return_page);
	}









	// Save changes to Buildings
	public function buildings_action() {
		$myBaseModel = $this->loadModel('mybase');
		$myBaseModel->saveBuildingLevels(Session::get('user_id'));
		header('Location: '.URL.'mybase/buildings');
	}

	// Save changes to Defenses
	public function defenses_action() {
		$myBaseModel = $this->loadModel('mybase');
		$myBaseModel->saveBuildingLevels(Session::get('user_id'));
		header('Location: '.URL.'mybase/defenses');
	}

	// Heroes action
	public function heroes_action() {
		$myBaseModel = $this->loadModel('mybase');
		$myBaseModel->saveBuildingLevels(Session::get('user_id'));
		header('Location: '.URL.'mybase/heroes');
	}




	// Troops and Spells page
	public function troops_action() {
		$myBaseModel = $this->loadModel('mybase');
		$myBaseModel->saveTroopLevels(Session::get('user_id'));
		header('Location: '.URL.'mybase/troops');
	}

	

	// Walls page
	public function walls() {
		$myBaseModel = $this->loadModel('mybase');

		// Get wall data
		$this->view->wall_data = $myBaseModel->getWallData(Session::get('user_id'));

		// Render view
		$this->view->render('mybase/walls');
	}

	// Save changes to walls
	public function walls_action() {
		$myBaseModel = $this->loadModel('mybase');
		$myBaseModel->saveWallCounts(Session::get('user_id'));
		header('Location: '.URL.'mybase/walls');
	}

}

?>