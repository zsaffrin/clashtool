<?php

class User extends Controller {

	public function __construct() {
		parent::__construct();
		Auth::checkLogin();
	}

	// Default view behavior
	public function index() {
		// Interrupt if password reset is required
		if (isset($_SESSION['force_password_reset']) AND $_SESSION['force_password_reset'] == true) {
			header('Location: '.URL.'user/setPassword');
		}

		$this->view->render('user/index');
	}

	// User account self-service view
	public function myAccount() {
		// Interrupt if password reset is required
		if (isset($_SESSION['force_password_reset']) AND $_SESSION['force_password_reset'] == true) {
			header('Location: '.URL.'user/setPassword');
		}

		$userModel = $this->loadModel('userModel');
		$this->view->user = $userModel->getUser(Session::get('user_id'));

		// Set up Form options and inputs
		$this->view->form_inputs = array(
			array(
				'id' => 'firstname', 
				'type' => 'text',
				'title' => 'First Name',
				'value' => $this->view->user->user_firstname,
				'icon' => 'user'),
			array(
				'id' => 'lastname', 
				'type' => 'text',
				'title' => 'Last Name',
				'value' => $this->view->user->user_lastname,
				'icon' => 'user'),
			array(
				'id' => 'email', 
				'type' => 'text',
				'title' => 'Email',
				'value' => $this->view->user->user_email,
				'icon' => 'envelope-o'));
		$this->view->form_action = 'saveUserData';
		$this->view->form_submit_label = 'Save Changes';

		$this->view->render('user/myaccount');
	}

	// Save user info updates
	public function saveUserData() {
		$userModel = $this->loadModel('userModel');
		require HELPERS_PATH.'PHPMailer/PHPMailerAutoload.php';
		$userModel->saveUserData();
		header('Location: '.URL.'user/myAccount');
	}

	// User password self-service view - with authentication
	public function changePassword() {
		// Set up Form and inputs
		$this->view->form_inputs = array(
			array(
				'id' => 'password', 
				'type' => 'password',
				'title' => 'Current Password',
				'icon' => 'key'),
			array(
				'id' => 'new_password', 
				'type' => 'password',
				'title' => 'New Password',
				'icon' => 'key'),
			array(
				'id' => 'new_password_confirm', 
				'type' => 'password',
				'title' => 'Confirm New Password',
				'icon' => 'key'));
		$this->view->form_action = 'changePassword_action';
		$this->view->form_submit_label = 'Change Password';

		// Render view
		$this->view->render('user/changepassword');
	}

	// Save new user password
	public function changePassword_action() {
		$userModel = $this->loadModel('userModel');
		if ($userModel->changePassword()) {
			header('Location: '.URL.'user/myAccount');
		} else {
			header('Location: '.URL.'user/changePassword');
		}
		
	}

	// User password self-service view - No authentication
	public function setPassword() {
		// Set up Form and inputs
		$this->view->form_inputs = array(
			array(
				'id' => 'new_password', 
				'type' => 'password',
				'title' => 'New Password',
				'icon' => 'key'),
			array(
				'id' => 'new_password_confirm', 
				'type' => 'password',
				'title' => 'Confirm New Password',
				'icon' => 'key'),
			array(
				'id' => 'userid', 
				'type' => 'hidden',
				'value' => Session::get('user_id')));
		$this->view->form_action = 'setPassword_action';
		$this->view->form_submit_label = 'Set Password';

		// Render view
		$this->view->render('user/resetpassword');
	}

	// Force-set user password
	public function setPassword_action() {
		$userModel = $this->loadModel('userModel');
		if ($userModel->setPassword()) {
			unset($_SESSION['force_password_reset']);
			header('Location: '.URL);
		} else {
			header('Location: '.URL.'user/setPassword');
		}
	}

}

?>
