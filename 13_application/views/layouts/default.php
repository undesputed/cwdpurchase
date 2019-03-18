<!doctype html>
<html>
<head>
 <title><?php echo $template['title'] ?></title>
<meta content="utf-8" http-equiv="encoding">
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta content="" name="description">
<meta content="" name="author">

<link href="<?php echo base_url();?>asset/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/font-awesome.min.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/jquery-ui.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/datatable-bootstrap.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/chosen.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/prism.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/examples.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/style.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/morris.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/plot.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/jquery-confirm.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/thickbox.css" rel="stylesheet" media="screen">

<link href="<?php echo base_url();?>asset/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" media="screen">

<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/chosen.jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/prism.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/fancybox/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/projects_plugin.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/custom.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.nav.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.nicescroll.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.jeditable.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.sieve.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.slimscroll.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery-confirm.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/thickbox-compressed.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.jscroll.js"></script>


<script>
	$(function(){
		$("html").niceScroll();		
		 var searchTemplate = "<div class='search-sieve'><div class='search-group'><input type='text' placeholder='' class='form-control'> <span> <i class='fa fa-search'></i></span></div></div>"
		 $(".table-sieve").sieve({ searchTemplate: searchTemplate }); 		 
	});	
</script>

<base href="<?php echo base_url().index_page();?>">
</head>

<body>		
		<div class="navbar navbar-default navbar-static-top navbar-custom">
			<div class="navbar-inner">
				<div class="container nav-color">					
					 <div class="navbar-header ">
						<button class="navbar-toggle collapsed" data-target=".navbar-collapse" data-toggle="collapse" type="button">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand site-title" href="<?php echo base_url(); ?>">
							<span class="site-name"><?php echo $this->config->item('site_title') ?></span>
							<span><?php echo $this->session->userdata('title_project'); ?></span>
							<small><?php echo $this->session->userdata('title_profitcenter'); ?></small>
						</a>
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
											<?php if($this->extra->is_admin()): ?>											
											<li><a href="<?php echo base_url().index_page() ?>/manage">Manage Users</a></li>											
											<li><a href="<?php echo base_url().index_page() ?>/auth/settings">Account Settings</a></li>
											<li class="divider"></li>	
											<?php endif; ?> 											
											<li><a href="<?php echo base_url().index_page() ?>/auth/logout">Logout</a></li>											
										</ul>
									</li>								
						  		</ul>

								<ul class="nav navbar-nav navbar-right">
						 			<!-- <li class="dropdown notification-drowdown">
						 				<a href="#" id="notification-btn" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-bell"></span> <span id="notification-circle"></span></a>
						 			</li> -->
						 		</ul>

						 		<ul class="nav menu-create-ul navbar-nav navbar-right">
									<li class="dropdown notification-drowdown">
										<a href="#" id="notification-btn" class="menu-create-button dropdown-toggle" data-toggle="dropdown"><span> <span class="fa fa-plus"></span> CREATE <b class="caret"></b> </span> </a>
										<ul class="dropdown-menu">											
											<li><a href="<?php echo base_url().index_page() ?>/transaction/create/pr">Purchase Requisition</a></li>											
										</ul>
									</li>
						 		</ul>
								
						 </div>
				  </div>
				</div>
			</div>
		</div>

<!-- <div class="container">
	<div class="alert alert-danger" role="alert" style="margin-top:5px;">
		<strong><span></span></strong>
	</div>
</div> -->

<?php echo $template['body'] ?>
</body>
<script type="text/javascript">
/*	var notify = function(){
		$.post('<?php echo base_url().index_page();?>/notification/transaction',function(response){			

			$('#notification-circle').addClass(response.class).html(response.cnt);			
			$('#notification-btn').after(response.content);

		},'json');
	}
	notify();*/

	$('.tool_tip').tooltip();
	
</script>
</html>