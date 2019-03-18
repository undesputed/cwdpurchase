
<div class="container">
	<h4>Assign Supplier</h4>
	<hr>
	<div class="row">
		<div class="col-xs-6">
			<div class="t-title">
				<div>Select Supplier : </div> 
				<div>
					<select name="" id="supplier_list" class="form-control">
						<?php foreach($supplier as $key=>$row): ?>
							 <optgroup label="<?php echo $key; ?>">
							 	<?php foreach($row as $row1): ?>
									<option data-type="<?php echo $key ?>" value="<?php echo $row1['supplier_id']; ?>" data-address="<?php echo $row1['address']; ?>" data-contact="<?php echo $row1['contact_no']; ?>"><?php echo $row1['business_name']; ?></option>
							 	<?php endforeach; ?>
							  </optgroup>
						<?php endforeach; ?>
					</select>
				</div>			
			</div>

			<div class="t-title">
				<div>Supplier Type :</div>
				<strong id="supplier-type"></strong>
			</div>

		</div>
		<div class="col-xs-6">
			<div class="t-title">
				<div>Supplier Address:</div>
				<strong id="supplier-address">-</strong>
			</div>
			<div class="t-title">
				<div>Contact No.:</div>
				<strong id="supplier-contact">-</strong>
			</div>			
		</div>	
	</div>
	<div class="row">
			<div class="col-xs-12">
				<div class="t-title">
					<div>Supplier Remarks : </div>
					<input type="text" class="form-control" id="supplier_remarks">
				</div>
			</div>
	</div>
	<table id="tbl-supplier" class="table table-item">
		<thead>
			<tr>
				<th style="width:200px">Item Name</th>
				<th>Unit</th>
				<th class="td-number">P.R Qty</th>
				<th style="display:none">Qty</th>
				<th>Orig. Price</th>
				<th colspan="2" style="text-align:center">% Less Discount</th>				
				<th>Discounted Price</th>
				<th class="td-number">Total</th>				
				<th>Remarks</th>
			</tr>
		</thead>
		<tbody>			
			<?php 
				foreach($pr_details as $row): 
					if($row['qty'] > 0){
			?>
				<tr>
					<td style="display:none" class="itemNo"><?php echo $row['itemNo']; ?></td>
					<td class="itemDesc"><?php echo $row['itemDesc']; ?></td>
					<td class="unit_measure"><?php echo $row['unitmeasure']; ?></td>
					<td class="td-qty td-number pr-qty"><?php echo $row['qty']; ?></td>
					<td style="display:none"><input type="text" class="form-control compute sup-qty" value="<?php echo $row['qty']; ?>"></td>
					<td><input type="number" class="form-control compute unit_cost"></td>
					<td><input type="number" class="form-control discount percentage" maxlength="2" value="0" style="width:50px"></td>
					<td class="td-number td-qty "><input type="text" class="percent_discount form-control" disabled="disabled"></td>
					<td><input type="text" class="form-control numbers_only discount discounted_price" disabled="disabled"></td>
					<td class="td-number td-qty total-discountedPrice total"></td>
					<td class="td-number td-qty supp_total total" style="display:none"></td>
					<td><input type="text" class="form-control remarks"></td>
				</tr>
			<?php 
					}
				endforeach; 
			?>			
		</tbody>
		<tfoot>
			<tr>
				<td><?php echo count($pr_details); ?> item(s)</td>
				<td></td>				
				<td></td>
				<td></td>
				<td></td>
				
				<td colspan="2" class="td-qty">Total : </td>
				<td class="td-number td-qty"><span id="discounted_grand_total"> - </span></td>
				<td class="td-number td-qty" style="display:none"><span id="grand_total"> - </span></td>
				<td></td>
			</tr>
		</tfoot>
	</table>
	<div class="row">
		<div class="col-xs-6"></div>
		<div class="col-xs-6">
			<button id="supplier-save" class="btn btn-primary btn-sm pull-right">Add</button>
		</div>
	</div>
</div>

