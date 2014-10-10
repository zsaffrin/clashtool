<div class="size-1-1">
	
	<?php $this->showMessages(); ?>

	<div class="size-1-1">
		<h3 class="no-space">My Base</h3>
		<h2 class="mybase-sect-title">Walls</h2>
	</div>

	<form method="post" action="walls_action">
		<div class="size-1-1">
			<small>Enter the number of walls you have at each level</small>
		</div>
		<div class="size-1-1">
			<table>
				<?php
					for ($i=1;$i<=$this->wall_data->max_wall_level;$i++) {
						echo '<tr>';
						echo '<th>Level '.$i;
						echo '<td>';
						echo '<input type="text" name="count-'.$i.'" value="';
							foreach ($this->wall_data->wall_counts as $c) {
								if ($c->building_level == $i) {
									echo $c->building_count;
								}
							}
							echo '" class="mybase-shortnum" />';
						echo '<td>&nbsp;';
					}
				?>
				<tr class="total-row">
					<th>Total
					<td class="total"><?php echo $this->wall_data->wall_total; ?>
					<td><?php echo '/ '.$this->wall_data->max_wall_count.' available'; ?>
			</table>
		</div>

		<div class="size-1-1">
			<p><input type="submit" class="button" value="Save Changes">
		</div>

	</form>

</div>
