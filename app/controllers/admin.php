<?php

class Admin extends Controller {

	public function __construct() {
		parent::__construct();
		Auth::checkLogin();
		Auth::checkLevel(3);
	}

	// Default landing page
	public function index() {
		$this->users();
	}

	/**
	* 	User management page
	**/
	public function users() {
		$adminModel = $this->loadModel('admin');

		// Prepare Add New User form
		$newUserForm = new stdClass();
		$newUserForm->form_inputs = array(
			0 => array(
				'id' => 'email', 
				'type' => 'text',
				'title' => 'Email',
				'icon' => 'envelope-o'));
		$newUserForm->form_action = URL.'admin/addUser';
		$newUserForm->form_submit_label = 'Add New User';
		$this->view->new_user_form = $newUserForm;

		// Fetch user data
		$userList = $adminModel->getAllUsers();
		$this->view->user_list = $userList;

		// Render view
		$this->view->render('admin/users');
	}

	/**
	* 	Toggle user lock status
	**/
	public function toggleUserLock($userid) {
		$adminModel = $this->loadModel('admin');
		$user = $adminModel->getUser($userid);
		if ($user->user_status == 1) {
			$adminModel->setUserStatus($userid, 2);
		} elseif ($user->user_status == 2) {
			$adminModel->setUserStatus($userid, 1);
		}
		header('Location: '.URL.'admin/users');
	}

	/**
	* 	Activate user
	* 	For approving pending user signup
	**/
	public function activateUser($userid) {
		$adminModel = $this->loadModel('admin');

		$adminModel->setUserStatus($userid, 2);
		$adminModel->verifyUserEmail($userid, 1);
		header('Location: '.URL.'admin/users');
	}

	// Trigger email verification process
	public function trigger_email_verification($userid) {
		$adminModel = $this->loadModel('admin');
		$adminModel->verifyUserEmail($userid);
		header('location: '.URL.'admin/users');
	}

	// Delete user
	public function deleteUser($userid, $confirmDelete=0) {
		$adminModel = $this->loadModel('admin');

		// Check if deletion confirmed, else confirm deletion
		if ($confirmDelete == 1) {
			$adminModel->deleteUser($userid);
			header('location: '.URL.'admin/users');
		} else {
			$userInfo = $adminModel->getUser($userid);
			$this->view->user_info = $userInfo;
			$this->view->render('admin/deleteuser');
		}
	}

	// Toggle flag to force user to choose a new password upon next login
	public function forcePasswordReset($userid) {
		$adminModel = $this->loadModel('admin');
		$adminModel->forcePasswordReset($userid);
		header('location: '.URL.'admin/users');
	}

	// Add new user
	public function addUser() {
		$adminModel = $this->loadModel('admin');
		$adminModel->createUser();
		header('Location: '.URL.'admin/users');
	}


}

?>
