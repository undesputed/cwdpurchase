



<div class="t-content" style="min-height:100px">
	<div class="t-header">
		<h4>Remaining Items</h4>
	</div>
	<div class="row">
		<div class="col-md-12">
			
			<table class="table">
				<colgroup>
					<col style="width:20px">
					<col>
					<col>
				</colgroup>
				<thead>
					<tr>
						<th></th>
						<th>Item Name</th>
						<th>Remaining Qty</th>								
					</tr>
				</thead>
				<tbody>
					<?php 
						$cnt = 0;
						foreach($rr_details as $row): $cnt++?>
						<tr>
							<td><input type="checkbox" class="check-pr" data-item-no="<?php echo $row['item_id']; ?>"></td>
							<td><?php echo $row['item_name_ordered']; ?></td>
							<?php 
									$rem = $row['item_quantity_ordered'] - $row['item_quantity_actual'];									
							 ?>
							<td><?php echo $rem; ?></td>							
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td><?php echo $cnt; ?> item(s)</td>
						<td><a href="javascript:void(0)" class="pull-right" id="create_pr">Create PR</a></td>
					</tr>
				</tfoot>
			</table>

		</div>
	</div>
</div>

<script>
	$(function(){
		$('#create_pr').on('click',function(){
			console.log($('.check-pr:checked').length);
			if($('.check-pr:checked').length == 0)
			{		

			    $.alert({
				    title: 'INFO!',
				    content: 'No item selected',
				    confirm: function(){}
			    });
				return false;

			}else
			{


				var data = {
					'item_no'          : "",
					'item_description' : "",
					'serial_no'        : "",
					'qty'              : "",
					'model_no'         : "",
					'unit' 		       : "",
					'remarks'          : "",
					'groupID'          : "",
					'unit_cost'        : "",
					'for_usage'        : "",
				}


			}
		});
	});

</script>	