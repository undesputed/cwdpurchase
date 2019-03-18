<style>	
	.total{
		font-weight: 100;
		font-size:11px;
	}
	.sub-total{
		font-weight: bold;
	}
	.grand-total{
		font-size:12px;
	}
	#boq-table input{
		height:20px !important;
	}
	.contract_t{
		width: 8em;
		float: left;
	}
	#contract_amt{
		border-bottom: 1px solid #666;
		min-width:100px!important;
		float: left;
		text-align: center;
		min-height: 20px;
	}
</style>

	
	<div class="panel panel-default">
	  <div class="panel-body">
		<div class="row">
			<div class="col-xs-12">
				<select name="" id="project_site" >
		  			<?php foreach($project_site as $row): ?>
			  			<option value="<?php echo $row['project_id']; ?>"><?php echo $row['project_full_name']; ?></option>
		  			<?php endforeach; ?>
		  		</select>		  		
		  		<select name="" id="main-category" >
		  			<?php foreach($project_category as $row): ?>
			  			<option value="<?php echo $row['id']; ?>"><?php echo $row['project_name']; ?></option>
		  			<?php endforeach; ?>
		  		</select>
		  		<select name="" id="floor">
		  			<?php foreach($floor as $row): ?>
						<option value="<?php echo $row['flr_id']; ?>"><?php echo $row['flooring']; ?></option>
		  			<?php endforeach; ?>
		  		</select>

		  		<div style="float:right">
		  			<div class="contract_t">Contract Amount:</div>					
		  			<div id="contract_amt"></div>					
		  		</div>

			</div>					
		</div>
	  </div>	 
	</div>
	
	
	<div class="table-responsive">
	<table id="boq-table" class="table table-condensed">
		<thead>
			<tr>
				<td rowspan="2" style="width:10px"></td>
				<td rowspan="2" style="width:100px">Item No.</td>	
				<td rowspan="2" style="width:400px">Item Descriptions</td>			
				<td rowspan="2">Qty</td>	
				<td rowspan="2">Unit</td>	
				<td colspan="4">Unit Cost</td>	
				<td rowspan="2">Amount</td>	
			</tr>
			<tr>				
				<td>Material Price</td>
				<td>Labor</td>
				<td>Others</td>
				<td>Total</td>
			</tr>
		</thead>
		<tbody>
			
			<tr class="add-row sub-total">
				<td></td>
				<td><a href="javascript:void(0)" class="add_row_main">add main</a></td>
				<td></td>
				<td colspan="2" style="text-align:center"> Grand Total ====</td>
				
				<td></td>
				<td></td>
				<td></td>
				<td id="grand_total_total"  class="td-number  grand-total"></td>
				<td id="grand_total_amount" class="td-number grand-total"></td>
				
			</tr>
		</tbody>

	</table>
	</div>

	<div class="panel panel-default">
	  <div class="panel-body">
		<div class="row">		
			<div class="col-xs-10"></div>	
			<div class="col-xs-2">
				<button class="pull-right form-control btn btn-primary " id="save">Save</button>	
			</div>			
		</div>
	  </div>	 
	</div>

	
