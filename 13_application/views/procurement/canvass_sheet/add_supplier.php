
<div class="container">
	<h5 class="text-muted"><?php echo ucfirst($this->input->post('type')); ?></h5>
	<h4><?php echo $supplier[0]['business_name']; ?></h4>
	<small><?php echo $supplier[0]['address']; ?></small>

	<div class="row">
		<div class="col-md-12">
			<table id="supplier-table" class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Item No</th>
						<th>Item Desc</th>
						<th>Unit of Measure</th>
						<th>P.R Qty</th>						
						<th>Quantity</th>
						<th>Item Cost</th>
						<th>Total</th>
						<th>Remarks</th>						
					</tr>
				</thead>
				<tbody>
						<?php $cnt = 0; 
							foreach($pr_details as $row): 
								$cnt++;
						?>
								<tr>
									<td><?php echo $cnt;?></td>									
									<td class="itemNo"><?php echo $row['itemNo']; ?></td>
									<td class="itemDesc"><?php echo $row['itemDesc']; ?></td>
									<td class="unitmeasure"><?php echo $row['unitmeasure']; ?></td>
									<td class="pr_qty"><?php echo $row['qty']; ?></td>								
									<td><input type="text" class="supp_qty numbers_only compute required-ad"></td>
									<td><input type="text" class="supp_cost numbers_only compute required-ad"></td>
									<td><input type="text" class="supp_total required-ad"  disabled></td>
									<td><input type="text" class="remarks "></td>									
								</tr>

						<?php endforeach; ?>
				</tbody>
				<tfoot>
					   <tr>
					   	 <td colspan="9">
					   	 	<div class="pull-right">
						   	 	<div class="btn-group">
						   	 		<button id="supp-add" class="btn btn-primary">Add</button>
						   	 	</div>						   	 	
					   	 	</div>
					   	 </td>
					   </tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<script>
	var supplier = {
		init:function(){
			this.bindEvents();
		},bindEvents:function(){		
			$('#supp-add').on('click',this.populate);
			$('.compute').on('blur',function(){

				var value = 1;
				$(this).closest('tr').find('.compute').each(function(i,val){
					if($(val).val() == '')
						$(val).val(0);
					value = value * parseFloat($(val).val());
				});
				$(this).closest('tr').find('.supp_total').val(value.toFixed(2));

			});

		},populate:function(){
			var bool = $('.required-ad').required();			
			if(bool){
				return false;
			}

			$data = new Array();
			$('#supplier-table tbody tr').each(function(i,val){
				$row = {
					itemNo     : $(val).find('.itemNo').text(),
					itemDesc   : $(val).find('.itemDesc').text(),
					unitmeasure: $(val).find('.unitmeasure').text(),
					pr_qty     : $(val).find('.pr_qty').text(),
					supp_qty   : $(val).find('.supp_qty').val(),
					supp_cost  : $(val).find('.supp_cost').val(),
					supp_total : $(val).find('.supp_total').val(),
					remarks    : $(val).find('.remarks').val(),
				}
				$data.push($row);				
			});

			$supplier = {
				supplier_id   : "<?php echo $supplier[0]['supplier_id']; ?>",
				supplier_name : "<?php echo $supplier[0]['business_name']; ?>",
				type          : "<?php echo $this->input->post('type'); ?>",
				items         : $data,
			}
			
			canvass_supplier.push($supplier);
			render_supplier();
		
			$.fancybox.close();
		}
	}
	supplier.init();
</script>