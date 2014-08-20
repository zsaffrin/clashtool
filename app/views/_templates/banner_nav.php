<div class="section">
	<div class="container">
		<div class="size-1-1">
			<header class="align-c">
				<h1>Clash of Clans Toolkit</h1>
			</header>
		</div>
		<div class="size-1-1">
			<nav>
				<ul class="nav-left">
					<li><a href="<?php echo URL; ?>" class="button">Home</a>
					<li><a href="<?php echo URL.'reference/'; ?>" class="button">Reference</a>
					<?php if (Session::get('user_logged_in')) { ?>
							<li><a href="<?php echo URL.'mybase/'; ?>" class="button">My Base</a>
					<?php } ?>
				</ul>
				<ul class="nav-right">
					<?php
					// If user is logged in, display internal navigation
					if (Session::get('user_logged_in')) { 
						// Admin tools
						if ($_SESSION['user_level']>=4) { ?>
							<li><a href="<?php echo URL.'admin/users'; ?>" class="button">Users</a>
						<?php } ?>
						<li><span class="button label"><?php echo Session::get('user_firstname').' '.Session::get('user_lastname'); ?></span>
						<li><a href="<?php echo URL.'user/myaccount'; ?>" class="button">My Account</a>
						<li><a href="<?php echo URL.'login/logout'; ?>" class="button">Log Out</a>
					<?php } else {
						// Otherwise, display public navigation ?>
						<li><a href="#" class="button inactive">Sign Up</a>
						<li><a href="<?php echo URL.'login'; ?>" class="button">Log In</a>
					<?php } ?>
				</ul>
			</nav>
		</div>
	</div>
</div>