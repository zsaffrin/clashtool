<?php

class User extends Controller {

	public function __construct() {
		parent::__construct();
		Auth::checkLogin();
	}

	// Default view behavior
	public function index() {
		header('Location: '.URL.'user/myAccount');
	}

	// User account self-service view
	public function myAccount() {
		$this->checkInterrupts();

		$userModel = $this->loadModel('user');
		$this->view->user = $userModel->getUser(Session::get('user_id'));

		// Set up Form options and inputs
		$userInfoForm = new stdClass();
		$userInfoForm->form_inputs = array(
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
		$userInfoForm->form_action = 'saveUserInfo';
		$userInfoForm->form_submit_label = 'Save Changes';
		$this->view->userinfo_form = $userInfoForm;

		$this->view->render('user/myaccount');
	}

	// Save user info updates
	public function saveUserInfo() {
		$userModel = $this->loadModel('user');
		require HELPERS_PATH.'PHPMailer/PHPMailerAutoload.php';
		$userModel->saveUserInfo();
		header('Location: '.URL.'user/myAccount');
	}

	// User password self-service view - No verification of current password
	public function setNewPassword() {
		// If form submitted, apply new password. Else, display input form
		if (isset($_POST['submitted']) AND $_POST['submitted'] == 1) {
			$userModel = $this->loadModel('user');
			if ($userModel->setPassword()) {
				if (isset($_SESSION['force_password_reset'])) { unset($_SESSION['force_password_reset']); }
				header('Location: '.URL);
			} else {
				header('Location: '.URL.'user/setnewpassword');
			}
		} else {
			$setPasswordForm = new stdClass();
			$setPasswordForm->form_inputs = array(
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
			$setPasswordForm->form_action = 'setNewPassword';
			$setPasswordForm->form_submit_label = 'Set Password';
			$this->view->set_password_form = $setPasswordForm;

			// Render view
			$this->view->render('user/setnewpassword');
		}
	}

	// User password self-service view - With verification of current password
	public function changePassword() {
		// If form submitted, apply new password. Else, display input form
		if (isset($_POST['submitted']) AND $_POST['submitted'] == 1) {
			$userModel = $this->loadModel('user');
			if ($userModel->changePassword()) {
				header('Location: '.URL);
			} else {
				header('Location: '.URL.'user/changepassword');
			}
		} else {
			$changePasswordForm = new stdClass();
			$changePasswordForm->form_inputs = array(
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
			$changePasswordForm->form_action = 'changePassword';
			$changePasswordForm->form_submit_label = 'Change Password';
			$this->view->change_password_form = $changePasswordForm;

			// Render view
			$this->view->render('user/changepassword');
		}
	}

}

?>
