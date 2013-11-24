<a href="<?php echo site_url("{$this->controller}");?>">Back</a>
<h3><span class="glyphicon glyphicon-trash"></span> {content:classname}</h3>

<div class="row">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-lg-4 pull-left" id="searchContainer">
				<input style="width:300px; margin-left:5px;" type="text" id="inputSearch" name="search" class="form-control pull-left" placeholder="Type keyword to search...">
			</div>
			<div class="col-lg-8 pull-right" id="pageContainer">
				<?php echo $pagination;?>
			</div>
		</div>
		<br>
		<div class="table-responsive">
			{content:table}
		</div>
	</div>
	
</div>

<img id="loading" class="hide" style="position:fixed;top:10px;left:50%;z-index:9999" src="<?php echo base_url("public/img/loading.gif");?>">

<script type="text/javascript">
var pathSearch = '<?php echo site_url("$this->controller/trash-search");?>';

$(function(){

searching();
init();

function init(){
	pagination();
	deleteRestoreRow();
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

function deleteRestoreRow(){
	$(".btnAction").unbind('click').on('click', function(event) {
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

});
</script>