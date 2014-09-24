<div class="section">
	<div class="container">
		
		<?php $this->showMessage(); ?>

		<div class="size-1-1">
			<h3 class="no-space">My Base</h3>
			<h2 class="mybase-sect-title">Heroes</h2>
		</div>

		<form method="post" action="heroes_action">

			<div class="size-1-1">
				<h4>Heroes</h4>
				<?php $this->display_mybaseItemLevelSet($this->buildings, 3, 1); ?>
			</div>
			<div class="clear"></div>

			<div class="size-1-1">
				<p><input type="submit" class="button" value="Save Changes">
			</div>

		</form>

	</div>
</div>
