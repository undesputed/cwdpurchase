<div class="header">
	<div class="container">	
		<div class="row">
			<div class="col-md-8">
					<h2>Received Purchase</h2>					
			</div>
			<div class="col-md-4">					
			</div>
		</div>
	</div>
</div>

<div class="container">

	<input type="hidden" id="supplier_id" value="<?php echo $item[0]['supplierID'] ?>">
	<input type="hidden" id="location"    value="<?php echo $location; ?>">
	<input type="hidden" id="project"     value="<?php echo $project; ?>">

	<div class="content-title">
		<h3>Items To Received</h3>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<table class="table table-striped" id="item_list">
					<thead>
						<tr>							
							<th>PO Number</th>
							<th>Item Name</th>
							<th>Quantity</th>
							<th>Unit Measure</th>
							<th>Unit Cost</th>
							<th>Total Unit Cost</th>
							<th>Received Qty</th>
							<th>Discrepancy</th>
							<th>Discrepancy Remarks</th>							
						</tr>
					</thead>
					<tbody>
						<?php foreach($item as $key => $value): ?>
							<tr>
								
								<td data-name="department" style="display:none"><?php echo $value['department']; ?></td>								
								<td data-name="accountCode" style="display:none"><?php echo $value['accountCode']; ?></td>
								<td data-name="po_id" style="display:none"><?php echo $value['po_id']; ?></td>
								<td data-name="itemNo" style="display:none"><?php echo $value['itemNo']; ?></td>
								<td data-name="po_number"><?php echo $value['po_number'];?></td>
								<td data-name="item_name"><?php echo $value['item_name'];?></td>
								<td data-name="quantity"><?php echo $value['quantity'];?></td>
								<td data-name="unit_msr"><?php echo $value['unit_msr'];?></td>
								<td data-name="unit_cost"><?php echo $value['unit_cost'];?></td>
								<td data-name="total_unitcost"><?php echo $value['total_unitcost'];?></td>
								<td data-name="quantity"><?php echo $value['quantity'];?></td>
								<td data-name="discrepancy" class="editable-td"></td>
								<td data-name="discrepancy_remarks" class="editable-input"></td>
							</tr>
						<?php endforeach; ?>
					</tbody>

				</table>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col-md-12">
		
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
						  			<div class="control-label">Delivered By</div>
						  			<input type="text" name="" id="delivered_by" class="form-control input-sm uppercase">
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
							  			<div class="control-label">Checked By</div>
							  			<select name="" id="checked_by" class="form-control input-sm"></select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
							  			<div class="control-label">Noted By</div>
							  			<select name="" id="noted_by" class="form-control input-sm"></select>
									</div>
								</div>
							</div>
							

							<div class="row">
								<div class="col-md-7">
									<div class="form-group">
							  			<div class="control-label">Received By</div>
							  			<select name="" id="received_by" class="form-control input-sm"></select>
									</div>
								</div>
								<div class="col-md-5">
									<div class="form-group">
							  			<div class="control-label">Received Status</div>
							  			<select name="" id="received_status" class="form-control input-sm">
							  				<option value="Full">Full</option>
							  				<option value="Partial">Partial</option>
							  			</select>
									</div>
								</div>
							</div>

						</div>						
						<div class="col-md-2">
							<div class="form-group">
						  			<div class="control-label">RR Date</div>
						  			<input name="" type="text" id="rr_date" class="form-control input-sm">
							</div>
								
							<div class="form-group">
						  			<div class="control-label">Invoice Date</div>
						  			<input type="text" name="" id="invoice_date" class="form-control input-sm">
							</div>
							
						</div>
						<div class="col-md-2">
							
							<div class="form-group">						  		
						  			<div class="control-label">RR No</div>
						  			<input type="text" name="" id="rr_no" class="form-control input-sm">
							</div>
							<div class="form-group">
						  			<div class="control-label">Invoice No</div>
						  			<input type="text" name="" id="invoice_no" class="form-control input-sm">
							</div>
						</div>

					</div>
					
				</div>	
				<div class="form-footer">
						<div class="row">
						<div class="col-md-8"> </div>
						<div class="col-md-4">
							<input id="save" class="btn btn-success col-xs-5 pull-right" type="submit" value="Save">						
						</div>
						</div>
				</div>
			</div>
		</div>		
	</div>	
</div>

	<select name="" id="discrepancy_remarks" style="display:none">
		<option value=""></option>
		<option value="Damange">Damange</option>
		<option value="Discrepancy">Discrepancy</option>
	</select>


<script>
	var app_received = {
		init:function(){
			$('#rr_date').date({
				url : 'get_rr_code',
				dom : $('#rr_no'),
				event : 'change'
			});
			$('#invoice_date').date();
				
			var profit_center_value = '';
			if(typeof ($('#profit_center option:selected').val()) != 'undefined'){
				 profit_center_value = $('#profit_center option:selected').val();
			}			

			$.signatory({
				noted_by    : ['7','9','1',profit_center_value],
				received_by : ['7','8','1',profit_center_value],
				checked_by  : ['7','2','1',profit_center_value],
			});
			this.bindEvents();
		},bindEvents:function(){			
			$('#save').on('click',this.save);
		},save:function(){

			$.save({appendTo : '.fancybox-outer'});

			$post = {
				receipt_no     :   $('#rr_no').val(),
				supplier_id    :   $('#supplier_id').val(),
				employee_receiver_id    :   $('#received_by option:selected').val(),
				employee_checker_id     :   $('#checked_by option:selected').val(),
				delivered_by    :   $('#delivered_by').val(),
				date_received   :   $('#rr_date').val(),
				project_id      :   $('#project').val(),
				supplier_invoice    :   $('#invoice_no').val(),
				title_id        :   $('#location').val(),
				posted_by       :   $('#noted_by').val(),
				invoice_date    :   $('#invoice_date').val(),
				status          :   $('#received_status option:selected').val(),
				details         :   app_received.get_details(),
			};

			$.post('<?php echo base_url().index_page(); ?>/procurement/received_purchase/save_receiving',$post,function(response){
				if(response == 1){
					$.save({action : 'success',reload : 'true'});
				}else{
					$.save({action : 'success'});
				}
			}).error(function(){
				$.save({action : 'error'});
			});

			
	},get_details:function(){
			var data = new Array;
			$('#item_list tbody tr').each(function(i,val){
				var row = new Object;
				$(val).find('td').each(function(z,value){
					row[$(value).attr('data-name')] = $(value).text();					
				});
				data.push(row);
			});			
			return data;
		}
	};

$(function(){	
	app_received.init();

	$('.editable-td').editable_td({
		insert : 'select',
		clone  : $('#discrepancy_remarks'),
	});
	$('.editable-input').editable_td({
		insert : 'input',
	});

});
</script>