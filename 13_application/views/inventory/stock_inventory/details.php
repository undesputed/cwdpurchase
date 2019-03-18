<div class="header">
	<h2>Stock Inventory</h2>	
</div>

<input type="hidden" id="date"         value="">
<input type="hidden" id="division"     value="<?php echo $post['division'] ?>">
<input type="hidden" id="account_code" value="<?php echo $post['account_code'] ?>">
<input type="hidden" id="location"     value="<?php echo $post['location'] ?>">
<input type="hidden" id="item_id"     value="<?php echo $post['item_id'] ?>">


<div class="container">
	<div class="row">
		<div class="col-md-7">
			<div class="content-title">
				<h3>Transaction History</h3>
			</div>	
			<div class="panel panel-default">
				<?php echo $table; ?>		  
			</div>
		</div>
		<div class="col-md-5">
			<div class="content-title">
				<h3>Form</h3>
			</div>
			<div class="panel panel-default">		
			  	<table class="table table-striped">
					<tbody>
						<tr>
							<td>Item Code</td>
							<td><input type="text" id="item_code" class="form-control input-sm" readonly></td>
						</tr>
						<tr>
							<td>Item Name</td>
							<td><input type="text" id="item_name" class="form-control input-sm" value="<?php echo $post['item_name']; ?>"></td>
						</tr>
						<tr>
							<td>Serial No</td>
							<td><input type="text" id="serial_no" class="form-control input-sm" value="<?php echo $post['serial_no'] ?>"></td>
						</tr>
						<tr>
							<td>Current Quantity</td>
							<td><input type="text" id="current_quantity" class="form-control input-sm" value="<?php echo $post['current_qty']; ?>" readonly></td>
						</tr>
						<tr>
							<td>Max Item Limit</td>
							<td><input type="text" id="maximum_amount" class="form-control input-sm" value="<?php echo $post['max_qty']; ?>"></td>
						</tr>
						<tr>
							<td>Min Item Limit</td>
							<td><input type="text" id="minimum_amount" class="form-control input-sm" value="<?php echo $post['min_qty']; ?>"></td>
						</tr>
						<tr>
							<td>Remarks</td>
							<td>
								<select class="form-control input-sm" id="remarks">
									<option value="on_hand">On Hand</option>
									<option value="out_of_stock">Out of Stock</option>
									<option value="not_serviceable">Not Serviceable</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan='2'>
								<button id="update" class="pull-right btn btn-info btn-sm">Update</button>
							</td>
						</tr>
					</tbody>				
				</table> 
			</div>

		</div>

	</div>	


</div>


<script>
	var app_details = {
		init:function(){
			$('#date').date({
				url : 'get_max_itemCode',
				dom : $('#item_code'),
				event : 'change',
			});
			this.bindEvents();
		},bindEvents:function(){
			$('#update').on('click',this.update);
		},update:function(){
			
			$.save({appendTo : '.fancybox-outer'});

			var $post = {
				item_no          : $('#item_id').val(),
				division_code    : $('#division').val(),
				account          : $('#account_code').val(),
				project_location : $('#location').val(),
				item_code        : $('#item_code').val(),
				maximum_amount   : $('#maximum_amount').val(),
				minimum_amount   : $('#minimum_amount').val(),
				remarks          : $('#remarks option:selected').val(),
				serial_no        : $('#serial_no').val(),
			};


			$.post('<?php echo base_url().index_page();?>/inventory/stock_inventory/update_stock_inventory',$post,function(response){
					switch(response){
						case "1":
							app.get_item();
							$.save({'action':'success'});

						break;
						default:

						break;
					}
			}).error(function(){
				alert('Service Unavailable');
			});
		}
	};

	$(function(){
		app_details.init();
	});

</script>
