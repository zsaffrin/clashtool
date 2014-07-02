<?php

class User extends Controller {

	public function __construct() {
		parent::__construct();
		Auth::checkLogin();
	}

	// User account self-service view
	public function myAccount() {
		$userModel = $this->loadModel('userModel');
		$this->view->user = $userModel->getUser(Session::get('user_id'));

		// Set up Form options and inputs
		$this->view->form_inputs = array(
			array(
				'id' => 'firstname', 
				'type' => 'text',
				'title' => 'First Name',
				'value' => $this->view->user->user_firstname),
			array(
				'id' => 'lastname', 
				'type' => 'text',
				'title' => 'Last Name',
				'value' => $this->view->user->user_lastname),
			array(
				'id' => 'email', 
				'type' => 'text',
				'title' => 'Email',
				'value' => $this->view->user->user_email));
		$this->view->form_action = 'saveUserData';
		$this->view->form_submit_label = 'Save Changes';

		$this->view->render('user/myaccount');
	}

	// Save user info updates
	public function saveUserData() {
		$userModel = $this->loadModel('userModel');
		$userModel->saveUserData();
		header('Location: '.URL.'user/myAccount');
	}
}

?>
