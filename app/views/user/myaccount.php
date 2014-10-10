<div class="size-1-1">

	<div class="size-1-1">
		<h2>My Account</h2>
	</div>

	<?php $this->showMessages(); ?>

	<div class="size-1-1">
		<div class="size-1-1">
			<h3>Profile</h3>
			<p>Please note that if you change your email address, you will need to verify the new address before you can log in again.
		</div>

		<div class="size-1-2">
			<?php $this->displayForm($this->userinfo_form); ?>
		</div>
		<div class="size-1-2">
			&nbsp;
		</div>
	</div>

	<div class="size-1-1">
		<div class="size-1-1">
			<h3>Change Password</h3>
		</div>
		<div class="size-1-1">
			<p><a href="changePassword" class="button">Change My Password</a>
		</div>
	</div>

</div>