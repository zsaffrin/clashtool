<div class="section">
	<div class="container">
		<div class="size-1-3 grid-drop">&nbsp;</div>
		<div class="size-1-3 grid-fill align-c">
			<?php
			if (isset($_SESSION['msg'])) {
				echo '<p>'.$_SESSION['msg'];
				unset($_SESSION['msg']);
			}
			?>
			<form name="login" method="post" action="login">
				<input type="text" name="username" placeholder="Username" class="text"><br>
				<input type="password" name="password" placeholder="Password" class="text"><br>
				<input type="hidden" name="submitted" value="1">
				<input type="submit" value="Sign In" class="submit button action">
			</form>
		</div>
		<div class="size-1-3 grid-drop">&nbsp;</div>
	</div>
</div>