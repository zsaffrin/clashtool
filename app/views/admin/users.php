<div class="section">
	<div class="container">

		<div class="size-1-1">
			<h2>User Administration</h2>
		</div>

		<?php $this->showMessage(); ?>

		<div class="size-1-2 grid-stick">
			<form method="post" action="addUser">
				<div class="input-field">
					<input type="text" id="email" name="email" title="Email" placeholder="Email" />
					<label for="email"><span class="fa fa-envelope-o fa-fw"></label>
				</div>
				<div class="size-1-1 align-c submit">
					<input type="submit" class="button" value="Add New User">
				</div>
			</form>
		</div>
		<div class="size-1-2 grid-stick">&nbsp;</div>

		<div class="size-1-1">
			<h3>All Users</h3>
			<table class="list">
				<tr>
					<th>ID
					<th>Email
					<th>Status
					<th>Name
					<th>Level
					<th>Last Login
					<th>Failed Logins
					<th>&nbsp;
				<?php 
					if (!empty($this->user_list)) {
						foreach ($this->user_list as $u) { ?>
							<tr>
								<td><?php echo $u->user_id; ?>  
								<td><?php 
									echo $u->user_email;
									if ($u->user_email_verified == 1) { echo ' <span class="fa fa-check-circle-o fa-fw" style="color:#27AE60"></span>'; }
									else { echo ' <span class="fa fa-times-circle-o fa-fw" style="color:#E44444"></span>'; }
									?> 
								<td><?php 
									if ($u->user_status == 0) { echo 'Pending'; }
									elseif ($u->user_status == 1) { echo 'Active'; }
									elseif ($u->user_status == 2) { echo 'Locked'; }
									?> 
								<td><?php echo $u->user_firstname.' '.$u->user_lastname; ?> 
								<td><?php echo $u->user_level; ?> 
								<td><?php echo $u->user_last_login; ?> 
								<td><?php echo $u->user_failed_logins; ?> 
								<td>
									<ul class="inline-user-tools">
										<li>
											<a 
											  href="<?php echo 'force_password_reset/'.$u->user_id; ?>" 
											  title="Force Password Reset" 
											  class="<?php if ($u->force_password_reset == 1) { echo 'active'; } ?>" >
												<span class="fa fa-key fa-fw"></span>
											</a>
										<li>
											<a 
											  href="<?php echo 'trigger_email_verification/'.$u->user_id; ?>" 
											  title="Trigger Email Verification" >
												<span class="fa fa-envelope fa-fw"></span>
											</a>
										<li>
											<?php
												if ($u->user_status == 0) {
													echo '<a href="activateUser/'.$u->user_id.'" title="Approve user">';
													echo '<span class="fa fa-check-square-o fa-fw"></span>';
													echo '</a>';
												} elseif ($u->user_status == 1) {
													echo '<a href="toggleUserLock/'.$u->user_id.'" title="Lock user">';
													echo '<span class="fa fa-lock fa-fw"></span>';
													echo '</a>';
												} elseif ($u->user_status == 2) {
													echo '<a href="toggleUserLock/'.$u->user_id.'" title="Unlock user">';
													echo '<span class="fa fa-unlock-alt fa-fw"></span>';
													echo '</a>';
												}
											?>
										<li>
											<a 
											  href="<?php echo 'deleteUser/'.$u->user_id; ?>" 
											  title="Delete User" >
												<span class="fa fa-times fa-fw"></span>
											</a>
									</ul>

						<?php }
					} 
				?>
			</table>
		</div>

	</div>
</div>