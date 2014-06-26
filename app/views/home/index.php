<div class="section">
	<div class="container">
		<div class="size-1-3 grid-drop">
			<p>This is the Home/index View!
			<?php
				if (isset($_SESSION['msg'])) {
					echo '<p>'.$_SESSION['msg'];
					unset($_SESSION['msg']);
				} else {
					echo '<p>No system message';
				}

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


