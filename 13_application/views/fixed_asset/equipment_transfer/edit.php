<style>
	.myTable tbody tr:hover{
		cursor:pointer;
		text-decoration: underline;
	}
	.item-table{
		table-layout:fixed
	}

	.item-table td{
		white-space: nowrap;
		overflow: hidden;
		text-overflow:ellipsis;
	}

	.equipment_table tr:hover{
		cursor: pointer;
	}
	
	.equipment_table td{
		white-space: nowrap;
		overflow: hidden;
		text-overflow:ellipsis;
	}

	.equipment_table tbody tr:hover > td .event {		
		visibility: visible !important;	
	}

	.equipment_table tbody tr .event{
		visibility: hidden;
	}
	
</style>

<div class="header">
	<h2>Equipment Transfer <small>Edit</small></h2>	
</div>


<input  type="hidden"  value="<?php echo $main['from_location']; ?>" id="location">
<input  type="hidden"  value="<?php echo $main['title_id']; ?>" id="project">
<input  type="hidden"  value="" id="inventory_id">
<input  type="hidden"  value="" id="mr_code">
<input  type="hidden"  value="" id="hdn_date">
<input  type="hidden"  value="<?php echo $main['equipment_request_id']; ?>" id="equipment_request_id">
<input  type="hidden"  value="<?php echo $db_equipment['equipment_id']; ?>" id="equipment_id">
<input  type="hidden"  value="<?php echo $db_equipment['equipment_itemno']; ?>" id="item_no">
<input  type="hidden"  value="<?php echo $db_equipment['inventory_id']; ?>" id="inventory_id">


<div class="container">	
	<div class="row" id='items_content' style="display:none">
		<div class="col-md-12">
			<div class="content-title">
				<h3>Item List</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">			  		
			  </div>	
			  <div class="equipment_content">
				  <table class="table table-striped">
				  	<thead>
				  		<tr>
				  			<th>Equipment Description</th>
							<th>Equipment Status</th>
							<th>Equipment Plate Number</th>
							<th>Equipment Driver</th>
							<th>Referrence No</th>
							<th>Action</th>
				  		</tr>
				  	</thead>			  	
				  </table>
				</div> 
			</div>
		
		</div>
	</div>
	<div class="row" id="form_content">
		<div class="col-md-12">
			<div class="content-title">
				<h3>Equipment Transfer Form</h3>
			</div>			
			<table class="table table-striped">
				<tr>
					<td>Equipment Name</td>
					<td><input type="text" id="txtequipmentname" value="<?php echo $db_equipment['equipment_description']; ?>" class="form-control input-sm" readonly></td>
					<td>Plate No/Prop No</td>
					<td><input type="text" id="txtpropertyno" value="<?php echo $db_equipment['equipment_platepropertyno']; ?>" class="input-sm form-control" readonly></td>
				</tr>

				<tr>
					<td>Chassis No</td>
					<td><input type="text" id="txtchassisno" value="<?php echo $db_equipment['equipment_chassisno']; ?>" class="input-sm form-control" readonly></td>					
					<td>Engine / Serial No</td>
					<td><input type="text" id="txtengineno" value="<?php echo $db_equipment['equipment_engineno']; ?>" class="input-sm form-control" readonly></td>
				</tr>

				<tr>
					<td>Brand Name</td>
					<td><input type="text" id="txtBrand" value="<?php echo $db_equipment['equipment_brand']; ?>" class="input-sm form-control" readonly></td>					
					<td>Full Tank(<small>liters</small>)</td>
					<td><input type="text" id="txtfulltank" value="<?php echo $db_equipment['equipment_fulltank']; ?>" class="input-sm form-control numbers_only" readonly></td>
				</tr>

				<tr>
					<td>Model</td>
					<td><input type="text" id="txtModel" value="<?php echo $db_equipment['equipment_model']; ?>" class="input-sm form-control" readonly></td>
					<td>Purpose</td>
					<td><input type="text" id="txtremarks" value="<?php echo $main['remarks'] ?>" class="input-sm form-control"></td>
				</tr>
			</table>

			<div class="content-title">
				<h3>Transfer Information</h3>
			</div>
			<table class="table table-striped">
				<tr>
					<td>Ref Number</td>
					<td><input type="text" id="ref_no" value="<?php echo $main['equipment_request_no']; ?>" class="input-sm form-control"></td>
					<td>Ref Date</td>
					<td><input type="text" id="ref_date" value="<?php echo $main['date_requested']; ?>" class="input-sm form-control"></td>
					<td>MR Number</td>
					<td><input type="text" id="mr_no" value="<?php echo $main['MR_ID']; ?>" class="input-sm form-control"></td>
				</tr>

				<tr>
					<td>To Project Name</td>
					<td colspan="2"><select  id="to_project" value="" class="input-sm form-control"></select></td>					
					<td>To Profit Center</td>
					<td colspan="2"><select  id="to_profit_center" value="" class="input-sm form-control"></select></td>
				</tr>

				<tr>
					<td>Issued To</td>
					<td><select id="issue_to" class="input-sm form-control">
						<?php  foreach($signatory as $row): ?>
						<?php $selected = ($row['Person Code']==$main['to_receiver'])? 'selected="selected"' : '' ; ?>
						<option <?php echo $selected; ?> value="<?php echo $row['Person Code'] ?>"><?php echo $row['Full Name']; ?></option>
						<?php  endforeach;?>
					</select></td>
					<td>Requested By</td>
					<td><select id="requested_by" class="input-sm form-control">
						<?php  foreach($signatory as $row): ?>
						<?php $selected = ($row['Person Code']==$main['requested_by'])? 'selected="selected"' : '' ; ?>
						<option <?php echo $selected; ?> value="<?php echo $row['Person Code'] ?>"><?php echo $row['Full Name']; ?></option>
						<?php  endforeach;?>
					</select></td>
					<td>Recommeded By</td>
					<td><select id="recommended_by" class="input-sm form-control"></select></td>
				</tr>
				
				<tr>
					<td>Approved By</td>
					<td><select id="approved_by" class="input-sm form-control"></select></td>
					<td colspan="4"></td>
					
				</tr>
					<td colspan='6'>
						<div class="row">
							<div class="col-md-9"></div>
							<div class="col-md-1"></div>
							<!-- <div class="col-md-1"><button class="btn btn-block btn-link btn-sm " id="reset">Reset</button></div> -->
							<div class="col-md-2"><button class="btn btn-block btn-success btn-sm " id="save">Update</button></div>
						</div>						
					</td>					
				</tr>
			</table>

		</div>
	</div>	
