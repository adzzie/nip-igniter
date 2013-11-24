<div id="messageContainer" class="alert alert-info hide"></div>

<form role="form" action="<?php echo site_url("{$this->controller}/edit");?>" id="formEdit" method="post">
  <input type="hidden" name="{content:primary}" value="<?php echo ${content:primary};?>">
  <input type="hidden" name="{content:classname}[{content:primary}]" value="<?php echo $model->{content:primary};?>">

  {content:fields}

  <button type="submit" class="btn btn-primary btn-large" id="btnSubmit">Submit</button>
</form>

<script type="text/javascript">
$(function(){

  submitForm();

  function submitForm(){
    $("#formEdit").on('submit', function(event) {
      event.preventDefault();
      var currentForm = $(this);
      $("#btnSubmit").button("loading");

      currentForm.ajaxSubmit({
        dataType: 'json',
        success : function(rs){
          $("#messageContainer").removeClass('hide').html(rs.message);
          $("#btnSubmit").button("reset");
          if(rs.status == 1){
            window.location.href = '<?php echo site_url("{$this->controller}");?>';
          }
        }
      });
      
    });
  }

});
</script>