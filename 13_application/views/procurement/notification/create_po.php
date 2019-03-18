
<input type="hidden" value="" class="date" id="po_date">
<input type="hidden" value="" class="" id="po_no">
<input type="hidden" value="<?php echo $canvass_main['pr_id']; ?>" id="pr_id">
<input type="hidden" value="<?php echo $canvass_main['can_id'] ?>" id="can_id">

<input type="hidden" value="<?php echo $supplier_info['supplier_id'] ?>" id="supplier_id">
<input type="hidden" value="BUSINESS" id="supplier_type">



<div class="content-title">
	<h3>Create P.O</h3>	
</div>

<div class="row">
	<div class="col-md-12">
		<div class="t-content">			
			<div class="row">
				<div class="col-md-2">
					<div class="panel panel-default">
						<div class="panel-heading">
							Canvass Supplier
						</div>
						<table class="table">
							<tbody>
								<?php foreach($supplier as $row): ?>
								<?php 
									$supplier_id = $this->uri->segment(4);
									$active = ($row['supplier_id'] == $supplier_id )? 'act': '';
								 ?>
											<tr class="<?php echo $active; ?>">
												<td>
													<?php if(!empty($row['po_id'])): ?>
														<label  class="label label-success">Already PO</label>
													<?php endif; ?>
													<a href="<?php echo base_url().index_page(); ?>/transaction/create_po/<?php echo $canvass_main['c_number']; ?>/<?php echo $row['supplier_id']; ?>"><?php echo $row['Supplier'] ?></a></td>
											</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-10">
					
					<div class="t-header">
						<a href="<?php echo base_url().index_page(); ?>/transaction_list/purchase_order/for_po" class="close"><span aria-hidden="true">&times;</span><span></a>
						<!-- <h4 id="po-no-title">-</h4> -->
					</div>

					<div class="row">
						<div class="col-md-4">
							
							 <div class="t-title">
							 	<div>Reference No : </div>
							 	<input type="text" class="form-control required" id="reference_no" placeholder="" value="<?php echo $pr_main['from_projectNo'].'-'.$reference_no; ?>">
							 </div>
							
							 <div class="t-title">
							 	<div>Date of Delivery : </div>
							 	<input type="text" class="form-control required" id="date_of_delivery">
							 </div>

							 <div class="t-title">
							 	<div>Place of Delivery : </div>
							 	<input type="text" class="form-control required uppercase" id="place_delivery" >
							 </div>
								
							  <div class="t-title">
							 	<div>Delivery Terms : </div>
							 	<div class="row">
							 		<div class="col-md-6">
							 			<select name="" class="form-control" id="terms_payment">
							 				<option value="COD">COD</option>
							 				<option value="In Days">In Days</option>
							 			</select>
							 		</div>
							 		<div class="col-md-6">
							 			<input type="number" id="in_days"  value="1" class="form-control">
							 		</div>
							 	</div>					 	
							 </div>
							 <div class="t-title">
							 	<div>Remarks : </div>
							 	<input type="text" class="form-control required uppercase" id="remarks">		 	
							 </div>							
						</div>
						<div class="col-md-3">
							<div class="control-group">						
								<div class="t-title">
									<div>Supplier :</div>
									<strong><?php echo $supplier_info['business_name']; ?></strong>
								</div>
								<div class="t-title">
									<div>Address :</div>
									<strong><?php echo $supplier_info['address']; ?></strong>
								</div>
								<div class="t-title">
									<div>Contact No :</div>
									<strong><?php echo $supplier_info['contact_no']; ?></strong>
								</div>					
							</div>
						</div>
						<div class="col-md-2">
							
							<div class="t-title">
								<div>Canvass No :</div>
								<strong><?php echo $canvass_main['c_number']; ?></strong>
							</div>
							<div class="t-title">
								<div>Canvass Date :</div>
								<strong><?php echo $this->extra->format_date($canvass_main['c_date']); ?></strong>
							</div>					
						</div>
						<div class="col-md-2">
							<div class="t-title">
								<div>P.R No :</div>
								<strong><?php echo $pr_main['purchaseNo']; ?></strong>
							</div>
							<div class="t-title">
								<div>P.R Date :</div>
								<strong><?php echo $this->extra->format_date($pr_main['purchaseDate']); ?></strong>
							</div>
							<div class="t-title">
								<div>Created From :</div>
								<strong><?php echo $pr_main['from_projectCodeName']; ?></strong>								
							</div>							
						</div>					
					</div>
					<div class="table-responsive">				
					<table id="tbl_pr_details" class="table table-item">
						<thead>
							<tr>
								<th style="width:40px">Change Order?</th>
								<th>Item Name</th>
								<th>Unit</th>
								<th class="td-number td-qty">Qty</th>
								<th class="td-number td-qty">Unit Price</th>
								<th class="td-number">Total</th>
								<th>Project type</th>
								<th>PR Remarks</th>
								<th>PO Remarks</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$grand_total = 0;
								if(count($canvass_details) > 0):
								foreach($canvass_details as $row): 
							?>
								<tr>
									<td><input type="checkbox" class="change_order"></td>
									<td class="item-desc"><?php echo $row['itemName']; ?></td>
									<td class="item-no" style="display:none"><?php echo $row['itemNo']; ?></td>
									<td class="unit"><?php echo $row['unit_measure']; ?></td>
									
									<td class="td-number td-qty "><span class="editable qty"><?php echo $this->extra->comma($row['pr_qty']); ?></span></td>
									<td class="td-number unit-price"><?php echo $this->extra->number_format($row['discounted_price']); ?></td>
									<!-- <td class="td-number td-qty precentage"><?php echo $row['percentage']; ?></td> -->
									<!-- <td class="td-number td-qty discounted_price"><?php echo $row['discounted_price']; ?></td> -->
									<?php
										$total = $row['pr_qty'] * $row['discounted_price'];
										$grand_total += $total;
									?>
									<td class="td-number total"><?php echo $this->extra->number_format($total); ?></td>
									<td class="for_usage"><?php echo $row['for_usage']; ?></td>
									<td><?php echo $row['remarkz']; ?></td>
									<td class="remarks editable_remarks"><?php echo (empty($row['c_remarks']))? '-': $row['c_remarks']; ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
								<tr>
									<td colspan="7">Empty</td>
								</tr>
						<?php endif; ?>
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td><?php echo count($canvass_details); ?> item(s)</td>
								<td></td>
								<td></td>	
								<td></td>
								<td class="td-number td-qty">Total : <span id="grand_total"><strong><?php echo $this->extra->number_format($grand_total);  ?></strong></span></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tfoot>
					</table>
					</div>
					

					<div class="row">
						<div class="col-md-3">
							<div class="t-title">
								<div>Requested by : </div>
								<select name="" id="prepared_by" class="form-control"></select>
							</div>
							<!--<div class="t-title">
								<div>Checked by : </div>
								<select name="" id="checked_by" class="form-control"></select>
							</div>-->					
						</div>

						<div class="col-md-3">
							<div class="t-title">
								<div>Approved By : </div>
								<select name="" id="approved_by" class="form-control"></select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<button id="save" class="btn btn-primary pull-right">Save</button>
						</div>
					</div>

				</div>
			</div>
		</div>		
	</div>
