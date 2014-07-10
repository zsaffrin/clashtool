<?php

class Home extends Controller {

	// Home page - Default landing page
	public function index() {
		$this->view->render('home/index');
	}

	

}

?>
