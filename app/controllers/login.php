<?php

class Login extends Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	* 	Default login page
	**/
	public function index() {
		// Set up Form and inputs
		$this->view->form_inputs = array(
			array(
				'id' => 'username', 
				'type' => 'text',
				'title' => 'Username'), 
			array(
				'id' => 'password', 
				'type' => 'password',
				'title' => 'Password'));
		$this->view->form_action = 'login/login';
		$this->view->form_submit_label = 'Log In';

		// Render view
		$this->view->render('login/index');
	}

	/**
	* 	Log in
	**/
	public function login() {
		$loginModel = $this->loadModel('loginModel');
		$loginSuccess = $loginModel->login();
		if ($loginSuccess) {
			header('Location: '.URL);
		} else {
			header('Location: '.URL.'login');
		}
	}

	/**
	* 	Log out
	**/
	public function logout() {
		$loginModel = $this->loadModel('loginModel');
		$loginModel->logout();
		header('location: '.URL);
	}
}

?>