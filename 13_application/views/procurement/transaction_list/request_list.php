
<div class="header" style="margin-bottom:20px;">
	<h2>Request List</h2>
</div>

<div class="container">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="classification_setup_content">
		 	 <table class="table table-condensed " style="overflow:scroll;">
				<thead>
					<tr>
						<th> </th>
						<th>Item Descreption</th>
						<th>Unit</th>
						<th>Request Qty</th>
						
					</tr>
				</thead>
				<tbody>
					<?php foreach ($result as $row ): ?>
						<tr>
							<th class="item_no" style="display:none"><?php echo $row['item_no']; ?></th>
							<th><input type="checkbox" class="checkbox-list"></th>
							<th class="item_description"><?php echo $row['item_description']; ?></th>
							<th class="unit_measure"><?php echo $row['unit_measure']; ?></th>
							<td class="stocks" style="display:none"><?php echo $row['stocks']; ?></td>
							<th class="request_qty"><?php echo $row['request_qty']; ?></th>
						</tr>
					<?php endforeach; ?>
				</tbody>
			 </table>

		  </div>
		</div>
		
	</div>
</div>
<div class="bottom">
	 <input id="addtolist" class="btn btn-primary col-xs-3 pull-right btn-sm" type="submit" value="Add to list">	
</div>
<script>
	var classification ={
		init:function(){
			this.bindEvent();
		},bindEvent:function(){
			
			$('#addtolist').on('click',this.addtolist);

		},addtolist:function(){
			var request_list = new Array();
			$('.checkbox-list:checked').each(function(i,val){
				var tr = $(val).closest('tr');
				var list = {
					item_no 		 : tr.find('.item_no').text(),
					item_description : tr.find('.item_description').text(),
					unit_measure     : tr.find('.unit_measure').text(),
					stocks           : tr.find('.stocks').text(),
					request_qty      : tr.find('.request_qty').text(),

				}
				request_list.push(list);
			});


			if(request_list.length > 0){
				$('#item_list tbody').html('');
				$.each(request_list,function(i,val){

						var div = "";

						div +='<tr>';
							div +='<td><span class="close item-close"><span aria-hidden="true">&times;</span></span></td>';
							div +='<td style="display:none" class="inventory_id"></td>';
							div +='<td  style="display:none" data-ir="true" class="item_no">'+val.item_no+'</td>';
							div +='<td><span id="item_select" class="btn-link editable item_desc">'+val.item_description+'</span></td>';
							div +='<td class="unit_measure">'+val.unit_measure+'</td>';
							div +='<td class="stocks">'+val.stocks+'</td>';
							div +='<td style="display:none" class="item_cost"></td>';
							div +='<td><input type="text" class="form-control required request_qty numbers_only" style="width:80px" value="'+val.request_qty+'"></td>';
						div +='</tr>';

						$('#item_list tbody').append(div);			
						item_issuance_app.count();

						$('.editable').editable('<?php echo base_url().index_page(); ?>/ajax/display',{
						     data    : data,
						     type    : 'select',
						     submit  : 'OK',
						     style   : 'inherit',
						     callback: function (value,settings){
						     	$(this).html(item_content[value].item_name);
						     	$(this).closest('tr').find('.item_no').html(value);
						     	$(this).closest('tr').find('.item_cost').html(item_content[value].item_cost);
						     	$(this).closest('tr').find('.unit_measure').html(item_content[value].unit_measure);
						     	$(this).closest('tr').find('.stocks').html(item_content[value].stocks);
						     	$(this).closest('tr').find('.inventory_id').html(item_content[value].inventory_id);
							 }
						});

						$('.chosen-select').chosen({width:"100%"});


				});
			}




			$.fancybox.close();


			/*console.log(request_list);*/
			/*item_issuance_app.add_item();*/

		}
	};
	classification.init();
	
</script>

