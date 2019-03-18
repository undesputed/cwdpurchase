<div class="header">
		<div class="container">	
		<div class="row">
			<div class="col-md-8">
				<h2>Purchase Request </h2>			
			</div>
			<div class="col-md-4">
			</div>
		</div>
	</div>
</div>

<div class="container">
	
	<div class="row">
		<div class="col-md-3">
			<div class="content-title">
				<h3>Request To</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">
			  		<div class="form-group">
			  			<div class="control-label">Project</div>
			  			<select name="" id="create_project" class="form-control input-sm"></select>
			  		</div>

			  		<div class="form-group">
			  			<div class="control-label">Profit Center</div>
			  			<select name="" id="create_profit_center" class="form-control input-sm"></select>
			  		</div>

			  		<div class="form-group" >
			  			<div class="control-label">Division</div>
			  			<select name="" id="division" class="form-control input-sm">
			  				<?php 
			  				 if(count($division)>0):
			  				 foreach($division as $row):
			  				?>
								<option value="<?php echo $row['division_id']; ?>"><?php echo $row['division_name']; ?></option>
			  				<?php 
			  				endforeach;
			  				endif;
			  				?>			  				
			  			</select>
			  		</div>

			  		<input type="hidden" id="account_code">
			  		<div class="form-group" style="display:none">
			  			<div class="control-label">Address</div>			  			
			  			<input type='text' name="" id="location" class="form-control input-sm">
			  		</div>

			  		<input type='text' name="" id="to" class="form-control input-sm" style="display:none">

			  </div>	 
			</div>

		</div>
		<div class="col-md-9">
			<div class="content-title">
				<h3>Item Information</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">
									
			  		<div class="row">
			  			<div class="col-md-4">
				  			<div class="form-group">
					  			<div class="control-label">Item Name</div>
					  			<select name="" id="item_name" class="form-control input-sm"></select>
				  			</div>
			  			</div>
			  			<div class="col-md-4">
				  			<div class="form-group" style="display:none">
					  			<div class="control-label">Item Category</div>
					  			<select name="" id="item_category" class="form-control input-sm"></select>
				  			</div>
							
							<div class="form-group">
								<div class="control-label">Unit of Measure</div>
								<span><strong id="dp-unit"></strong></span>
							</div>
			  			</div>
			  			<div class="col-md-2">
				  				<div class="form-group">
						  			<div class="control-label">Date</div>
						  			<input type="text" name="" id="date" class="form-control input-sm date" readonly>
					  			</div>
			  			</div>
			  			<div class="col-md-2">
				  				<div class="form-group">
						  			<div class="control-label">PR No.</div>
						  			<input type="text" name="" id="pr_no" class="form-control input-sm" readonly>
					  			</div>
			  			</div>
			  		</div>
			  		<div class="row">			  			
			  			<div class="col-md-2">
				  			<div class="form-group">
					  			<div class="control-label">Model No</div>
					  			<input type="text" name="" id="model_no" class="form-control input-sm">
				  			</div>
			  			</div>
			  			<div class="col-md-2">
				  			<div class="form-group">
					  			<div class="control-label">Serial No</div>
					  			<input type="text" name="" id="serial_no" class="form-control input-sm">
				  			</div>
			  			</div>
			  			<div class="col-md-3">
				  			<div class="form-group">
					  			<div class="control-label">Remarks</div>
					  			<input type="text" name="" id="remarks" class="form-control input-sm">
				  			</div>
			  			</div>
			  			<div class="col-md-2">
				  			<div class="form-group">
					  			<div class="control-label">Quantity</div>
					  			<input type="text" name="" id="quantity" class="form-control input-sm numbers_only">
				  			</div>
			  			</div>
			  			<div class="col-md-3">
			  				<button id="add" class="btn btn-primary nxt-btn">Add Item</button>
			  			</div>
			  		</div>
			  		<!--
			  		<div class="row">
						<div class="col-sm-5">
							<div class="form-group">
						  			<div class="control-label">Pay Item</div>
						  			<select name="" id="pay_item" class="form-control input-sm">
						  				<?php foreach($pay_center as $row): ?>
											<option value="<?php echo $row['id']; ?>"><?php echo $row['paycenter']; ?></option>
						  				<?php endforeach;?>
						  			</select>
					  		</div>	
						</div>
					</div>
					-->
			  </div>	 
			</div>

		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
								
				<div class="panel panel-default">		
				  <table id="item_add" class="table table-striped">
				  	<thead>
				  		<tr>
				  			<th>Item</th>
				  			<th>Item Description</th>
				  			<th>Qty</th>
				  			<th>Unit Of Measure</th>
				  			<th>Model No</th>
				  			<th>Serial No</th>
				  			<th>Remarks</th>
				  			<th></th>
				  		</tr>
				  	</thead>
				  	<tbody>
				  		<tr>
				  			<td colspan='8'>No Data</td>				  		
				  		</tr>
				  	</tbody>
				  </table>
				</div>

		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">		
			  <div class="panel-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
					  			<textarea name="" id="pr_remarks" cols="30" rows="5" class="form-control input-sm" placeholder="Remarks"></textarea>
					  			
					  		</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
					  			<div class="control-label">Prepared by</div>
					  			<select name="prepared_by" id="prepared_by" class="form-control input-sm"></select>
					  		</div>
					  		<div class="form-group">
					  			<div class="control-label">Recommended By</div>
					  			<select name="recommended_by" id="recommended_by" class="form-control input-sm"></select>
					  		</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
					  			<div class="control-label">Priority</div>
					  			<div class="radio-inline">
					  				<input type="radio" id="priority" name="legend" value="1.)Priority / Emergency"><label for="priority">1.)Priority / Emergency</label>
					  			</div>
					  			<div class="radio-inline">
					  				<input type="radio" id="regular" name="legend" value="2.)Regular" checked><label for="regular">2.)Regular </label>
					  			</div>
					  			<input type="text" class="date">
					  		</div>

							<div class="form-group">
					  			<div class="control-label">Approved By</div>
					  			<select name="approved_by" id="approved_by" class="form-control input-sm"></select>
					  		</div>

						</div>
					</div>			  		
			  </div>	 

			  <div class="form-footer">			  	
					<div class="row">
						<div class="col-md-8"> </div>
						<div class="col-md-4">
							<input id="save" class="btn btn-success  col-xs-5 pull-right" type="submit" value="Save">
							<!-- <input id="reset" class="btn btn-link  pull-right" type="reset" value="Reset"> -->
						</div>
					</div>					
			  </div>
			</div>
		</div>
	</div>


