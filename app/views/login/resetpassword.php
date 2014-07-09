<div class="section">
	<div class="container">

		<div class="size-1-1">
			<h2>Reset Password for <?php echo $this->user_firstname.' '.$this->user_lastname; ?></h2>
		</div>

		<?php $this->showMessage(); ?>

		<div class="size-1-1">
			<p>Please enter your new password.<br>
				Passwords must be at least 6 characters and may contain any combination of letters, numbers or symbols.
		</div>
		<div class="size-1-2">
			<?php $this->form(); ?>
		</div>
		<div class="size-1-2">
			&nbsp;
		</div>

	</div>
</div>