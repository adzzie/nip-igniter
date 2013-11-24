	<div class="form-group">
		<label for="input_{content:field}">{content:label}</label>
		<select name="{content:classname}[{content:field}]" class="form-control">
			<option value="">
				Choose
			</option>
			<?php foreach(${content:field} as $row):?>
			<option value="<?php echo $row->{content:fk_primary};?>" <?php echo ($row->{content:fk_primary}==$model->{content:field}?"selected":"");?>>
				<?php echo getLabel($row->{content:fk_label});?>
			</option>
			<?php endforeach;?>
		</select>
	</div>