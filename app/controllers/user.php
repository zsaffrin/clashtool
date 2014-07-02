<?php

class User extends Controller {

	// User account self-service
	public function myAccount() {
		$this->view->render('user/myaccount');
	}
}

?>
