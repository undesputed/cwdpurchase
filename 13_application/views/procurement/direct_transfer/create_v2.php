<div class="content-page">
	<div class="content">
<div class="header">
	<h2>New Direct Transfer</h2>
</div>

<div class="container">
	
	<div class="row">
		<div class="col-md-4">
			<div class="content-title">
				<h3>Location</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">

			  		<div class="form-group" style="display:none">
			  			<div class="control-label">Company Name</div>
			  			<select name="" id="create_project" class="form-control input-sm"></select>
			  		</div>

			  		<div class="form-group">
			  			<div class="control-label">Project To </div>
			  			<select name="" id="create_profit_center" class="select2" style="width:100%;"></select>					  			
		  			</div>

			  		<input type="hidden" id="account_code">			
			  		<input type='text' name="" id="to" class="form-control input-sm" style="display:none">
					
			  </div>	 
			</div>

		</div>
		<div class="col-md-8">
			<div class="content-title">
				<h3>Item Information</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">					
			  		<div class="row">
			  			<div class="col-md-7">
				  			<div class="form-group">
					  			<div class="control-label">Item Name</div>
					  			<input type="text" name="" id="item_name" style="width:100%" placeholder="Search item Description">
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
					  			<div class="control-label">Quantity</div>
					  			<input type="number" name="" id="quantity" class="form-control input-sm numbers_only">
				  			</div>
			  			</div>	
			  			
			  			<div class="col-md-2" style="float:right;">
			  				<button id="add" class="btn btn-sm btn-primary nxt-btn">Add</button>
			  			</div>
			  		</div>
			  </div>	 
			</div>

		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
								
				<div class="panel panel-default">
				<div class="table-responsive">
				  <table id="item_add" class="table table-striped">
				  	<thead>
				  		<tr>
				  			<th>Item Description</th>
				  			<th>Unit Of Measure</th>
				  			<th>Qty</th>
				  			<th></th>
				  		</tr>
				  	</thead>
				  	<tbody>
				  		<tr>
				  			<td colspan='4'>No Data</td>				  		
				  		</tr>
				  	</tbody>
				  	<tfoot>
				  		<tr>
				  			<td><span id='cnt-items'></span> item(s)</td>
				  			<td></td>
				  			<td></td>
				  			<td></td>
				  		</tr>
				  	</tfoot>
				  </table>
				</div>
				</div>

		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">		
			  <div class="form-footer">			  	
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
					  			<textarea name="" id="dt_remarks" cols="30" rows="5" class="form-control input-sm" placeholder="Remarks"></textarea>					  			
					  		</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
					  			<div class="control-label">Prepared by</div>
					  			<select name="prepared_by" id="prepared_by" class="form-control input-sm"></select>
					  		</div>
							<input id="save" class="btn btn-success  col-xs-5 pull-left" type="submit" value="Save">
						</div>
					</div>					
			  </div>
			</div>
		</div>
	</div>

</div>

</div>
</div>

