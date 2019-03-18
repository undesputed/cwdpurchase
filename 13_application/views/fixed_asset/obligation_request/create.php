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

	.tools-table tbody tr:hover > td .event {		
		visibility: visible !important;	
	}

	.tools-table tbody tr .event{
		visibility: hidden;
	}

	.tools-table-render tbody tr:hover > td .event {		
		visibility: visible !important;	
	}

	.tools-table-render tbody tr .event{
		visibility: hidden;
	}
	
	.tools-content{
		max-height:170px;
		overflow: auto;
	}

	
</style>

<div class="header">
	<h2>Obligation Request</h2>	
</div>

<input  type="hidden"  value="<?php echo $post['location']; ?>" id="location">
<input  type="hidden"  value="<?php echo $post['project']; ?>" id="project">
<input  type="hidden"  value="" id="inventory_id">
<input  type="hidden"  value="" id="mr_code">
<input  type="hidden"  value="" id="hdn_date">
<select type="hidden"  value="" id="cmbdivision" style="display:none"></select>


<div class="container">	
	<div class="row" id='items_content'>
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
	<div class="row" id="form_content" style="display:none">
		<div class="col-md-12">
			
			<div class="row">
				<div class="col-md-5">
					<div class="content-title">
						<h3>Equipment Details</h3>
					</div>	
						<table class="table table-striped">
							
							<tr>
								<td>Equipment Name</td>
								<td><input type="text" id="txtequipmentname" value="" class="form-control input-sm" readonly></td>							
							</tr>
							
							<tr>
								<td>Plate No/Prop No</td>
								<td><input type="text" id="txtpropertyno" value="" class="input-sm form-control" readonly></td>
							</tr>
							
							<tr>
								<td>Status</td>
								<td><input type="text" id="txtStatus" value="" class="input-sm form-control" readonly></td>													
							</tr>

							<tr>
								<td>Made In</td>
								<td><input type="text" id="txtMadeIn" value="" class="input-sm form-control" readonly></td>								
							</tr>
							
							<tr>
								<td>Value</td>
								<td><input type="text" id="txtValue" value="" class="input-sm form-control"></td>
							</tr>
							
						</table>
				</div>
				<div class="col-md-7">
					<div class="content-title">
						<h3>Accessories List</h3>
					</div>	
					<div class="panel panel-default">		
					  <div class="panel-body">	
					  	<button id="add_accessories" class="btn btn-primary btn-sm pull-right" title="Add Accessories"><i class="fa fa-plus" ></i></button>
					  </div>
						<div class="tools-content">
							<table class="table table-striped tools-table-render">
								<thead>
									<tr>
										<th>Description</th>
										<th>Property No</th>
										<th>Cost</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="4">Empty List</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>		
						
			<div class="content-title">
				<h3>Transfer Information</h3>
			</div>
			<table class="table table-striped">

				<tr>
					<td></td>
					<td></td>
					<td>Ref Date</td>
					<td><input type="text" id="ref_date" value="" class="input-sm form-control"></td>
					<td>MR Number</td>
					<td><input type="text" id="mr_no" value="" class="input-sm form-control"></td>
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
						<?php /*$selected = ($row['Person Code']==$this->session->userdata('person_code'))? 'selected="selected"' : '' ;*/ ?>
						<option  value="<?php echo $row['Person Code'] ?>"><?php echo $row['Full Name']; ?></option>
						<?php  endforeach;?>
					</select></td>
					<td>Prepared By</td>
					<td><select id="prepared_by" class="input-sm form-control">
						<?php  foreach($signatory as $row): ?>
						<?php /*$selected = ($row['Person Code']==$this->session->userdata('person_code'))? 'selected="selected"' : '' ;*/ ?>
						<option  value="<?php echo $row['Person Code'] ?>"><?php echo $row['Full Name']; ?></option>
						<?php  endforeach;?>
					</select></td>
					<td>Checked By</td>
					<td><select id="checked_by" class="input-sm form-control"></select></td>
				</tr>

				<tr>
					<td>Approved By</td>
					<td><select id="approved_by" class="input-sm form-control"></select></td>
					<td colspan="4"></td>
					
				</tr>
					<td colspan='6'>
						<div class="row">
							<div class="col-md-9"></div>
							<div class="col-md-1"><button class="btn btn-block btn-link btn-sm " id="reset">Reset</button></div>
							<div class="col-md-2"><button class="btn btn-block btn-success btn-sm " id="save">Save</button></div>
						</div>						
					</td>					
				</tr>
			</table>
		</div>
	</div>	
