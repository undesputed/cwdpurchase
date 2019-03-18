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
 <link href="<?php echo base_url();?>asset/css/style.css" rel="stylesheet" media="screen">
 <link rel="stylesheet" href="<?php echo base_url();?>asset/css/style.css">
 <script src="<?php echo base_url();?>asset/js/jquery-1.7.2.js"></script>
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
					<a class="navbar-brand" href="<?php echo base_url(); ?>">Lawfirm</a>
				 </div>

				 <div class="navbar-collapse collapse">
					  <ul class="nav navbar-nav">													
						
					  </ul>
					  
					   <ul class="nav navbar-nav navbar-right">			
							<li><a href="<?php echo base_url().index_page();?>/signup">sign up</a></li>
							<li><a href="<?php echo base_url().index_page();?>/auth/login">login</a></li>
					  </ul>
				  </div>
				</div>
			</div>
		</div>	


 <?php echo $template['body'] ?>


 <script src="<?php echo base_url();?>asset/js/bootstrap.js"></script>
</body>
</html> 
