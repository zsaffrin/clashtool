<div class="section">
	<div class="container">
		
		<?php $this->showMessage(); ?>

		<div class="size-1-1">
			<h2>My Base</h2>
		</div>

		<?php require VIEWS_PATH.'_templates/mybase_nav.php'; ?>

		<div class="size-1-2">
			<div class="size-1-1">
				<h4>Troops</h4>
				<?php $this->display_mybaseItemLevelSet($this->troops, 1); ?>
			</div>
		</div>

		<div class="size-1-2">
			<div class="size-1-1">
				<h4>Dark Troops</h4>
				<?php $this->display_mybaseItemLevelSet($this->troops, 2); ?>
			</div>
			<div class="size-1-1">
				<h4>Spells</h4>
				<?php $this->display_mybaseItemLevelSet($this->troops, 3); ?>
			</div>
		</div>
		<div class="clear"></div>

	</div>
</div>
