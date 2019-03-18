<input type="hidden" id="ws_date">
<input type="hidden" id="ws_no">

<div class="t-content">

	<div class="t-header">
		<a href="<?php echo base_url().index_page(); ?>/transaction_list/item_withdrawal" class="close close-info"><span aria-hidden="true">&times;</span><span></a>
		<h4 id="ws-no"><i class="fa fa-spin fa-spinner"></i></h4>
	</div>
	
	<div class="table-responsive">
	<table id="item_list" class="table table-item">
		<thead>
			<tr>
				<th style="width:10px"></th>
				<th style="display:none">Inventory_id</th>
				<th style="display:none">Item No</th>
				<th>Item Description</th>
				<th>Unit Measure</th>
				<th>Stocks</th>
				<th style="display:none">Item Cost</th>
				<th>Withdrawn Qty</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><span class="close item-close"><span aria-hidden="true">&times;</span></span></td>
				<td style="display:none" class="inventory_id"></td>
				<td  style="display:none" class="item_no"></td>
				<td><span id="item_select" class="btn-link editable item_desc">Click to select item</span></td>
				<td class="unit_measure"></td>
				<td class="stocks"></td>
				<td style="display:none" class="item_cost"></td>
				<td><input type="text" class="form-control required withdraw_qty numbers_only" style="width:80px"></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th></th>
				<th></th>				
				<th>
					<div><span id="item-count"></span> item(s)</div>
					<div style="margin-top:5px;"><a href="javascript:void(0)" id="add_item">+ Add Item</a></div>
				</th>
				<th></th>
				<th></th>				
							
			</tr>			
		</tfoot>
	</table>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="t-title">
				<div>Remarks/Purpose :</div>
				<input type="text" class="form-control" id="remarks">
			</div>
		</div>		
	</div>
	<div class="row">
		<div class="col-xs-6">
			<div>Tenant:</div>
			<select name="" id="tenant">
				<option value="-">No Tenant</option>
				<?php foreach($tenants as $row): ?>
					<option value="<?php echo $row['id'] ?>"><?php echo $row['full_name']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-xs-6"></div>
	</div>
	<hr>
	<div class="row">
		<div class="col-xs-6">
			<div class="t-title">
				<div>Requested By : </div> 
				<select name="" id="requested_by" class="form-control">	
				<?php foreach($requested_by as $row): ?>
					<option value="<?php echo $row['emp_number'] ?>"><?php echo $row['Person Name']; ?></option>
				<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="t-title">
				<div>Approved By : </div>
				<select name="" id="approved_by" class="form-control">
				<?php foreach($approved_by as $row): ?>
					<option value="<?php echo $row['emp_number'] ?>"><?php echo $row['Person Name']; ?></option>
				<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<button id="save" class="btn btn-primary pull-right">Save</button>
		</div>
	</div>
		
</div>

