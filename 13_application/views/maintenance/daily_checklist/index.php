

<div class="header">
	<div class="container">
	
	<div class="row">
		<div class="col-md-8">
			<h2>Daily Equipment Checklist <small>Daily Checklist</small></h2>			
		</div>
		<div class="col-md-4">
				<div class="btn-group pull-right " style="margin-top:5px;">
					  <a href="<?php echo base_url().index_page(); ?>/maintenance/daily_checklist/" class="btn btn-primary active ">Transaction Form</a>
					  <a href="<?php echo base_url().index_page(); ?>/maintenance/daily_checklist/cumulative" class="btn btn-primary">Cumulative Data</a>	  
				</div>
		</div>
	</div>
	

	

	</div>
</div>

<div class="container">	

<form action="" method="post" id="form">
	<div class="content-title">
		<h3>Daily Checklist Form</h3>
	</div>
	
<?php echo $this->extra->alert(); ?>

	<div class="row">
		<div class="col-md-4">
				  <div class="panel panel-default">		
				  <div class="panel-body">
				  			  														
			  			<div class="form-group">			  				
					  		<div class="control-label">Project</div>				  		
					  		<select name="project" id="project" class="form-control"></select>
			  			</div>

			  			<div class="form-group">			  				
					  		<div class="control-label">Profit Center</div>				  		
					  		<select name="profit_center" id="profit_center" class="form-control"></select>
			  			</div>
			  												  							  	
				  </div>
				</div>	
		</div>			

		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-body">

					<div class="row">

						<div class="col-md-5">
								<div class="form-group">			  				
							  		<div class="control-label">Item Name</div>				  		
							  		<select name="cbxName" id="item_name" class="form-control">
							  			<?php foreach($item_name as $row): ?>
							  			<option value="<?php echo $row['group_detail_id'] ?>"><?php echo $row['description'] ?></option>
							  		<?php endforeach; ?>
							  		</select>
			  					</div>
						</div>

					
						<div class="col-md-2">
								<div class="form-group">			  				
							  		<div class="control-label">Shift</div>				  		
							  		<select name="cbxShift" id="shift" class="form-control">
							  			<?php foreach($shift as $row): ?>
							  			<option value="<?php echo $row['id']?>"><?php echo $row['TYPE']; ?></option>
							  			<?php endforeach; ?>
							  		</select>
			  					</div>
						</div>

						<div class="col-md-3">
								<div class="form-group">			  				
							  		<div class="control-label">Date</div>				  		
							  			<div class="input-group">
								  			<input type="text" name="dtpDate" id="date" class="form-control date">
								  			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								  		</div>
			  					</div>
						</div>
					</div>

					<div class="row">

						<div class="col-md-3">
								<div class="form-group">			  				
							  		<div class="control-label">Equipment Name</div>				  		
							  		<select name="cbxEquipment" id="equipment_name" class="form-control"></select>
			  					</div>
						</div>

						<div class="col-md-3">
								<div class="form-group">			  				
							  		<div class="control-label">Model No</div>				  		
							  		<input type="text" name="txtModelNo" id="model_no" class="form-control">
			  					</div>
						</div>

						<div class="col-md-3">
								<div class="form-group">			  				
							  		<div class="control-label">Serial No</div>				  		
							  		<input type="text" name="txtSerialNo" id="serial_no" class="form-control">
			  					</div>
						</div>

						<div class="col-md-3">
								<div class="form-group">			  				
							  		<div class="control-label">Operator's Name</div>				  		
							  		<input type="text" name="txtOperator" id="operator_name" class="form-control">
							  		<input type="hidden" name="txtOperatorID" id="operator_id" class="form-control">
			  					</div>
						</div>
						

					</div>

				</div>
			</div>
		</div>	
	</div>
	
	<div class="content-title" style="margin-top:0px">
		<h3>Checklist</h3>
	</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">	
					<div class="table-content table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Type</th>
									<th>Category</th>
									<th><div class="checkbox-inline"><input type="checkbox"> Ok </div></th>
									<th><div class="checkbox-inline"><input type="checkbox"> No </div></th>
									<th><div class="checkbox-inline"><input type="checkbox"> NA </div></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>				
				</div>
			</div>		
		</div>

	<div class="alert alert-warning">

		<p><strong>Mandatory :</strong> will not be allowed to operate</p>
		<p><strong>Minor :</strong> For Reference and Information of operator and safety</p>

	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-4">
					<textarea name="txtMainRemarks" id="" cols="30" rows="5" class="form-control" placeholder="Remarks">
						
					</textarea>
				</div>
				<div class="col-md-4">
					<div class="form-group">			  				
					  		<div class="control-label">Checked by</div>				  		
					  		<select name="cbxCheckedby" id="shift" class="form-control">
					  			<?php foreach($signatory as $row): ?>
					  			<option value="<?php echo $row['emp_number'];?>"><?php echo $row['pp_fullname']; ?></option>
					  			<?php endforeach; ?>
					  		</select>
				  	</div>
				  	<div class="form-group">			  				
					  		<div class="control-label">Inspected By</div>				  		
					  		<select name="cbxInspectedby" id="shift" class="form-control">
					  			<?php foreach($signatory as $row): ?>
					  			<option value="<?php echo $row['emp_number'];?>"><?php echo $row['pp_fullname']; ?></option>
					  			<?php endforeach; ?>
					  		</select>
				  	</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">			  				
					  		<div class="control-label">Approved by</div>				  		
					  		<select name="cbxApprovedby" id="shift" class="form-control">
					  			<?php foreach($signatory as $row): ?>
					  			<option value="<?php echo $row['emp_number'];?>"><?php echo $row['pp_fullname']; ?></option>
					  			<?php endforeach; ?>
					  		</select>
				  	</div>
				</div>
			</div>
		</div>

		<div class="form-footer">
			<div class="row">
				<div class="col-md-8">
					
				</div>
				<div class="col-md-4">
					<input type="submit" id="save" class="btn btn-primary btn-lg col-xs-5 pull-right" value="Save">
					<input type="reset" id="reset" class="btn btn-link btn-lg pull-right" value="Reset">
				</div>
			</div>
		</div>

	</div>
	
			
	<select name="" id="clone-td" style="display:none">
		<?php foreach($category as $row): ?>
			<option value="<?php echo $row['id']?>"><?php echo $row['type'] ?></option>
		<?php endforeach; ?>
	</select>
