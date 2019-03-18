<style>
	.table-boq-details { 
		border: 1px solid #ccc;
	}
	.t1{
		display:block;
		font-size:11px;
	}
	.table-boq-details thead th, .table-boq-details thead td{
		text-align: center;
		border: 1px solid #ccc;
		font-weight: bold;
	}
	.table-boq-details tbody td{
		vertical-align: middle !important;
		text-align: center;
	}
	.number{
		font-size:15px;
	}

	.table-boq-details td{
		border : 1px solid #ccc;
	}
	
	.pointer {
		cursor: pointer;
	}
	
	.text-right { 
		text-align: right;
	}
	.text-left { 
		text-align: left !important;
	}
	.thead { 
		font-weight: bold;
	}
</style>

<div class="content-page">
	<div class="content">
		
		<div class="header">
			<h2>BOQ PROJECT DETAILS</h2>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-12">
					<div class="content-title">
						<h3>Project Information</h3>
					</div>
					<div class="panel panel-default">		
						<div class="panel-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<div class="control-label">Project </div>
										<strong><?php echo $boq_main_new['project_name']; ?></strong>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div class="control-label">Project Type </div>
										<strong><?php echo $boq_main_new['project_category_name']; ?></strong>				  			
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<div class="control-label">Location </div>
										<strong><?php echo $boq_main_new['project_location']; ?></strong>
									</div>
								</div>
							</div>
						</div>	 
					</div>
				</div>
				<div class="col-md-12" id="purchase-request-container">
					
				</div>
			</div>
		</div>
		<div id="boq-details-container">
			<!-- AJAX REQUEST -->
		</div>
	</div>
</div>
<div id="ajax-loader" style="width: 350px; display: none;">
	<div style="text-align: center;"><img src="images/loadingAnimation.gif" /></div>
</div>
<script type="text/javascript">
	$(function(){
		var base_url = '<?php echo base_url(); ?>index.php/';
		
		$(document).ready(function(){ 
			ajax_show_boq_details();
			click_mark_completed_btn();
			click_edit_boq_detail_btn();
			click_update_boq_detail_btn();
			click_purchase_request_btn();
			click_pr_add_item();
			remove_pr_item();
			save_purchase_request();
		});
		
		function click_mark_completed_btn() { 
			$('body').on('click', '#btn-mark-completed', function() { 
				var boq_main_id = $(this).attr('data-id');
				$.post(base_url+'boq/ajax_mark_as_completed', {'boq_main_id' : boq_main_id}, function(res) { 
					var obj_res = $.parseJSON(res);
					if ( obj_res.ctr ) { 
						$('#btn-mark-completed').css('display', 'none');
					}
					if ( $.trim(obj_res.msg) != '' ) { 
						alert(obj_res.msg);
					}
				});
			});
		}
		
		function click_edit_boq_detail_btn() { 
			$('body').on('click', '.edit-boq-detail', function() { 
				var boq_detail_id = $(this).attr('data-id');
				
				$.fancybox({
					type: 'ajax', 
					href: base_url+'boq/ajax_popup_edit_boq_detail/'+boq_detail_id, 
					afterLoad: function() { 
						
					}
				});
			});
		}
		
		function click_update_boq_detail_btn() { 
			$('body').on('click', '#btn-update-boq-detail', function() { 
				var boq_detail_id = $(this).attr('data-id');
				
				$.post(base_url+'boq/ajax_update_boq_detail/'+boq_detail_id, $('#frm-boq-details').serialize(), function(res) { 
					var obj_res = $.parseJSON(res);
					
					if ( obj_res.ctr ) { 
						ajax_show_boq_details();
						$.fancybox.close();
					}
					if ( $.trim(obj_res.msg) != '' ) alert(obj_res.msg);
				});
			});
		}
		
		function ajax_show_boq_details() { 
			$.get(base_url+'boq/ajax_show_boq_details/<?php echo $boq_main_new['id']; ?>', function(res) { 
				$('#boq-details-container').html(res);
			});
		}
		
		function click_purchase_request_btn() { 
			$('body').on('click', '.btn-purchase-request', function() { 
				var boq_detail_id = $(this).attr('data-id');
				
				$.fancybox({
					type: 'ajax', 
					href: base_url+'boq/ajax_popup_pr_add_item/'+boq_detail_id, 
					afterLoad: function() { 
						
					}
				});
			});
		}
		
		function click_pr_add_item() { 
			$('body').on('click', '#btn-pr-add-item', function() { 
				var boq_detail_id = $(this).attr('data-id');
				
				$.post(base_url+'boq/ajax_add_pr_item/'+boq_detail_id, $('#frm-boq-details').serialize(), function(res) { 
					show_pr_form();
					$.fancybox.close();
				});
			});
		}
		
		function show_pr_form() { 
			$.get(base_url+'boq/ajax_show_pr_request_form', function(res) { 
				$('#purchase-request-container').html(res).promise().done(function(){ 
					$('#pr_date').date({
						url : 'get_pr_code',
						dom : $('#pr_no'),
						event : 'change',
					});
					$('#priority_date').date();
				});
				$('html,body').animate({ scrollTop: $('#purchase-request-container').offset().top-50 }, 'slow');
			});
		}
		
		function remove_pr_item() { 
			$('body').on('click', '.remove-pr-item', function() { 
				var index = $(this).attr('data-id');
				
				if ( confirm('Continue removing item?') ) { 
					$.post(base_url+'boq/ajax_remove_pr_item', {'index' : index}, function() { 
						show_pr_form();
					});
				}
			});
		}
		
		function save_purchase_request() { 
			$('body').on('click', '#btn-save-pr', function() { 
				$.post(base_url+'boq/ajax_process_save_new_pr', $('#frm-purchase-request').serialize(), function(res) { 
					var obj_res = $.parseJSON(res);
					if ( obj_res.ctr ) {
						$('#purchase-request-container').html('');
					}
					if ( $.trim(obj_res.msg) ) { alert(obj_res.msg); }
				});
			});
		}
	});
</script>