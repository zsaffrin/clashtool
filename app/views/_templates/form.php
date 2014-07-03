<div class="size-1-1">
	<form method="post" action="<?php echo $this->form_action; ?>">
		<table class="vert">
			<?php foreach ($this->form_inputs as $i) { ?>
				<tr>
					<th><label for="<?php echo $i['id']; ?>"><?php echo $i['title']; ?></label>
					<td><input
							type="<?php echo $i['type']; ?>" 
							id="<?php echo $i['id']; ?>" 
							name="<?php echo $i['id']; ?>" 
							<?php if (isset($i['value'])) { ?> value="<?php echo $i['value']; ?>" <?php } ?>
							>
			<?php } ?>	
				<tr>
					<td colspan="2" class="submit">
						<input type="hidden" name="submitted" value="1">
						<input type="submit" class="button" value="<?php echo $this->form_submit_label; ?>">
		</table>
	</form>
</div>