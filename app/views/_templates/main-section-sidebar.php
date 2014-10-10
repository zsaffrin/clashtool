<div class="section main-section">
	<div class="container">

		<div class="sidebar">
			<ul>
				<li<?php if (isset($this->cur_page) AND $this->cur_page == "mybase") { echo ' class="active"'; } ?>>
					<a href="<?php echo URL.'mybase'; ?>">
						<span class="fa fa-home fa-fw"></span>&nbsp; My Base
					</a>
					<ul class="subnav">
						<li><a href="<?php echo URL.'mybase'; ?>"><span class="fa fa-desktop fa-fw"></span>&nbsp; Dashboard</a>
						<li><a href="<?php echo URL.'mybase/buildings'; ?>"><span class="fa fa-university fa-fw"></span>&nbsp; Buildings and Resources</a>
						<li><a href="<?php echo URL.'mybase/defenses'; ?>"><span class="fa fa-shield fa-fw"></span>&nbsp; Defenses and Traps</a>
						<li><a href="<?php echo URL.'mybase/troops'; ?>"><span class="fa fa-users fa-fw"></span>&nbsp; Troops and Spells</a>
							<li><a href="<?php echo URL.'mybase/heroes'; ?>"><span class="fa fa-child fa-fw"></span>&nbsp; Heroes</a>
						<li><a href="<?php echo URL.'mybase/walls'; ?>"><span class="fa fa-cubes fa-fw"></span>&nbsp; Walls</a>
					</ul>
				<li<?php if (isset($this->cur_page) AND $this->cur_page == "ref") { echo ' class="active"'; } ?>>
					<a href="<?php echo URL.'reference'; ?>">
						<span class="fa fa-database fa-fw"></span>&nbsp; Reference
					</a>
			</ul>

			<?php if ((Session::get('user_level')) >= 3) { ?>
			<ul>
				<li>&nbsp;
				<li><a href="<?php echo URL.'admin/users'; ?>"><span class="fa fa-gears fa-fw"></span>&nbsp; Users</a>
			</ul>
			<?php } ?>
		</div>

		<div class="main-content">
	