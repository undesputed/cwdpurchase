
<h4><?php echo $item_name; ?></h4>
<table class="table">
	<thead>
		<tr>
			<th></th>
			<th>Supplier</th>			
			<th>Unit Cost</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($supplier as $key=>$row): ?>
			<tr style="font-weight:bold;">
				<td colspan="4"><?php echo $c_date[$key]; ?> <span class="text-warning">(<?php echo $key; ?>)</span></td>
			</tr>
				<?php foreach($row as $row1): ?>
					<tr>
						<td></td>
						<td><?php echo $row1['supplier']; ?></td>						
						<td><?php echo $row1['unit_cost']; ?></td>
					</tr>
				<?php endforeach; ?>
		<?php endforeach; ?>
	</tbody>
</table>





