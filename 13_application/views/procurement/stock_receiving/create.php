
<div class="container">
	<div class="header">
		<h2>Stock Receiving</h2>
	</div>
	<div class="content-title">
		<h3>Item Details</h3>
	</div>
	
	<input type="hidden" id="itemType"      value="<?php echo $itemType; ?>">
	<input type="hidden" id="hdn_itemNo"    value="<?php echo $main['group_detail_id']; ?>">
	<input type="hidden" id="hdn_itemCode"  value="<?php echo $itemType; ?>">
	<input type="hidden" id="location"      value="<?php echo $location; ?>">
	<input type="hidden" id="project"       value="<?php echo $project; ?>">
	<input type="hidden" id="unit_msr"      value="<?php echo $main['unit_measure']; ?>">
	
	<div class="row">
		<div class="col-md-6">

			 <div class="panel panel-default">		
			   <div class="panel-body">
			   		<div class="row">
			   			
			   			<div class="col-md-8">
			   				<div class="form-group">
					   			<div class="control-label">Reference(PO#)</div>
					   			<input type="text" id="reference" value="" class="form-control input-sm">
					   		</div>
			   			</div>
			   			<div class="col-md-4">
			   				<div class="form-group">
					   			<div class="control-label">Date of Purchase</div>
					   			<input type="text" value="" id="date" class="form-control input-sm" readonly>
					   		</div>
			   			</div>	
			   		</div>
					
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
							   	<div class="control-label">Item Name</div>
							   	<input id="item_name" type="text" value="<?php echo $main['description'] ?>" class="form-control input-sm" readonly>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">Item Qty</div>
							   	<input id="item_qty" type="text" value="<?php echo $main['quantity']; ?>" class="form-control input-sm" readonly>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
							   	<div class="control-label">Supplier Name</div>
							   	<select id="supplier" type="text" value="" class="form-control input-sm">
							   		<?php foreach($supplier as $row): ?>
							   			<option value="<?php echo $row['business_number'] ?>"><?php echo $row['business_name']; ?></option>
							   		<?php endforeach; ?>
							   	</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">Tag/Plate No</div>
							   	<input type="text" value="" class="form-control input-sm">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
							   	<div class="control-label">Remarks</div>
							   	<textarea name="" id="remarks" cols="30" rows="4" class="form-control input-sm"></textarea>							   
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							   	<div class="control-label">Unit Cost</div>
							   	<input type="text" id="unit_cost" value="<?php echo $main['unit_cost']; ?>" class="form-control input-sm" readonly>
							</div>
							<div class="form-group">
							<div class="control-label">Operator Name</div>

							   	<select id="operator" type="text" value="" class="form-control input-sm">
							   		<?php foreach($operator as $row): ?>
							   			<option value="<?php echo $row['Person Code'] ?>"><?php echo $row['Full Name']; ?></option>
							   		<?php endforeach; ?>
							   	</select>

							</div>
						</div>
					</div>
			   		
					
								   	
			   </div>	 
			 </div>
		</div>

		<div class="col-md-6">
			<div class="panel panel-default">		
			  <div class="panel-body">
			  		<div class="row">

						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">Model</div>
							   		<select type="text" id="model" class="form-control input-sm">
							   		<?php foreach($model as $row): ?>
							   			<option value="<?php echo $row['pm_model_id'] ?>"><?php echo $row['code']; ?></option>
							   		<?php endforeach; ?>
							   	</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">Type</div>
							   	<select type="text" id="type" class="form-control input-sm">
							   		<?php foreach($type as $row): ?>
							   			<option value="<?php echo $row['id'] ?>"><?php echo $row['equipmenttype']; ?></option>
							   		<?php endforeach; ?>
							   	</select>
							</div>
						</div>	

						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">Body No</div>
							   	<input type="text" value="" class="form-control input-sm required">
							</div>
						</div>
					
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">Made</div>
							   	<input type="text" value="" class="form-control input-sm required">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">Engine No</div>
							   	<input type="text" value="" class="form-control input-sm">
							</div>
						</div>	

						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">Fuel</div>
							   	<input type="text" value="" class="form-control input-sm">
							</div>
						</div>					
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">Chassis No</div>
							   	<input type="text" value="" class="form-control input-sm required">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">Prog Hrs</div>
							   	<input type="text" value="" class="form-control input-sm numbers_only" placeholder="0">
							</div>
						</div>	

						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">Est Life</div>
							   	<input type="text" value="" class="form-control input-sm numbers_only" placeholder="0">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">Factor/Depth</div>
							   	<input type="text" value="" class="form-control input-sm numbers_only" placeholder="0">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">Year</div>
							   	<input type="text" value="" class="form-control input-sm required numbers_only" placeholder="0">
							</div>
						</div>	

						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">Capacity/Size</div>
							   	<input type="text" value="" class="form-control input-sm numbers_only" placeholder="0">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
							   	<div class="control-label">SMR</div>
							   	<input type="text" value="" class="form-control input-sm numbers_only" placeholder="0">
							</div>
						</div>
					
					</div>

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

<script>
	var app_create = {
		init:function(){
			$('#date').date();		
			this.bindEvent();	

		},bindEvent:function(){
			$('#save').on('click',this.save);
		},save:function(){

			if($('.required').required()){
				return false;
			}

			$.save({appendTo : '.fancybox-outer'});


			$post = {
				itemType            :  $('#itemType').val(),
				item_no             :  $('#hdn_itemNo').val(),
				item_description    :  $('#item_name').val(),
				item_cost           :  $('#unit_cost').val(),
				item_measurement    :  $('#hdn_measurement').val(),
				supplier_id         :  $('#supplier option:selected').val(),
				received_quantity   :  $('#item_qty').val(),
				withdrawn_quantity  :  0 ,
				receipt_no          :  0 ,
				withdraw_no         :  0 ,
				registered_no       :  1 ,
				division_code       :  0 ,
				account_code        :  0 ,
				location            :  $('#location').val(),
				project             :  $('#project').val(),
				item_code           :  $('#hdn_itemCode').val(),
				ref_po              :  $('#reference').val(),
				remarks             :  $('#remarks').val(),
				unit_msr            :  $('#unit_msr').val(),							
				equipment_description  : $('#item_name').val(),
				equipment_type         : $('#type option:selected').val(),
				equipment_typecode     : $('#type option:selected').text(),
				equipment_fueltype     : $('#fuel option:selected').text(),
				equipment_fueltypecode : $('#fuel option:selected').val(),
				equipment_platepropertyno : $('#plate_no').val(),
				equipment_chassisno       : $('#chassisNo').val(),
				equipment_engineno        : $('#engine').val(),
				equipment_driver          : $('#operator option:selected').text(),
				equipment_drivercode      : $('#operator option:selected').val(),
				equipment_cost            : $('#unit_cost').val(),
				equipment_life            : $('#life').val(),
				equipment_purchase        : $('#date').val(),
				equipment_remarks         : $('#remarks').val(),
				equipment_fulltank        : $('#size').val(),
				equipment_accountcode     : $('#accountCode').val(),
				equipment_itemno          : $('#hdn_itemNo').val(),				
				equipment_model           : $('#model').val(),				
				referrence_no             : $('#reference').val(),
				equipment_brand           : $('#body').val(),
				made_in                   : $('#made').val(),												
				equipment_smr             : $('#smr').val(),
				year_model                : $('#year').val(),
				program_hrs               : $('#proghrs').val(),
				equipment_factor          : $('#factor').val(),
				smr_balance               : $('#smr').val(),
			};

			$.post('<?php echo base_url().index_page();?>/procurement/stock_receiving/save',$post,function(response){
					switch(response){
						case "1": 
						$.save({action :'success',reload : "true"});
						break;
						default:
						$.save({action :'hide',reload : "false"});
						break;
					}

			}).error(function(){
				alert('Failed to Save...');
				$.save({action :'hide',reload : "false"});
			});

		}


	};

$(function(){
	app_create.init();
});
</script>


