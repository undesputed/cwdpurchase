
<input type="hidden" value="<?php echo $main_data['po_id'] ?>" id="transaction_id">		
<input type="hidden" value="<?php echo $main_data['po_number'] ?>" id="po_number">		
<input type="hidden" value="" id="rr_no" class="required">
<input type="hidden" value="<?php echo $supplier['supplier_id']; ?>" id="supplier_id">

						<div class="t-content">
							<div class="t-header">
								<a href="<?php echo base_url().index_page();?>/transaction_list/receiving_report" class="close close-info" data-dismiss="modal"><span aria-hidden="true">&times;</span><span></a>
								<h4><?php echo $main_data['po_number']; ?> <small id="rr-no-title"><i class="fa fa-spin fa-spinner"></i></small></h4>								
							</div>
							<div class="row">
								<div class="col-xs-6">
									
								</div>
								<div class="col-xs-6">
									<div class="control-group">
										<?php if($main_data['status'] == 'PARTIAL'): ?>
										<span class="label label-info">Partial</span>										
										<?php elseif($main_data['status'] == 'FULL'): ?>
										<span class="control-item-group">
											<span class="action-status">Complete</span>											
										</span>
										<?php endif; ?>								
									</div>
								</div>
							</div>
							
							<div class="row" style="margin-top:10px">
								<div class="col-xs-5">
									<div class="t-title">
										<div>RR Date : </div>
										<input type="text" id="rr_date" class="form-control date required">
									</div>
									<div class="t-title">
										<div>S.I No : </div>
										<input type="text" id="invoice_no" class="form-control required">
									</div>
									<div class="t-title">
										<div>S.I Date : </div>
										<input type="text" id="invoice_date" class="form-control date required">
									</div>
									<div class="t-title">
										<div>D.R No : </div>
										<input type="text" id="dr_no" class="form-control required">
									</div>
									<div class="t-title">
										<div>Status : </div>
										<span id="_status">
											<span class="label label-info">Partial</span>
										</span>
										<input type="hidden" id="rr_status" value="">										
									</div>
								</div>
								<div class="col-xs-3">
									<div class="t-title">
										<div>P.O No : </div> 
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
							</div>
							
							<div class="table-responsive">
							<table id="receiving_table" class="table table-item">
								<thead>
									<tr>
										<th style="display:none">Item No</th>
										<th>Item Name</th>										
										<th>Unit</th>
										<th class="td-number td-qty">P.O Qty</th>
										<th class="td-number">Qty Received</th>
										<th class="td-number">Unit Cost</th>										
										<th>Discrepancy</th>										
									</tr>
								</thead>
								<tbody>
									<?php 
										$grand_total = 0;
										foreach ($details_data as $row): 
										?>
										<tr>
											<td class="itemNo" style="display:none"><?php echo $row['itemNo']; ?></td>
											<td class="itemName"><?php echo $row['item_name'];?></td>											
											<td class="unit_msr"><?php echo $row['unit_msr'];?></td>
											<td class="qty td-number td-qty"><?php echo $row['quantity']; ?></td>
											<td class="td-qty td-number"><input type="number" class="form-control required rr-qty numbers_only" style="width:80px"></td>
											<td class="td-number unit_cost"><?php echo $this->extra->number_format($row['unit_cost']);?></td>
											<!-- <td class="td-number total_unit_cost"><?php echo $this->extra->number_format($row['total_unitcost']);?></td> -->
											<?php $grand_total += $row['total_unitcost'];?>
											<td><input type="number" class="form-control discrepancy" style="width:80px" value="0"></td>											
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
									</tr>
								</tfoot>
							</table>
						</div>
							<div class="row">
								<div class="col-xs-12">
									<div>
										<div class="t-title">
										<div>Delivered By :</div>
										<input type="text" id="delivered_by" class="form-control required uppercase">
										</div>										
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<div class="t-title">
										<div>Checked By : </div> 
										<select name="" id="checked_by" class="form-control">
											
										</select>
									</div>
										<div class="t-title">
										<div>Noted By : </div> 
										<select name="" id="noted_by" class="form-control">
											
										</select>
									</div>									
								</div>
								<div class="col-xs-6">
									<div class="t-title">
										<div>Received By : </div> 
										<select name="" id="received_by" class="form-control">										
										</select>
									</div>
								</div>
							</div>
							
							<div class="row">								
								<div class="col-xs-12">
									<button class="pull-right btn btn-primary" id="save">Save</button>
								</div>
							</div>
														
						</div>


