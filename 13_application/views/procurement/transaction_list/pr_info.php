<input type="hidden" value="<?php echo $main_data['pr_id'] ?>" id="transaction_id">
<?php  $data = $this->md_transaction_history->get_transaction_main_ref_id($main_data['pr_id']); ?>

<?php 
	

	$outgoing = false;

	if(isset($type)){
		$pr_type = $type;
	}else{
		$pr_type = $this->uri->segment(3);
	}
		
		
 ?> 
						<div class="t-content">
														
							<div class="t-header">
								<!-- <a href="<?php echo base_url().index_page(); ?>/transaction_list/purchase_request/<?php echo $pr_type ?>" class="close close-info"><span aria-hidden="true">&times;</span><span></a> -->
								<h4><?php echo $main_data['purchaseNo']; ?></h4>
							</div>
							
							<div class="row">
								<div class="col-xs-5">
									<div class="control-group">										
										<?php if(strtoupper($main_data['status'])=="APPROVED"): ?>
										<a href="<?php echo base_url().index_page();?>/print/pr/<?php echo $main_data['purchaseNo']; ?>" target="_blank" class="action-status"><i class="fa fa-print"></i> Print PR</a>
										<a href="<?php echo base_url().index_page();?>/print/pr_quotation/<?php echo $main_data['purchaseNo']; ?>" target="_blank" class="action-status"><i class="fa fa-print"></i> Print RFQ</a>
										<?php else: ?>
											<?php
												if($this->lib_auth->restriction('USER'))
												{
													if($pr_type == 'outgoing'){
														if(strtoupper($main_data['status'])!='APPROVED'){
															echo $this->lib_transaction->status2($main_data['status'],$main_data['purchaseNo']);
														}else{
															echo "<span class='label label-success'>".$main_data['status']."</span>";
														}
													}
												}										
											?>
										<?php endif; ?>
									</div>
								</div>
								
								<div class="col-md-5">
									
									<?php 										 
										 if($pr_type=='outgoing'){
										 	$outgoing = true;
										 }
									
										$branch_type = $this->session->userdata('branch_type');										
										$where = '';									
										switch($branch_type){
											case "MAIN OFFICE":
																									
													if($pr_type == 'incoming'){
														if($this->lib_auth->restriction('USER'))
														{
															echo $this->lib_transaction->status($main_data['status'],$main_data['pr_id']);
														}

														if($this->lib_auth->restriction('CANVASS USER'))
														{
															if(strtoupper($main_data['status'])=='APPROVED'){
															?>			
																
															<?php
															}	
														}
														
													}else{
														echo $this->extra->label($main_data['status']);	
													}

											break;
											case "PROFIT CENTER":
												$outgoing = true;											
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
										<strong><?php echo $main_data['from_projectMainName']; ?></strong>
										<strong><?php echo $main_data['from_projectCodeName']; ?></strong>
									</div>
									<div class="t-title">
										<div>Date: </div>
										<strong><p><?php echo $main_data['purchaseDate'] ?></p></strong>
									</div>
									<div class="t-title">
										<div>Remarks : </div>
										<strong><p><?php echo $main_data['pr_remarks'] ?></p></strong>
									</div>
									
								</div>
								<div class="col-xs-6">
									<div class="t-title">
										<div>Request To : </div> 
										<strong><?php echo $main_data['to_projectMainName']; ?></strong>
										<strong><?php echo $main_data['to_projectCodeName']; ?></strong>
									</div>
								</div>
							</div>
							
							<div class="table-responsive" style="overflow:auto">
							<table class="table table-item long_item">
								<thead>
									<tr>										
										<th>Item Name</th>
										<th>Brand</th>
										<th>Request Qty</th>
										<th>Unit</th>
										<th>Approved Qty</th>
										<th>Model No</th>
										<th>Remarks</th>
										<th>Project</th>
										<th>Charging</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($details_data as $row): ?>
										<tr>
											<td><?php echo $row['itemDesc'];?></td>
											<td><?php echo $row['serialNo'];?></td>
											<td style="display:none"><?php echo $row['itemNo']; ?></td>	
											<td class="td-qty td-number req_qty"><?php echo $row['req_qty'];?></td>										
											<td><?php echo $row['unitmeasure'];?></td>
											<td class="td-number td-qty">
												<?php if(strtoupper($main_data['status']) == 'APPROVED'): ?>
												<?php echo $row['qty']; ?>
												<?php elseif($outgoing): ?>
													-
												<?php else: ?>
												<input type="number" data-itemno="<?php echo $row['itemNo']; ?>" class="form-control approved-qty required numbers_only" value="<?php echo $row['req_qty'];?>" style="width:80px">
												<?php endif; ?>
											</td>
											<td><?php echo $row['modelNo'];?></td>
											<td><?php echo $row['remarkz'];?></td>
											<td><?php echo $row['for_usage'];?></td>
											<td><?php echo $row['charging'];?></td>
										</tr>
									<?php endforeach ?>
								</tbody>
								<tfoot>
									<tr>
										<td><?php echo count($details_data); ?> item(s)</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td class="td-number"></td>
										<td></td>
										<td></td>
									</tr>
								</tfoot>
							</table>
							</div>							
							<div class="row">
								<div class="col-xs-6">
									<div class="t-title">
										<div>Prepared By : </div> 
										<strong><?php echo $main_data['person_preparedBy']; ?></strong>										
									</div>
									<div class="t-title">
										<div>Requested By : </div> 
										<strong><?php echo $main_data['person_recommendedBy'] ?></strong>										
									</div>
									<div class="t-title">
										<div>Checked By : </div> 
										<strong><?php echo $main_data['person_checked_by'] ?></strong>										
									</div>
								</div>
								<div class="col-xs-6">
									<div class="t-title">
										<div>Approved By : </div> 
										<strong><?php echo $main_data['person_approvedBy'] ?></strong>										
									</div>
								</div>
							</div>
					
						</div>

						<?php 
							$a = array(
								 'transaction_id'=>$main_data['pr_id'],
								 'transaction_no'=>$main_data['purchaseNo'],
								 'type'=>'Purchase',
								);
							echo $this->lib_transaction->comments($a);
						?>



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
					transaction_no : '<?php echo $main_data['purchaseNo']; ?>',
					status : 'PENDING',
					type   : 'PR',
					remarks: '',
				};
				event_app.execute_query($post);

			},approved_event:function(){

				if($('.required').required()){
					return false;
				}
				var d = true;
				$('.approved-qty').each(function(i,val){
					var req_qty = remove_comma($(val).closest('tr').find('.req_qty').text());
					var approved_qty = $(val).val();
					if(parseInt(approved_qty) > parseInt(req_qty))
					{	
						alert('Invalid Approved Qty');
						d = false;
						return false;
					}
				});

				if(!d)
				{
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
					transaction_no : '<?php echo $main_data['purchaseNo']; ?>',
					type    : 'PR',
					remarks : '',
					details : item_container,
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
					transaction_no : "<?php echo $main_data['purchaseNo']; ?>",
					remarks: remarks,
					type   : 'PR',
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
					transaction_no : "<?php echo $main_data['purchaseNo']; ?>",
					remarks: '',
					type   : 'PR',
				};				
				event_app.execute_query($post);

			},execute_query:function($post){
					$.post('<?php echo base_url().index_page();?>/transaction/change_status',$post,function(response){
					switch($.trim(response)){
						case "1":						
							alert('Successfully Updated');
							updateContent();
							updateStatus($post.status);
							
							$.fancybox.close();
							/*location.reload('true');*/
						break;
					}
					
				});
			}

		}
		event_app.init();
	});
</script>						