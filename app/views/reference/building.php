<div class="section">
	<div class="container">
		
		<div class="size-1-1">
			<h2><?php echo $this->building_info->building_name; ?></h2>
		</div>

		<?php $this->showMessage(); ?>

		<div class="size-1-1">
			<table class="list align-c">
				<tr>
					<th>Level
					<th>Build Time
					<th>Build Cost
					<?php 	if ($this->building_info->building_type == 2) { ?>
								<th>Rearm Cost
					<?php 	} 
							$production = array(11, 24, 26);
							if (in_array($this->building_info->building_id, $production)) { ?>
								<th>Production /hr
					<?php 	} 
							$capacity = array(10, 11, 12, 18, 19, 20, 21, 23, 24, 25, 26);
							if (in_array($this->building_info->building_id, $capacity)) { ?>
								<th>Capacity
					<?php 	} 

				foreach ($this->building_levels as $l) { ?>
				<tr>
					<td><?php echo $l->building_level; ?> 
					<td><?php echo $this->formatTime($l->build_time); ?> 
					<td><?php echo number_format($l->build_cost); ?> 
					<?php 	if ($this->building_info->building_type == 2) { ?>
								<td><?php echo $l->build_armcost; ?> 
					<?php 	} 
							$production = array(11, 24, 26);
							if (in_array($this->building_info->building_id, $production)) { ?>
								<td><?php echo number_format($l->production); ?> 
					<?php 	} 
							$capacity = array(10, 11, 12, 18, 19, 20, 21, 23, 24, 25, 26);
							if (in_array($this->building_info->building_id, $capacity)) { ?>
								<td><?php echo number_format($l->capacity); ?> 
					<?php 	} 
				} ?>
			</table>
		</div>

		<?php if ($this->building_info->building_id != 1) { ?>
		<div class="size-1-1">
			<table class="list align-c">
				<tr>
					<th>Town Hall Level
					<th>Max Count
					<th>Max Level

				<?php foreach ($this->th_reqs as $t) { ?>
				<tr>
					<td><?php echo $t->th_level; ?> 
					<td><?php echo $t->max_count; ?> 
					<td><?php echo $t->max_level; ?> 
				<?php } ?>
			</table>
		</div>
		<?php } ?>

	</div>
</div>
