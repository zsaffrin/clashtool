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
				<tr>
					<th>Gold
					<td><?php echo $this->production["gold"]; ?>
				<tr>
					<th>Elixir
					<td><?php echo $this->production["elixir"]; ?>
				<tr>
					<th>Dark Elixir
					<td><?php echo $this->production["delixir"]; ?>
			</table>
		</div>

	</div>
</div>
