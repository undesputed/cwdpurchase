<input type="hidden" id="tr_date">
<input type="hidden" id="tr_no">

<div class="t-content">
	<div class="t-header">
		<div class="row">
			<div class="col-xs-4">
				
				<h4 id="tr-no"><i class="fa fa-spin fa-spinner"></i></h4>
			</div>
			<div class="col-xs-8">
				<a href="<?php echo base_url().index_page(); ?>/transaction_list/item_transfer/request" class="close"><span aria-hidden="true">&times;</span><span></a>
				<button id="create" class="btn pull-left btn-primary btn-sm">Request List</button>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="t-title">
				<div>Transfer To:</div>
				<select name="" id="request_to" class="form-control">
					<option value="-"> - Select Request Location - </option>
					<?php foreach($profit_center as $row): ?>
					<option value="<?php echo $row['project_id']; ?>"><?php echo $row['project_full_name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>

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
				<th>Transfer Qty</th>
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
				<td><input type="text" class="form-control required request_qty numbers_only" style="width:80px"></td>
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
			
	<div class="row">
		<div class="col-xs-12">
			<div class="t-title">
				<div>Remarks :</div>
				<input type="text" class="form-control required" id="remarks">
			</div>
		</div>		
	</div>
	<hr>
	<div class="row">
		<div class="col-xs-6">
			<div class="t-title">
				<div>Request By :</div>
				<input type="text" class="form-control required" id="request_by">
			</div>
		</div>	
		<div class="col-xs-6">
			<div class="t-title">
				<div>Prepared By : </div> 
				<input type="text" class="form-control" value="<?php echo $this->extra->user(); ?>">
			</div>			
		</div>	
	</div>	
	<div class="row">
		<div class="col-xs-12">
			<button id="save" class="btn btn-primary pull-right">Send</button>
		</div>
	</div>

</div>

<script>
	/*var item_content = eval(<?php echo $item_content; ?>);
	var data = eval(<?php echo $item_value; ?>);*/
	var item_content = '';
	var data = '';
	var item_issuance_app = {
		init:function(){

			$('#tr_date').date({
				url : 'get_max_transfer',
				dom : $('#tr_no'),
				div : $('#tr-no'),
				event : 'change',
			});

			$.signatory({
				prepared_by   : 'sesssion',
				recommended_by: ["4", "5", "1","3"],
				approved_by   : ["4", "4", "1","3"],
			});

			$('.chosen-select').chosen({width:"100%"});

			this.bindEvent();
			item_issuance_app.count();
			
			/*		
			$('.editable').editable('<?php echo base_url().index_page(); ?>/ajax/display', {
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

			$('#request_to').on('change',function(){

				$post = {
					location : <?php echo $this->session->userdata('Proj_Code'); ?>,
				};

				$.post('<?php echo base_url().index_page();?>/ajax/item_request_get_item',$post,function(response){

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
							div +='<td><input type="text" class="form-control required request_qty numbers_only" style="width:80px"></td>';
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
				
			});

			$('#request_to').trigger('change');
						
			$('#create').on('click',this.create);
			$('#add_item').on('click',this.add_item);
			$('body').on('click','.item-close',this.item_close);
			$('#save').on('click',this.save);

		},create:function(){

			$.fancybox.showLoading();
			$.post('<?php echo base_url().index_page();?>/transaction_list/request_list',function(response){

				$.fancybox(response,{
					width     : 600,
					height    : 500,
					fitToView : false,
					autoSize  : false,
				})

			});
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
				div +='<td><input type="text" class="form-control required request_qty numbers_only" style="width:80px"></td>';
			div +='</tr>';

			$('#item_list tbody').append(div);			
			item_issuance_app.count();

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
			item_issuance_app.count();

		},count:function(){			
			$('#item-count').html($('#item_list tbody tr').length);
		},save :function(){
			
			if(item_issuance_app.check()){
				alert('Please select an item');
				return false;
			}

			if($('.required').required())
			{
				return false;
			}

			var bool = confirm('Do you want to Proceed?');
			if(!bool){				
				return false;
			}

			var item_container = new Array();
			$('#item_list tbody tr').each(function(i,val){

				var item = {
					item_no      : $(val).find('.item_no').text(),
					item_description : $(val).find('.item_desc').text(),
					unit_measure : $(val).find('.unit_measure').text(),
					stocks       : $(val).find('.stocks').text(),
					item_cost    : $(val).find('.item_cost').text(),
					request_qty : $(val).find('.request_qty').val(),
					inventory_id : $(val).find('.inventory_id').text(),
				}				
				item_container.push(item);				
			});
			
		

			$post = {
				transfer_no     : $('#tr_no').val(),
				transaction_date: $('#tr_date').val(),
				prepared_by     : $('#prepared_by').val(),
				request_by      : $('#request_by').val(),
				remarks         : $('#remarks').val(),
				to_project_id	: $('#request_to option:selected').val(),
				details         : item_container,
			}
			

			console.log($post);

			$.post('<?php echo base_url().index_page();?>/procurement/item_transfer/save',$post,function(response){
				switch($.trim(response)){
					case "1":
						alert('Successfully Send');
						location.reload('true');
					break;
					default:
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
		item_issuance_app.init();
	});

</script>
