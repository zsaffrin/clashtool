<div class="section top-bar">
	<div class="container">
		<div class="pull-left">
			<a href="<?php echo URL; ?>" class="site-title">ClashTool</a>
		</div>
		<div class="pull-right">
			<ul class="top-nav">
				<?php
				// If user is logged in, display internal navigation
				if (Session::get('user_logged_in')) { ?>
					<li><a href="#" class="inactive"><?php echo Session::get('user_email'); ?></a>
					<li><a href="<?php echo URL.'user/myaccount'; ?>">My Account</a>
					<li><a href="<?php echo URL.'login/logout'; ?>">Log Out</a>
				<?php } else {
					// Otherwise, display public navigation
					if (isset($this->signup_topnav) AND $this->signup_topnav) {
						echo '<li><a href="'.URL.'login/signup">Sign Up</a>';
					} else {
						echo '<li><a href="'.URL.'login/">Log In</a>';
					}
				} 
				?>
			</ul>
		</div>
	</div>
</div>
	