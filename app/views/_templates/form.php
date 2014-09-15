<form method="post" action="<?php echo $this->form_action; ?>">
	
	<?php
		foreach ($this->form_inputs as $i) {
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
		foreach ($this->form_inputs as $i) {
			if ($i['type'] == 'hidden') {
				echo '<input type="hidden" name="'.$i['id'].'" value="'.$i['value'].'" />';
			}
		}
		?>
		<input type="submit" class="button" value="<?php echo $this->form_submit_label; ?>">
	</div>


<!-- old version, remove
	<table class="vert">
		<?php foreach ($this->form_inputs as $i) { ?>
			<tr>
				<th><label for="<?php echo $i['id']; ?>"><?php echo $i['title']; ?></label>
				<td><input
						type="<?php echo $i['type']; ?>" 
						id="<?php echo $i['id']; ?>" 
						name="<?php echo $i['id']; ?>" 
						<?php if (isset($i['value'])) { ?> value="<?php echo $i['value']; ?>" <?php } 
								elseif ($i['type']!=='password' && isset($_POST[$i['id']])) { ?> value="<?php echo $_POST[$i['id']]; ?>" <?php } ?>
						>
		<?php } ?>	
			<tr>
				<td colspan="2" class="submit">
					<?php if (isset($this->form_hidden_fields)) {
						foreach ($this->form_hidden_fields as $h) { ?>
							<input type="hidden" name="<?php echo $h['id']; ?>" value="<?php echo $h['value']; ?>"> 
						<?php }
					} ?>
					<input type="hidden" name="submitted" value="1">
					<input type="submit" class="button" value="<?php echo $this->form_submit_label; ?>">
	</table>
-->

</form>