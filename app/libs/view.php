<?php

class View {

	public function render($view_path) {
		require VIEWS_PATH.'_templates/header.php';
		require VIEWS_PATH.'_templates/banner_nav.php';
		require VIEWS_PATH.$view_path.'.php';
		require VIEWS_PATH.'_templates/footer.php';
	}

	public function form() {
		require VIEWS_PATH.'_templates/form.php';
	}

	public function showMessage() {
		require VIEWS_PATH.'_templates/message.php';
		
		Session::set('msg_errors', null);
		Session::set('msg_success', null);
	}
}

?>