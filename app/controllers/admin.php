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
		$userList = $adminModel->getUsers();
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
			header('Location: '.URL.'admin/addUser');
		}
		
	}

}

?>