</div>

<div id="dialog" title="Tools / Accessories List" style="display:none">
	<div class="dialog-content">
		Loading....
	</div>
</div>

<script type="text/javascript">
	var obj = new Object();
	var tool_obj = new Array;
	var app_create = {
		init:function(){

			$('#ref_date').date({
				url   : 'get_mr_code2',
				dom   : $('#mr_no'),
				event : 'change',
			});
						
			$('.date').date();

			var option = {
				profit_center : $('#to_profit_center')
			}			
			$('#to_project').get_projects(option);

			this.bindEvents();
			this.get_equipment();

			/*cmbrequested.DataSource = DataSource(String.Format("CALL display_person_signatory1('{0}')", frmMain.LBLEMP.Text))
            'cmbrequested.SelectedIndex = 0
            cmbissued.DataSource = DataSource(String.Format("CALL display_person_signatory('{0}','{1}','{2}','{3}')", "9", "2", "1", cmbprojectlocation.SelectedValue))
            'cmbissued.SelectedIndex = 0
            cmbapproved.DataSource = DataSource(String.Format("CALL display_person_signatory('{0}','{1}','{2}','{3}')", "9", "4", "1", cmbprojectlocation.SelectedValue))*/


			$.signatory({
				checked_by     : ['9','2','1',$('#location').val()],
				approved_by    : ['9','4','1',$('#location').val()],				
			});

			$('#dialog').dialog({
				modal : true,
				autoOpen: false ,
				appendTo: '.fancybox-outer',
				minHeight: 20 ,
				maxHeight: 50,
			});

			this.get_tools();

		},bindEvents:function(){			
			$('body').on('click','.equipment_table .event',this.selected);
			$('body').on('click','.tools-table .event',this.tools_selected);
			$('body').on('click','.equipment_table .event,#reset',this.panel);
			$('body').on('click','.tools-table-render .remove',this.remove_node);
			
			$('#save').on('click',this.save);
			$('#add_accessories').on('click',this.add_accessories);
		},get_equipment:function(){

			$('.equipment_content').content_loader('true');
			$post = {
				location : $('#location').val(),
			};

			$.post('<?php echo base_url().index_page();?>/fixed_asset/obligation_request/get_equipment',$post,function(response){
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
				txtStatus          : tr.find('td.equipment_status').text(),
				txtMadeIn          : tr.find('td.made_in').text(),
				txtValue           : tr.find('td.equipment_cost').text(),
				request_id         : tr.find('td.from_location').text(),
			};

			console.log(obj);

			$.each(obj,function(i,value){
				$('#'+i).val(value);
			});

			$('#save').removeClass('disabled');

			app_create.get_mr(obj.equipment_id);



		},save:function(){
			$.save({appendTo : '.fancybox-outer'});

			// $post = {
			// 	txtinventory              : $('#inventory_id').val(),
			// 	txtequipmentname          : $('#txtequipmentname').val(),
			// 	cmbequipmenttype          : $('#cmbequipmenttype option:selected').text(),
			// 	cmbequipmenttype_option   : $('#cmbequipmenttype option:selected').val(),
			// 	cmbfueltype               : $('#cmbfueltype option:selected').text(),
			// 	cmbfueltype_option        : $('#cmbfueltype option:selected').val(),
			// 	txtpropertyno             : $('#txtpropertyno').val(),
			// 	txtchassisno              : $('#txtchassisno').val(),
			// 	cmbdivision               : obj.cmbdivision,
			// 	cmbdivision_option        : obj.cmbdivision_option,
			// 	txtengineno               : $('#txtengineno').val(),
			// 	cmbdriver                 : $('#cmbdriver option:selected').text(), 
			// 	cmbdriver_option          : $('#cmbdriver option:selected').val(),
			// 	txtunitcost               : $('#txtunitcost').val(),
			// 	txtlife                   : $('#txtlife').val(),
			// 	dtpdateofpurchase         : $('#dtpdateofpurchase').val(),
			// 	txtremarks                : $('#txtremarks').val(),
			// 	txtfulltank               : $('#txtfulltank').val(),
			// 	cmbaccount_option         : obj.cmbaccount_option,
			// 	txtequipmentname_tag      : obj.item_no,				
			// 	cmbModel_option           : $('#cmbModel').val(),
			// 	cmblocation_option        : $('#location').val(),
			// 	txtreferenceno            : $('#txtreferenceno').val(),
			// 	txtbrand                  : $('#txtbrand').val(),
			// 	txtmadein                 : $('#txtmadein').val(),
			// 	cmbassignee_option        : "0",
			// 	cmbMainProject_option     : $('#project').val(),
			// 	txtSMR                    : $('#txtSMR').val(),
			// 	txtyear                   : $('#txtyear').val(),
			// }

			/*
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
	              equipment_id     : obj.equipment_id,
	              recommended_by   : $('#recommended_by option:selected').val(),
	              item_no          : obj.item_no,
				  inventory_id     : obj.inventory_id,
         	};
         	*/

         	$post = {
         		  txtmrno      : $('#mr_no').val(),
         		  cmbperson    : $('#issue_to option:selected').val(),
         		  equipment_id : obj.equipment_id,
         		  itemno       : obj.item_no,
         		  cmbprojectlocation : $('#location').val(),
         		  txtmadein    : $('#txtMadeIn').val(),
         		  dtpmrdate    : $('#ref_date').val(),
         		  txtvalue     : $('#txtValue').val(),
         		  cmbtoprojectlocation : $('#to_profit_center option:selected').val(),
         		  cmbrequested : $('#prepared_by option:selected').val(),
         		  cmbissued    : $('#checked_by option:selected').val(),
         		  cmbapproved  : $('#approved_by option:selected').val(),
         		  from_project : $('#to_project option:selected').val(),
         		  plate_property_no : $('#txtpropertyno').val(),
         		  cmbperson_value   : $('#issue_to option:selected').text(),
         		  request_id        : obj.request_id,
         		  txtequipmentname  : $('#txtequipmentname').val(),
         		  details        : tool_obj,

         	}
			         		
			$.post('<?php echo base_url().index_page();?>/fixed_asset/obligation_request/save_obligation',$post,function(response){				
					if(response==1){
						$.save({action : "success",reload : 'true'});
					}else{
						$.save({action : "hide"  ,reload : 'false'});
					}
			});

		},panel:function(e){

			$('#items_content').slideToggle();
			$('#form_content').slideToggle();
			

		},get_mr:function($id){
			/*$post = {
				id : $id,
			};
			$.post('<?php echo base_url().index_page();?>/fixed_asset/equipment_transfer/get_mr',$post,function(response){
				$('#mr_no').val(response.MR_no);
			},'json');*/
		},add_accessories : function(){
			$( "#dialog" ).dialog('open');
		},get_tools:function(){
			$post = {
				location : $('#location').val(),
			}
			$.post('<?php echo base_url().index_page();?>/fixed_asset/obligation_request/get_mr_accessories',$post,function(response){
				$('.dialog-content').html(response);
			});
		},tools_selected : function(){			
			var tr = $(this).closest('tr');
			var data = {
				equipment_description       : tr.find('td.equipment_description').text(),
				equipment_platepropertyno   : tr.find('td.equipment_platepropertyno').text(),
				equipment_cost              : tr.find('td.equipment_cost').text(),
			}

			tool_obj.push(data);
			app_create.render_tools();

		},render_tools:function(){
			$('.tools-table-render tbody').html('');
			var td = "";

			if(tool_obj.length==0){
				$('.tools-table-render tbody').html('<tr><td colspan="4">Empty List</td></tr>');
				return false;
			}

			$.each(tool_obj,function(i,val){
				td += "<tr><td>"+val.equipment_description+"</td>";
				td += "<td>"+val.equipment_platepropertyno+"</td>";
				td += "<td>"+val.equipment_cost+"</td>";
				td += "<td><span class='btn-link event remove'>Remove</span></td>";
				td += "</tr>";
			});

			$('.tools-table-render tbody').html(td);


		},remove_node:function(){

			var index = $(this).closest('tr').index();
			tool_obj.splice(index,1);
			app_create.render_tools();

		}


	}

$(function(){

	app_create.init();

});
</script>