<div class="section">
	<div class="container">
		
		<?php $this->showMessage(); ?>

		<div class="size-1-1">
			<h3 class="no-space">My Base</h3>
			<h2 class="mybase-sect-title">Troops and Spells</h2>
		</div>

		<form method="post" action="troops_action">

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

			<div class="size-1-1">
				<p><input type="submit" class="button" value="Save Changes">
			</div>

		</form>

	</div>
</div>
