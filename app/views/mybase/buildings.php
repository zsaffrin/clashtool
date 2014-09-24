<div class="section">
	<div class="container">
		
		<?php $this->showMessage(); ?>

		<div class="size-1-1">
			<h3 class="no-space">My Base</h3>
			<h2 class="mybase-sect-title">Buildings and Resources</h2>
		</div>

		<form method="post" action="buildings_action">

			<div class="size-1-1">
				<div class="size-1-2">
					<div class="size-1-1">
						<h4>Town Hall</h4>
						<?php $this->display_mybaseItemLevelSet($this->buildings, 1, 1); ?>
					</div>
					<div class="size-1-1">
						<h4>Army</h4>
						<?php $this->display_mybaseItemLevelSet($this->buildings, 1, 3); ?>
					</div>
				</div>
				<div class="size-1-2">
					<div class="size-1-1">
						<h4>Resources</h4>
						<?php $this->display_mybaseItemLevelSet($this->buildings, 1, 2); ?>
					</div>
				</div>
				
			</div>
			<div class="clear"></div>

			<div class="size-1-1">
				<p><input type="submit" class="button" value="Save Changes">
			</div>

		</form>

	</div>
</div>
