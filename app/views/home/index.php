<div class="section">
	<div class="container">
		<div class="size-1-1">
			<?php Home::showMessage(); ?>
			<div class="size-1-1">
				<p>This is the Home/index View 
				<?php if ($user->level>1) { ?>
					<p>When a user is logged in, this view will be replaced by the Dashboard once the My Base functionality is built 
				<?php } ?>
			</div>
		</div>
	</div>
</div>
