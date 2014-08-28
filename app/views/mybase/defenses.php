<div class="section">
	<div class="container">
		
		<?php $this->showMessage(); ?>

		<div class="size-1-1">
			<h3 class="no-space">My Base</h3>
			<h2 class="mybase-sect-title">Defenses and Traps</h2>
		</div>

		<form method="post" action="defenses_action">

			<div class="size-1-1">
				<div class="size-1-2">
					<h4>Defenses</h4>
					<?php $this->display_mybaseItemLevelSet($this->buildings, 4, 1); ?>
				</div>
				<div class="size-1-2">
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
