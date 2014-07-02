<?php

class Login extends Controller {

	/**
	* 	Default login page
	**/
	function index() {
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