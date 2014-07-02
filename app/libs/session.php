<?php

class Session {

	/**
	 * 	Start the session
	 */
	public static function start() {
		// If no active session, start one
		if (session_id()=='') {
			session_start();
		}
	}

	/**
	 * 	set a specific session key to a given value
	 */
	public static function set($key, $value) {
		$_SESSION[$key] = $value;
	}

	/**
	 * 	get the value of a specific session key
	 */
	public static function get($key) {
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}
	}

	/**
	 * 	destroy the session / log out
	 */
	public static function destroy() {
		session_destroy();
	}

}

?>