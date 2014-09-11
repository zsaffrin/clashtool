<?php

class Admin extends Controller {

	public function __construct() {
		parent::__construct();
		Auth::checkLogin();

		// Admins only
		if (!Auth::checkLevel(4)) {
			header('location: '.URL);
		}
	}

	// User admin home page
	public function users() {
		$adminModel = $this->loadModel('adminModel');
		$userList = $adminModel->getAllUsers();
		$this->view->user_list = $userList;
		$this->view->render('admin/users');
	}

	// Add new user
	public function addUser() {
		// Set up Form options and inputs
		$this->view->form_inputs = array(
			array(
				'id' => 'firstname', 
				'type' => 'text',
				'title' => 'First Name'),
			array(
				'id' => 'lastname', 
				'type' => 'text',
				'title' => 'Last Name'),
			array(
				'id' => 'email', 
				'type' => 'text',
				'title' => 'Email'),
			array(
				'id' => 'new_password', 
				'type' => 'password',
				'title' => 'Password'),
			array(
				'id' => 'new_password_confirm', 
				'type' => 'password',
				'title' => 'Confirm Password'));
		$this->view->form_action = 'addUser_action';
		$this->view->form_submit_label = 'Add User';

		// Render the view
		$this->view->render('admin/adduser');
	}

	// Add new user action
	public function addUser_action() {
		$adminModel = $this->loadModel('adminModel');
		if ($adminModel->insertUser()) {
			header('Location: '.URL.'admin/users');
		} else {
			$this->addUser();
		}
		
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

}

?>