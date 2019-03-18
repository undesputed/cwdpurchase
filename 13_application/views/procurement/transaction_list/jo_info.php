<input type="hidden" value="<?php echo $main_data['id'] ?>" id="transaction_id">
 
						<div class="t-content">
														
							<div class="t-header">
								<h4><?php echo $main_data['job_order_no']; ?></h4>
							</div>
							
							<div class="row">
								<div class="col-xs-5">
									<div class="control-group">										
										<?php if(strtoupper($main_data['status'])!="CANCELLED"): ?>
										<a href="<?php echo base_url().index_page();?>/print/jo/<?php echo $main_data['job_order_no']; ?>" target="_blank" class="action-status"><i class="fa fa-print"></i> Print</a>
										<?php else: ?>
											<?php
												if($this->lib_auth->restriction('USER'))
												{
													
													if(strtoupper($main_data['status'])!='APPROVED'){
														echo $this->lib_transaction->status2($main_data['status'],$main_data['job_order_no']);
													}else{
														echo "<span class='label label-success'>".$main_data['status']."</span>";
													}
													
												}										
											?>
										<?php endif; ?>
									</div>
								</div>
								
								<div class="col-md-5">
									
									<?php 						
									
										$branch_type = $this->session->userdata('branch_type');										
										$where = '';									
										switch($branch_type){
											case "MAIN OFFICE":
																								

													if($this->lib_auth->restriction('USER'))
													{
														echo $this->lib_transaction->status4($main_data['status'],$main_data['id']);
													}

													/*if($this->lib_auth->restriction('CANVASS USER'))
													{
														if(strtoupper($main_data['status'])=='APPROVED'){
															echo $this->extra->label($main_data['status']);	
														}	
													}*/
														
													

											break;
											case "PROFIT CENTER":
												echo $this->extra->label($main_data['status']);									
											break;
											default:
													
											break;										
										}
																
								  ?>
								  
								</div>
							</div>
													
							<div class="row" style="margin-top:10px">
								<div class="col-xs-6">
									<div class="t-title">
										<div>Created From : </div> 
										<strong><?php echo $main_data['project_name']; ?></strong>
									</div>
									<div class="t-title">
										<div>Date: </div>
										<strong><p><?php echo $main_data['job_order_date'] ?></p></strong>
									</div>
									<div class="t-title">
										<div>Remarks : </div>
										<strong><p><?php echo $main_data['remarks'] ?></p></strong>
									</div>
									
								</div>
								<div class="col-xs-6">
									<div class="t-title">
										<div>Request To : </div> 
										<strong><?php echo $main_data['supplier']; ?></strong>
									</div>
								</div>
							</div>
							
							<div class="table-responsive" style="overflow:auto">
							<table class="table table-item long_item">
								<thead>
									<tr>										
										<th>Item Description</th>
										<th>Amount</th>
										<th>Remarks</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($details_data as $row): ?>
										<tr>
											<td><?php echo $row['item_description'];?></td>
											<td class="td-number amount" style="text-align:right;"><?php echo number_format($row['amount'],2);?></td>										
											<td><?php echo $row['remark'];?></td>
										</tr>
									<?php endforeach ?>
								</tbody>
								<tfoot>
									<tr>
										<td><?php echo count($details_data); ?> item(s)</td>
										<td class="td-number" style="text-align:right;">TOTAL: <?php echo number_format($main_data['total_amount'],2);?></td>
										<td></td>
									</tr>
								</tfoot>
							</table>
							</div>							
							<div class="row">
								<div class="col-xs-6">
									<div class="t-title">
										<div>Prepared By : </div> 
										<strong><?php echo $main_data['preparedByName']; ?></strong>										
									</div>
								</div>
								<div class="col-xs-6">
									<div class="t-title">
										<div>Approved By : </div> 
										<strong><?php echo $main_data['approvedByName'] ?></strong>										
									</div>
								</div>
							</div>
					
						</div>



<script>
	$(function(){
		var event_app = {
			init:function(){
				this.bindEvent();
			},
			bindEvent:function(){

				$('.pending-event').on('click',this.pending_event);
				$('.approved-event').on('click',this.approved_event);
				$('.reject-event').on('click',this.reject_event);
				$('.cancel-event').on('click',this.cancel_event);
				
			},pending_event:function(){

				$post = {
					id     : $('#transaction_id').val(),
					transaction_no : "<?php echo $main_data['job_order_no']; ?>",
					status : 'PENDING',
					type   : 'JO',
					remarks: '',
				};
				event_app.execute_query($post);

			},approved_event:function(){

				if($('.required').required()){
					return false;
				}
				
				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}
				var item_container = new Array();
				$('.approved-qty').each(function(i,val){

					var item = {
						qty    : $(val).val(),
						itemNo : $(val).attr('data-itemno'),
					}
					item_container.push(item);

				});
				
				$post = {
					id      : $('#transaction_id').val(),
					status  : 'APPROVED',
					transaction_no : "<?php echo $main_data['job_order_no']; ?>",
					type    : 'JO',
					remarks : '',
				};

				event_app.execute_query($post);
				
			},reject_event:function(){
				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}

				var remarks = prompt('Reason for Rejection');
					
				if (remarks == '') {
					alert('Please add reason');
					return false;
				}

				$post = {
					id     : $('#transaction_id').val(),					
					status : 'REJECTED',
					transaction_no : "<?php echo $main_data['job_order_no']; ?>",
					remarks: remarks,
					type   : 'JO',
				};
				event_app.execute_query($post);

			},cancel_event:function(){
				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}

				$post = {
					id     : $('#transaction_id').val(),					
					status : 'CANCEL',
					transaction_no : "<?php echo $main_data['job_order_no']; ?>",
					remarks: '',
					type   : 'JO',
				};				
				event_app.execute_query($post);

			},execute_query:function($post){
					$.post('<?php echo base_url().index_page();?>/transaction/change_status',$post,function(response){
					switch($.trim(response)){
						case "1":						
							alert('Successfully Updated');
							/*updateContent();
							updateStatus($post.status)*/;
							
							$.fancybox.close();
							location.reload('true');
						break;
					}
					
				});
			}

		}
		event_app.init();
	});
</script>						