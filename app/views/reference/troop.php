<div class="section">
	<div class="container">
		
		<div class="size-1-1">
			<h2><?php echo $this->troop_info->troop_name; ?></h2>
		</div>

		<?php $this->showMessage(); ?>

		<div class="size-1-1">
			<table class="list align-c">
				<tr>
					<th>Camp/Training Space 
					<th>Train Time 
					<th>Barracks Level Required
					<?php 	if ($this->troop_info->troop_type != 3) { ?>
								<th>Attack Target
					<?php 	} ?>
				<tr>
					<td><?php echo $this->troop_info->troop_space; ?> 
					<td><?php echo $this->formatTime($this->troop_info->train_time); ?> 
					<td><?php echo $this->troop_info->barracks_req; ?> 
					<?php 	if ($this->troop_info->troop_type != 3) { ?>
								<td><?php echo $this->troop_info->target; ?> 
					<?php 	} ?>
			</table>
		</div>

		<div class="size-1-1">
			<table class="list align-c">
				<tr>
					<th>Level
					<th>Lab Level Req'd
					<th>Research Time
					<th>Research Cost
					<th>Cost to Train
				<?php foreach ($this->troop_levels as $l) { ?>
				<tr>
					<td><?php echo $l->troop_level; ?> 
					<td><?php echo $l->lab_level_required; ?> 
					<td><?php echo $this->formatTime($l->research_time); ?> 
					<td><?php echo number_format($l->research_cost); ?> 
					<td><?php echo number_format($l->train_cost); ?> 
				<?php } ?>
			</table>
		</div>

	</div>
</div>
