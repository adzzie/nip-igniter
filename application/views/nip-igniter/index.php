<?php if(!is_writable(APPPATH."controllers")):?>
<div class="alert alert-danger">
	Directory application/controllers is not writeable. Please change folder permission.
</div>
<?php endif;?>

<?php if(!is_writable(APPPATH."models")):?>
<div class="alert alert-danger">
	Directory application/models is not writeable.  Please change folder permission.
</div>
<?php endif;?>

<?php if(!is_writable(APPPATH."views")):?>
<div class="alert alert-danger">
	Directory application/views is not writeable.  Please change folder permission.
</div>
<?php endif;?>

<div id="messageContainer" class="alert alert-danger hide"></div>

<div class="row">
	<div class="col-lg-4">
		<h1>Model Generator</h1>
		<form action="<?=site_url("nip-igniter/generate-model")?>" method="post" id="formModelGenerator">
			<div class="form-group">
				<label for="inputTableName">Table Name</label>
				<input id="inputTableName" placeholder="Table name..." class="form-control" name="table_name"><br>
			</div>
			<div class="checkbox">
				<label for="checkboxGenerate">
					<input id="checkboxGenerate" type="checkbox" name="is_crud" value="1">
					Generate CRUD ?
				</label>
			</div>
			<button class="btn btn-primary">Generate</button>
		</form>
	</div>
	<div class="col-lg-8" id="fields">

	</div>
</div>

<!-- sample modal content -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:80%">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Generated Content</h4>
      </div>
      <div class="modal-body" id="myModalBody">
      	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
$(function(){
	$("#formModelGenerator").on('submit', function(event) {
		event.preventDefault();
		var form = $(this);

		form.find('button.btn-primary').button('loading');
		$(this).ajaxSubmit({
			dataType : "json",
			success : function(rs){
				if(rs.status == 1){
					if(rs.fields != null){
						$("#fields").html(rs.fields);
					}else{
						$("#myModalBody").html(rs.content);
						$("#myModal").modal("show");
					}
				}else if(rs.status == 0){
					$("#messageContainer").removeClass('hide').html(rs.message);
				}
				form.find('button.btn-primary').button('reset');
			}
		});
	});
})
</script>