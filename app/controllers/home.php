<?php

class Home extends Controller {

	// Home page - Default landing page
	public function index() {
		session_start();
		$userModel = $this->loadModel('userModel');
		require 'app/views/_templates/header.php';
		require 'app/views/home/index.php';
		require 'app/views/_templates/footer.php';
	}

}

?>
