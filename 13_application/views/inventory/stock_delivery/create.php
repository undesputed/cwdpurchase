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

</style>

<div class="header">
	<h2>Stock Delivery</h2>	
</div>


<input type="hidden" value="<?php echo $post['location']; ?>" id="location">
<input type="hidden" value="<?php echo $post['project']; ?>" id="project">
<input type="hidden" value="" id="to_location">
<input type="hidden" value="" id="equipment_id">
<input type="hidden" value="" id="equipment_request_id">


<div class="container">
	<div class="content-title">
		<h3>Form Information</h3>
	</div>

	<div class="row">
		<div class="col-md-12">
					<div class="panel panel-default">		
					  <div class="panel-body">					  
					 	
						  <div class="row">
						  	<div class="col-md-6">
						  		<div class="panel panel-default">
						  				<div class="panel-body"><h4>Request List</h4></div>						  				
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
						  		  			<div class="col-md-8">
						  		  				<div class="form-group">
														<div class="control-label">Please Allow</div>
														<input type="text" id="deliver_by" class="form-control input-sm uppercase">
												</div>
						  		  			</div>
						  		  			<div class="col-md-4">
						  		  				<div class="form-group">
														<div class="control-label">To Bring</div>
														<div class="radio-inline"><input type="radio" name="position" id="inside"  value="YES"><label for="inside">Inside</label></div>
														<div class="radio-inline"><input type="radio" name="position" id="outside" value="NO" checked><label for="outside">Outside</label></div>
												</div>
						  		  			</div>
						  		  		</div>	
						  		  		<hr>								
						  		  		<div class="row">
						  		  			<div class="col-md-6">	
													<div class="form-group">
														<div class="control-label">Purpose</div>
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
									  		  			<div class="control-label">Noted By</div>
									  		  			<select name="" id="noted_by" class="form-control input-sm">
									  		  				<?php  foreach($signatory as $row): ?>
									  		  					<option value="<?php echo $row['Person Code'] ?>"><?php echo $row['Full Name']; ?></option>
									  		  				<?php  endforeach;?>
									  		  			</select>
									  		  		</div>
									  		  		<div class="form-group">
									  		  			<div class="control-label">Approved By</div>
									  		  			<select name="" id="approved_by" class="form-control input-sm">
									  		  				<?php  foreach($signatory as $row): ?>
									  		  					<option value="<?php echo $row['Person Code'] ?>"><?php echo $row['Full Name']; ?></option>
									  		  				<?php  endforeach;?>
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
									  		<div class="col-md-6">
									  			<div class="form-group">
										  			<div class="control-label">GP Date</div>
										  			<input type="text" class="form-control input-sm" id="date">
									  			</div>
									  		</div>
									  		<div class="col-md-6">
									  			<div class="form-group">
										  			<div class="control-label">GP No</div>
									  				<input type="text" class="form-control input-sm" id="gp_no" readonly>
								  				</div>					  			
									  		</div>
									  	</div>
								  </div>	 
								</div>


								<div class="add-panel" style="display:none">
									<div class="panel panel-default" id="details_content">									
										 	<table class="table table-striped">
												
										 	</table>								
									</div>
									<hr>
								</div>	
																
								<div class="content-title">
									<h3>Item list</h3>
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
				url : 'get_max_dispatchMain',
				dom : $('#gp_no'),
				event : 'change',
			});

			this.get_item();
			this.bindEvents();
			
		},bindEvents:function(){

			$('body').on('click','.action-add',this.add);			
			$('body').on('click','.btn-addToList',this.add_list);
			$('#save').on('click',this.save);
			$('#cost_center').on('change',this.get_item);
			$('body').on('click','.remove',this.remove);
			$('body').on('click','.details',this.item_details);

		},get_item:function(){
			$('.item_content').content_loader('true');			
			var $post = {
				location : $('#location').val(),
				type     : $('#cost_center option:selected').val(),
			};
			$.post('<?php echo base_url().index_page(); ?>/inventory/stock_delivery/get_dispatch',$post,function(response){
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
			$('.added').addClass('listed');
			$('#to_location').val($('.added.listed').find('td.to_location').text());
			$('#equipment_id').val($('.added.listed').find('td.equipment_id').text());
			$('#equipment_request_id').val($('.added.listed').find('td.equipment_request_id').text());

			$('.add-to-list tbody tr').each(function(i,val){
				var data = {
					equipment_request_id : $(val).find('td.equipment_request_id').text(),				
					equipment_id         : $(val).find('td.equipment_id').text(),
					to_location          : $(val).find('td.to_location').text(),
					item_no              : $(val).find('td.Item_No').text(),
					item_description     : $(val).find('td.Item_Description').text(),
					quantity             : $(val).find('td.quantity').text(),
					withdrawn_qty        : $(val).find('td.quantity').text(),					
				}
				withdrawn_list.push(data);
			});
				
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
				dispatch_no   : $('#gp_no').val(),				
				issued_by     : $('#issued_by option:selected').val(),				
				approved_by   : $('#approved_by option:selected').val(),
				date_created  : $('#date').val(),
				remarks       : $('#remarks').val(),
				to_location   : $('#to_location').val(),
				from_location : $('#location').val(),
				request_id    : $('#equipment_request_id').val(),
				from_title_id : $('#project').val(),
				delivered_by  : $('#deliver_by').val(),
				inside : $('input[name="position"]:checked').val(),
				dispatch_type : ($('#equipment_id').val()==0)? 'YES':'NO',
				details : withdrawn_list,				
			};


			$.post('<?php echo base_url().index_page();?>/inventory/stock_delivery/save_delivery',$post,function(response){				
				
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

		},item_details:function(){

			$(this).closest('tr').addClass('added');

			var id = $(this).attr('id');
			$('#details_content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/inventory/stock_delivery/get_item_details/'+id,function(response){
				$('#details_content').html(response);
				$('.add-panel').slideDown();
			});

		}


	}

$(function(){

	app_create.init();

});
</script>