<script type="text/javascript">	
	var check_item = [];
	var	xhr = '' ;
	var DATA = [];
	var app = {
		init:function(){

			$('.select2').select2();
			$('.date').date();
			
			var option = {
				profit_center : $('#create_profit_center'),
				main_office : true,
			}			

			$('#create_project').get_projects(option);
			
			this.bindEvents();
			this.get_item();

			var profit_center_value = '';
			if(typeof ($('#create_profit_center option:selected').val()) != 'undefined'){
				 profit_center_value = $('#create_profit_center option:selected').val();
			}
			
			$.signatory({
				type          : 'rr',
				prepared_by   : 'sesssion'
			});
			
		},bindEvents:function(){

			$('#add').on('click',this.add);
			$('#item_add').on('click','.remove',this.remove_data);
			$('#save').on('click',this.save);
			$('#create_profit_center').on('change',this.location);

		},location:function(){
			
			$('#location').val($('#create_profit_center option:selected').data('location'));
			$('#to').val($('#create_profit_center option:selected').data('to'));
		},get_item:function(){
			$("#item_name").select2({
		     	 	placeholder: "Search Items Here",
		     	 	allowClear: true,
				    ajax: {
				        url: '<?php echo site_url("procurement/purchase_request/get_items"); ?>',
				        dataType: 'json',
				        type: "GET",
				        quietMillis: 50,
				        data: function (term) {
				            return {
				                q: term
				            };
				        },				     
				        results: function (data){
				            return {
				                results: $.map(data, function (item) {					                              
				                    return {
				                        text: item.description,
				                        id  : item.group_detail_id,
				                        group_id : item.group_id,
				                        account_id : item.account_id,
				                        unit_cost : item.unit_cost,
				                        unit      : item.unit_measure,
				                        desc : item.item_description
				                    }
				                })
				            };
				        }
				    },initSelection: function (element, callback){
				         	var id = $(element).val();
					}
			});
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

		},add:function(){

				var qty = $('#quantity').val();
				var item = $("#item_name").select2('data');

				if(qty ==''){
						alert('No Quantity');
					return false;					
				}else if(item == null){
						alert('No Item Selected');
					return false;
				}

				var data = {
					'item_id'             : item.id,
					'item_description'    : item.desc,
					'unit_measure'        : item.unit,
					'quantity'            : $('#quantity').val(), 
				};

				if(jQuery.inArray(data.item_description, check_item) !== -1){
					alert('Item Already in the list');				
					return false;
				}
				
				check_item.push(data.item_no);

				DATA.push(data);
				
				$('#item_name').val('');
				$('#quantity').val('');

				app.render();

		},render:function(){
			if(DATA.length>0){
				$('#item_add tbody').html('');

				$.each(DATA,function(i,value){
									var content  ="<tr>";
										content +="<td>"+value.item_description+"</td>";
										content +="<td>"+value.unit_measure+"</td>";
										content +="<td>"+value.quantity+"</td>";
										content +="<td><a href='javascript:void(0)' class='remove' data-id='"+i+"'>Remove</a></td>";
										content +="</tr>";
					$('#item_add tbody').append(content);
				})
				$('#cnt-items').html(DATA.length);

				/*if($(DATA).toArray().length == 1){
					var amount = $('#amount').val();
					$('#total_amount').html(amount);
				}else if($(DATA).toArray().length >= 2){
					var temp = $('#total_amount').html();
					var amount = $('#amount').val();
					var total_amount = 0;
					total_amount = parseFloat(temp) + parseFloat(amount);
					$('#total_amount').html(total_amount);
				}*/

			}else{
				$('#item_add tbody').html('<tr><td colspan="4">No Data</td></tr>');
				$('#cnt-items').html('');
			}
		},remove_data:function(){
					var bool = confirm('Are you Sure?');
					if(bool){
						var id = $(this).data('id');
						
						DATA.splice(id,1);
						check_item.splice(id,1);

						/*total = parseFloat(total) - parseFloat(amount);

						$('#total_amount').html(total);*/

						app.render();
					}
		},save:function(){

				if($('.required').required()){
					alert('Required Fields');
					return false;
				}


				if(DATA.length <= 0){
					alert('No Items Added');
					return false;
				}
				
				var bool = confirm('Are you sure to Proceed?');
				if(!bool){
					return false;
				}
				
				var	$post = {
					'Date'           : $('#date').val(),
					'project_id'     : $('#create_profit_center option:selected').val(),
					'title_id'       : $('#create_project option:selected').val(),
					'data'           : DATA,
					'remarks'        : $('#dt_remarks').val(),
					'prepared_by'    : $('#prepared_by option:selected').val()
				};
								
				$.save({appendTo : 'body'});

		        if(xhr && xhr.readystate != 4){
		            xhr.abort();
		        }

				xhr = $.post('<?php echo base_url().index_page(); ?>/procurement/purchase_request/save_transfer',$post,function(response){					
					switch(response){

						case "success":						   	
							$.save({action : 'success',reload : 'true'});
							window.location = '<?php echo base_url().index_page(); ?>/transaction_list/item_transfer/request'
						break;

						default:
						    alert(response);
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