<script>
	/*var item_content = eval(<?php echo $item_content; ?>);
	var data = eval(<?php echo $item_value; ?>);*/
	var item_content = '';
	var data = '';
	var xhr = "";
	var item_withdrawal_app = {
		init:function(){

			$('#ws_date').date({
				url : 'get_max_withdrawal',
				dom : $('#ws_no'),
				div : $('#ws-no'),
				event : 'change',
			});

			$('.chosen-select').chosen({width:"100%"});

			this.bindEvent();
			item_withdrawal_app.count();
			
					
			/*$('.editable').editable('<?php echo base_url().index_page(); ?>/ajax/display', {
			     data    : data,
			     type    : 'select',
			     submit  : 'OK',
			     style   : 'inherit',
			     callback: function (value,settings){
			     	$(this).html(item_content[value].item_name);
			     	$(this).closest('tr').find('.item_no').html(value);
			     	$(this).closest('tr').find('.item_cost').html(item_content[value].item_cost)
			     	$(this).closest('tr').find('.unit_measure').html(item_content[value].unit_measure);
			     	$(this).closest('tr').find('.stocks').html(item_content[value].stocks)
			     	$(this).closest('tr').find('.inventory_id').html(item_content[value].inventory_id)
				 }
			});*/

		},bindEvent:function(){

				$.post('<?php echo base_url().index_page();?>/ajax/item_withdraw_get_item',$post,function(response){

					data         = response.item_value;
					item_content = response.item_content;

						var div = "";

						div +='<tr>';
							div +='<td><span class="close item-close"><span aria-hidden="true">&times;</span></span></td>';
							div +='<td style="display:none" class="inventory_id"></td>';
							div +='<td  style="display:none" class="item_no"></td>';
							div +='<td><span id="item_select" class="btn-link editable item_desc">Click to select item</span></td>';
							div +='<td class="unit_measure"></td>';
							div +='<td class="stocks"></td>';
							div +='<td style="display:none" class="item_cost"></td>';
							div +='<td><input type="text" class="form-control required withdraw_qty numbers_only" style="width:80px"></td>';
						div +='</tr>';

					$('#item_list tbody').html(div);

					$('.editable').editable('<?php echo base_url().index_page(); ?>/ajax/display', {
					     data    : data,
					     type    : 'select',
					     submit  : 'OK',
					     style   : 'inherit',
					     callback: function (value,settings){

					     	console.log(item_content[value]);
					     	console.log(item_content[value].item_name);

					     	$(this).html(item_content[value].description);
					     	$(this).closest('tr').find('.item_no').html(value);
					     	$(this).closest('tr').find('.item_cost').html(item_content[value].item_cost)
					     	$(this).closest('tr').find('.unit_measure').html(item_content[value].unit_measure);
					     	$(this).closest('tr').find('.stocks').html(item_content[value].stocks)
					     	$(this).closest('tr').find('.inventory_id').html(item_content[value].inventory_id)
						 }
					});

				},'json');
				

			
			$('#add_item').on('click',this.add_item);
			$('body').on('click','.item-close',this.item_close);
			$('#save').on('click',this.save);

		},add_item:function(){

			var div = "";

			div +='<tr>';
				div +='<td><span class="close item-close"><span aria-hidden="true">&times;</span></span></td>';
				div +='<td style="display:none" class="inventory_id"></td>';
				div +='<td  style="display:none" class="item_no"></td>';
				div +='<td><span id="item_select" class="btn-link editable item_desc">Click to select item</span></td>';
				div +='<td class="unit_measure"></td>';
				div +='<td class="stocks"></td>';
				div +='<td style="display:none" class="item_cost"></td>';
				div +='<td><input type="text" class="form-control required withdraw_qty numbers_only" style="width:80px"></td>';
			div +='</tr>';

			$('#item_list tbody').append(div);			
			item_withdrawal_app.count();

			$('.editable').editable('<?php echo base_url().index_page(); ?>/ajax/display', {
			     data    : data,
			     type    : 'select',
			     submit  : 'OK',
			     style   : 'inherit',
			     callback: function (value,settings){
			     	$(this).html(item_content[value].description);
			     	$(this).closest('tr').find('.item_no').html(value);
			     	$(this).closest('tr').find('.item_cost').html(item_content[value].item_cost)
			     	$(this).closest('tr').find('.unit_measure').html(item_content[value].unit_measure);
			     	$(this).closest('tr').find('.stocks').html(item_content[value].stocks)
			     	$(this).closest('tr').find('.inventory_id').html(item_content[value].inventory_id)
				 }
			});

			$('.chosen-select').chosen({width:"100%"});

		},item_close:function(){

			$(this).closest('tr').remove();
			item_withdrawal_app.count();

		},count:function(){			
			$('#item-count').html($('#item_list tbody tr').length);
		},save :function(){
			
			if(item_withdrawal_app.check()){
				alert('Please select an item');
				return false;
			}

			if($('.required').required())
			{
				return false;
			}

			
			var item_container = new Array();
			var withdraw_check = false;
			$('#item_list tbody tr').each(function(i,val){

				var item = {
					item_no      	 : $(val).find('.item_no').text(),
					item_description : $(val).find('.item_desc').text(),
					unit_measure 	 : $(val).find('.unit_measure').text(),
					stocks       	 : $(val).find('.stocks').text(),
					item_cost    	 : $(val).find('.item_cost').text(),
					withdraw_qty 	 : $(val).find('.withdraw_qty').val(),
					inventory_id 	 : $(val).find('.inventory_id').text(),
				}
				
				if(parseInt(item.withdraw_qty) > parseInt(item.stocks)){
					withdraw_check = true;
				}

				item_container.push(item);
				
			});

			if(withdraw_check){
				alert('Please Check Withdrawal Qty');
				return false;
			}

			var bool = confirm('Do you want to Proceed?');

			if(!bool){
				return false;
			}

			$.save({appendTo : 'body'});

			$post = {
				withdraw_no    			   : $('#ws_no').val(),
				withdraw_person_id         : $('#requested_by option:selected').val(),
				withdraw_person_incharge   : $('#approved_by option:selected').val(),
				date_withdrawn  		   : $('#ws_date').val(),
				remarks        			   : $('#remarks').val(),
				location       			   : "<?php echo $this->session->userdata('Proj_Code'); ?>",
				title_id       			   : "<?php echo $this->session->userdata('Proj_Main'); ?>",
				details        			   : item_container,
				tenant_id                  : $('#tenant option:selected').val(),
				tenant_name                : $('#tenant option:selected').text(),
			}
	        if(xhr && xhr.readystate != 4){
	            xhr.abort();
	        }
			xhr = $.post('<?php echo base_url().index_page();?>/inventory/stock_withdrawal/save_withdrawal2',$post,function(response){
				switch($.trim(response)){
					case "1":
						$.save({action : 'success',reload : 'true'});
						alert('Successfully Save');
						updateContent();
						/*location.reload('true');*/
					break;
					default:
						$.save({action : 'error',reload : 'false'});
						alert('Internal Server Error');
					break;
				}
			});
					
		},check:function(){
			if($('#item_list tbody tr td.stocks').html()==''){
				return true;
			}else{
				return false;
			}
		}
	}

	$(function(){
		item_withdrawal_app.init();
	});

</script>
