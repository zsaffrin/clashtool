<?php

class View {

	public function render($view_path) {
		require VIEWS_PATH.'_templates/header.php';
		require VIEWS_PATH.'_templates/banner_nav.php';
		require VIEWS_PATH.$view_path.'.php';
		require VIEWS_PATH.'_templates/footer.php';
	}

	public function form() {
		require VIEWS_PATH.'_templates/form.php';
	}

	public function showMessage() {
		require VIEWS_PATH.'_templates/message.php';
		
		Session::set('msg_errors', null);
		Session::set('msg_success', null);
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

	// Display level inputs for a subset of items
	public function mybaseBuildingLevelSet($buildingset, $type, $subtype) {
		echo '<table>';
		
		// Find max level
		$maxLevel = 0;
		foreach ($buildingset as $b) {
			if ((!isset($type) OR ($type == $b->type)) 
				AND (!isset($subtype) OR ($subtype == $b->subtype)) 
				AND ($b->max_level > $maxLevel)) { $maxLevel = $b->max_level; }
		}

		// Echo cells for levels of each building
		foreach ($buildingset as $b) {
			if ((!isset($type) OR ($type == $b->type)) 
				AND (!isset($subtype) OR ($subtype == $b->subtype))) { 
					echo '<tr>';
					echo '<td>'.$b->building_name.' '.$b->building_num;
					for ($i=0;$i<=$maxLevel;$i++) {
						echo '<td>';
						if ($i > $b->max_level) {
							echo '&nbsp;';
						} else {
							if ($i == $b->building_level) { echo '<b>'; }
							echo $i;
							if ($i == $b->building_level) { echo '</b>'; }	
						}
						
					}
			}
		}

		echo '</table>';
	}
}

?>