<script>
	$(function(){
		var xhr = "";
		var event_app = {
			init:function(){

				$('#rr_date').date({
					url   : 'get_rr_code',
					dom   : $('#rr_no'),
					div   : $('#rr-no-title'),
					event : 'change',
				});

				$('.date').date();
				this.bindEvent();


				$.signatory({
					type          : 'rr',										
					checked_by    : ["4", "5", "1", "0"],
					noted_by      : ["4", "4", "1", "0"],
					received_by   : ["4", "4", "1", "0"],
				});


			},
			bindEvent:function(){

				$('.pending-event').on('click',this.pending_event);				
				$('.approved-event').on('click',this.approved_event);
				$('.reject-event').on('click',this.reject_event);				
				$('.cancel-event').on('click',this.cancel_event);
				$('.received-event').on('click',this.received_event);				
				$('#save').on('click',this.save);

				$('.rr-qty').on('change',function(i,val){							
					var me = $(this);

					var po_qty = me.closest('tr').find('.qty').text();
					var rr_qty = me.val();
					var dis_total= po_qty - rr_qty ;
					me.closest('tr').find('.discrepancy').val(dis_total);
					

					if(parseInt(rr_qty) > parseInt(po_qty)){						
						alert('Invalid Qty');
						me.val('');
					}else if(parseInt(rr_qty) == parseInt(po_qty)){
						me.closest('tr').addClass('complete success');
					}else{
						me.closest('tr').removeClass('complete success')
						me.closest('tr').addClass('partial');
					}

					event_app.if_complete();

				})

				$('.rr-qty').trigger('blur');

			},if_complete:function(){					
				if($('#receiving_table tbody tr').length === $('#receiving_table tbody tr.complete').length){
					$('#_status').html('<span class="label label-success">Complete</span>');
					$('#rr_status').val('COMPLETE');
				}else{
					$('#_status').html('<span class="label label-info">Partial</span>');
					$('#rr_status').val('PARTIAL');
				}
			},
			received_event:function(){
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
				};
				event_app.execute_query($post);
			},execute_query:function($post){
					$.post('<?php echo base_url().index_page();?>/transaction/change_status',$post,function(response){
					location.reload('true');
				});
			},save:function(){
					if($('.required').required()){
						alert('Please fill up the Required Fields');
						return false;
					}	

					if($('#checked_by option:selected').val()==" "){
						alert('Please Select CHECKED BY signatory');
						return false;
					}

					if($('#received_by option:selected').val()==" "){
						alert('Please Select RECEIVED BY signatory');
						return false;
					}
						
				    var bool = confirm('Do you want to Proceed?');

				    if(!bool){
				    	return false;
				    }
				   				
				    
					$post = {
						receipt_no      :   $('#rr_no').val(),
						supplier_id     :   $('#supplier_id').val(),
						employee_receiver_id:   $('#received_by option:selected').val(),
						employee_checker_id :   $('#checked_by option:selected').val(),
						delivered_by    :   $('#delivered_by').val(),
						date_received   :   $('#rr_date').val(),
						project_id      :   '<?php echo $this->session->userdata("Proj_Code"); ?>',
						supplier_invoice:   $('#invoice_no').val(),
						dr_no           :   $('.dr_no').val(),
						title_id        :   '<?php echo $this->session->userdata("Proj_Main"); ?>',
						posted_by       :   $('#noted_by').val(),
						invoice_date    :   $('#invoice_date').val(),
						status          :   $('#rr_status').val(),
						details         :   event_app.get_details(),
					};	

					if(xhr && xhr.readystate != 4){
						xhr.abort();
					}

					$.save({appendTo : 'body'});


					xhr = $.post('<?php echo base_url().index_page();?>/procurement/received_purchase/save_receiving_2',$post,function(response){
						 switch($.trim(response)){
						 	case "1":
						 	 alert('Successfully Save');
						 	  $.save({action : 'success',reload : 'false'});	
						 	 updateContent();
						 	 updateStatus($post.status,'label2');
						 	break;
						 	default:
						 		alert('Internal Server Error');
						 	break;
						 }
					});

					
										

			},get_details:function(){
				var item_list = new Array();
				$('#receiving_table tbody tr').each(function(i,val){

					var data = {
						po_id                 : $('#transaction_id').val(),
						po_number             : $('#po_number').val(),
						item_id  			  : $(val).find('.itemNo').text(),
						item_quantity_ordered : remove_comma($(val).find('.qty').text()),
						item_quantity_actual  : remove_comma($(val).find('.rr-qty').val()),
						item_name_ordered  	  : $(val).find('.itemName').text(),
						item_name_actual  	  : $(val).find('.itemName').text(),
						item_cost_ordered  	  : remove_comma($(val).find('.unit_cost').text()),
						item_cost_actual  	  : remove_comma($(val).find('.unit_cost').text()),
						discrepancy  		  : $(val).find('.discrepancy').val(),
						discrepancy_remarks   : '',
						unit_msr	  		  : $(val).find('.unit_msr').text(),
					}

					item_list.push(data);
				});
				return item_list;				
			}

		}
		event_app.init();
	});
</script>						