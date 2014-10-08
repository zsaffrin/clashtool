<?php

class Login extends Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	* 	Default login page
	**/
	public function index() {
		// Action if form submitted
		if (isset($_POST['submitted']) AND $_POST['submitted'] == 1) {
			$loginModel = $this->loadModel('login');
			if ($loginModel->login()) {
				header('Location: '.URL);
			}
		}

		// Toggle alternate top-nav
		$this->view->signup_topnav = true;

		// Prepare form
		$loginForm = new stdClass();
		$loginForm->form_inputs = array(
			0 => array(
				'id' => 'email', 
				'type' => 'text',
				'title' => 'Email',
				'icon' => 'envelope-o'), 
			1 => array(
				'id' => 'password', 
				'type' => 'password',
				'title' => 'Password',
				'icon' => 'key'));
		if (isset($_POST['email']) AND !empty($_POST['email'])) { 
			$loginForm->form_inputs[0]['value'] = $_POST['email'];
		}
		$loginForm->form_action = URL.'login';
		$loginForm->form_submit_label = 'Log In';
		$this->view->login_form = $loginForm;

		// Render view
		$this->view->render('login/login', false);
	}

	/**
	* 	Log out
	**/
	public function logout() {
		$loginModel = $this->loadModel('login');
		$loginModel->logout();
		header('Location: '.URL);
	}

	/**
	* 	Signup page
	**/
	public function signup() {
		// Action if form submitted
		if (isset($_POST['submitted']) AND $_POST['submitted'] == 1) {
			$loginModel = $this->loadModel('login');
			require HELPERS_PATH.'PHPMailer/PHPMailerAutoload.php';
			if ($loginModel->signup()) {
				header('Location: '.URL.'login/signup_success');
			}
		}

		// Prepare form
		$signupForm = new stdClass();
		$signupForm->form_inputs = array(
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
		if (isset($_POST['email']) AND !empty($_POST['email'])) { 
			$signupForm->form_inputs[0]['value'] = $_POST['email'];
		}
		$signupForm->form_action = 'signup';
		$signupForm->form_submit_label = 'Sign Up';
		$this->view->signup_form = $signupForm;

		// Render view
		$this->view->render('login/signup', false);
	}

	/**
	* 	Log out
	**/
	public function signup_success() {
		$this->view->render('login/signup-success', false);
	}

	/**
	 * 	Verify email
	 * 	Action when responding to email verification link
	 * 	@param $uid 	User ID to verify
	 * 	@param $code 	Verification code to confirm
	 * 	@param $unlock 	Unlock account once verified - 0 = No (Default); 1 = Yes
	 */
	public function verify_email($uid, $code, $unlock=0) {
		$loginModel = $this->loadModel('login');
		$loginModel->verifyEmail($uid, $code, $unlock);
		$this->view->render('login/message', false);
	}

	/**
	* 	Password recovery page
	**/
	public function forgotPassword() {
		// If form submitted, recover password
		if (isset($_POST['submitted']) AND ($_POST['submitted'] == 1)) {
			$loginModel = $this->loadModel('login');
			require HELPERS_PATH.'PHPMailer/PHPMailerAutoload.php';
			if ($loginModel->recoverPassword()) {
				$this->view->render('login/forgotpassword-success', false);
			} else {
				header('Location: '.URL.'login/forgotPassword');
			}
		} else {
			// Set up Form and inputs
			$recoveryForm = new stdClass();
			$recoveryForm->form_inputs = array(
				array(
					'id' => 'email', 
					'type' => 'text',
					'title' => 'Email',
					'icon' => 'envelope-o'));
			$recoveryForm->form_action = 'forgotPassword';
			$recoveryForm->form_submit_label = 'Reset Password';
			$this->view->recovery_form = $recoveryForm;

			// Render view
			$this->view->render('login/forgotpassword', false);
		}
	}

	/**
	* 	Password recovery action in response to recovery email link
	* 	@param $userid 	User ID to reset
	* 	@param $code 	Password recovery code
	**/
	public function recoverPassword($userid, $code) {
		$loginModel = $this->loadModel('login');
		if ($loginModel->recoveryLogin($userid, $code)) {
			header('Location: '.URL.'user/setNewPassword');
		} else {
			$this->view->render('login/message', false);
		}
	}

}

?>