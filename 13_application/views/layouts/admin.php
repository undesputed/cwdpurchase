<!doctype html>
<html>
<head>
 <title><?php echo $template['title'] ?></title>
<meta charset="utf-8"> 
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta content="" name="description">
<meta content="" name="author">
<meta http-equiv="Cache-control" content="no-cache">

<link href="<?php echo base_url();?>asset/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/font-awesome.min.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/style.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="<?php echo base_url();?>asset/css/style.css">
<link rel="stylesheet" href="<?php echo base_url();?>asset/fullcalendar/fullcalendar.css">
<link rel="stylesheet" href="<?php echo base_url();?>asset/css/timePicker.css">
<link rel="stylesheet" href="<?php echo base_url();?>asset/css/jquery-ui.css">
<link href="<?php echo base_url();?>asset/css/datatable-bootstrap.css" rel="stylesheet" media="screen">


<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>asset/fancy/source/jquery.fancybox.css?v=2.1.4" media="screen" />

<script  src="<?php echo base_url();?>asset/js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/fancy/source/jquery.fancybox.js"></script>
<script  src="<?php echo base_url();?>asset/js/jquery-ui.js"></script>

 <script type="text/javascript" src="<?php echo base_url();?>asset/js/bootstrap.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>asset/js/data.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>asset/fullcalendar/fullcalendar.min.js"></script>
 
 <script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.dataTables.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>asset/js/jqBarGraph.1.1.min.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.jqplot.min.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>asset/js/jqplot.pieRenderer.min.js"></script>




<script type="text/javascript">
	var base_url = '<?php echo base_url().index_page();?>';
</script>





</head>
<body>
		<div class="navbar navbar-default navbar-static-top">
			<div class="navbar-inner">
				<div class="container">

				 <div class="navbar-header">
					<button class="navbar-toggle collapsed" data-target=".navbar-collapse" data-toggle="collapse" type="button">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?php echo base_url(); ?>"><?php echo $this->config->item('site_title'); ?></a>
				 </div>

				 <div class="navbar-collapse collapse">
					  <ul class="nav navbar-nav">	

					  </ul>

					  <ul class="nav navbar-nav navbar-right">										
							<?php $this->admin->user_dp(); ?>
					  </ul>

				  </div>
				</div>
			</div>
		</div>	

 <?php echo $template['body'] ?>

<div style="margin-top:2em;margin-bottom:3em"></div>

 
</body>
</html> 
