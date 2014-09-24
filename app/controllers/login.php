<?php

class Login extends Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	* 	Default login page
	**/
	public function index() {
		$this->view->page_id = 'login';

		// Set up Form and inputs
		$this->view->form_inputs = array(
			array(
				'id' => 'email', 
				'type' => 'text',
				'title' => 'Email',
				'icon' => 'envelope-o'), 
			array(
				'id' => 'password', 
				'type' => 'password',
				'title' => 'Password',
				'icon' => 'key'));
		$this->view->form_action = 'login/login';
		$this->view->form_submit_label = 'Log In';

		// Render view
		$this->view->render_noLeftNav('login/index');
	}

	/**
	* 	Log in
	**/
	public function login() {
		$loginModel = $this->loadModel('loginModel');
		$loginSuccess = $loginModel->login();
		if ($loginSuccess) {
			if (isset($_SESSION['force_password_reset']) AND $_SESSION['force_password_reset'] == true) {
				header('Location: '.URL.'user/setPassword');
			} else {
				header('Location: '.URL);
			}
		} else {
			header('Location: '.URL.'login');
		}
	}

	/**
	* 	Log out
	**/
	public function logout() {
		$loginModel = $this->loadModel('loginModel');
		$loginModel->logout();
		header('location: '.URL);
	}
	
	/**
	* 	Signup page
	**/
	public function signup() {
		$this->view->page_id = 'signup';

		// Set up Form and inputs
		$this->view->form_inputs = array(
			array(
				'id' => 'email', 
				'type' => 'text',
				'title' => 'Email',
				'icon' => 'envelope-o'),
			array(
				'id' => 'new_password', 
				'type' => 'password',
				'title' => 'Password',
				'icon' => 'lock'),
			array(
				'id' => 'new_password_confirm', 
				'type' => 'password',
				'title' => 'Confirm Password',
				'icon' => 'lock'));
		$this->view->form_action = 'signup_action';
		$this->view->form_submit_label = 'Sign Up';

		// Render view
		$this->view->render_noLeftNav('login/signup');
	}

	/**
	* 	Signup action
	**/
	public function signup_action() {
		$loginModel = $this->loadModel('loginModel');
		require HELPERS_PATH.'PHPMailer/PHPMailerAutoload.php';
		
		$signupSuccess = $loginModel->signup();
		if ($signupSuccess) {
			header('Location: '.URL.'login/signup_success');
		} else {
			header('Location: '.URL.'login/signup');
		}

	}

	/**
	* 	Signup success confirmation
	**/
	public function signup_success() {
		$this->view->page_id = 'signup';
		$this->view->render_noLeftNav('login/signup-success');
	}

	/**
	 * 	Verify user email
	 * 	@param $uid 	User ID to verify
	 * 	@param $code 	Verification code to confirm
	 * 	@param $unlock 	Unlock account once verified - 0 = No (Default); 1 = Yes
	 */
	public function verify_email($uid, $code, $unlock=0) {
		$this->view->page_id = 'login';
		$loginModel = $this->loadModel('loginModel');
		$verifySuccess = $loginModel->verifyEmail($uid, $code, $unlock);
		if ($verifySuccess) {
			header('Location: '.URL);
		} else {
			$this->view->render_noLeftNav('login/message');
		}
	}

	/**
	* 	Password recovery page
	**/
	public function forgotPassword() {
		$this->view->page_id = 'login';

		// Set up Form and inputs
		$this->view->form_inputs = array(
			array(
				'id' => 'email', 
				'type' => 'text',
				'title' => 'Email',
				'icon' => 'envelope-o'));
		$this->view->form_action = 'forgotPassword_action';
		$this->view->form_submit_label = 'Reset Password';

		// Render view
		$this->view->render_noLeftNav('login/forgotpassword');
	}

	/**
	* 	Password recovery action
	**/
	public function forgotPassword_action() {
		$this->view->page_id = 'login';
		$loginModel = $this->loadModel('loginModel');
		require HELPERS_PATH.'PHPMailer/PHPMailerAutoload.php';
		$loginModel->forgotPassword();
		$this->view->render_noLeftNav('login/message');
	}

	/**
	* 	Password recovery action in response to clicked email link
	* 	@param $userid 	User ID to reset
	* 	@param $code 	Password recovery code
	**/
	public function recoverPassword($userid, $code) {
		$this->view->page_id = 'login';
		$loginModel = $this->loadModel('loginModel');
		$recoverySuccess = $loginModel->recoveryLogin($userid, $code);
		if ($recoverySuccess) {
			header('Location: '.URL.'user/setPassword');
		} else {
			$this->view->render_noLeftNav('login/message');
		}
	}

}

?>