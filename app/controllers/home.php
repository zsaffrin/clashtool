<?php

class Home extends Controller {

	public function __construct() {
		parent::__construct();

		// Interrupt if password reset is required
		if (isset($_SESSION['force_password_reset']) AND $_SESSION['force_password_reset'] == true) {
			header('Location: '.URL.'user/setPassword');
		}
	}

	// Home page - Default landing page
	public function index() {
		if (Session::get('user_logged_in')) {
			header('Location: '.URL.'mybase');
		} else {
			header('Location: '.URL.'login');
		}
	}

	

}

?>
