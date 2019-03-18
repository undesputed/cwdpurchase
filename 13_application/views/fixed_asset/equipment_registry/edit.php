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


</style>

<div class="header">
	<h2>Equipment Registry <small>Edit</small></h2>	
</div>


<input type="hidden"  value="<?php echo $main['equipment_location']; ?>" id="location">
<input type="hidden"  value="<?php echo $main['title_id']; ?>" id="project">
<input type="hidden"  value="<?php echo $main['inventory_id']; ?>" id="inventory_id">
<input type="hidden"  value="" id="mr_code">
<input type="hidden"  value="" id="hdn_date">
<input type="hidden"  value="<?php echo $main['equipment_id']; ?>" id="txtequipmendid">


<div class="container">
		
	<div class="row">

		<div class="col-md-12">
			<div class="content-title">
				<h3>Edit Equipment Information</h3>
			</div>
			<table class="table table-striped">
				<tr>
					<td>Body No</td>
					<td><input type="text" id="txtbrand"  class="input-sm form-control"  value="<?php echo $main['equipment_brand']; ?>"></td>
					<td>Made</td>
					<td><input type="text" id="txtmadein"  class="input-sm form-control" value="<?php echo $main['made_in']; ?>"></td>
				</tr>
				<tr>
					<td>Equipment Name</td>
					<td><input type="text" id="txtequipmentname"  class="input-sm form-control" value="<?php echo $main['equipment_description']; ?>"></td>
					<td>Plate No/Prop No</td>
					<td><input type="text" id="txtpropertyno"  class="input-sm form-control" value="<?php echo $main['equipment_platepropertyno']; ?>"></td>
				</tr>
				<tr>
					<td>Model</td>
					<td><input type="text" id="cmbModel"  class="input-sm form-control" value="<?php echo $main['equipment_model']; ?>"></td>
					<td>Equipment Type</td>
					<td><select id="cmbequipmenttype"  class="input-sm form-control">
						<?php foreach($equipment_type as $row): ?>
							<?php $selected = ($row['id'] == $main['equipment_typecode'])? "selected='selected'": "" ; ?>
							<option <?php echo $selected; ?> value="<?php echo $row['id']; ?>"><?php echo $row['equipmenttype']; ?></option>
						<?php endforeach; ?>
					</select></td>
				</tr>
				<tr>
					<td>Operator Name</td>
					<td><select type="text" id="cmbdriver"  class="input-sm form-control">
						<?php  foreach($signatory as $row): ?>
							<?php $selected = ($row['id'] == $main['equipment_drivercode'])? "selected='selected'": "" ; ?>
							<option <?php echo $selected; ?> value="<?php echo $row['Person Code'] ?>"><?php echo $row['Full Name']; ?></option>
						<?php  endforeach;?>
					</select></td>
					<td>Engine No</td>
					<td><input type="text" id="txtengineno"  class="input-sm form-control" value="<?php echo $main['equipment_engineno']; ?>"></td>
				</tr>
				<tr>
					<td>Chassis No</td>
					<td><input type="text" id="txtchassisno"  class="input-sm form-control" value="<?php echo $main['equipment_chassisno']; ?>"></td>
					<td>Remarks</td>
					<td><input type="text" id="txtremarks"  class="input-sm form-control" value="<?php echo $main['equipment_remarks']; ?>"></td>
				</tr>
				<tr>
					<td>Date of Purchase</td>
					<td><input type="text" id="dtpdateofpurchase"  class="input-sm form-control date" value="<?php echo $main['equipment_purchase']; ?>"></td>
					<td>SMR</td>
					<td><input type="text" id="txtSMR"  class="input-sm form-control numbers_only" value="<?php echo $main['equipment_smr']; ?>"></td>
				</tr>
				<tr>
					<td>Estimated Life(<small>yrs</small>)</td>
					<td><input type="text" id="txtlife"  class="input-sm form-control" value="<?php echo $main['equipment_smr']; ?>"></td>
					<td>Full Tank(<small>liters</small>)</td>
					<td><input type="text" id="txtfulltank"  class="input-sm form-control numbers_only" value="<?php echo $main['equipment_fulltank']; ?>" ></td>
				</tr>
				<tr>
					<td>Reference No</td>
					<td><input type="text" id="txtreferenceno"  class="input-sm form-control" value="<?php echo $main['referrence_no']; ?>"></td>
					<td>Year</td>
					<td><input type="text" id="txtyear"  class="input-sm form-control numbers_only" maxlength="4" placeholder="YYYY" value="<?php echo $main['year_model']; ?>"></td>
				</tr>
				<tr>
					<td>Unit Cost</td>
					<td><input type="text" id="txtunitcost"  class="input-sm form-control numbers_only" value="<?php echo $main['equipment_cost']; ?>"></td>
					<td>Fuel Type</td>
					<td><select name="" id="cmbfueltype" class="form-control input-sm"></select></td>
				</tr>
				<tr>
					<td colspan='4'>
						<div class="row">
							<div class="col-md-9"></div>
							<div class="col-md-3"><button class="btn btn-block btn-success btn-sm " id="save">Update</button></div>
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

			$('#hdn_date').date({
				url : 'get_mr_code2',
				dom : $('#mr_code'),
				event: 'change',
			});
			$('.date').date();
			this.bindEvents();
			this.get_equipment();			

		},bindEvents:function(){

			$('body').on('click','.equipment_table tbody tr',this.selected);
			$('#save').on('click',this.save);

		},get_equipment:function(){
			$('.equipment_content').content_loader('true');
			$post = {
				location : $('#location').val(),
			};			
			$.post('<?php echo base_url().index_page();?>/fixed_asset/equipment_registry/get_equipment',$post,function(response){
				$('.equipment_content').html(response);
				$('.equipment_table').dataTable(datatable_option);
			});

		},selected:function(){
			$(this).addClass('selected');

			obj = {
				item_no           : $(this).find('td.Item_No').text(),
				txtequipmentname  : $(this).find('td.Item_Description').text(),
				qty_on_hand       : $(this).find('td.Quantity_on_Hand').text(),
				division_code     : $(this).find('td.Division_Code').text(),
				txtreferenceno    : $(this).find('td.Reference_No').text(),
				txtunitcost       : $(this).find('td.Equipment_Cost').text(),
				inventory_id      : $(this).find('td.Inventory_ID').text(),
				cmbdivision       : $(this).find('td.Division_Code').text(),
				cmbdivision_option : $(this).find('td.Division_Name').text(),
				cmbaccount_option  : $(this).find('td.Account_Code').text(),
			};

			$.each(obj,function(i,value){
				$('#'+i).val(value);
			});

			$('#save').removeClass('disabled');

		},save:function(){
			$.save({appendTo : '.fancybox-outer'});

			$post = {
				txtinventory              : $('#inventory_id').val(),
				txtequipmentname          : $('#txtequipmentname').val(),
				cmbequipmenttype          : $('#cmbequipmenttype option:selected').text(),
				cmbequipmenttype_option   : $('#cmbequipmenttype option:selected').val(),
				cmbfueltype               : $('#cmbfueltype option:selected').text(),
				cmbfueltype_option        : $('#cmbfueltype option:selected').val(),
				txtpropertyno             : $('#txtpropertyno').val(),
				txtchassisno              : $('#txtchassisno').val(),
				cmbdivision               : obj.cmbdivision,
				cmbdivision_option        : obj.cmbdivision_option,
				txtengineno               : $('#txtengineno').val(),
				cmbdriver                 : $('#cmbdriver option:selected').text(), 
				cmbdriver_option          : $('#cmbdriver option:selected').val(),
				txtunitcost               : $('#txtunitcost').val(),
				txtlife                   : $('#txtlife').val(),
				dtpdateofpurchase         : $('#dtpdateofpurchase').val(),
				txtremarks                : $('#txtremarks').val(),
				txtfulltank               : $('#txtfulltank').val(),
				cmbaccount_option         : obj.cmbaccount_option,
				txtequipmentname_tag      : obj.item_no,				
				cmbModel_option           : $('#cmbModel').val(),
				cmblocation_option        : $('#location').val(),
				txtreferenceno            : $('#txtreferenceno').val(),
				txtbrand                  : $('#txtbrand').val(),
				txtmadein                 : $('#txtmadein').val(),
				cmbassignee_option        : "0",
				cmbMainProject_option     : $('#project').val(),
				txtSMR                    : $('#txtSMR').val(),
				txtyear                   : $('#txtyear').val(),
				txtequipmendid            : $('#txtequipmendid').val(),
			}
			
			$.post('<?php echo base_url().index_page();?>/fixed_asset/equipment_registry/update_equipmentRegistry',$post,function(response){

					if(response==1){
						$.save({action : "success",reload : 'true'});
					}else{
						$.save({action : "hide"  ,reload : 'false'});
					}

			});
		}


	}

$(function(){

	app_create.init();

});
</script>