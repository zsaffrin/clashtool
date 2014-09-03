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
				<li><a href="#" class="inactive"><?php echo Session::get('user_firstname').' '.Session::get('user_lastname'); ?></a>
				<li><a href="<?php echo URL.'user/myaccount'; ?>">My Account</a>
				<li><a href="<?php echo URL.'login/logout'; ?>">Log Out</a>
			<?php } else {
				// Otherwise, display public navigation ?>
				<li><a href="#">Sign Up</a>
			<?php } ?>
			</ul>
		</div>
	</div>
</div>
	