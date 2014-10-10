<div class="size-1-1">
		
		<?php $this->showMessages(); ?>

		<div class="size-1-1">
			<h2>Delete user</h2>
		</div>

		<div class="size-1-1">
			<div class="alert red">
				Are you <b>certain</b> you want to delete this user? This action is permanent and cannot be undone. 
			</div>
		</div>

		<div class="size-1-1">
			<?php $user = $this->user_info; ?>
			<table class="vert">
				<tr>
					<th>User ID
					<td><?php echo $user->user_id; ?>
				<tr>
					<th>Email
					<td><?php echo $user->user_email; ?>
				<tr>
					<th>Name
					<td><?php echo $user->user_firstname.' '.$user->user_lastname; ?>
			</table>
		</div>

		<div class="size-1-1">
			<input type="hidden" name="confirm_delete" value="1" />
			<a href="<?php echo URL.'admin/deleteUser/'.$user->user_id.'/1'; ?>" class="button">Delete User</a>
			<a href="<?php echo URL.'admin/users'; ?>" class="button">Cancel</a>
		</div>

</div>
