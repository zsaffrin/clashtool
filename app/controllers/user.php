<?php

class User extends Controller {

	// Default
	public function index() {
		$this->account();
	}

	// Log In
	public function login() {
		session_start();
		$userModel = $this->loadModel('userModel');
		if (isset($_POST['submitted'])) {
			if (!empty($_POST['username'])&&!empty($_POST['password'])) {
				if ($userModel->authenticate($_POST['username'], $_POST['password'])) {
					$_SESSION['msg'] = 'Logged In';
					header('location: '.URL);
				} else {
					$_SESSION['msg'] = 'Incorrect Username and/or Password';
					header('location: '.URL.'user/login');
				}
			} else {
				$_SESSION['msg'] = 'Missing Username and/or Password';
				header('location: '.URL.'user/login');
			}
		} else {
			require 'app/views/_templates/header.php';
			require 'app/views/user/login.php';
			require 'app/views/_templates/footer.php';
		}
	}

	// Log Out
	public function logout() {
		session_start();
		$userModel = $this->loadModel('userModel');
		$userModel->destroySession();
		header('location: '.URL);
	}

	// User account self-service
	public function myAccount() {
		session_start();
		$userModel = $this->loadModel('userModel');
		require 'app/views/_templates/header.php';
		require 'app/views/user/myaccount.php';
		require 'app/views/_templates/footer.php';
	}
}

?>
