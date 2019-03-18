
<table class="table table-item">
	<thead>
		<tr>
			<th>Item Name</th>
			<th>Item No</th>
			<th>Unit</th>
			<th class="td-number td-qty">Qty</th>
			<th class="td-number">Unit Cost</th>
			<th class="td-number">Total</th>
			<th>Remarks</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$grand_total = 0;
			foreach($canvass_details as $row): 

		?>
			<tr>
				<td><?php echo $row['itemName']; ?></td>
				<td><?php echo $row['itemNo']; ?></td>
				<td><?php echo $row['unit_measure']; ?></td>
				<td class="td-number td-qty "><span class="editable"><?php echo $this->extra->comma($row['pr_qty']); ?></span></td>
				<td class="td-number"><?php echo $this->extra->number_format($row['supplier_cost']); ?></td>
				<?php 
					$total = $row['pr_qty'] * $row['supplier_cost']; 
					$grand_total += $total;
				?>
				<td class="td-number"><?php echo $this->extra->number_format($total); ?></td>
				<td><span class="remarks editable"><?php echo (empty($row['c_remarks']))? '-': $row['c_remarks']; ?></span></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<td><?php echo count($canvass_details); ?> item(s)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td class="td-number td-qty">Total : <span><?php echo $this->extra->number_format($grand_total);  ?></span></td>
			<td></td>
						
		</tr>
	</tfoot>
</table>

<script>
	$(function(){

		$(".editable").editable("<?php echo base_url().index_page(); ?>/ajax/display", { 
			      indicator : "<img src='<?php echo base_url().index_page();?>/asset/img/indicator.gif'>",
			      tooltip   : "Click to edit...",
			      style  : "inherit"
		});
	});
</script>