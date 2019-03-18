<script>
	$(function(){
		event_app.init();
	});
</script>

<input type="hidden" value="<?php echo $main_data['po_id'] ?>" id="transaction_id">		
<input type="hidden" value="<?php echo $main_data['po_number'] ?>" id="po_number">		
<input type="hidden" value="" id="rr_no">
<input type="hidden" value="<?php echo $supplier['supplier_id']; ?>" id="supplier_id">

						<div class="t-content">
							<div class="t-header">
								<!--<a href="<?php echo base_url().index_page();?>/transaction_list/receiving_report" class="close close-info" data-dismiss="modal"><span aria-hidden="true">&times;</span><span></a>-->
								<h4 id="rr-no-title"><?php echo $rr_main['receipt_no']; ?></h4>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<div class="control-group">
										<span class="control-item-group">
											<a href="<?php echo base_url().index_page();?>/print/rr/<?php echo $rr_main['receipt_no']; ?>" target="_blank" class="action-status"><i class="fa fa-print"></i> Print</a>	
										</span>
										<span class="control-item-group">
											<a class="action-status history-link" href="<?php echo base_url().index_page();?>/transaction_list/receiving_report/" data-title="<?php echo $rr_main['receipt_no']; ?>" data-value2="<?php echo $main_data['po_number'] ?>" data-value="<?php echo $rr_main['receipt_id'] ?>" data-type="edit" data-method="receiving" data-form="dialog">Edit</a>
										</span>
										<span class="control-item-group">
											<a class="action-status cancel-event" id="cancel-event" href="javascript:void(0)" data-value="<?php echo $rr_main['receipt_id'] ?>" data-type="" data-method="receiving">Cancel</a>
										</span>
									</div>
								</div>
								<div class="col-xs-6">
									
								</div>
							</div>
							
							<div class="row" style="margin-top:10px">
								<div class="col-xs-4">
									<div class="t-title">
										<div>RR Date : </div>
										<strong><?php echo $rr_main['date_received']; ?></strong>
									</div>
									<div class="t-title">
										<div>DR No : </div>
										<strong><?php echo $rr_main['supplier_invoice']; ?></strong>
									</div>
									<div class="t-title">
										<div>DR Date : </div>
										<strong><?php echo $rr_main['invoice_date']; ?></strong>
									</div>
									<div class="t-title">
										<div>Status : </div>
										<strong>
											<?php 
												switch($rr_main['received_status'])
												{
													case "COMPLETE":
													?>
													<span class="label label-success">COMPLETE</span>
													<?php 
													break;

													case "PARTIAL":
													?>
													<span class="label label-info">PARTIAL</span>
													<?php
													break;
												}
											?>
										</strong>
									</div>
								</div>
								<div class="col-xs-4">
									
									<div class="t-title">
										<div>P.O No : </div> 
										<strong>PO <?php echo $main_data['reference_no']; ?></strong>										
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
														
							<table id="receiving_table" class="table table-item">
								<thead>

									<tr>
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
										foreach ($rr_details as $row): 
											$complete = '';
											if($row['item_quantity_ordered'] == $row['item_quantity_actual'])
											{
												$complete = 'success';
											}else{												
											}										
										?>
										<tr class="<?php echo $complete; ?>">											
											<td class="itemNo" style="display:none"><?php echo $row['item_id']; ?></td>
											<td class="itemName"><?php echo $row['item_name_ordered'];?></td>											
											<td class="unit_msr"><?php echo $row['unit_msr'];?></td>
											<td class="qty td-number td-qty"><?php echo $row['item_quantity_ordered']; ?></td>
											<td class="td-qty td-number"><?php echo $row['item_quantity_actual']; ?></td>
											<?php $total = $row['item_quantity_actual'] * $row['item_cost_ordered']; ?>
											<?php $grand_total += $total;?>
											<td class="td-number unit_cost"><?php echo $this->extra->number_format($row['item_cost_ordered']);?></td>
											<td><?php echo $row['discrepancy']; ?></td>											
										</tr>
									<?php endforeach ?>

								</tbody>
								<tfoot>
									<tr>
										<td><?php echo count($rr_details); ?> item(s)</td>
										<td></td>
										<td></td>
										<td colspan="2">
											<div class="pull-right">
											Total : <span><?php echo $this->extra->number_format($grand_total) ?></span>
											</div>
										</td>
										<td></td>
									</tr>
								</tfoot>
							</table>
							<div class="row">
								<div class="col-xs-12">
									<div>
										<div class="t-title">
										<div>Delivered By :</div>
										<strong><?php echo $rr_main['delivered_by']; ?></strong>
										<!-- <input type="text" id="delivered_by" class="form-control required uppercase"> -->
										</div>										
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<div class="t-title">
										<div>Checked By : </div> 
										<strong><?php echo $rr_main['rr_checked_by']; ?></strong>
									<!-- 	<select name="" id="checked_by" class="form-control">
										<?php foreach($signatory as $row): ?>
											<option value="<?php echo $row['emp_number']; ?>"><?php echo $row['person_name']; ?></option>
										<?php endforeach; ?>
									</select> -->
									</div>
										<div class="t-title">
										<div>Noted By : </div> 
										<strong><?php echo $rr_main['rr_posted_by']; ?></strong>
										<!-- <select name="" id="noted_by" class="form-control">
											<?php foreach($signatory as $row): ?>
												<option value="<?php echo $row['emp_number']; ?>"><?php echo $row['person_name']; ?></option>
											<?php endforeach; ?>
										</select> -->
									</div>									
								</div>
								<div class="col-xs-6">
									<div class="t-title">
										<div>Received By : </div> 
										<strong><?php echo $rr_main['rr_received_by']; ?></strong>
										<!-- <select name="" id="received_by" class="form-control">
											<?php foreach($signatory as $row): ?>
												<option value="<?php echo $row['emp_number']; ?>"><?php echo $row['person_name']; ?></option>
											<?php endforeach; ?>
										</select> -->
									</div>
								</div>
							</div>
						<!-- 	
										<div class="row">								
											<div class="col-xs-12">
												<button class="pull-right btn btn-primary" id="save">Save</button>
											</div>
										</div>
													 -->				
						</div>


