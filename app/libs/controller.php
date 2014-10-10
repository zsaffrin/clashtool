<?php

class Controller {

	/**
	 * 	@var 	null 	Database Connection
	 */
	public $db = null;

	/**
	 * 	Upon construction, open DB connection
	 * 	One DB connection will be passed between all models
	 */
	function __construct() {
		Session::start();
		$this->openDatabaseConnection();
		$this->view = new View();
	}

	/**
	 * 	Open Database Connection
	 * 	Credentials are defined in app/config/config.php
	 */
	private function openDatabaseConnection() {
		// 	Set PDO default options
		// 	Default Fetch mode: Object
		$options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);

		// Open PDO database connection
		$this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS, $options);
	}

	/**
	 * 	Load a given model
	 * 	Model will be passed active DB connection object when called
	 */
	public function loadModel($model_name) {
		// Convert model name to lowercase and load model
		require MODELS_PATH.strtolower($model_name).'.php';
		// Return model object with common database connection
		$model_title = $model_name.'Model';
		return new $model_title($this->db);
	}

	/**
	 * 	Check interrupts
	 * 	Take alternate action if any trigger conditions met
	 */
	protected function checkInterrupts() {
		if (isset($_SESSION['force_password_reset']) AND ($_SESSION['force_password_reset'] == true)) { 
			header('Location: '.URL.'user/setNewPassword'); 
		}
	}

}

?>
