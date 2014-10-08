<form method="post" action="<?php echo $this->render_form->form_action; ?>">
	
	<?php
		foreach ($this->render_form->form_inputs as $i) {
			if ($i['type'] != 'hidden') {
				echo '<div class="input-field">';
				echo '<input type="'.$i['type'].'" id="'.$i['id'].'" name="'.$i['id'].'" title="'.$i['title'].'" placeholder="'.$i['title'].'" ';
				if (isset($i['value'])) { echo 'value="'.$i['value'].'" '; }
				echo '/>';
				echo '<label for="'.$i['id'].'"><span class="fa fa-'.$i['icon'].' fa-fw"></span></label>';
				echo '</div>';
			}
		}
	?>

	<div class="size-1-1 align-c submit">
		<input type="hidden" name="submitted" value="1">
		<?php
		foreach ($this->render_form->form_inputs as $i) {
			if ($i['type'] == 'hidden') {
				echo '<input type="hidden" name="'.$i['id'].'" value="'.$i['value'].'" />';
			}
		}
		?>
		<input type="submit" class="button" value="<?php echo $this->render_form->form_submit_label; ?>">
	</div>

</form>