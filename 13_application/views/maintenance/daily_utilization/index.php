

<div class="header">
	<div class="container">
	
		<div class="row">
			<div class="col-md-8">
				<h2>Daily Equipment Utilization <small>Daily Utilization</small></h2>			
			</div>
			<div class="col-md-4">
					<div class="btn-group pull-right " style="margin-top:5px;">
						  <a href="<?php echo base_url().index_page(); ?>/maintenance/daily_utilization/" class="btn btn-primary active ">Transaction Form</a>
						  <a href="<?php echo base_url().index_page(); ?>/maintenance/daily_utilization/cumulative" class="btn btn-primary">Cumulative Data</a>	  
					</div>
			</div>
		</div>

	</div>
</div>

<div class="container">	

<form action="" method="post" id="form">
	<div class="content-title">
		<h3>Daily Utilization Form</h3>
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
							  		<div class="control-label">Equip. Util No</div>				  		
							  			<input type="text" id="equip_util_no" name="txt_equip_util_no" class="form-control">
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
							  		<div class="control-label">Scope</div>				  		
							  		<select name="" id="scope" class="form-control"></select>
			  					</div>
						</div>

						<div class="col-md-3">
								<div class="form-group">			  				
							  		<div class="control-label">Unit NO</div>				  		
							  		<select name="" id="unit_no" class="form-control"></select>
			  					</div>
						</div>

						<div class="col-md-3">
								<div class="form-group">			  				
							  		<div class="control-label">Model No</div>				  		
							  		<input type="text" name="" id="model_no" class="form-control">
			  					</div>
						</div>

						<div class="col-md-3">
								<div class="form-group">			  				
							  		<div class="control-label">Chassis</div>				  		
							  		<input type="text" name="txtOperator" id="operator_name" class="form-control">							  		
			  					</div>
						</div>
						

					</div>

				</div>
			</div>
		</div>	
	</div>
	
	
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">	
					<div class="panel-body">
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">			  				
							  		<div class="control-label">Shift</div>				  		
							  		<select name="" id="shift" class="form-control"></select>
			  					</div>
							</div>
						
							<div class="col-md-2">
								<div class="btn-group">
								<button class="btn btn-primary nxt-btn "><i class="fa fa-plus"></i></button>
								<button class="btn btn-danger nxt-btn "><i class="fa fa-trash-o"></i></button>
								</div>
							</div>

						</div>
					</div>
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

			app.get_equip_util_no();			

		},bindEvents:function(){
			$('.date').on('change',this.get_equip_util_no);
			$('#profit_center').on('change',this.get_scope);
			$('#unit_no').on('change',this.get_modelno);			
		},get_equip_util_no:function(){
			$post = {
				date : $('.date').val()
			}
			$.post('<?php echo base_url().index_page(); ?>/maintenance/daily_utilization/get_equip_util_no',$post,function(response){
				$('#equip_util_no').val(response);
			});
		},get_scope:function(){

			$post = {
				location : $('#profit_center option:selected').val()
			}
			$.post('<?php echo base_url().index_page(); ?>/maintenance/daily_utilization/scope',$post,function(response){				
				$('#scope').select({
					json : response,
					attr : {
							text  : 'type',
							value : 'id'
					}
				});
				app.get_unit();
			},'json');

		},get_unit:function(){

			$post = {
				scope_id : $('#scope option:selected').val()
			}

			$.post('<?php echo base_url().index_page();?>/maintenance/daily_utilization/unit_no',$post,function(response){
					$('#unit_no').select({
						json : response,
						attr :{
								text : 'equipment_brand',
								value : 'db_equipment_id',
						}
					});

					app.get_modelno();

			},'json');
			
		},get_modelno:function(){

			$post = {
				equip_id : $('#unit_no option:selected').val()
			};			
			$.post('<?php echo base_url().index_page(); ?>/maintenance/daily_checklist/model_no',$post,function(response){				
				$('#model_no').val(response[0].code);
			},'json');
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