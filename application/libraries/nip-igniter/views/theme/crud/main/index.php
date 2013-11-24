<!-- Modal -->
<div class="modal fade" id="modalContainer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">{content:classname}</h4>
      </div>
      <div class="modal-body" id="formContainer">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<h2>{content:classname}</h2>

<div class="row">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-lg-4 pull-left" id="searchContainer">
				<button class="btn btn-primary pull-left btnShowModal" data-id="" data-url="<?php echo site_url("{$this->controller}/edit");?>">Add</button>
				<input style="width:300px; margin-left:5px;" type="text" id="inputSearch" name="search" class="form-control pull-left" placeholder="Type keyword to search...">
			</div>
			<div class="col-lg-8 pull-right" id="pageContainer">
				<?php echo $pagination;?>
			</div>
		</div>
		<br>
		<div class="table-responsive">
			{content:table}
			<?php if($this->Model->getSoftDeletes()):?>
				<a href="<?php echo site_url("{$this->controller}/trash/index");?>" class="btn btn-default btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> Trash</a>
			<?php endif;?>
			<div class="clearfix"></div>
		</div>
	</div>
	
</div>

<img id="loading" class="hide" style="position:fixed;top:10px;left:50%;z-index:9999" src="<?php echo base_url("public/img/loading.gif");?>">

<script type="text/javascript">
var pathSearch = '<?php echo site_url("$this->controller/search");?>';

$(function(){

searching();
init();

function init(){
	pagination();
	showModal();
	deleteRow();
}

function pagination(){
	$("#pageContainer .pagination li a").unbind('click').on('click', function(event) {
		event.preventDefault();
		var currentLink = $(this);
		if(currentLink.attr('href')==undefined)
			return;
		$('#loading').addClass('hide').removeClass('hide');
		$.ajax({
			url: currentLink.attr('href'),
			type: 'post',
			dataType: 'json',
			success : function(rs){
				$("#tableBodyContainer").html(rs.view);
				$("#pageContainer").html(rs.pagination);
				$('#loading').addClass('hide');
				init();
			}
		});
		
	});
}

function searching(){
	$("#inputSearch").unbind('keyup').on('keyup', function(event) {
		event.preventDefault();
		var inputSearch = $(this);
		if(inputSearch.val()!==""){
			$('#loading').addClass('hide').removeClass('hide');
			$.ajax({
				url: pathSearch,
				dataType : 'json',
				data: {keyword: inputSearch.val()},
				success : function(rs){
					$("#tableBodyContainer").html(rs.view);
					$("#pageContainer").html(rs.pagination);
					$('#loading').addClass('hide');
					init();
				}
			});
			
		}
	});
}

function showModal(){
	$(".btnShowModal").unbind('click').on('click', function(event) {
		event.preventDefault();		
		var currentBtn = $(this);
		$('#loading').addClass('hide').removeClass('hide');
		$.ajax({
			url: currentBtn.data('url'),
			type: 'post',
			data: {{content:primary}: currentBtn.data('id')},
			success : function(content){
				$("#formContainer").html(content);
				$('#loading').addClass('hide');
				$("#modalContainer").modal('show');
			}
		});
		
	});
}

function deleteRow(){
	$(".btnDelete").unbind('click').on('click', function(event) {
		event.preventDefault();
		var currentBtn = $(this);
		$(".table-responsive").find("div.alert").remove();
		$('#loading').addClass('hide').removeClass('hide');
		$.ajax({
			url: currentBtn.data('url'),
			dataType : "json",
			type: 'post',
			data: {{content:primary}: currentBtn.data('id')},
			success : function(rs){
				$(".table-responsive").prepend('<div class="alert alert-'+rs.param+'"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>'+rs.message+'</div>');
				$("#tr-"+currentBtn.data('id')).hide();
				$('#loading').addClass('hide');
				restoreRow();
			}
		});
	});
}

function restoreRow(){
	$(".btnRestore").unbind('click').on('click', function(event) {
		event.preventDefault();
		var currentBtn = $(this);
		$(".table-responsive").find("div.alert").remove();
		$('#loading').addClass('hide').removeClass('hide');
		$.ajax({
			url: currentBtn.data('url'),
			dataType : "json",
			type: 'post',
			data: {{content:primary}: currentBtn.data('id')},
			success : function(rs){
				$(".table-responsive").prepend('<div class="alert alert-'+rs.param+'"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>'+rs.message+'</div>');
				$("#tr-"+currentBtn.data('id')).show();
				$('#loading').addClass('hide');
			}
		});
	});
}

});
</script>