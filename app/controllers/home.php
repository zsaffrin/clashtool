<?php

class Home extends Controller {
	
	// Home page - Default landing page
	public function index() {
		session_start();
		$userModel = $this->loadModel('userModel');
		require 'app/views/_templates/page.header.php';
		require 'app/views/home/index.php';
		require 'app/views/_templates/page.footer.php';
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
					header('location: '.URL.'home/login');
				}
			} else {
				$_SESSION['msg'] = 'Missing Username and/or Password';
				header('location: '.URL.'home/login');
			}
			
		} else {
			require 'app/views/_templates/page.header.php';
			require 'app/views/home/login.php';
			require 'app/views/_templates/page.footer.php';
		}
	}

	// Log Out
	public function logout() {
		session_start();
		$userModel = $this->loadModel('userModel');
		$userModel->destroySession();
		header('location: '.URL);
	}

}

?>
