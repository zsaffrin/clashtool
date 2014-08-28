<!-- Top navigation -->
<div class="section top-nav">
	<div class="container">
		<div class="size-1-6 grid-stick">
			ClashTool
		</div>
		<div class="size-5-6 grid-stick">
			<ul class="horizontal-list nav-right">
			<?php
			// If user is logged in, display internal navigation
			if (Session::get('user_logged_in')) { ?>
				<li><?php echo Session::get('user_firstname').' '.Session::get('user_lastname'); ?>
				<li><a href="<?php echo URL.'user/myaccount'; ?>">My Account</a>
				<li><a href="<?php echo URL.'login/logout'; ?>">Log Out</a>
			<?php } else {
				// Otherwise, display public navigation ?>
				<li><a href="#">Sign Up</a>
				<li><a href="<?php echo URL.'login'; ?>">Log In</a>
			<?php } ?>
		</ul>
		</div>
	</div>
</div>
<div class="clear"></div>

<!-- Left sidebar navigation -->
<div class="section main">
	<div class="container">
		<div class="left-nav">
			<ul>
				<li><a href="<?php echo URL; ?>"><span class="fa fa-home fa-fw"></span>&nbsp; Home</a>
				<li<?php if (isset($this->cur_page) AND $this->cur_page == "mybase") { echo ' class="active"'; } ?>>
					<a href="<?php echo URL.'mybase'; ?>">
						<span class="fa fa-building-o fa-fw"></span>&nbsp; My Base
					</a>
					<ul class="subnav">
						<li><a href="<?php echo URL.'mybase'; ?>"><span class="fa fa-desktop fa-fw"></span>&nbsp; Dashboard</a>
						<li><a href="<?php echo URL.'mybase/buildings'; ?>"><span class="fa fa-university fa-fw"></span>&nbsp; Buildings and Resources</a>
							<li><a href="<?php echo URL.'mybase/defenses'; ?>"><span class="fa fa-shield fa-fw"></span>&nbsp; Defenses and Traps</a>
						<li><a href="<?php echo URL.'mybase/troops'; ?>"><span class="fa fa-users fa-fw"></span>&nbsp; Troops and Spells</a>
					</ul>
				<li<?php if (isset($this->cur_page) AND $this->cur_page == "ref") { echo ' class="active"'; } ?>>
					<a href="<?php echo URL.'reference'; ?>">
						<span class="fa fa-database fa-fw"></span>&nbsp; Reference
					</a>
			</ul>
		</div>
		<div class="content">
	