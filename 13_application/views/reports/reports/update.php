	


	
	<section id="sidebar">
		
		<div class="row">
			<div class="col-md-12">
				<div class="content-title" style="margin-left:15px">
					<h3>Archive</h3>
				</div>
					
				<ul class="sidebar-ul">
						<?php echo $sidebar; ?>
				</ul>
			</div>
		</div>	
	</section>
		
	<section class="page-content-wrapper">

		<div id="content">		
			<div class="container">
				<form id="form1" method="post" action="" enctype="multipart/form-data">			
							<div class="content-title">
								<h3>Update Report</h3>
							</div>
							<?php echo $this->extra->alert(); ?>		
							<!--panel-->
							<div class="panel panel-default">		
							 <div class="panel-body">
									
									<div class="row">
										<div class="col-md-6">										
													<div class="form-group">
											  			<div class="control-label">Subject</div>
											  			<input name="subject" id="subject" class="form-control input-sm required" value="<?php echo $row['subject']; ?>">
											  		</div>
										</div>
										<div class="col-md-3"></div>
										<div class="col-md-3">
											<div class="form-group">
											  			<div class="control-label">Submission Date</div>
											  			<input name="date" id="date" class="form-control input-sm" value="<?php echo $row['submission_date']; ?>">
											</div>
										</div>
									</div>

									<div class="row">

										<div class="col-md-9">
											<div class="form-group">
											  			<div class="control-label">Caption</div>
											  			<textarea name="caption" id="caption" cols="30" rows="4" class="form-control input-sm"><?php echo $row['caption']; ?></textarea>
											</div>
										</div>

										<div class="col-md-3">								
											<div class="form-group">
												<div class="control-label">Attach File</div>
												<div class="well">
													<input type="file" id="file" name="file">
												</div>
											</div>
										</div>

									</div>

							</div><!--/endbody-->

							<div class="form-footer">
								<div class="row">
									<div class="col-md-9"></div>
									<div class="col-md-3">
										<input id="save" name="submit" class="btn btn-success col-xs-5 pull-right disabled" type="submit" value="Update">
									</div>
								</div>
							</div>

						</div>
						<!--/Panel-->
						</div>
						</form>

			</div>
		</div>
				
	</section>




	
	<script>
	var app = {
			init:function(){
				$('#date').date();
				this.bindEvents();
			},
			bindEvents:function(){
				$('.required').on("keyup",function(){
					if($(this).val()!=""){
						$('#save').removeClass("disabled");
					}else{
						$('#save').addClass("disabled");
					}					
				});

				/*
				$('#form1').on('submit',function(){
					//$('#form1').serialize();
					$.post('<?php echo base_url().index_page();?>/reports/execute_create',$('#form1').serialize,function(response){
						console.log(response);
					});
				});
				*/
			}

		
		}

	$(function(){
		app.init();			
	});

	</script>