<script>
	$(function(){
		supplier_app = {
			init:function(){
				this.bindEvents();
			},bindEvents:function(){

				$('.percentage,.unit_cost').on('change',function(){

					var me = $(this);
					var pr_qty     = me.closest('tr').find('.pr-qty').text();
					var item_price = me.closest('tr').find('.unit_cost').val();
					var percentage = me.closest('tr').find('.percentage').val();
					var a = discounted(item_price,percentage);				
					var discounted_price = a.result;
					me.closest('tr').find('.percent_discount').val(a.dis_value.toFixed(2));								
					me.closest('tr').find('.discounted_price').val(discounted_price.toFixed(2));
					var total_discountedPrice = parseFloat(discounted_price) * +pr_qty;
					me.closest('tr').find('.total-discountedPrice').text(comma(total_discountedPrice.toFixed(2)));
					var total = 0;					
					$('#tbl-supplier tbody tr').each(function(i,val){
						var discounted = $(val).find('.total-discountedPrice').text();
						if(discounted !="")
						{
							total += parseFloat(discounted.replace(/,/g,''));
						}												
					});
					$('#discounted_grand_total').html(comma(total.toFixed(2)));

				});

				$('.compute').on('focus',function(){
					this.select();
				});

				$('.compute').on('change',function(){
					var value = 1;
					
					$(this).closest('tr').find('.compute').each(function(i,val){						
						if($(val).val() == '')
							$(val).val(0);
						value = value * parseFloat($(val).val());										
					});

					$(this).closest('tr').find('.supp_total').html(comma(value.toFixed(2)));										
					var grand_total = 0;	
					$('.supp_total').each(function(i,val){
						var total = $(val).html();						
						if(total !='')
						{
							console.log(total);
							grand_total += parseFloat(total.replace(/,/g,''));

						}
					});		
					console.log(grand_total);			
					$('#grand_total').html(comma(grand_total.toFixed(2)));					
				});

				$('#supplier_list').on('change',this.supplier_list);
				$('#supplier_list').trigger('change');
				$('#supplier-save').on('click',this.save);

			},supplier_list:function(){

				$('#supplier-address').html($('#supplier_list option:selected').attr('data-address'));
				$('#supplier-contact').html($('#supplier_list option:selected').attr('data-contact'));
				$('#supplier-type').html($('#supplier_list option:selected').attr('data-type'));
				
			},save:function(){
				
				supplier_app.get_data();

			},get_data:function(){

				var data_container = new Array();
				var data_group     = new Array();

				var name = $('#supplier_list option:selected').text();

				if(jQuery.inArray(name, check_supplier)!==-1){
					alert('Supplier Already Added !');
					return false;					
				}else{
					check_supplier.push(name);
				}

				$('#tbl-supplier tbody tr').each(function(i,val){

					var data = {
								supplier_id   	 : $('#supplier_list option:selected').val(),
								type          	 : $('#supplier_list option:selected').attr('data-type'),
						        itemNo        	 : $(val).find('.itemNo').text(),
						        itemDesc      	 : $(val).find('.itemDesc').text(),
						        unit_measure  	 : $(val).find('.unit_measure').text(),
						        pr_qty        	 : $(val).find('.pr-qty').text(),
						        sup_qty          : $(val).find('.sup-qty').val(),
						        unit_cost        : $(val).find('.unit_cost').val(),
						        supp_total       : $(val).find('.supp_total').text(),
						        remarks          : $(val).find('.remarks').val(),
						        supplier_remarks : $('#supplier_remarks').val(),
						        percentage       : $(val).find('.percentage').val(),
						        discount         : $(val).find('.percent_discount').val(),
						        discounted_price : $(val).find('.discounted_price').val(),
						        total_discounted_price : $(val).find('.total-discountedPrice').text(),						        
					};
					data_group.push(data);
				});


				data_container = {
					supplier_name    : $('#supplier_list option:selected').text(),
					supplier_id      : $('#supplier_list option:selected').val(),
					items            : data_group,
					total            : $('#grand_total').text(),
					discounted_total : $('#discounted_grand_total').text(),
					type             : $('#supplier_list option:selected').attr('data-type'),
					supplier_remarks : $('#supplier_remarks').val(),
				};

				supplier_content.push(data_container);				
				
				canvass.render();
				$.fancybox.close();				

			}
		};		
		supplier_app.init();
	});
</script>