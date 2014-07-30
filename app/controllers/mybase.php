<?php

class myBase extends Controller {

	public function __construct() {
		parent::__construct();
		Auth::checkLogin();
	}

	// Home page - Default landing page
	public function index() {
		$myBaseModel = $this->loadModel('myBaseModel');

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

	// Troops and Spells page
	public function troops() {
		$myBaseModel = $this->loadModel('myBaseModel');

		// Get troop set
		$troopSet = $myBaseModel->getTroopSet(Session::get('user_id'));

		// Pass data and render view
		$this->view->troops = $troopSet;
		$this->view->render('mybase/troops');
	}

}

?>
