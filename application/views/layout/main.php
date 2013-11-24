<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?php echo base_url();?>public/bootstrap/assets/ico/favicon.png">

    <title><?php echo $pageTitle;?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url();?>public/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    
    <link href="<?php echo base_url();?>public/plugin/datepicker/css/datepicker.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <style type="text/css">
	body {
	  padding-top: 70px;
	}
	.modal-backdrop{
		z-index:2;
	}
	</style>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo base_url();?>public/bootstrap/assets/js/html5shiv.js"></script>
      <script src="<?php echo base_url();?>public/bootstrap/assets/js/respond.min.js"></script>
    <![endif]-->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url();?>public/bootstrap/assets/js/jquery.js"></script>
    <script src="<?php echo base_url();?>public/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>public/js/jquery.form.js"></script>
  </head>

  <body>

    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo site_url();?>">NipIgniter Project</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="<?php echo site_url();?>">Home</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">

      <?php echo $pageContent;?>

    </div> <!-- /container -->

  </body>
</html>
