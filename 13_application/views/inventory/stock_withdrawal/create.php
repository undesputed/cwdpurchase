<style>
	.myTable tbody tr:hover{
		cursor:pointer;
		text-decoration: underline;
	}

</style>

<div class="header">
	<h2>Stock Withdrawal</h2>	
</div>

<input type="hidden" value="<?php echo $post['location']; ?>" id="location">
<input type="hidden" value="<?php echo $post['location']; ?>" id="project">
<input type="hidden" value="" id="account_code">
<input type="hidden" value="" id="division">

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
				  				<div class="form-group">
								  			<div class="control-label">Cost Center Type</div>
								  			<select id="cost_center" class="form-control input-sm">
								  				<?php foreach($cost_center as $row): ?>
								  					<option value="<?php echo $row['id']; ?>"><?php echo $row['itemdescription']; ?></option>
								  				<?php endforeach; ?>
								  			</select>
							  	</div>
					  		</div>
					  		<div class="col-md-3"></div>
					  		<div class="col-md-4">
					  				<div class="row">
								  		<div class="col-md-6">
								  			<div class="form-group">
									  			<div class="control-label">MIS Date</div>
									  			<input type="text" class="form-control input-sm" id="date">
								  			</div>
								  		</div>

								  		<div class="col-md-6">
								  			<div class="form-group">
									  			<div class="control-label">MIS No</div>
								  				<input type="text" class="form-control input-sm" id="mis_no" readonly>
							  				</div>					  			
								  		</div>
								  	</div>
					  		</div>
					  	</div>
					 
	
						  <div class="row">
						  	<div class="col-md-6">
						  		<div class="panel panel-default">
						  				<div class="panel-body"><h4>Item List</h4></div>
						  				
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
						  		  		<di class="row">
						  		  			<div class="col-md-6">	
													<div class="form-group">
														<div class="control-label">Remarks</div>
														<textarea name="" id="remarks" cols="30" rows="2" class="form-control input-sm"></textarea>														
													</div>
						  		  					<div class="form-group">
									  		  			<div class="control-label">Issued by</div>
									  		  			<select name="" id="issued_by" class="form-control input-sm">
									  		  				<?php  foreach($signatory as $row): ?>
									  		  					<?php $selected = ($row['Person Code']==$this->session->userdata('person_code'))? 'selected="selected"' : '' ; ?>
									  		  					<option <?php echo $selected; ?> value="<?php echo $row['Person Code'] ?>"><?php echo $row['Full Name']; ?></option>
									  		  				<?php  endforeach;?>
									  		  			</select>
									  		  		</div>
						  		  			</div>
						  		  			<div class="col-md-6">
						  		  					<div class="form-group">
									  		  			<div class="control-label">Noted by</div>
									  		  			<select name="" id="noted_by" class="form-control input-sm">
									  		  				<?php  foreach($signatory as $row): ?>
									  		  					<option value="<?php echo $row['Person Code'] ?>"><?php echo $row['Full Name']; ?></option>
									  		  				<?php  endforeach;?>
									  		  			</select>
									  		  		</div>
									  		  		<div class="form-group">
									  		  			<div class="control-label">Received by</div>
									  		  			<select name="" id="received_by" class="form-control input-sm">
									  		  				<?php  foreach($signatory as $row): ?>
									  		  					<option value="<?php echo $row['Person Code'] ?>"><?php echo $row['Full Name']; ?></option>
									  		  				<?php  endforeach;?>
									  		  			</select>
									  		  		</div>
						  		  			</div>
						  		  		</di>

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
									<h3>Item Withdrawn List</h3>
								</div>							 
							 	<table class="table table-striped" id="withdrawn_list">	
							 		<thead>
							 			<tr>
							 				<th>Item No</th>
							 				<th>Item Description</th>
							 				<th>Withdrawn Qty</th>
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
						<input id="save" class="btn btn-success col-xs-5 pull-right" type="submit" value="Save">					
					</div>
					</div>
			</div>
			</div>
		
		</div>	

	</div>

</div>

<script type="text/javascript">

	var withdrawn_list = new Array;
	var app_create = {
		init:function(){

			$('#date').date({
				url : 'get_maxMIS',
				dom : $('#mis_no'),
				event : 'change',
			});

			this.get_item();
			this.bindEvents();
					
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

			$('.add-panel').slideDown()

			// if($(this).find('td.dataTables_empty').length>0){
			// 	return false;
			// }
			// $.fancybox.showLoading();
			// $post = {
			// 	location     : $('#profit_center option:selected').val(),
			// 	project      : $('#project option:selected').val(),
			// 	item_id      : $(this).find('td.Item_ID').text(),
			// 	item_name    : $(this).find('td.Item_Name').text(),
			// 	current_qty  : $(this).find('td.Current_Qty').text(),
			// 	max_qty      : $(this).find('td.Max_Qty').text(),
			// 	min_qty      : $(this).find('td.Min_Qty').text(),
			// 	division     : $(this).find('td.division_code').text(),
			// 	account_code : $(this).find('td.account_code').text(),
			// 	serial_no    : $(this).find('td.serial_no').text(),
			// };

			// $.post('<?php echo base_url().index_page();?>/inventory/stock_inventory/get_details',$post,function(response){
			// 	$.fancybox(response,{
			// 		width     : 1200,
			// 		height    : 550,
			// 		fitToView : false,
			// 		autoSize  : false,
			// 	})
			// });
			
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
					withdraw_no  : $('#mis_no').val(),
					division     : $('#division').val(),
					account_code : $('#account_code').val(),
					withdraw_person_id  : $('#issued_by option:selected').val(),
					withdraw_person_incharge  : $('#noted_by option:selected').val(),
					date_withdrawn  : $('#date').val(),
					location  : $('#location').val(),
					title_id  : $('#project').val(),
					remarks   : $('#remarks').val(),
					withdraw_receive_by  : $('#received_by option:selected').val(),
					db_equipment_id  : '0',
					pay_item  : $('#cost_center option:selected').val(),
					details   : withdrawn_list,
					cost_center : $('#cost_center option:selected').val(),
			}

			$.post('<?php echo base_url().index_page();?>/inventory/stock_withdrawal/save_withdrawal',$post,function(response){
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