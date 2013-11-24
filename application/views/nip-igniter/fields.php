<h4>Crud Configuration</h4>
<div id="messageContainer" class="alert alert-info hide"></div>

<form role="form" action="<?php echo site_url("{$this->controller}/generate-crud");?>" id="formCrudGenerator" method="post">
  <input type="hidden" name="primary" value="<?php echo $primary;?>">
  <input type="hidden" name="classname" value="<?php echo $classname;?>">
  <table class="table table-bordered table-hover">
    <tr><td>Field Name</td><td>Field Type</td><td>Show on Grid</td></tr>
    <?php 
    $ignoreField = array("created","updated","deleted");
    foreach($fields as $field):?>
      <?php if(!in_array($field->name, $ignoreField)):?>
      <tr>
        <td>
          <?php echo $field->name;?>
        </td>
        <td>
          <select class="form-control type" name="fields[<?php echo $field->name;?>][type]">
            <option value="text">Text</option>
            <option value="textarea">Textarea</option>
            <option value="email">Email</option>
            <option value="password">Password</option>
            <option value="fk">Select</option>
          </select>
          <div id="input" class="hide">
            <input type="text" class="form-control" name="fields[<?php echo $field->name;?>][fk_name]" value="" placeholder="Model Name...">
            <input type="text" class="form-control" name="fields[<?php echo $field->name;?>][fk_id]" value="" placeholder="Model's Primary Key...">
            <input type="text" class="form-control" name="fields[<?php echo $field->name;?>][fk_label]" value="" placeholder="Field as Select Label...">
          </div>
        </td>
        <td>
          <input type="checkbox" name="fields[<?php echo $field->name;?>][show]" value="1" <?php echo ($field->primary_key!=1?"checked":"");?>>
        </td>
      </tr>
      <?php endif;?>
    <?php endforeach;?>
  </table>

  <button type="submit" class="btn btn-primary btn-large">Submit</button>
</form>

<script type="text/javascript">
$(function(){
  $(".type").on('change', function(event) {
    event.preventDefault();
    var input = $(this);
    if(input.val()=="fk"){
      input.next("#input").removeClass('hide');
    }else{
      input.next("#input").addClass('hide');
    }
  });

  $("#formCrudGenerator").on('submit', function(event) {
    event.preventDefault();
    var form = $(this);

    form.find('button.btn-primary').button('loading');
    $(this).ajaxSubmit({
      dataType : "json",
      success : function(rs){
          $("#messageContainer").removeClass('hide').html(rs.message);
          setTimeout('window.location.href="<?php echo site_url("nip-igniter");?>"',1000);
          form.find('button.btn-primary').button('reset');
      }
    });
  });
});
</script>