</div>

<script type="text/javascript">
	var obj = new Object();
	var app_create = {
		init:function(){

			$('#ref_date').date();
						
			$('.date').date();

			var option = {
				profit_center : $('#to_profit_center')
			}			
			$('#to_project').get_projects(option);

			this.bindEvents();
			this.get_equipment();

			$.signatory({
				recommended_by : ['8','5','1',$('#location').val()],
				approved_by    : ['8','4','1',$('#location').val()],
				recommended_by_id : '<?php echo $main["recommend_by"] ?>',
				approved_by_id : '<?php echo $main["approver_id"] ?>',
			});


		},bindEvents:function(){			
			$('body').on('click','.event',this.selected);			
			$('body').on('click','.event,#reset',this.panel);
			$('#save').on('click',this.save);

		},get_equipment:function(){

			$('.equipment_content').content_loader('true');
			$post = {
				location : $('#location').val(),
			};

			$.post('<?php echo base_url().index_page();?>/fixed_asset/equipment_transfer/get_equipment',$post,function(response){
				 $('.equipment_content').html(response);
				 $('.equipment_table').dataTable(datatable_option);
			});

		},selected:function(){

			//$(this).addClass('selected');
			var tr = $(this).closest('tr');

			obj = {
				item_no            : tr.find('td.equipment_itemno').text(),
				txtequipmentname   : tr.find('td.equipment_description').text(),
				qty_on_hand        : tr.find('td.Quantity_on_Hand').text(),
				division_code      : tr.find('td.Division_Code').text(),
				txtreferenceno     : tr.find('td.Reference_No').text(),
				txtunitcost        : tr.find('td.Equipment_Cost').text(),
				inventory_id       : tr.find('td.inventory_id').text(),
				cmbdivision        : tr.find('td.Division_Code').text(),
				cmbdivision_option : tr.find('td.Division_Name').text(),
				cmbaccount_option  : tr.find('td.Account_Code').text(),
				txtpropertyno	   : tr.find('td.equipment_platepropertyno').text(),
				txtchassisno       : tr.find('td.equipment_chassisno').text(),
				txtengineno        : tr.find('td.equipment_engineno').text(),
				txtengineno        : tr.find('td.equipment_engineno').text(),
				txtModel           : tr.find('td.equipment_model').text(),
				txtBrand           : tr.find('td.equipment_brand').text(),
				txtfulltank        : tr.find('td.equipment_fulltank').text(),
				equipment_id       : tr.find('td.equipment_id').text(),
			};
			

			console.log(obj);

			$.each(obj,function(i,value){
				$('#'+i).val(value);
			});

			$('#save').removeClass('disabled');

			app_create.get_mr(obj.equipment_id);

		},save:function(){
			$.save({appendTo : '.fancybox-outer'});


			$post = {
				  ref_no           : $('#ref_no').val(),
	              to_profit_center : $('#to_profit_center option:selected').val(),
	              issue_to         : $('#issue_to option:selected').val(),
	              approved_by      : $('#approved_by').val(),
	              equipment_status_id  : $('#approved_by').val(),
	              requested_by     : $('#requested_by').val(),
	              ref_date         : $('#ref_date').val(),              
	              from_profit_center : $('#location').val(),
	              purpose          : $('#txtremarks').val(),
	              mr_id            : $('#mr_no').val() ,
	              from_project     : $('#project').val() ,
	              to_project       : $('#to_project').val(),
	              equipment_id     : $('#equipment_id').val(),
	              recommended_by   : $('#recommended_by option:selected').val(),
	              item_no          : $('#item_no').val(),
				  inventory_id     : $('#inventory_id').val(),
				  equipment_request_id : $('#equipment_request_id').val(),				  
         	};
			
			$.post('<?php echo base_url().index_page();?>/fixed_asset/equipment_transfer/update_equipmentTransfer',$post,function(response){				
					if(response==1){
						$.save({action : "success",reload : 'true'});
					}else{
						$.save({action : "hide"  ,reload : 'false'});
					}
			}).error(function(){
				alert('Service Unavailable');
				$.save({action : "hide"  ,reload : 'false'});
			});
		},panel:function(e){

			$('#items_content').slideToggle();
			$('#form_content').slideToggle();

			console.log();

		},get_mr:function($id){

			$post = {
				id : $id,
			};

			$.post('<?php echo base_url().index_page();?>/fixed_asset/equipment_transfer/get_mr',$post,function(response){
				$('#mr_no').val(response.MR_no);
			},'json');

		}
	}

$(function(){

	app_create.init();

});
</script>