</div>

<script>
		var xhr = "";
		var po = {
			init:function(){

				$('#date_of_delivery').date();
				$('#po_date').date({
					url   : 'get_po_code',
					dom   : $('#po_no'),
					div   : $('#po-no-title'),
					event : 'change',
				});

				var cmbproject = '<?php echo $this->session->userdata("Proj_Code"); ?>';

				$.signatory({
					type           : 'po',
					approved_by    : ["6","4","1",cmbproject],
					checked_by     : ["6","5","1",cmbproject],
				});

				$(".editable").editable("<?php echo base_url().index_page(); ?>/ajax/display",{ 					      
					      tooltip   : "Click to edit...",
					      width     : $('.editable').width() + 50,
					      callback  : function(value, settings){							
							var unit = remove_comma($(this).closest('tr').find('.unit-price').text());
							var total = parseFloat(value) * parseFloat(unit);
							$(this).closest('tr').find('.total').html(comma(total.toFixed(2)));
							var grand_total = 0;
							$('#tbl_pr_details tbody tr').each(function(i,val){
								var total = remove_comma($(val).find('.total').text());								
								grand_total +=parseFloat(total);
							});
							$('#grand_total').html(comma(grand_total.toFixed(2)));
					      }
				});

				$(".editable_remarks").editable("<?php echo base_url().index_page(); ?>/ajax/display",{ 					      
					      tooltip   : "Click to edit...",
					      width     : $('.editable_remarks').width() + 50,					  
				});

				this.bindEvent();

			},
			bindEvent:function(){
				$('#supplier').on('change',this.po_items);
				$('#supplier').trigger('change');
				$('#save').on('click',this.save);
			},
			po_items:function(){
				$post = {
					can_id : $('#supplier option:selected').attr('data-can_id'),
					supplier_id : $('#supplier option:selected').val(),
				}
				$.post('<?php echo base_url().index_page();?>/transaction/get_po_items',$post,function(response){
					$('#po_items').html(response);
				});
			},save:function(){

				if($('.required').required()){
					return false;
				}
								
				var bool = confirm('Are you sure?');

				if(!bool){
					return false;
				}

				var details = new Array();
				var details_main = new Array();
				$('#tbl_pr_details tbody tr').each(function(i,val){

					details = {
						itemNo   : $(val).find('.item-no').text(),
						itemDesc : $(val).find('.item-desc').text(),
						unit     : $(val).find('.unit').text(),
						qty      : remove_comma($(val).find('.qty').text()),
						unit_price : remove_comma($(val).find('.unit-price').text()),
						total    : remove_comma(($(val).find('.total').text())),
						remarks  : $(val).find('.remarks').text(),
						change_order : $(val).find('.change_order').is(':checked'),
						for_usage : $(val).find('.for_usage').text(),
					}					
					details_main.push(details);
					details = [];

				});
				
				$post = {
						can_id		  : $('#can_id').val(),
						po_number     : $('#po_no').val(),
						po_date       : $('#po_date').val(),
						reference_no  : $('#reference_no').val(),
						pr_id         : $('#pr_id').val(),
						supplierID    : $('#supplier_id').val(),
						supplierType  : $('#supplier_type').val(),
						placeDelivery : $('#place_delivery').val(),
						deliverTerm   : $('#delivery_term').val(),
						paymentTerm   : $('#terms_payment option:selected').val(),
						indays        : $('#in_days').val(),
						dtDelivery    : $('#date_of_delivery').val(),
						approvedBy    : $('#approved_by').val(),
						preparedBy    : $('#prepared_by').val(),					
						recommendedBy : $('#checked_by').val(),
						title_id      : '<?php echo $this->session->userdata("Proj_Main"); ?>',
						project_id    : '<?php echo $this->session->userdata("Proj_Code"); ?>',
						po_remarks    : $('#remarks').val(),
						from_id       : '<?php echo $pr_main["from_projectCode"] ?>',
						from_name     : '<?php echo $pr_main["from_projectCodeName"] ?>',
						data          : details_main,
				}
				
				$.save({appendTo : 'body'});

		        if(xhr && xhr.readystate != 4){
		            xhr.abort();		            
		        }
								
				xhr = $.post('<?php echo base_url().index_page();?>/procurement/purchase_order/save_purchaseOrder',$post,function(response){
					switch($.trim(response)){
						case "1":
							alert('Successfully Save');
							$.save({action : 'success',reload : 'true'});												
						break;
						case "2":							
							alert('Already Save!');
							$.save({action : 'error',reload : 'true'});							
						break;
						default:
							alert(response);
							$.save({action : 'error',reload : 'true'});		
						break;
					}
				});
				
				

			}
		}

	$(function(){
		po.init();
	});
</script>