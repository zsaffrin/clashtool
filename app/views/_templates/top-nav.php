<div class="section top-nav">
	<div class="container">
		<div class="page-title">
			<a href="<?php echo URL; ?>">ClashTool</a>
		</div>
		<div class="user-nav">
			<ul>
			<?php
			// If user is logged in, display internal navigation
			if (Session::get('user_logged_in')) { ?>
				<li><a href="#" class="inactive"><?php echo Session::get('user_email'); ?></a>
				<li><a href="<?php echo URL.'user/myaccount'; ?>">My Account</a>
				<li><a href="<?php echo URL.'login/logout'; ?>">Log Out</a>
			<?php } else {
				// Otherwise, display public navigation
				if ($this->page_id == 'login') {
					echo '<li><a href="'.URL.'login/signup">Sign Up</a>';
				} elseif ($this->page_id == 'signup') {
					echo '<li><a href="'.URL.'login">Log In</a>';
				}
			} 
			?>
			</ul>
		</div>
	</div>
</div>
	