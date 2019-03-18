<div class="header">
	<h2>Received Transfer</h2>
</div>

<div class="container">

	<input type="hidden" id="supplier_id" value="<?php echo $main['Supplier ID']; ?>">
	<input type="hidden" id="to_location" value="<?php echo $main['to_location']; ?>">
	<input type="hidden" id="project"     value="<?php echo $location_info['project']; ?>">
	<input type="hidden" id="location"    value="<?php echo $location_info['location']; ?>">
	<input type="hidden" id="division"    value="<?php echo $main['division']; ?>">
	<input type="hidden" id="dispatch_id"    value="<?php echo $main['dispatch_id']; ?>">

	<div class="content-title">
		<h3>Received Transfer</h3>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<table class="table table-striped" id="item_list">
				
					<thead>
						<tr>							
							<th>Item No</th>
							<th>Item Description</th>
							<th>Item Cost</th>
							<th>Unit Measure</th>
							<th>Received Qty</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($item as $key => $value): ?>
							<tr>
								
								<td data-name="item_no"><?php echo $value['item_no']; ?></td>
								<td data-name="item_description" ><?php echo $value['Item Description']; ?></td>
								<td data-name="item_cost" ><?php echo $value['Item Cost']; ?></td>
								<td data-name="item_measurement" ><?php echo $value['Item Measurement']; ?></td>
								<td data-name="supplier_id" style="display:none;"><?php echo $value['Supplier ID']; ?></td>
								<td data-name="quantity" ><?php echo $value['quantity']; ?></td>
								<td data-name="dispatch_no" style="display:none;"><?php echo $value['dispatch_no']; ?></td>
								<td data-name="division" style="display:none;"><?php echo $value['division']; ?></td>
								<td data-name="account" style="display:none;"><?php echo $value['account']; ?></td>
								<td data-name="to_location" style="display:none;"><?php echo $value['to_location']; ?></td>
								<td data-name="discrepancy" style="display:none;">0</td>
								<td data-name="equipment_request_id" style="display:none;"><?php echo $value['equipment_request_id']; ?></td>
								<td data-name="equipment_id" style="display:none;"><?php echo $value['equipment_id']; ?></td>
								<td data-name="quantity" style="display:none;"><?php echo $value['quantity']; ?></td>
								<td data-name="dispatch_id" style="display:none;"><?php echo $value['dispatch_id']; ?></td>

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

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
							  			<div class="control-label">Delivered By</div>
							  			<input type="text" name="" id="delivered_by" class="form-control input-sm uppercase">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
							  			<div class="control-label">Location From</div>
							  			<input type="text" name="" id="location_from" class="form-control input-sm uppercase" value="<?php echo $main['LOCATION FROM']; ?>">
									</div>
								</div>								
							</div>
														
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
							  			<div class="control-label">Checked By</div>
							  			<select name="" id="checked_by" class="form-control input-sm">
							  				<?php foreach($names as $row): ?>
							  					<option value="<?php echo $row['Person Code']; ?>"><?php echo $row['Full Name']; ?></option>
							  				<?php endforeach; ?>
							  			</select>
							  			

									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
							  			<div class="control-label">Received By</div>
							  			<select name="" id="received_by" class="form-control input-sm">
							  				<?php foreach($names as $row): ?>
							  					<option value="<?php echo $row['Person Code']; ?>"><?php echo $row['Full Name']; ?></option>
							  				<?php endforeach; ?>
							  			</select>
									</div>
								</div>
							</div>			
						</div>						
						<div class="col-md-2">
							<div class="form-group">
						  			<div class="control-label">RRP Date</div>
						  			<input name="" type="text" id="rr_date" class="form-control input-sm">
							</div>
								
							<div class="form-group">
						  			<div class="control-label">DS No</div>
						  			<input type="text" name="" id="ds_no" class="form-control input-sm" value="<?php echo $main['dispatch_no']; ?>">
							</div>
							
						</div>
						<div class="col-md-2">
							
							<div class="form-group">						  		
						  			<div class="control-label">RRP No</div>
						  			<input type="text" name="" id="rr_no" class="form-control input-sm">
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
				url : 'get_receivedTransfer',
				dom : $('#rr_no'),
				event : 'change'
			});
			
			
			this.bindEvents();
		},bindEvents:function(){			
			$('#save').on('click',this.save);
		},save:function(){
			$.save({appendTo : '.fancybox-outer'});

			$post = {
				dispatch_id	         : $('#dispatch_id').val(),
				receipt_no           : $('#rr_no').val(),
				supplier_id          : $('#supplier_id').val(),
				employee_receiver_id : $('#received_by option:selected').val(),
				employee_checker_id  : $('#checked_by option:selected').val(),
				delivered_by         : $('#delivered_by').val(),
				date_received        : $('#rr_date').val(),
				project_id           : $('#project').val(),
				title_id             : $('#location').val(),				
				location_from        : $('#to_location').val(),
				details              : app_received.get_details(),
				division             : $('#division').val(),
			}
	
			
			$.post('<?php echo base_url().index_page(); ?>/procurement/received_transfer/save',$post,function(response){
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