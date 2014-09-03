<?php

class Home extends Controller {

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
