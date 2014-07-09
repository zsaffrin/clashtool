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
				'id' => 'email', 
				'type' => 'text',
				'title' => 'Email'), 
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

	/**
	* 	Reset password without validation
	* 	Used for Forgot Password (via email) and Admin-initiated reset
	**/
	public function resetPassword($userid) {
		// Admins only
		if (!Auth::checkLevel(4)) {
			header('location: '.URL);
		}

		// Fetch target user info
		$loginModel = $this->loadModel('loginModel');
		$userInfo = $loginModel->getUserInfo($userid);
		if (!$userInfo) {
			header('location: '.URL);
		} else {
			$this->view->user_firstname = $userInfo->user_firstname;
			$this->view->user_lastname = $userInfo->user_lastname;
		}

		// Set up form and inputs
		$this->view->form_inputs = array(
			array(
				'id' => 'new_password', 
				'type' => 'password',
				'title' => 'New Password'),
			array(
				'id' => 'new_password_confirm', 
				'type' => 'password',
				'title' => 'Confirm New Password'));
		$this->view->form_hidden_fields = array(
			array(
				'id' => 'userid', 
				'value' => $userid
			)
		);
		$this->view->form_action = 'resetPassword_action';
		$this->view->form_submit_label = 'Save New Password';

		// Render view
		$this->view->render('login/resetpassword');
	}

	/**
	* 	Save new password
	**/
	public function resetPassword_action() {
		$loginModel = $this->loadModel('loginModel');
		if ($loginModel->resetPassword()) {
			header('location: '.URL.'login/resetPassword');	
		} else {
			header('location: '.URL);
		}
		
	}
}

?>