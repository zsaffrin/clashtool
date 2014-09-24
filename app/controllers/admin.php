<?php

class Admin extends Controller {

	public function __construct() {
		parent::__construct();
		Auth::checkLogin();

		// Admins only
		if (!Auth::checkLevel(4)) {
			header('location: '.URL);
		}

		// Interrupt if password reset is required
		if (isset($_SESSION['force_password_reset']) AND $_SESSION['force_password_reset'] == true) {
			header('Location: '.URL.'user/setPassword');
		}
	}

	// Default landing page
	public function index() {
		$this->users();
	}

	// User admin home page
	public function users() {
		$adminModel = $this->loadModel('adminModel');

		// Get user data
		$userList = $adminModel->getAllUsers();
		$this->view->user_list = $userList;

		// Render view
		$this->view->render('admin/users');
	}

	// Trigger flag to force user to choose a new password upon next login
	public function force_password_reset($userid) {
		$adminModel = $this->loadModel('adminModel');
		$adminModel->force_password_reset($userid);
		header('location: '.URL.'admin/users');
	}

	// Trigger email verification process
	public function trigger_email_verification($userid) {
		$adminModel = $this->loadModel('adminModel');
		require HELPERS_PATH.'PHPMailer/PHPMailerAutoload.php';
		$adminModel->verifyUserEmail($userid);
		header('location: '.URL.'admin/users');
	}

	// Toggle user lock status
	public function toggleUserLock($userid) {
		$adminModel = $this->loadModel('adminModel');
		$user = $adminModel->getUser($userid);
		if ($user->user_status == 1) {
			$adminModel->setUserStatus($userid, 2);
		} elseif ($user->user_status == 2) {
			$adminModel->setUserStatus($userid, 1);
		} 
		header('location: '.URL.'admin/users');
	}

	// Toggle user activation status
	public function activateUser($userid) {
		$adminModel = $this->loadModel('adminModel');
		require HELPERS_PATH.'PHPMailer/PHPMailerAutoload.php';
		$user = $adminModel->getUser($userid);
		
		$adminModel->setUserStatus($userid, 2);
		$adminModel->verifyUserEmail($userid, 1);
		header('location: '.URL.'admin/users');
	}

	// Add new user
	public function addUser() {
		$adminModel = $this->loadModel('adminModel');
		$adminModel->createUser();
		header('Location: '.URL.'admin/users');
	}

	// Edit user
	public function editUser($userid) {
		$adminModel = $this->loadModel('adminModel');
		$userInfo = $adminModel->getUser($userid);
		$this->view->user_info = $userInfo;

		$this->view->render('admin/edituser');
	}

	// Change user password
	public function resetPassword($userid) {
		$adminModel = $this->loadModel('adminModel');

		// Get and set personal user data
		$userInfo = $adminModel->getUser($userid);
		$this->view->user_id = $userInfo->user_id;
		$this->view->user_firstname = $userInfo->user_firstname;
		$this->view->user_lastname = $userInfo->user_lastname;
		$this->view->user_email = $userInfo->user_email;

		// Set up form and inputs
		$this->view->form_inputs = array(
			array(
				'id' => 'new_password', 
				'type' => 'password',
				'title' => 'New Password'),
			array(
				'id' => 'new_password_confirm', 
				'type' => 'password',
				'title' => 'Confirm New Password')
		);
		$this->view->form_action = '../resetPassword_action/'.$userid;
		$this->view->form_submit_label = 'Save New Password';

		// Render the view
		$this->view->render('admin/resetpassword');
	}

	// Change user password action
	public function resetPassword_action($userid) {
		$adminModel = $this->loadModel('adminModel');
		if ($adminModel->resetPassword($userid)) {
			header('location: '.URL.'admin/users');
		} else {
			header('location: '.URL.'admin/resetPassword/'.$userid);
		}
	}

	// Delete user
	public function deleteUser($userid) {
		$adminModel = $this->loadModel('adminModel');
		$userInfo = $adminModel->getUser($userid);
		$this->view->user_info = $userInfo;

		$this->view->render('admin/deleteuser');
	}

	// Delete user
	public function deleteUser_action($userid) {
		$adminModel = $this->loadModel('adminModel');
		$adminModel->deleteUser($userid);
		header('location: '.URL.'admin/users');
	}

}

?>