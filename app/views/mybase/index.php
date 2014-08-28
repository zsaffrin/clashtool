<div class="section">
	<div class="container">
		
		<?php $this->showMessage(); ?>

		<div class="size-1-1">
			<h3 class="no-space">My Base</h3>
			<h2 class="mybase-sect-title">Dashboard</h2>
		</div>

		<div class="size-1-1">
			<div class="size-1-3">
				<div class="mybase-dash-tile green">
					<div class="tile-titlebar">
						<span class="tile-title">Town Hall</span>
					</div>
					<div class="tile-content">
						<div class="pull-right big-num"><?php echo $this->th_level; ?></div>
					</div>
				</div>
			</div>
		</div>

		<div class="size-1-1">
			<div class="size-1-1">
				<h3 class="mybase-sect-title half-space">Production</h3>
			</div>
			<div class="size-1-3">
				<div class="mybase-dash-tile gold">
					<div class="tile-titlebar">
						<span class="tile-title">Gold</span>
					</div>
					<div class="tile-content">
						<div class="pull-right">
							<table>
								<?php
									$hours = array(1, 4, 8, 12, 16);
									foreach ($hours as $h) {
										echo '<tr>';
										echo '<th>'.$h.'h';
										echo '<td>'.number_format(abs($this->production[1]['count'] * $h)).' ';
									}
								?>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="size-1-3">
				<div class="mybase-dash-tile purple">
					<div class="tile-titlebar">
						<span class="tile-title">Elixir</span>
					</div>
					<div class="tile-content">
						<div class="pull-right">
							<table>
								<?php
									$hours = array(1, 4, 8, 12, 16);
									foreach ($hours as $h) {
										echo '<tr>';
										echo '<th>'.$h.'h';
										echo '<td>'.number_format(abs($this->production[2]['count'] * $h)).' ';
									}
								?>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="size-1-3">
				<div class="mybase-dash-tile black">
					<div class="tile-titlebar">
						<span class="tile-title">Dark Elixir</span>
					</div>
					<div class="tile-content">
						<div class="pull-right">
							<table>
								<?php
									$hours = array(1, 4, 8, 12, 16);
									foreach ($hours as $h) {
										echo '<tr>';
										echo '<th>'.$h.'h';
										echo '<td>'.number_format(abs($this->production[3]['count'] * $h)).' ';
									}
								?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
