<style>
	.myTable tbody tr:hover{
		cursor:pointer;
		text-decoration: underline;
	}

</style>

<div class="header">
	<h2>Stock Transfer <small>Edit</small></h2>	
</div>

<input type="hidden" value="<?php echo $main['from_location']; ?>" id="location">
<input type="hidden" value="<?php echo $main['title_id']; ?>" id="project">
<input type="hidden" value="" id="account_code">
<input type="hidden" value="" id="division">
<input type="hidden" value="<?php echo $main['equipment_request_id']; ?>" id="equipment_request_id">

<div class="container">
	<div class="content-title">
		<h3>Form Information</h3>
	</div>

	<div class="row">
		<div class="col-md-12">
					<div class="panel panel-default">		
					  <div class="panel-body">
					  	<div class="row">
					  		<div class="col-md-5">

					  		</div>
					  		<div class="col-md-3"></div>
					  		<div class="col-md-4">
					  				<div class="row">
								  		<div class="col-md-6">
								  			<div class="form-group">
									  			<div class="control-label">Ref Date</div>
									  			<input type="text" class="form-control input-sm" id="date" value="<?php echo $main['date_requested']; ?>">
								  			</div>
								  		</div>

								  		<div class="col-md-6">
								  			<div class="form-group">
									  			<div class="control-label">Ref No</div>
								  				<input type="text" class="form-control input-sm" id="ref_no" value="<?php echo $main['equipment_request_no']; ?>" readonly>
							  				</div>					  			
								  		</div>
								  	</div>
					  		</div>
					  	</div>
					 		
						<div class="row">
						  	<div class="col-md-6">
						  		<div class="panel panel-default">
						  				<div class="panel-body"><h4>Inventory List</h4></div>
						  				
						  				<div class="item_content">
								  			<table class="table table-striped">
										  		<thead>
										  			<tr>
										  				<th>Item No</th>
										  				<th>Item Description</th>
										  				<th>Qty</th>
										  			</tr>
										  		</thead>							
									  		</table>

								  		</div> 
						  		</div>
								
						  		<div class="panel panel-default">		
						  		  <div class="panel-body">
						  		  		<div class="row">
						  		  			<div class="col-md-12">
						  		  				<div class="form-group">
										  			<div class="control-label">Project</div>
										  			<select name="" id="to_project" class="form-control input-sm"></select>
										  		</div>
										  		<div class="form-group">
										  			<div class="control-label">Deliver To</div>
										  			<select name="" id="to_profit_center" class="form-control input-sm"></select>
										  		</div>
										  		<div class="form-group">
										  			<div class="control-label">Division</div>
										  			<select name="" id="division" class="form-control input-sm">
										  				<?php foreach($division as $row): ?>
										  					<option value="<?php echo $row['division_id'] ?>"><?php echo $row['division_name']; ?></option>
										  				<?php endforeach; ?>
										  			</select>
										  		</div>
						  		  			</div>

						  		  		</div>

						  		  		<div class="row">
						  		  			<div class="col-md-6">	
													<div class="form-group">
														<div class="control-label">Purpose</div>
														<textarea name="" id="purpose" cols="30" rows="2" class="form-control input-sm"><?php echo $main['remarks']; ?></textarea>														
													</div>
						  		  					<div class="form-group">
									  		  			<div class="control-label">Issued To</div>
									  		  			<select name="" id="issued_to" class="form-control input-sm">
									  		  				<?php  foreach($signatory as $row): ?>
									  		  					<?php $selected = ($row['Person Code']==$main['to_receiver'])? 'selected="selected"' : '' ; ?>
									  		  					<option <?php echo $selected; ?> value="<?php echo $row['Person Code'] ?>"><?php echo $row['Full Name']; ?></option>
									  		  				<?php  endforeach;?>
									  		  			</select>
									  		  		</div>
						  		  			</div>
						  		  			<div class="col-md-6">

						  		  					<div class="form-group">
									  		  			<div class="control-label">Requested By</div>
									  		  			<select name="" id="requested_by" class="form-control input-sm">
									  		  				<?php foreach($signatory as $row): ?>
									  		  				<?php $selected = ($row['Person Code'] == $main['requested_by'])? 'selected="selected"' : '' ; ?>
									  		  					<option <?php echo $selected ?> value="<?php echo $row['Person Code'] ?>"><?php echo $row['Full Name']; ?></option>
									  		  				<?php  endforeach;?>
									  		  			</select>
									  		  		</div>

									  		  		<div class="form-group">
									  		  			<div class="control-label">Approved By</div>
									  		  			<select name="" id="approved_by" class="form-control input-sm">
									  		  				<?php  foreach($signatory as $row): ?>
									  		  				<?php $selected = ($row['Person Code'] == $main['approver_id'])? 'selected="selected"' : '' ; ?>
									  		  					<option <?php echo $selected ?> value="<?php echo $row['Person Code'] ?>"><?php echo $row['Full Name']; ?></option>
									  		  				<?php  endforeach;?>
									  		  			</select>
									  		  		</div>
						  		  			</div>
						  		  		</div>

						  		  </div>	 
						  		</div>
						  		
						  	</div>
							<div class="col-md-6">
								<div class="add-panel" style="display:none">
									<div class="panel panel-default">									
										 	<table class="table table-striped">
												<tbody>
														<input type="hidden" id="inventory_id" value="">
														<input type="hidden" id="item_no" value="">
														<input type="hidden" id="item_cost" value="">
													<tr>
														<td>Item Description</td>
														<td><input type="text" id="item_description" class="form-control input-sm"></td>
													</tr>
													<tr>
														<td>Inventory Quantity</td>
														<td><input type="text" id="quantity" class="form-control input-sm"></td>
													</tr>
													<tr>
														<td>Withdrawn Quantity</td>
														<td><input type="text" id="withdrawn_qty" class="form-control input-sm"></td>
													</tr>													
													<tr>
														<td colspan='2'> <button id="add_to_list" class="pull-right btn btn-info">Add to List</button></td>
													</tr>
												</tbody>
										 	</table>								
									</div>
									<hr>
								</div>	
								

														
								
								<div class="content-title">
									<h3>Item Request List</h3>
								</div>

							 	<table class="table table-striped" id="withdrawn_list">	
							 		<thead>
							 			<tr>
							 				<th>Item No</th>
							 				<th>Item Description</th>
							 				<th>Quantity</th>
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
			<div class="form-footer">
					<div class="row">
					<div class="col-md-8"> </div>
					<div class="col-md-4">
						<input id="save" class="btn btn-success col-xs-5 pull-right" type="submit" value="Update">					
					</div>
					</div>
			</div>
			</div>
		
		</div>	

	</div>

