<div class="section">
	<div class="container">

		<div class="size-1-1">
			<h2>User Administration</h2>
		</div>

		<?php $this->showMessage(); ?>

		<div class="size-1-1">
			<ul>
				<li><a href="addUser" class="button">Add New User</a>
				<li>&nbsp;
			</ul>
		</div>
		<div class="size-1-1">
			<table class="list">
				<tr>
					<th>ID
					<th>Email
					<th>First Name
					<th>Last Name
					<th>Level
					<th>Last Login
					<th>Failed Logins
				<?php 
					if (!empty($this->user_list)) {
						foreach ($this->user_list as $u) { ?>
							<tr>
								<td><?php echo $u->user_id; ?> 
								<td><?php echo $u->user_email; ?> 
								<td><?php echo $u->user_firstname; ?> 
								<td><?php echo $u->user_lastname; ?> 
								<td><?php echo $u->user_level; ?> 
								<td><?php echo $u->user_last_login; ?> 
								<td><?php echo $u->user_failed_logins; ?> 
								<td><a href="<?php echo 'resetPassword/'.$u->user_id; ?>" title="Reset User Password"><span class="fa fa-key fa-fw"></span></a>
						<?php }
					} 
				?>
			</table>
		</div>

	</div>
</div>