</form>

</div>

<script type="text/javascript">

	var app = {
		init:function(){					
			this.bindEvents();	

			var option = {
				profit_center : $('#profit_center')
			}

			$('#project').get_projects(option);			
			
			$('.date').date();

			this.get_equipmentname();

		},bindEvents:function(){
			$('#item_name').on('change',this.get_equipmentname);
			$('#equipment_name').on('change',this.get_modelno);
			$('body').on('click','.chk-head',this.toggleCheck)

			$('#form').bind('submit',this.save);

			$('#reset').on('click',function(e){
				location.reload(true);
			});

			$('body').on('click','tr .ok, tr .no,tr .na',this.checkbox_once);

			
		},get_equipmentname:function(){

			$post = {
				id : $('#item_name option:selected').val(),
			};
			$.post('<?php echo base_url().index_page(); ?>/maintenance/daily_checklist/get_equipmentname',$post,function(response){
					$('#equipment_name').select({
						json : response,
						attr : {
							text  : 'equipment_brand',
							value : 'equipment_id',
						},
						data : {
							'data-chassisno'  : 'equipment_chassisno',
							'data-driver'     : 'equipment_driver',
							'data-drivercode' : 'equipment_drivercode',
						}
					});

				app.get_modelno();
				app.get_checklist();

			},'json');

		},get_modelno:function(){

			$post = {
				equip_id : $('#equipment_name option:selected').val()
			};			
			$.post('<?php echo base_url().index_page(); ?>/maintenance/daily_checklist/model_no',$post,function(response){				
				$('#model_no').val(response[0].code);
				$('#serial_no').val($('#equipment_name option:selected').attr('data-chassisno'));
				$('#operator_name').val($('#equipment_name option:selected').attr('data-driver'));
				$('#operator_id').val($('#equipment_name option:selected').attr('data-drivercode'));
			},'json');
		},get_checklist:function(){

			$('.table-content').content_loader('true');
			$post = {
				item_id : $('#item_name option:selected').val()
			};	
			$.post('<?php echo base_url().index_page(); ?>/maintenance/daily_checklist/get_checklist',$post,function(response){
				$('.table-content').html(response);
				datatable_option['bSort'] = false;
				$('.myTable').dataTable(datatable_option);

			});

		},toggleCheck:function(){

			var $id = $(this).attr('id');
			var $checked = $(this).is(':checked');			
			$('.chk-head').prop('checked',false);
			$('.chk-head#'+$id).prop('checked',$checked);

			var rows = $(".myTable").dataTable().fnGetNodes();
			for(var i=0;i<rows.length;i++){

				   var name = $(rows[i]).find('.'+$id).attr('name');   
				   $(rows[i]).find('input[name="'+name+'"]').prop('checked',false);
		           $(rows[i]).find('.'+$id).prop('checked',$checked);

		    }


		},save:function(e){
			e.preventDefault();

			var list = app.get_table();
			if(list.length == 0){
				alert('Please Checked a Checklist');
				return false;
			}

			$post = {
				form : $('#form').serialize(),
				list : app.get_table(),
			}

			$('#save').addClass('disabled');
			$.post('<?php echo base_url().index_page();?>/maintenance/daily_checklist/save',$post,function(response){				
					location.reload(true);
			});
			

			
		},get_table:function(){

			var rows = $(".myTable").dataTable().fnGetNodes();
			var $row_content = new Array();
			for(var i=0;i< rows.length;i++){
		           
		           if($(rows[i]).find('.ok').is(':checked')){
		           		$row_content.push(app.get_td(rows[i]));
		           }else if($(rows[i]).find('.no').is(':checked')){
		           		$row_content.push(app.get_td(rows[i]));
		           }else if($(rows[i]).find('.na').is(':checked')){
		           		$row_content.push(app.get_td(rows[i]));
		           }

		    }

		    return $row_content;

		},get_td:function(row){
			$td_content = new Array();
			$(row).find('td').each(function(i,val){
				if($(val).find('input').length){
					$td_content.push($(val).find('input').is(':checked'));
				}else{
					$td_content.push($(val).text());
				}				
			});		
			return $td_content;
		},checkbox_once:function(){

			var name = $(this).attr('name');
			$('input[name="'+name+'"]').prop('checked',false);
			$(this).prop('checked',true);

		}
	}

$(function(){
	app.init();

	$('.editable-td').editable_td({
		insert : 'select',
		clone  : "#clone-td",
	});

	$('.editable-td-input').editable_td({
		insert :  'input'
	});

});
</script>