<script>
	$(function(){

		
		var main  = eval(<?php echo $main_category; ?>);
		var main_data = eval(<?php echo $main_category_data; ?>);
		var sub   = eval(<?php echo $sub_category; ?>);
		var sub_data = eval(<?php echo $sub_category_data; ?>);
		var item_list = eval(<?php echo $item_list; ?>);
		var item_list_data = eval(<?php echo $item_list_data; ?>);

	
	
		var row_list = new Array();

		var assign_editable = function(type,td_id,data){
			
			switch(type){
				case "main":										
					row_list[td_id.main_id] = $.extend({}, row_list[td_id.main_id],data);
				break;
				case "sub":
					row_list[td_id.main_id].sub[td_id.sub_id] = $.extend({}, row_list[td_id.main_id].sub[td_id.sub_id],data);
				break;
				case "item":
					row_list[td_id.main_id].sub[td_id.sub_id].items[td_id.item_id] = $.extend({},row_list[td_id.main_id].sub[td_id.sub_id].items[td_id.item_id],data);
				break;
			}		
		}

		var return_editable = function(type,td_id){
			switch(type){
				case "main":										
					return row_list[td_id.main_id];
				break;
				case "sub":
					return row_list[td_id.main_id].sub[td_id.sub_id];
				break;					
				case "item":
					return row_list[td_id.main_id].sub[td_id.sub_id].items[td_id.item_id];
				break;
			}
		}

		var xhr = "";

		var transaction_app = {
			init:function(){
				
				/*
				var option = {
					profit_center : $('#create_profit_center')
				}
				$('#create_project').get_projects(option);
				*/
			   this.bindEvent();
			   	
			},bindEvent:function(){

				
				$('#contract_amt').editable(function(value,setings){
						$post = {
							value : value,
							project_category :  $('#main-category option:selected').val(),
						};
						$.post('<?php echo base_url().index_page();?>/boq/save_contract',$post,function(response){
							
						});
						return value;
					},{						 
						placeholder : '0.00',

					});


				$('#main-category').on('change',function(){
					$post = {
						ref_id : $('#main-category option:selected').val(),
						floor_id : $('#floor option:selected').val(),
						project_site : $('#project_site option:selected').val(),
					};
										
			        if(xhr && xhr.readystate != 4){
			        	$("#main-category + span").remove();
			            xhr.abort();
			        }
			        $('#floor').after('<span style="margin-left:5px"><i class="fa fa-spinner fa-spin"></i></span>');
					xhr = $.post('<?php echo base_url().index_page();?>/boq/boq_data',$post,function(response){
								$("#floor + span").remove();
								row_list = response.result;	
								console.log(response.contract_amt);
								if(response.contract_amt != "" || response.contract_amt != "NULL"){
									$('#contract_amt').html(response.contract_amt);	
								}
								
								transaction_app.render();
					},'json');

				});

				$('#project_site').on('change',function(){
					$('#main-category').trigger('change');					
				});


				$('#main-category').trigger('change');

				$('#floor').on('change',function(){
					$('#main-category').trigger('change');					
				});

				$('.add_row_main').on('click',this.add_row);
				$('body').on('click','.close_row',this.close_row);
				$('body').on('click','.add_sub',this.add_sub);
				$('body').on('click','.add_items',this.add_items);
				$('#save').on('click',this.save);

				$('body').on('hover','.main-editable',function(){
					var me  = $(this);
					var main_id = me.closest('tr').attr('data-id');		

					$(this).editable(function(value,settings){

						row_list[main_id].main_title = value;
					    row_list[main_id].main_id    = '';
					    transaction_app.render();
					    return value;

					},{						 
						placeholder : ''					     
					});
				});


				$('body').on('hover','.sub-editable',function(){
					var me  = $(this);
					var main_id = me.closest('tr').attr('data-id');	
					var sub_id  = me.closest('tr').attr('data-sub_id');		
					
					$(this).editable(function(value,settings){
						row_list[main_id].sub[sub_id].main_title = value;	
						row_list[main_id].sub[sub_id].main_id = ''
						transaction_app.render();
						return value;
					},{											    
					     placeholder : '',
					});

				});

				$('body').on('hover','.item-editable',function(){

					var me  = $(this);
					var main_id = me.closest('tr').attr('data-id');
					var sub_id  = me.closest('tr').attr('data-sub_id');
					var item_id = me.closest('tr').attr('data-items_id');

					$(this).editable(function(value,settings){

						row_list[main_id].sub[sub_id].items[item_id].main_title = value;	
						row_list[main_id].sub[sub_id].items[item_id].main_id    = item_list_data[value].id;
						row_list[main_id].sub[sub_id].items[item_id].unit       = item_list_data[value].unit_measure;

						transaction_app.render();

						return value;

					},{
						 data    : item_list,
					     type    : 'select',
					     submit  : 'OK',
					     style   : 'inherit',
					     placeholder : ''
					});
					
				});

				$('body').on('hover','.editable',function(){
					var me = $(this);
					var td_id = {
						main_id : me.closest('tr').attr('data-id'),	
						sub_id  : me.closest('tr').attr('data-sub_id'),
						item_id : me.closest('tr').attr('data-items_id'),	
					}
										
					var type = '';
					if(typeof(td_id.item_id) == 'undefined')
					{
						if(typeof(td_id.sub_id) == 'undefined')
						{							
							type = 'main';
						}else{							
							type = 'sub';
						}
					}else{
							type = 'item';
					}

					$(this).editable(function(value,settings){

							me.closest('tr').find('td.total');

							console.log(type,td_id);							
							var obj = return_editable(type,td_id);
							console.log(obj);

							var entry  = {
								qty      : '',
								unit     : '',
								material : '',
								labor    : '',
								others   : '',
								total    : '',
							}
							$.extend(true,entry,obj);

							value = remove_comma(value);

							if(me.hasClass('qty')){									
									entry.qty = value;
							}else if (me.hasClass('unit')){								
									entry.unit = value;
							}else if (me.hasClass('mat')){
									entry.material = value;									
							}else if (me.hasClass('labor')){
									entry.labor = value;									
							}else if (me.hasClass('others')){
									entry.others = value;
							}
							
							var others = [];

							others[0] = entry.material;
							others[1] = entry.labor;
							others[2] = entry.others;

							var total = 0;
							for (var i = others.length - 1; i >= 0; i--){
								if(typeof(others[i])!='undefined'){
									total += +others[i];
								}
							};

							entry.total = total;
							me.closest('tr').find('td.total').text(total);
							
							var amount = [];
							amount[0] = entry.qty;
							amount[1] = entry.total;
							
							var total_amount = +amount[0] * +amount[1];
							
							me.closest('tr').find('td.amount').text(total_amount);
							entry.amount = total_amount;
							
							console.log(entry);

							assign_editable(type,td_id,entry);

							transaction_app.render();

							return value;

					},{
						placeholder : ''
					});


					
				})

			},add_row:function(){

				var data = {					
					sub        : [],
					main_id    : '',
					main_title : '',
					no         : '',
					qty        : '',
					unit       : '',
					material   : '',
					labor      : '',
					others     : '',
					total      : '',
					amount     : '',
				};

				data.no = row_list.length + 1;
				row_list.push(data);
				transaction_app.render();

			},add_sub:function(){

				var id = $(this).closest('tr').attr('data-id');
				var length = row_list[id].sub.length + 1;

				var data = {
					id : length,
					items : [],
					main_id : '',
					main_title : '',
					no         : '',
					qty        : '',
					unit       : '',
					material   : '',
					labor      : '',
					others     : '',
					total      : '',
					amount     : '',
				}				
				data.no = row_list[id].no +"."+ pad(row_list[id].sub.length + 1,2);
				row_list[id].sub.push(data);
				transaction_app.render();

			},add_items:function(){

				var id = $(this).closest('tr').attr('data-id');
				var sub_id = $(this).closest('tr').attr('data-sub_id');
				var length = row_list[id].sub.length + 1;
				var data = {
					id : length,
					main_id : '',
					main_title : '',
					no     : '',
					qty    : '',
					unit   : '',
					material : '',
					labor  : '',
					others : '',
					total  : '',
					amount : '',
				}
				data.no = row_list[id].sub[sub_id].no +"."+ pad(row_list[id].sub[sub_id].items.length + 1 , 2);
				row_list[id].sub[sub_id].items.push(data);
				transaction_app.render();

			},close_row:function(){
				var bool = confirm('Are you sure?');

				if(!bool){
					return false;
				}

				if($(this).hasClass('sub')){
					var id     = $(this).closest('.sub-content').attr('data-id');
					var sub_id = $(this).closest('.sub-content').attr('data-sub_id');
					row_list[id].sub.splice(sub_id,1);
				}else if($(this).hasClass('items')){

					var id     = $(this).closest('.items-content').attr('data-id');
					var sub_id = $(this).closest('.items-content').attr('data-sub_id');
					var item_id = $(this).closest('.items-content').attr('data-items_id');
					
					row_list[id].sub[sub_id].items.splice(item_id,1);

				}else{
					var id =  $(this).closest('.row-content').attr('data-id');
					row_list.splice(id,1);
				}
				transaction_app.render();	

			},render:function(){
				var row = '';
				$('#boq-table tbody').find('.dup').remove();
				
				var sub_total_total  = 0;
				var sub_total_amount = 0;

				var grand_total_total = 0;
				var grand_total_amount = 0;

				if(row_list.length > 0)
				{

					var main_cnt = 1;
					$.each(row_list,function(i,val){

						val.no = main_cnt;
						row += '<tr class="row-content dup" data-id="'+i+'">'
									+ '<td><a href="javascript:void(0)" class="close_row">x</a></td>'
									+ '<td>'+val.no+'.0  <a href="javascript:void(0)" class="add_sub add">| add sub</a></td>'									
									+ '<td class="main-editable">'+val.main_title+'</td>'
									+ '<td class="editable qty td-number">'+val.qty+'</td>'									
									+ '<td class="editable unit ">'+val.unit+'</td>'
									+ '<td class="editable mat td-number">'+val.material+'</td>'
									+ '<td class="editable labor td-number">'+val.labor+'</td>'

									+ '<td class="editable others td-number">'+val.others+'</td>'
									+ '<td class="total td-number">'+comma(parseFloat(val.total).toFixed(2))+'</td>'
									+ '<td class="amount td-number">'+comma(parseFloat(val.amount).toFixed(2))+'</td>'
									+ '</tr>';
																		
									sub_total_total  = +sub_total_total + +val.total;
									sub_total_amount = +sub_total_amount + +val.amount;

						var sub_cnt = 1;
						if(val.sub.length > 0){
							$.each(val.sub,function(z,val_sub){
								row += '<tr class="sub-content dup" data-id ='+i+' data-sub_id="'+z+'">'
									+ '<td><a href="javascript:void(0)" class="close_row sub" >x</a></td>'									
									+ '<td class="sub-row">'+val_sub.no+'  <a href="javascript:void(0)" class="add_items add">| add items</a></td>'
									+ '<td class="sub-editable">'+val_sub.main_title+'</td>'
									+ '<td class="editable qty td-number">'+val_sub.qty+'</td>'
									+ '<td class="editable unit ">'+val_sub.unit+'</td>'
									+ '<td class="editable mat td-number">'+val_sub.material+'</td>'
									+ '<td class="editable labor td-number">'+val_sub.labor+'</td>'
									+ '<td class="editable others td-number">'+val_sub.others+'</td>'
									+ '<td class="total td-number">'+comma(parseFloat(val_sub.total).toFixed(2))+'</td>'
									+ '<td class="amount td-number">'+comma(parseFloat(val_sub.amount).toFixed(2))+'</td>'
									+ '</tr>';					

									sub_total_total  = +sub_total_total  + +val_sub.total;
									sub_total_amount = +sub_total_amount + +val_sub.amount;

								var item_cnt = 1;							
									if(typeof(val.sub[z].items)!='undefined' && val.sub[z].items.length > 0)
									{
										$.each(val.sub[z].items,function(y,val_items){
											row += '<tr class="items-content dup" data-items_id ='+y+' data-id ='+i+' data-sub_id="'+z+'">'
												+ '<td><a href="javascript:void(0)" class="close_row items" >x</a></td>'												
												+ '<td class="item-row">'+val_items.no+'</td>'
												+ '<td class="item-editable">'+val_items.main_title+'</td>'
												+ '<td class="editable qty td-number">'+val_items.qty+'</td>'
												+ '<td class="editable unit ">'+val_items.unit+'</td>'
												+ '<td class="editable mat td-number">'+val_items.material+'</td>'
												+ '<td class="editable labor td-number">'+val_items.labor+'</td>'
												+ '<td class="editable others td-number">'+val_items.others+'</td>'
												+ '<td class="total td-number">'+comma(parseFloat(val_items.total).toFixed(2))+'</td>'
												+ '<td class="amount td-number">'+comma(parseFloat(val_items.amount).toFixed(2))+'</td>'
												+ '</tr>';									
												item_cnt ++ ;
																								
												sub_total_total  = +sub_total_total + +val_items.total;
												sub_total_amount = +sub_total_amount + +val_items.amount;

										});								
									}
								sub_cnt ++ ;
							});
						
						}
						
						row += '<tr class="dup" data-id="'+i+'"><td colspan="10" height="20px"></td></tr>'
							+ '<tr class="main-total-content dup sub-total" data-id="'+i+'">'
												+ '<td></td>'
												+ '<td></td>'
												+ '<td>SUB TOTAL FOR '+val.main_title+'</td>'
												+ '<td></td>'
												+ '<td></td>'
												+ '<td></td>'
												+ '<td></td>'
												+ '<td></td>'
												+ '<td class="td-number">'+comma(parseFloat(sub_total_total).toFixed(2))+'</td>'
												+ '<td class="td-number">'+comma(parseFloat(sub_total_amount).toFixed(2))+'</td>'
							+ '</tr>'
							+ '<tr class="dup" data-id="'+i+'"><td colspan="10" height="20px"></td></tr>';
						main_cnt ++;

						grand_total_total  = +grand_total_total + +sub_total_total;
						grand_total_amount = +grand_total_amount + +sub_total_amount;

						sub_total_total  = 0;
						sub_total_amount = 0;

						

					});

					

					$('#boq-table tbody').find('.add-row').before(row);
				}
				$('#grand_total_total').html(comma(parseFloat(grand_total_total).toFixed(2)));
				$('#grand_total_amount').html(comma(parseFloat(grand_total_amount).toFixed(2)));

				$('#contract_amt').html(comma(parseFloat(grand_total_amount).toFixed(2)))


			},save:function(){

				var bool = confirm('Are you Sure?');

				if(!bool){
					return false;
				}

				$.save({appendTo : 'body'});

				$post = {
					data              : JSON.stringify(row_list),
					project_type      : $('#main-category option:selected').val(),
					project_type_name : $('#main-category option:selected').text(),
					floor_id          : $('#floor option:selected').val(),
					contract_amt      : $('#contract_amt').val(),
					project_site      : $('#project_site option:selected').val(),
				}
								
								
				$.post('<?php echo base_url().index_page();?>/boq/save',$post,function(response){
					switch($.trim(response)){
						case "1":
							$.save({action : 'success'});
						break;
						default:
							$.save({action : 'error'});
						break;
					}
				});
				
			}
		};

		transaction_app.init();
	});
	

</script>

