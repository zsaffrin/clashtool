<div class="section">
	<div class="container">
		
		<!-- Message display goes here -->

		<div class="size-1-1">
			<p>This is the Home/index View 
			<?php if (Session::get('user_logged_in')) { ?>
				<p>When a user is logged in, this view will be replaced by the Dashboard once the My Base functionality is built 
			<?php } ?>
		</div>
	</div>
</div>
