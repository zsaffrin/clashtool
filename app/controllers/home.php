<?php

class Home extends Controller {
	
	public function index() {
		require 'app/views/_templates/page.header.php';
		require 'app/views/home/index.php';
		require 'app/views/_templates/page.footer.php';
	}

}

?>