</div>

<script type="text/javascript">

	var withdrawn_list = JSON.parse('<?php echo $details; ?>');
	var app_create = {
		init:function(){

			$('#date').date();

			this.get_item();
			this.bindEvents();
			
			var option = {
				profit_center : $('#to_profit_center'),
				pc_id : '<?php echo $main["to_location"] ?>',
			}			
			$('#to_project').get_projects(option);		

			this.render();

		},bindEvents:function(){

			$('body').on('click','.action-add',this.add);
			$('#add_to_list').on('click',this.add_list);
			$('#save').on('click',this.save);
			$('#cost_center').on('change',this.get_item);
			$('body').on('click','.remove',this.remove);

		},get_item:function(){

			$('.item_content').content_loader('true');			
			var $post = {
				location : $('#location').val(),
				type     : $('#cost_center option:selected').val(),
			};

			$.post('<?php echo base_url().index_page(); ?>/inventory/stock_withdrawal/get_item',$post,function(response){
				$('.item_content').html(response);
				$('.item-table').dataTable(datatable_option);
			});

		},add:function(){

			var tr = $(this).closest('tr');
			$data = {
				inventory_id : tr.find('td.inventory_id').text(),
				item_no      : tr.find('td.item_no').text(),
				item_description :  tr.find('td.item_description').text(),
				quantity     :  tr.find('td.received_quantity').text(),
				item_cost    :  tr.find('td.item_cost').text(),
			};

			$('#division').val(tr.find('td.division_code').text());
			$('#account_code').val(tr.find('td.account_code').text());

			$.each($data,function(key,value){
				$('#'+key).val(value);
			});

			$('.add-panel').slideDown();
				
			
		},add_list :function(){
			$('.add-panel').slideUp();

			var data = {
				inventory_id : $('#inventory_id').val(),
				item_no      : $('#item_no').val(),
				item_description : $('#item_description').val(),
				quantity      : $('#quantity').val(),
				withdrawn_qty : $('#withdrawn_qty').val(),
				remarks       : $('#remarks').val(),
				item_cost     : $('#item_cost').val()
			};

			withdrawn_list.push(data);
			app_create.render();

		},render : function(){
			$('#withdrawn_list tbody').html('');
			var td = "";
			if(withdrawn_list.length <=0){
				td = "<td colspan=\"4\">Empty List</td>";
			}
			$.each(withdrawn_list,function(key,value){

				    td +="<tr><td>"+value.item_no+"</td>";
					td +="<td>"+value.item_description+"</td>";
					td +="<td>"+value.withdrawn_qty+"</td><td><a href='javascript:void(0)' class='remove'>remove</a></td></tr>";
			});
			$('#withdrawn_list tbody').html(td);
			$('#withdrawn_qty').val('');
			$('#remarks').val('');

		},save:function(){


			$.save({appendTo : '.fancybox-outer'});
		

			$post = {				
			    equipment_request_no : $('#ref_no').val(),
				to_location : $('#to_profit_center option:selected').val(),
				to_receiver : $('#issued_to option:selected').val(),
				approver_id : $('#approved_by option:selected').val(),
				equipment_status_id : 'true',
				requested_by : $('#requested_by option:selected').val(),
				date_requested : $('#date').val(),
				from_location  : $('#location').val(),
				remarks   : $('#purpose').val(),
				MR_ID     : '' ,
				title_id  : $('#project').val() ,
				details   : withdrawn_list,
				division  : $('#division').val(),
				account   : $('#account_code').val(),
				equipment_request_id : $('#equipment_request_id').val(),		
			}

			$.post('<?php echo base_url().index_page();?>/inventory/stock_transfer/update_stockTransfer',$post,function(response){

					if(response==1){
						$.save({action : 'success',reload : 'true'});
					}else{
						$.save({action : 'hide',reload : 'true'});
					}

			}).error(function(){
					alert('Service Unavailable');
			});
			
		},remove:function(){

			var index = $(this).closest('tr').index();			
			withdrawn_list.splice(index,1);
			app_create.render();

		}


	}

$(function(){

	app_create.init();

});
</script>