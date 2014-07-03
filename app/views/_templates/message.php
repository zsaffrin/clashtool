<div class="size-1-1">
	<?php

	$errors = Session::get('msg_errors');
	$success = Session::get('msg_success');

	if (isset($errors)) {
		echo '<p>';
		foreach ($errors as $msg) {
			echo $msg.'<br>';
		}
	}
	if (isset($success)) {
		echo '<p>';
		foreach ($success as $msg) {
			echo $msg.'<br>';
		}
	}
	
	?>
</div>