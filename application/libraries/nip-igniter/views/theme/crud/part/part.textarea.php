	<div class="form-group">
		<label for="input_{content:field}">{content:label}</label>
		<textarea class="form-control" id="input_{content:field}" name="{content:classname}[{content:field}]" placeholder="Enter {content:label}..."><?php echo $model->{content:field};?></textarea>
	</div>