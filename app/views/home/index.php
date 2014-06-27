<div class="section">
	<div class="container">
		<div class="size-1-3 grid-drop">
			<p>This is the Home/index View!
			<?php
				Home::showMessage();

				if (isset($user)) {
					echo '<p>User Status: <b>'.$user->level.'</b><br>';
					print_r($user);
				} else {
					echo '<p>No user object found';
				}
			?>
		</div>
	</div>
</div>


