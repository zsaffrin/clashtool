<div class="section">
	<div class="container">

		<div class="size-1-1">
			<h2>Reset Password</h2>
		</div>

		<?php $this->showMessage(); ?>

		<div class="size-1-1">
			<p>Changing User <?php echo $this->user_id; ?><br>
				<?php echo $this->user_firstname.' '.$this->user_lastname; ?><br>
				<?php echo $this->user_email; ?>
		</div>
		<div class="size-1-1">
			<div class="size-1-2">
				<?php $this->form(); ?>
			</div>
			<div class="size-1-2">
				&nbsp;
			</div>
		</div>

	</div>
</div>