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
		return new $model_name($this->db);
	}

	/**
	 * 	System message display handler
	 */
	public function showMessage() {
		if (isset($_SESSION['msg'])) {
			require 'app/views/_templates/sys_message.php';
		}
	}
}

?>
