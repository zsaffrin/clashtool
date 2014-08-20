<div class="section">
	<div class="container">
		
		<?php $this->showMessage(); ?>

		<div class="size-1-1">
			<h2>My Base</h2>
		</div>

		<?php require VIEWS_PATH.'_templates/mybase_nav.php'; ?>

		<form method="post" action="buildings_action">

			<div class="size-1-2">
				<div class="size-1-1">
					<h4>Resources</h4>
					<?php $this->display_mybaseItemLevelSet($this->buildings, 1, 2); ?>
				</div>
				<div class="size-1-1">
					<h4>Army</h4>
					<?php $this->display_mybaseItemLevelSet($this->buildings, 1, 3); ?>
				</div>
			</div>

			<div class="size-1-2">
				<div class="size-1-1">
					<h4>Defenses</h4>
					<?php $this->display_mybaseItemLevelSet($this->buildings, 4, 1); ?>
				</div>
				<div class="size-1-1">
					<h4>Traps</h4>
					<?php $this->display_mybaseItemLevelSet($this->buildings, 2, 1); ?>
				</div>
			</div>
			<div class="clear"></div>

			<div class="size-1-1">
				<p><input type="submit" class="button" value="Save Changes">
			</div>

		</form>

	</div>
</div>
