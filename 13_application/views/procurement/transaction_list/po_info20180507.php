<style>
	@media screen and (max-width: 600px) {
	  .print_row {
	    visibility: hidden;
	    clear: both;
	    float: left;
	    margin: 10px auto 5px 20px;
	    width: 28%;
	    display: none;
	  }
	}
</style>

<input type="hidden" value="<?php echo $main_data['po_id'] ?>" id="transaction_id">
						<div class="t-content">
							<div class="t-header">
								<a href="<?php echo base_url().index_page();?>/transaction_list/purchase_order" class="close close-info" data-dismiss="modal"><span aria-hidden="true">&times;</span><span></a>
								<h4><?php echo $main_data['po_number']; ?> 
									<?php 

										if($main_data['status'] == 'ACTIVE'){
											echo '<span class="label label-warning" style="font-size:8px!important">FOR APPROVAL</span>';
										}

									?>		
								</h4>	

							</div>
							
									<?php 
									$branch_type = $this->session->userdata('branch_type');
									$where = ''; 
									switch($branch_type){
										case "MAIN OFFICE":														
														?>
														<div class="row">

															<div class="col-xs-6">	
																<?php
																$privilage =  $this->session->userdata('privileges');																												
																?>
																<?php if($this->lib_auth->restriction('PO USER') || in_array('edit', $privilage)): ?>
																 <div class="control-group">
																	<span class="control-item-group">
																	<?php if($main_data['status'] !='CANCELLED'): ?>
																		<a class="cancel-event action-status" href="javascript:void(0)">Cancel</a>																		
																	<?php else: ?>
																		<span class="label label-danger">CANCELLED</span>	
																	<?php endif; ?>
																	</span>																
																	<?php if($this->lib_auth->restriction('ADMIN') || in_array('edit', $privilage)): ?>
																			<span class="control-item-group">
																				<a class="action-status history-link" data-method="purchase_order" data-type="edit" data-value="<?php echo $main_data['po_number']; ?>" href="<?php echo base_url().index_page();?>/transaction_list/purchase_order/for_approval/<?php echo $main_data['po_number']; ?>/edit">Edit</a>
																			</span>																		
																	<?php endif; ?>
																 </div>		
																 <?php endif; ?>
															</div>
															
															<div class="col-xs-6">
															<?php if($this->lib_auth->restriction('PO USER') || in_array('print',$privilage)): ?>
															<?php echo $this->lib_transaction->po_status($main_data['status'],$main_data['po_number']); ?>
															<?php endif; ?>
															</div>

														</div>
														<?php if($this->lib_auth->restriction('ADMIN') || in_array('edit', $privilage)): ?>
														<div class="row print_row">
															<div class="col-md-6"></div>
															<div class="col-md-6">
																<div class="form-group inline">
																	<?php $checked = (!empty($po_status) && $po_status['is_print'] == 'true')? 'checked="checked"' : ''; ?>
																	<label for="is_print"><input id="is_print" type="checkbox" <?php echo $checked; ?> class="is_already"> Already Printed</label>
																</div>
																<div class="form-group inline">
																	<?php $checked = (!empty($po_status) && $po_status['is_email'] == 'true')? 'checked="checked"' : ''; ?>
																	<label for="is_email"><input id="is_email" type="checkbox" <?php echo $checked; ?> class="is_already"> Already Emailed</label>
																</div>																
															</div>
														</div>
														<?php endif; ?>

												<?php

										break;
										case "PROFIT CENTER":
										
										break;			
										default:
											
										break;
									}
								  ?>

							<div class="row" style="margin-top:10px">
								<div class="col-xs-4">
									<div class="t-title">
										<div>Supplier : </div> 
										<strong><?php echo $supplier['business_name'] ?></strong>										
									</div>
									<div class="t-title">
										<div>Address : </div> 
										<strong><?php echo $supplier['address']; ?></strong>									
									</div>
									<div class="t-title">
										<div>Contact No : </div> 
										<strong><?php echo $supplier['contact_no']; ?></strong>									
									</div>
								</div>

								<div class="col-xs-4">
									
									<div class="t-title">
										<div>PO No : </div> 
										<strong><?php echo $main_data['po_number']; ?></strong>
									</div>
									<div class="t-title">
										<div>P.O Date : </div> 
										<strong><?php echo $main_data['po_date']; ?></strong>										
									</div>
									<div class="t-title">
										<div>Place of Delivery : </div> 
										<strong><?php echo $main_data['placeDelivery']; ?></strong>										
									</div>
									<div class="t-title">
										<div>Delivery Date :</div> 
										<strong><?php echo $main_data['dtDelivery']; ?></strong>										
									</div>
									<div class="t-title">
										<div>Terms of Payment :</div> 										
										<strong><?php echo $main_data['paymentTerm']." - ".$main_data['indays'] ?></strong>										
									</div>
								</div>

								<div class="col-xs-4">
									<div class="t-title">
										<div>P.R No : </div> 
										<strong><?php echo $pr_main['purchaseNo']; ?></strong>										
									</div>
									<div class="t-title">
										<div>P.R Date : </div> 
										<strong><?php echo $pr_main['purchaseDate']; ?></strong>										
									</div>
									<div class="t-title">
										<div>Created From :</div> 
										<strong><?php echo $pr_main['from_projectCodeName']; ?></strong>										
									</div>									
								</div>

							</div>

							<div class="row">
								<div class="col-xs-12">
									<div class="t-title">
										<div>P.O Remarks : </div>
										<strong><?php echo $main_data['po_remarks'];?></strong>
									</div>
									
								</div>
							</div>
							<div class="table-responsive">
							<table class="table table-item">
								<thead>
									<tr>
										<th>For</th>
										<th>Item Name</th>				
										<th>Brand</th>
										<th>Unit</th>										
										<th class="td-number">Qty</th>
										<th class="td-number">Unit Price</th>
										<th class="td-number">Total</th>										
										<th>PR Remarks</th>
										<th>Po Remarks</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$grand_total = 0;
										foreach ($details_data as $row): 
										?>
										<tr>
											<td><?php echo $this->extra->project_type_label($row['for_usage1']); ?></td>
											<td><?php echo $row['item_name'];?></td>
											<td><?php echo $row['ModelNo'] ?></td>
											<td><?php echo $row['unit_msr'];?></td>											
											<td class="td-qty td-number"><?php echo $this->extra->comma($row['quantity']);?></td>
											<?php $cost = (empty($row['discounted_price']))? $row['unit_cost'] : $row['discounted_price']; ?>
											<td class="td-number"><?php echo $this->extra->number_format($cost);?></td>
											<td class="td-number"><?php echo $this->extra->number_format($row['total_unitcost']);?></td>
											<?php $grand_total += $row['total_unitcost'];?>
											<td><?php echo $row['remarkz'];?></td>
											<td><?php echo $row['remarks'];?></td>
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
										<td colspan="2" class="td-number">Total : <strong><span><?php echo $this->extra->number_format($grand_total); ?></span></strong></td>										
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
										<strong><?php echo $main_data['preparedBy_name'] ?></strong>										
									</div>
									<div class="t-title">
										<div>Checked By : </div> 
										<strong><?php echo $main_data['recommendedBy_name'] ?></strong>										
									</div>
								</div>
								<div class="col-xs-6">
									<div class="t-title">
										<div>Approved By : </div> 
										<strong><?php echo $main_data['approvedBy_name'] ?></strong>										
									</div>
								</div>
							</div>
						</div>
						
						<?php 							
							$a = array(
								 'transaction_id'=>$main_data['po_id'],
								 'transaction_no'=>$main_data['po_number'],
								 'type'=>'Purchase Order',
								);
							echo $this->lib_transaction->comments($a);
						?>




