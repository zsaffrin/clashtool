<div class="section">
	<div class="container">
		
		<div class="size-1-1">
			<h2>Data Reference</h2>
			<p>Here you can browse the building, troop and other game data that power this toolkit
		</div>

		<?php $this->showMessage(); ?>

		<div class="size-1-3">
			<div class="size-1-1">
				<h3>Buildings</h3>
				<ul>
				<?php foreach ($this->building_list as $b) { ?>
					<li><?php echo $b->building_name; ?> 
				<?php } ?>
				</ul>
			</div>
		</div>
		
		<div class="size-1-3">
			<div class="size-1-1">
				<h3>Spells</h3>
				<ul>
				<?php foreach ($this->spell_list as $s) { ?>
					<li><?php echo $s->troop_name; ?> 
				<?php } ?>
				</ul>
			</div>
			<div class="size-1-1">
				<h3>Traps</h3>
				<ul>
				<?php foreach ($this->trap_list as $p) { ?>
					<li><?php echo $p->building_name; ?> 
				<?php } ?>
				</ul>
			</div>
		</div>

		<div class="size-1-3">
			<div class="size-1-1">
				<h3>Troops</h3>
				<ul>
				<?php foreach ($this->troop_list as $t) { ?>
					<li><?php echo $t->troop_name; ?> 
				<?php } ?>
				</ul>

				<h5>Dark Troops</h5>
				<ul>
				<?php foreach ($this->dark_troop_list as $d) { ?>
					<li><?php echo $d->troop_name; ?> 
				<?php } ?>
				</ul>
			</div>
		</div>

	</div>
</div>
