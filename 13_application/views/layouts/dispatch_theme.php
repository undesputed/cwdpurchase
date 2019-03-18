<!doctype html>
<html>
<head>
 <title><?php echo $template['title'] ?></title>
<meta charset="utf-8"> 
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta content="" name="description">
<meta content="" name="author">
<link href="<?php echo base_url();?>asset/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/font-awesome.min.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/jquery.ui.timepicker.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/jquery-ui.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/datatable-bootstrap.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/chosen.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/prism.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/examples.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/style.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/morris.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/plot.css" rel="stylesheet" media="screen">

<link href="<?php echo base_url();?>asset/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" media="screen">


<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.flot.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.flot.time.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.flot.axislabels.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.flot.valuelabels.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.flot.navigate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/chosen.jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/prism.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/fancybox/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/projects_plugin.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/custom.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/morris.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/rafael-min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/fixscroll.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.scrollTo.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.nav.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.carouFredSel-6.2.1.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.nicescroll.min.js"></script>

<script>
	$(function(){
		$("html").niceScroll();
	});	
</script>

<base href="<?php echo base_url().index_page();?>">
</head>
<body>
		
		<div class="navbar navbar-default navbar-static-top navbar-custom">
			<div class="navbar-inner">
				<div class="container nav-color">					
					 <div class="navbar-header">
						<button class="navbar-toggle collapsed" data-target=".navbar-collapse" data-toggle="collapse" type="button">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="<?php echo base_url(); ?>">DISPATCH</a>
					 </div>		
				
				 <div class="navbar-collapse collapse">
						
						<ul class="nav navbar-nav">
							<?php echo $this->lib_auth->navbar(); ?>
						</ul>
						
						 <div class="navbar-collapse">
						 		<ul class="nav navbar-nav navbar-right">			
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->extra->user(); ?> <b class="caret"></b></a>
										<ul class="dropdown-menu">
											<!-- <?php if($this->extra->is_admin()): ?>											
											<li><a href="<?php echo base_url().index_page() ?>/manage">Manage Users</a></li>
											<li class="divider"></li>
											<?php endif; ?> -->
											<li><a href="<?php echo base_url().index_page() ?>/auth/logout">Logout</a></li>
										</ul>
									</li>								
						  		</ul>
						 </div>

				  </div>
				</div>
			</div>
		</div>	


 <?php echo $template['body'] ?>

</body>
</html> 
