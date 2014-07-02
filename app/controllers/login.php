<?php

class Login extends Controller {

	/**
	* 	Default login page
	**/
	function index() {
		// If form is submitted
		if (isset($_POST['submitted'])) {
			if (!empty($_POST['username'])&&!empty($_POST['password'])) {
				$loginModel = $this->loadModel('loginModel');
				$loginSuccess = $loginModel->login();
				if ($loginSuccess) { 
					header('Location: '.URL);
				}
			} else {
				Session::set('msg', ERROR_LOGIN_FIELDS_EMPTY);
			}
		}
		
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
		$this->view->form_action = 'login';
		$this->view->form_submit_label = 'Log In';

		// Render view
		$this->view->render('login/index');
	}

	/**
	* 	Log out
	**/
	function logout() {
		$loginModel = $this->loadModel('loginModel');
		$loginModel->logout();
		header('location: '.URL);
	}
}

?>