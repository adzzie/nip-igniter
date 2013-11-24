<table class="table table-hover table-bordered">
	{content:tr}
	<tr><td>Created</td><td>:</td><td><?php echo date("d M Y H:i:s",strtotime($model->created));?></td></tr>
	<tr><td>Updated</td><td>:</td><td><?php echo $model->updated?date("d M Y H:i:s",strtotime($model->updated)):"-";?></td></tr>
</table>