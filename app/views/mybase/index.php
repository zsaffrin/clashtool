<div class="section">
	<div class="container">
		
		<?php $this->showMessage(); ?>

		<div class="size-1-1">
			<h2>My Base</h2>
		</div>

		<?php require VIEWS_PATH.'_templates/mybase_nav.php'; ?>

		<div class="size-1-1">
			<h4>Production</h4>
			<table>
				<?php 
					$hours = array(1, 2, 4, 8, 12, 16);
					
					echo '<tr>';
					echo '<th>&nbsp;';
					foreach ($hours as $h) {
						echo '<td>'.$h;
					}

					foreach ($this->production as $res) {
						echo '<tr>';
						echo '<th>'.$res['name'].' ';
						foreach ($hours as $h) {
							echo '<td>'.abs($res['count'] * $h).' ';
						}
					}
				?>
			</table>
		</div>

	</div>
</div>
