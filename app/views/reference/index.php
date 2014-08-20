<div class="section">
	<div class="container">
		
		<?php $this->showMessage(); ?>

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
					<li><a href="reference/building/<?php echo $b->building_id; ?>"><?php echo $b->building_name; ?></a>
				<?php } ?>
				</ul>
			</div>
			<div class="size-1-1">
				<h3>Defenses</h3>
				<ul>
				<?php foreach ($this->defense_list as $def) { ?>
					<li><a href="reference/building/<?php echo $def->building_id; ?>"><?php echo $def->building_name; ?></a>
				<?php } ?>
				</ul>
			</div>
		</div>
		
		<div class="size-1-3">
			<div class="size-1-1">
				<h3>Spells</h3>
				<ul>
				<?php foreach ($this->spell_list as $s) { ?>
					<li><a href="reference/troop/<?php echo $s->troop_id; ?>"><?php echo $s->troop_name; ?></a>
				<?php } ?>
				</ul>
			</div>
			<div class="size-1-1">
				<h3>Traps</h3>
				<ul>
				<?php foreach ($this->trap_list as $p) { ?>
					<li><a href="reference/building/<?php echo $p->building_id; ?>"><?php echo $p->building_name; ?></a>
				<?php } ?>
				</ul>
			</div>
			<div class="size-1-1">
				<h3>Heroes</h3>
				<ul>
				<?php foreach ($this->hero_list as $h) { ?>
					<li><a href="reference/building/<?php echo $h->building_id; ?>"><?php echo $h->building_name; ?></a>
				<?php } ?>
				</ul>
			</div>
		</div>

		<div class="size-1-3">
			<div class="size-1-1">
				<h3>Troops</h3>
				<ul>
				<?php foreach ($this->troop_list as $t) { ?>
					<li><a href="reference/troop/<?php echo $t->troop_id; ?>"><?php echo $t->troop_name; ?></a> 
				<?php } ?>
				</ul>

				<h5>Dark Troops</h5>
				<ul>
				<?php foreach ($this->dark_troop_list as $d) { ?>
					<li><a href="reference/troop/<?php echo $d->troop_id; ?>"><?php echo $d->troop_name; ?></a>
				<?php } ?>
				</ul>
			</div>
		</div>

	</div>
</div>