</div>

<script type="text/javascript">

	var DATA = [];
	var app = {
		init:function(){

			$('#date').date({
				url : 'get_pr_code',
				dom : $('#pr_no'),
				event : 'change',
			});

			$('.date').date();
			
			var option = {
				profit_center : $('#create_profit_center')
			}			

			$('#create_project').get_projects(option);			
			
			this.bindEvents();
			this.get_item();

			var profit_center_value = '';
			if(typeof ($('#create_profit_center option:selected').val()) != 'undefined'){
				 profit_center_value = $('#create_profit_center option:selected').val();
			}
			
			$.signatory({
				prepared_by   : 'sesssion',
				recommended_by: ["4", "5", "1",profit_center_value],
				approved_by   : ["4", "4", "1",profit_center_value],
			});
			
		},bindEvents:function(){
			$('#item_name').on('change',this.get_category);
			$('#add').on('click',this.add);
			$('#item_add').on('click','.remove',this.remove_data);
			$('#save').on('click',this.save);
			$('#create_profit_center').on('change',this.location);
		},location:function(){
			
			$('#location').val($('#create_profit_center option:selected').data('location'));
			$('#to').val($('#create_profit_center option:selected').data('to'));

		},get_item:function(){

			$.get('<?php echo base_url().index_page(); ?>/procurement/purchase_request/get_items',function(response){
				$('#item_name').select({
					json : response,
					attr : {
							text  : 'description',
							value : 'group_id',
					},
					data : {
						'data-group-detail-id' : 'group_detail_id',						 
						'data-account_id'      : 'account_id',
						'data-unit-cost'	   : 'unit_cost',
						'data-unit'            : 'unit_measure',
					}					
				});
				$('#item_name').chosen();
				app.get_category();
			},'json');

		},get_category:function(){

			$('#dp-unit').html($('#item_name option:selected').attr('data-unit'));

			$post = {
				group_id : $('#item_name option:selected').val(),
			}
			$.post('<?php echo base_url().index_page();?>/procurement/purchase_request/get_category',$post,function(response){
				 $('#item_category').select({
				 	json : response,
				 	attr : {
				 		   text  : 'group_description',
				 		   value : 'group_id',
				 	}
				 });

				 var account_id = $('#item_name option:selected').attr('data-account_id');				
				$('#account_description').find('option').each(function(i,value){
					if($(value).data('account_id') == account_id){
						$(value).attr({'selected':'selected'});
						$('#account_description').trigger('change');
					}else{
						$(value).removeAttr('selected');
					}
				});

			},'json');


			app.get_incomeAcct();

		},get_incomeAcct:function(){
			
			$post = {
				account_id : $('#item_name option:selected').attr('data-account_id'),
			}
			$.post('<?php echo base_url().index_page();?>/procurement/purchase_request/get_incomeAcct',$post,function(response){
				$('#account_code').val($.trim(response));
			});



		},add:function(){
				var model_no  = $('#model_no').val();
				var serial_no = $('#serial_no').val();
				var quantity  = $('#quantity').val();
				
				if(quantity ==''){
						alert('No Quantity');
					return false;					
				}else if($('#item_name').children().length == 0){
						alert('No Item Selected');
					return false;
				}
				
				var data = {
					'item_no'          : $('#item_name option:selected').data('group-detail-id'),
					'item_description' : $('#item_name option:selected').text(),
					'qty'              : comma($('#quantity').val()),
					'model_no'         : $('#model_no').val(),
					'serial_no'        : $('#serial_no').val(),
					'unit' 		       : $('#dp-unit').text(),
					'remarks'          : $('#remarks').val(),
					'groupID'          : $('#item_name option:selected').val(),
					'unit_cost'        : $('#item_name option:selected').data('unit-cost'),
				}
				
				DATA.push(data);			
				
				$('#model_no').val('');
				$('#serial_no').val('');
				$('#quantity').val('');
				$('#remarks').val('');
				
				app.render();
		},render:function(){
			if(DATA.length>0){
				$('#item_add tbody').html('');
				$.each(DATA,function(i,value){
									var content  ="<tr>";
										content +="<td>"+value.item_no+"</td>";
										content +="<td>"+value.item_description+"</td>";
										content +="<td>"+value.qty+"</td>";
										content +="<td>"+value.unit+"</td>";
										content +="<td>"+value.model_no+"</td>";
										content +="<td>"+value.serial_no+"</td>";
										content +="<td>"+value.remarks+"</td>";
										content +="<td><a href='javascript:void(0)' class='remove' data-id='"+i+"'>Remove</a></td>";
										content +="</tr>";
					$('#item_add tbody').append(content);
				})
			}else{
					$('#item_add tbody').html('<tr><td colspan="7">No Data</td></tr>');
			}
		},remove_data:function(){
					var bool = confirm('Are you Sure?');
					if(bool){
						var id = $(this).data('id');
						DATA.splice(id,1);
						app.render();
					}
		},save:function(){
				if(DATA.length<=0){
					alert('No Items Added');
					return false;
				}
				
				
				var bool = confirm('Are you sure to Proceed?');
				if(!bool){
					return false;
				}
				
				var	$post = {
					'purchaseNo'     : $('#pr_no').val(),
					'purchaseDate'   : $('#date').val(),
					'department'     : $('#division option:selected').val(),
					'legend'         : $('input[name="legend"]:checked').val(),
					'accountCode'    : $('#account_code').val(),
					'approvedBy'     : $('#approved_by option:selected').val(),
					'prepared_by'    : $('#prepared_by option:selected').val(),
					'recommendedBy'  : $('#recommended_by option:selected').val(),
					'project_id'     : $('#create_profit_center option:selected').val(),
					'title_id'       : $('#create_project option:selected').val(),
					'location'       : $('#location').val(),
					'to_'            : $('#to').val(),
					'data'           : DATA,
					'pr_remarks'     : $('#pr_remarks').val(),
					'account_id'     : $('#item_name option:selected').attr('data-account_id'),
					'requiredDate'   : $('.date').val(),
				};	

				$.save({appendTo : '.fancybox-outer'});			
				
				$.post('<?php echo base_url().index_page(); ?>/procurement/purchase_request/save_purchaseRequest',$post,function(response){					
					switch(response){
						case "success":
							$.save({action : 'success',reload : 'true'});
						break;
						default:
							$.save({action : 'error',reload : 'false'});
						break;
					}
				},'json').error(function(){
					$.save({action : 'error'});
				});
		}
	}

$(function(){
	app.init();
});
</script>