<div class="section">
	<div class="container">

		<div class="size-1-1">
			<h2>Edit User</h2>
		</div>

		<?php $this->showMessage(); ?>

		<div class="size-1-1">
			<form method="post" action="editUser_action">

				<div class="field">
					<input type="text" id="email" name="email" title="Email" 
						<?php if (isset($this->user_info->user_email)) { 
							echo 'value="'.$this->user_info->user_email.'"'; 
						} ?> 
						/>
					<label for="email">Email</label>
				</div>

				<div class="size-1-1 align-c submit">
					<input type="hidden" name="submitted" value="1">
					<input type="submit" class="button" value="Save Changes">
				</div>

			</form>
		</div>

	</div>
</div>