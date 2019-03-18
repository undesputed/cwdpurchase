

	<input type="hidden" value="<?php echo $main_data['po_id'] ?>" id="transaction_id">
						<div class="t-content">
							<div class="container">
							<div class="t-header">
								<a href="<?php echo base_url().index_page();?>/transaction_list/purchase_order" class="close close-info" data-dismiss="modal"><span aria-hidden="true">&times;</span><span></a>
								<h4><?php echo $main_data['po_number']; ?></h4>
							</div>
							
									<?php 
									$branch_type = $this->session->userdata('branch_type');
									$where = ''; 
									switch($branch_type){
										case "MAIN OFFICE":														
														?>
														<div class="row">
															<div class="col-md-6">	
																<div class="control-group">															
																<?php if($this->lib_auth->restriction('PO USER')): ?>
																 	
																	<!-- <span class="control-item-group">
																	<?php if($main_data['status'] !='CANCELLED'): ?>
																		<a class="cancel-event action-status" href="javascript:void(0)">Cancel</a>
																	<?php else: ?>
																		<span class="label label-danger">CANCELLED</span>	
																	<?php endif; ?>
																	</span> -->

																	<span class="control-item-group">
																		<a class="action-status history-back" href="<?php echo base_url().index_page();?>/transaction_list/purchase_order/for_approval/<?php echo $main_data['po_number']; ?>"> < Back</a>
																	</span>
																 </div>
																 <?php endif; ?>
															</div>
																<div class="col-md-6">
																<?php if($this->lib_auth->restriction('PO USER')): ?>
																<?php /*echo $this->lib_transaction->po_status($main_data['status'],$main_data['po_number']); */?>
																<?php endif; ?>
																<!-- <div class="control-item-group">
																	<a class="received-event action-status" href="javascript:void(0)">Received</a>
																</div> -->																
															</div>
														</div>
												<?php

										break;
										case "PROFIT CENTER":
										
										break;			
										default:
											
										break;
									}
								  ?>

							<div class="row" style="margin-top:10px">
								
								<div class="col-md-4">
									<div class="t-title">
										<div>Supplier : </div> 
										<select name="" id="supplier_list" class="form-control">
											<?php foreach($supplier_list as $key=>$row): ?>
												 <optgroup label="<?php echo $key; ?>">
												 	<?php foreach($row as $row1): ?>
												 		<?php $selected = ($supplier['supplier_id'] == $row1['supplier_id'])? "selected='selected'":''; ?>
														<option <?php echo $selected; ?> data-type="<?php echo $key ?>" value="<?php echo $row1['supplier_id']; ?>" data-address="<?php echo $row1['address']; ?>" data-contact="<?php echo $row1['contact_no']; ?>"><?php echo $row1['business_name']; ?></option>
												 	<?php endforeach; ?>
												  </optgroup>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="t-title">
										<div>Address : </div> 
										<strong id="address"></strong>									
									</div>
									<div class="t-title">
										<div>Contact No : </div> 
										<strong id="contact_no"></strong>									
									</div>
								</div>
								<div class="col-md-4">
									<div class="t-title">
										<div>PO No : </div> 
										<strong><input type="text" id="reference_no" readonly="readonly" value="<?php echo $main_data['po_number']; ?>"></strong>
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

								<div class="col-md-4">

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
								<div class="col-md-12">
									<div class="t-title">
										<div>P.O Remarks : </div>
										<strong><?php echo $main_data['po_remarks'];?></strong>
									</div>
									
								</div>
							</div>
														
							<div class="table-responsive">
							<table id="tbl-po" class="table table-item">
								<thead>
									<tr>
										<th>Item Name</th>				
										<th>Unit</th>
										<th class="td-number">Qty</th>										
										<th class="td-number">Unit Price</th>
										<th class="td-number">Total</th>
										<th>Remarks</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$grand_total = 0;
										foreach ($details_data as $row): 
										?>
										<tr>
											<td class="item_name"><?php echo $row['item_name'];?></td>											
											<td class="unit_measure"><?php echo $row['unit_msr'];?></td>
											<td class="itemNo" style="display:none"><?php echo $row['itemNo'];?></td>
											<td class="td-qty td-number"><input type="number" style="width:80px" class="input_qty" value="<?php echo $row['quantity'];?>"></td>											
											<?php $cost = (empty($row['discounted_price']))? $row['unit_cost'] : $row['discounted_price']; ?>
											<td class="td-number"> <input type="text" style="width:100px" class="input_cost" value="<?php echo $this->extra->number_format($cost);?>"></td>
											<td class="td-number total_cost"><?php echo $this->extra->number_format($row['total_unitcost']);?></td>
											<?php $grand_total += $row['total_unitcost'];?>
											<td class="remarks"><?php echo $row['remarkz'];?></td>
										</tr>
									<?php endforeach ?>
								</tbody>
								<tfoot>
									<tr>
										<td><?php echo count($details_data); ?> item(s)</td>
										<td></td>
										<td></td>
										<td></td>
										<td colspan="2" class="td-number">Total : <span id="grand_total"><?php echo $this->extra->number_format($grand_total); ?></span></td>
										
										
									</tr>
								</tfoot>
							</table>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="t-title">
										<div>Prepared By : </div> 
										<strong><?php echo $main_data['preparedBy_name'] ?></strong>										
									</div>
									<div class="t-title">
										<div>Recommended By : </div> 
										<strong><?php echo $main_data['recommendedBy_name'] ?></strong>										
									</div>
								</div>
								<div class="col-md-6">
									<div class="t-title">
										<div>Approved By : </div> 
										<strong><?php echo $main_data['approvedBy_name'] ?></strong>										
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary pull-right" id="save">Apply Changes</button>
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

				$('#supplier_list').on('change',this.supplier_list);
				$('#supplier_list').trigger('change');

				$('#save').on('click',this.save);

				$('.input_qty,.input_cost').on('change',function(){

					var input_qty  = $(this).closest('tr').find('.input_qty').val();
					var input_cost = $(this).closest('tr').find('.input_cost').val().replace(/,/g,'');			
					var total      = input_qty * input_cost;
					$(this).closest('tr').find('.total_cost').html(comma(total.toFixed(2)));
					var grand_total = 0;
					$('#tbl-po tbody tr').each(function(i,val){
						grand_total += parseFloat($(val).find('.total_cost').text().replace(/,/g,''));
					});
					$('#grand_total').html(comma(grand_total.toFixed(2)));
				});
				
			},supplier_list:function(){

				var address = $('#supplier_list option:selected').attr('data-address');
				var contact = $('#supplier_list option:selected').attr('data-contact');
				address = (address=="")? '-' : address;
				contact = (contact=="")? '-' : contact;
				$('#address').html(address);
				$('#contact_no').html(contact);

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
					id     : $('#transaction_id').val(),					
					status : 'APPROVED',
					type   : 'PO',
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
					remarks : '',
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
					remarks : '',
				};

				event_app.execute_query($post);

			},execute_query:function($post){					
					$.post('<?php echo base_url().index_page();?>/transaction/change_status',$post,function(response){
						location.reload('true');
					});
			},save:function(){
				var bool = confirm('Are you sure?');		

				if(!bool){
					return false;
				}
				var data_cont = new Array();
				$('#tbl-po tbody tr').each(function(i,val){
					var data = {
						item_name        : $(val).find('.item_name').text(),
						unit_measure     : $(val).find('.unit_measure').text(),
						item_no          : $(val).find('.itemNo').text(),
						quantity         : $(val).find('.input_qty').val(),
						unit_cost : $(val).find('.input_cost').val().replace(/,/g,''),
						total_cost       : $(val).find('.total_cost').text().replace(/,/g,''),
						remarks          : $(val).find('.remarks').text(),
					}
					data_cont.push(data);
				});	

				$post = {
					po_id        : $('#transaction_id').val(),
					transaction_no : '<?php echo $main_data['po_number']; ?>',
					reference_no : $('#reference_no').val(),
					supplierID   : $('#supplier_list option:selected').val(),
					address      : $('#address').val(),
					supplierType : $('#contact_no').val(),
					data         : data_cont,
				}

				$.post('<?php echo base_url().index_page();?>/procurement/purchase_order/edit_po',$post,function(response){
					switch($.trim(response)){
						case "1":
							alert('Successfully Updated');
							updateContent();
							History.back();
						break;

						default : 
						 	alert('Internal Server Error');
						break;
					}					
				});

			}
		}
		event_app.init();
	});
</script>						