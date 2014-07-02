<?php

class View {

	public function render($view_path) {
		require VIEWS_PATH.'_templates/header.php';
		require VIEWS_PATH.'_templates/banner_nav.php';
		require VIEWS_PATH.$view_path.'.php';
		require VIEWS_PATH.'_templates/footer.php';
	}
}

?>