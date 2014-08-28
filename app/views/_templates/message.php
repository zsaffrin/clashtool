<div class="size-1-1">
	<?php

	$messages = Session::get('messages');

	foreach ($messages as $m) {
		echo '<div class="message '.$m[0].'">';
		echo $m[1];
		echo '</div>';
	}
	
	?>
</div>