<script>
	$(function(){
		var xhr = '';
		var event_app = {
			init:function(){
				this.bindEvent();
			},
			bindEvent:function(){

				$('.pending-event').on('click',this.pending_event);				
				$('.approved-event').on('click',this.approved_event);
				$('.reject-event').on('click',this.reject_event);
				$('.cancel-event').on('click',this.cancel_event);
				$('.received-event').on('click',this.received_event);
				$('.edit-event').on('click',this.edit_event);

				$('.is_already').on('click',this.is_already);

			},is_already:function(e){
				e.preventDefault();
				var me = $(this);
				var status = $(this).is(":checked");
				
				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}
				
		        if(xhr && xhr.readystate != 4){
		            xhr.abort();
		        }

				 $post = {
						po_id  : $('#transaction_id').val(),
						data   :{
								po_id    : $('#transaction_id').val(),
								is_print : $('#is_print').is(':checked'), 
								is_email : $('#is_email').is(':checked'),	
						}						
					}

				xhr = $.post('<?php echo base_url().index_page();?>/transaction/user_status',$post,function(){
					me.prop('checked',status);
					location.reload(true);
				});


			},received_event:function(){
				$post = {
					id     : $('#transaction_id').val(),					
					status : 'PENDING',
					type   : 'PR',
				};
				event_app.execute_query($post);

			},pending_event:function(){

				$post = {
					id     : $('#transaction_id').val(),					
					status : 'PENDING',
					type   : 'PO',
					transaction_no : '<?php echo $main_data['po_number']; ?>',
					remarks : '',
				};
				event_app.execute_query($post);

			},approved_event:function(){

				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}

				$post = {
					id      : $('#transaction_id').val(),
					status  : 'APPROVED',
					type    : 'PO',
					transaction_no : '<?php echo $main_data['po_number']; ?>',
					remarks : '',
				};
				event_app.execute_query($post);
				
			},reject_event:function(){

				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}
				$post = {
					id     : $('#transaction_id').val(),					
					status : 'REJECTED',
					type   : 'PO',
					transaction_no : '<?php echo $main_data['po_number']; ?>',
					remarks: '',
				};
				event_app.execute_query($post);

			},cancel_event:function(){
				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}

				$post = {
					id     : $('#transaction_id').val(),					
					status : 'CANCELLED',
					type   : 'PO',
					transaction_no : '<?php echo $main_data['po_number']; ?>',
					remarks: '',
				};

				event_app.execute_query($post);

			},execute_query:function($post){

			        if(xhr && xhr.readystate != 4){
			            xhr.abort();
			        }
			        
					xhr = $.post('<?php echo base_url().index_page();?>/transaction/change_status',$post,function(response){
						switch($.trim(response)){
							case "1":
								alert('Status Updated!');
								updateContent();
								updateStatus($post.status);
							break;

						}
					});
			},
		}
		event_app.init();
	});
</script>						