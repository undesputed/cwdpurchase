<input type="hidden" value="<?php echo $main_data['pr_id'] ?>" id="transaction_id">
<?php  $data = $this->md_transaction_history->get_transaction_main_ref_id($main_data['pr_id']); ?>
<?php 
	$pr_type = $this->uri->segment(3);	
 ?>
						<div class="t-content">
							<div class="t-header">
								 <a href="<?php echo base_url().index_page(); ?>/transaction_list/purchase_request/<?php echo $pr_type ?>" class="close close-info"><span aria-hidden="true">&times;</span><span></a>
								<h4><?php echo $main_data['purchaseNo']; ?></h4>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<div class="control-group">
										<span class="control-item-group">
											<a class="action-status history-back" href="<?php echo base_url().index_page();?>/transaction_list/purchase_request/outgoing/<?php echo $main_data['purchaseNo']; ?>">Cancel</a>
										</span>
										<span class="control-item-group">
											<span class="label label-info">Edit</span>
										</span>
									</div>
									<?php 

										/*if($pr_type == 'outgoing'){
											echo $this->lib_transaction->status2($main_data['status']); 
										}*/

									 ?>
								</div>
								<div class="col-xs-6">
																	
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
										<textarea name="" id="pr_remarks" cols="3" rows="1" class="form-control"><?php echo $main_data['pr_remarks']; ?></textarea>
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
							<table id="pr-item-list" class="table table-item">
								<thead>
									<tr>										
										<th>Item Name</th>
										<th>Item No</th>
										<th>Unit</th>
										<th>Qty</th>
										<th>Model No</th>
										<th>Serial No</th>
										<th>Remarks</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($details_data as $row): ?>
										<tr>
											<td class="itemDesc"><?php echo $row['itemDesc'];?></td>
											<td class="itemNo"><?php echo $row['itemNo']; ?></td>											
											<td class="unit_measure"><?php echo $row['unitmeasure'];?></td>
											<td class="td-qty">
												<input type="text" class="form-control qty" value="<?php echo $row['qty'];?>" id="qty" style="width:60px">
											</td>
											<td><input type="text" class="form-control modelNo" value="<?php echo $row['modelNo'];?>"></td>
											<td><input type="text" class="form-control serialNo" value="<?php echo $row['serialNo'];?>"></td>
											<td><input type="text" class="form-control remarks" value="<?php echo $row['remarkz'];?>"></td>
											<td><?php echo $row['for_usage'];?></td>											
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
										<td class="td-number"></td>
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
							<hr>
							<div class="row">
								<div class="col-xs-12">
									<button id="update" class="btn btn-primary pull-right">Update</button>
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

				$('#update').on('click',this.update);				
			},pending_event:function(){

				$post = {
					id     : $('#transaction_id').val(),					
					status : 'PENDING',
					type   : 'PR',
				};
				event_app.execute_query($post);

			},approved_event:function(){

				$post = {
					id     : $('#transaction_id').val(),					
					status : 'APPROVED',
					type   : 'PR',
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
					type   : 'PR',
				};
				event_app.execute_query($post);
			},execute_query:function($post){
					$.post('<?php echo base_url().index_page();?>/transaction/change_status',$post,function(response){
					location.reload('true');
				});
			},update:function(){
				var bool = confirm('Are you Sure?');
				if(!bool){
					return false;
				}

				var item_list = new Array();
				$('#pr-item-list tbody tr').each(function(i,val){

					var item_details = {
						itemDesc  : $(val).find('.itemDesc').text(),
						itemNo    : $(val).find('.itemNo').text(),
						unit_measure : $(val).find('.unit_measure').text(),
						qty       : $(val).find('.qty').val(),
						model_no  : $(val).find('.modelNo').val(),
						serial_no : $(val).find('.serialNo').val(),
						remarks   : $(val).find('.remarks').val(),
					}
					item_list.push(item_details);

				});

				$post = {
					'pr_remarks': $('#pr_remarks').val(),
					'pr_id'     : $('#transaction_id').val(),
					'transaction_no' : '<?php echo $main_data['purchaseNo']; ?>',
					'details'   : item_list,
				};
								
				$.post('<?php echo base_url().index_page();?>/procurement/purchase_request/update',$post,function(response){
					alert('Successfully Updated');
					updateContent();
					/*window.location = "<?php echo base_url().index_page(); ?>/transaction_list/purchase_request/outgoing/<?php echo $main_data['purchaseNo']; ?>";*/
				});

			}
		}
		event_app.init();
	});
</script>						