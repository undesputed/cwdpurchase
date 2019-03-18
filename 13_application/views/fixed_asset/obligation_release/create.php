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
	<h2>Obligation Release</h2>	
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
				<h3>MR List</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">			  		
			  </div>	
			  <div class="equipment_content">

				  <table class="table table-striped">
				  	<thead>
				  		<tr>
				  			<th>MR No</th>
							<th>Equipment Name</th>
							<th>Plate No</th>
							<th>Chassis No</th>
							<th>Engine No</th>
							<th>Equipment Cost</th>
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
								<td><input type="text" id="txtStatus" value="" class="input-sm form-control" readonly ></td>													
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

			$('#ref_date').date();
						
			$('.date').date();

			var option = {
				profit_center : $('#to_profit_center')
			}			
			$('#to_project').get_projects(option);

			this.bindEvents();
			this.get_equipment();
							
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

			$('.container').unbind('click');
			$('body').on('click','.equipment_table .event',this.selected);
			$('body').on('click','.tools-table .event',this.tools_selected);
			$('.container').on('click','.equipment_table .event,#reset',this.panel);
			$('body').on('click','.tools-table-render .remove',this.remove_node);
			
			$('#save').on('click',this.save);
			$('#add_accessories').on('click',this.add_accessories);

		},get_equipment:function(){

			$('.equipment_content').content_loader('true');

			$post = {
				location : $('#location').val(),
				project  : $('#project').val(),
			};

			$.post('<?php echo base_url().index_page();?>/fixed_asset/obligation_release/get_cumulative_2',$post,function(response){
				 $('.equipment_content').html(response);
				 $('.equipment_table').dataTable(datatable_option);
			});

		},selected:function(){	

			//$(this).addClass('selected');
			var tr = $(this).closest('tr');

			obj = {
				item_no            : tr.find('td.equipment_itemno').text(),
				txtequipmentname   : tr.find('td.equipment_name').text(),
				qty_on_hand        : tr.find('td.Quantity_on_Hand').text(),
				division_code      : tr.find('td.Division_Code').text(),
				txtreferenceno     : tr.find('td.Reference_No').text(),
				txtunitcost        : tr.find('td.Equipment_Cost').text(),
				inventory_id       : tr.find('td.inventory_id').text(),
				cmbdivision        : tr.find('td.Division_Code').text(),
				cmbdivision_option : tr.find('td.Division_Name').text(),
				cmbaccount_option  : tr.find('td.Account_Code').text(),
				txtpropertyno	   : tr.find('td.plate_no').text(),
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
				mr_id              : tr.find('td.mr_id').text(),
				mr_no              : tr.find('td.mr_no').text(),
				ref_date           : tr.find('td.date_saved').text(),
				requestedby        : tr.find('td.requestedby').text(),
				issuedby           : tr.find('td.issuedby').text(),
				approvedby         : tr.find('td.approvedby').text(),				
			};

			app_create.get_tools_from(obj.mr_id,obj.equipment_id);

			/*$('#issue_to    option[value="'+obj.+'"]').prop('selected',true);*/			
			$('#prepared_by option[value="'+obj.requestedby+'"]').prop('selected',true);
			$('#checked_by  option[value="'+obj.issuedby+'"]').prop('selected',true);
			$('#approved_by option[value="'+obj.approvedby+'"]').prop('selected',true);

			$.each(obj,function(i,value){
				$('#'+i).val(value);
			});

			$('#save').removeClass('disabled');


		},save:function(){
			$.save({appendTo : '.fancybox-outer'});
	

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
			         		
			$.post('<?php echo base_url().index_page();?>/fixed_asset/obligation_release/save_obligation',$post,function(response){				
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

		},get_tools_from : function(mr_id,equipment_id){

			var $post = {
				mr_id        : mr_id,
				equipment_id : equipment_id
			}

			$.post('<?php echo base_url().index_page();?>/fixed_asset/obligation_release/get_tools',$post,function(response){

				tool_obj = response.mr_id;
				app_create.render_tools();

				$('#txtStatus').val(response.equipment_id.equipment_status);
				$('#txtStatus').attr({'data-value':response.equipment_id.equipment_statuscode});

			},'json');

		}


	}

$(function(){

	app_create.init();

});
</script>