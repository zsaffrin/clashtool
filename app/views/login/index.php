<div class="section">
	<div class="container">
		<div class="login-panel">
			<h2 class="align-c">Log In</h2>
			
			<?php $this->showMessage(); ?>
			
			<form method="post" action="login/login">
				<div class="field">
					<input type="text" id="email" name="email" title="Email" <?php if (isset($_POST["email"])) { echo 'value="'.$_POST["email"].'"'; } ?> />
					<label for="email">Email</label>
				</div>
				<div class="field">
					<input type="password" id="password" name="password" title="Password"/>
					<label for="password">Password</label>
				</div>
				<div class="size-1-1 align-c submit">
					<input type="hidden" name="submitted" value="1">
					<input type="submit" class="button" value="Log In">
				</div>
			</form>
			
		</div>
	</div>
</div>