<style>
	.t1{
		display:block;
		font-size:11px;
	}
	.table-boq-report thead th{
		text-align: center;
		font-size:20px;
	}
	.table-boq-report tbody td{
		vertical-align: middle !important;
		text-align: center;
	}
	.number{
		font-size:15px;
	}

	.table-boq-report td{
		border : 1px solid #ccc;
	}
	
	.pointer {
		cursor: pointer;
	}
</style>

<div class="content-page">
	<div class="content">
		
		<div class="header">
			<h2>BOQ UPLOADS</h2>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-9">
					<div class="content-title">
						<h3>Item Information</h3>
					</div>
					<div class="panel panel-default">		
						<div class="panel-body">
							<?php echo form_open_multipart('boq/process_upload'); ?>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="control-label">Project </div>
											<select name="project" id="project" class="form-control">
												<option value="">- Select Project -</option>
												<?php 
													foreach ( $projects AS $project ) { ?>
														<option value="<?php echo $project->project_id; ?>"><?php echo $project->project_name; ?> - <?php echo $project->project_location; ?></option>
												<?php 
													} ?>
											</select>					  			
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<div class="control-label">Project Type </div>
											<select name="project_type" id="project_type" class="form-control">
												<option value="">- Select Project Type -</option>
												<?php 
													foreach ( $project_categories AS $project_category ) { ?>
														<option value="<?php echo $project_category->id; ?>"><?php echo $project_category->project_name; ?></option>
												<?php 
													} ?>
											</select>					  			
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<div class="control-label">Upload File </div>
											<input type="file" name="boq_upload_file" id="boq_upload_file" />
										</div>
									</div>
								</div>
									
								<div class="col-md-12">
									<button id="upload_boq" class="btn btn-primary nxt-btn">Upload BOQ</button>
								</div>
								<?php 
									$upload_response = $this->session->flashdata('upload_response');
									if ( isset($upload_response['errors']) && is_array($upload_response['errors']) && count($upload_response['errors']) ) { ?>
										<div class="col-md-12" style="color: #ff0000;">
											<ul>
												<?php 
													foreach ( $upload_response['errors'] AS $error ) { 
														echo '<li>'.$error.'</li>';
													}
												?>
											</ul>
										</div>
								<?php 
									}
									else if ( isset($upload_response['success_message']) ) { ?>
										<div class="col-md-12" style="color: green;">
											<?php echo $upload_response['success_message'];  ?>
										</div>
								<?php 
									}
								?>
							</form>
						</div>	 
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" id="tbl-boq-main">
				<!-- AJAX Request Result -->
			</div>
		</div>
	</div>
</div>
<div id="ajax-loader" style="width: 350px; display: none;">
	<span style="font-weight: bold;">Processing BOQ Details.<br/>
	You will be redirected to BOQ Details page shortly. Please wait...</span>
	<div style="text-align: center;"><img src="images/loadingAnimation.gif" /></div>
</div>
<script type="text/javascript">
	$(function(){
		var base_url = '<?php echo base_url(); ?>index.php/';
		
		$(document).ready(function(){ 
			ajax_show_boq_table();
			click_delete_btn();
			click_process_boq_details();
		});
		
		function ajax_show_boq_table() {
			$.get(base_url+'boq/ajax_show_boq_table', function(res){ 
				$('#tbl-boq-main').html(res);
			});
		}
		
		function click_delete_btn() { 
			$('body').on('click', '.delete-boq', function() { 
				var boq_id = $(this).attr('data-id');
				
				if ( confirm('Continue deleting BOQ: ' + boq_id) ) { 
					$.post(base_url+'boq/ajax_delete_boq', {'boq_id': boq_id}, function(res) { 
						var obj_res = $.parseJSON(res);
						
						if ( obj_res.ctr ) { 
							ajax_show_boq_table();
						}
						if ( $.trim(obj_res.msg) != '' ) { 
							alert(obj_res.msg);
						}
					});
				}
			});
		}
		
		function click_process_boq_details() { 
			$('body').on('click', '.process_boq_details', function(res) { 
				var boq_main_id = $(this).attr('data-id');
				
				$.fancybox({
					href: '#ajax-loader', 
					modal: true, 
					afterLoad: function() { 
						$.get(base_url+'boq/ajax_process_boq_details/'+boq_main_id, function(res) { 
							var obj_res = $.parseJSON(res);
							if ( obj_res.ctr ) { 
								ajax_show_boq_table();
								window.location.href = base_url + 'boq/boq_details/'+boq_main_id;
							}
							if ( $.trim(obj_res.msg) ) { 
								alert(obj_res.msg);
								$.fancybox.close();
							}
						});
					}
				});
			});
		}
	});
</script>