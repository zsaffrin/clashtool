<?php

class View {

	public function render($view_path) {
		require VIEWS_PATH.'_templates/header.php';
		require VIEWS_PATH.'_templates/top-nav.php';
		require VIEWS_PATH.'_templates/main_with-left-nav.php';
		require VIEWS_PATH.$view_path.'.php';
		require VIEWS_PATH.'_templates/footer.php';
	}

	public function render_noLeftNav($view_path) {
		require VIEWS_PATH.'_templates/header.php';
		require VIEWS_PATH.'_templates/top-nav.php';
		require VIEWS_PATH.'_templates/main_no-left-nav.php';
		require VIEWS_PATH.$view_path.'.php';
		require VIEWS_PATH.'_templates/footer.php';
	}

	public function form() {
		require VIEWS_PATH.'_templates/form.php';
	}

	public function showMessage() {
		if (isset($_SESSION['messages'])) {
			require VIEWS_PATH.'_templates/message.php';
		}
		
		Session::set('messages', null);
	}

	// Display timespan (in seconds) in d/h/m/s format
	public function formatTime($timespan) {
		$days = floor($timespan / 86400);
		$hours = floor(($timespan - ($days * 86400)) / 3600);
		$minutes = floor(($timespan - ($days * 86400) - ($hours * 3600)) / 60);
		$seconds = floor($timespan % 60);

		if ($timespan > 0) {
			$result = '';
			if ($days > 0) { $result .= ' '.$days.'d'; }
			if ($hours > 0) { $result .= ' '.$hours.'h'; }
			if ($minutes > 0) { $result .= ' '.$minutes.'m'; }
			if ($seconds > 0) { $result .= ' '.$seconds.'s'; }
		} else {
			$result = 0;
		}

		return $result;
	}

	// Display a given value as game resource
	public function resource_format($resource_type, $value) {
		echo '<span class="resource-format ';
		if ($resource_type == 1) { echo 'gold'; }
		else if ($resource_type == 2) { echo 'elixir'; }
		else if ($resource_type == 3) { echo 'dark-elixir'; }
		echo '">';
		echo number_format($value);
		echo '</span>';
	}

	// Display item level input row
	public function display_mybaseItemLevelSelect($item, $maxLevel) {
		echo '<tr>';
		echo '<th>'.$item->name.' ';
		for ($n=0;$n<=$maxLevel;$n++) {
			echo '<td>';
			if (($n > $item->max_level)
				OR (($item->item_class == 2) AND ($n == 0))) {
				echo '&nbsp;';
			} else {
				echo '<input
						type="radio" 
						name="'.$item->item_id; if ($item->item_class == 1) { echo '-'.$item->building_num; } echo '" 
						id="'.$item->item_id; if ($item->item_class == 1) { echo '-'.$item->building_num; } echo '-'.$n.'" 
						value="'.$n.'" 
						class="mybase-lvl-select';
						if ($n < $item->level) { echo ' past-lvl'; }
						if ($n > $item->level) { echo ' avail-lvl'; }
						echo '"';
					if ($n == $item->level) { echo ' checked="checked"'; }
					echo '>';
				echo '<label for="'.$item->item_id; if ($item->item_class == 1) { echo '-'.$item->building_num; } echo '-'.$n.'">';
					echo '<span>'.$n.'</span>';
				echo '</label>';
			}
		}
		echo '<td class="mybase-level-info">';
		if (isset($item->next_level_cost) AND $item->next_level_cost > 0) {
			echo $this->resource_format($item->next_level_cost_type, $item->next_level_cost);
		}
	}

	// Display table of My Base item level inputs
	public function display_mybaseItemLevelSet($itemset, $type=null, $subtype=null) {
		// Find max level
		$maxLevel = 0;
		foreach ($itemset as $i) {
			if ((empty($type) OR ($type == $i->type)) 
				AND (empty($subtype) OR ($subtype == $i->subtype)) 
				AND ($i->max_level > $maxLevel)) {
				$maxLevel = $i->max_level;
			}
		}

		// Display table and render rows containing inputs
		echo '<table class="mybase-levels">';
		
		echo '<tr>';
		echo '<td>&nbsp;';
		for ($n=0;$n<=$maxLevel;$n++) { echo '<td>&nbsp;'; }
		echo '<td class="align-c"><small>Next Level</small>';

		foreach ($itemset as $i) {
			if ((empty($type) OR ($type == $i->type)) 
				AND (empty($subtype) OR ($subtype == $i->subtype))) {
				$this->display_mybaseItemLevelSelect($i, $maxLevel);
			}
		}
		echo '</table>';
	}

}

?>