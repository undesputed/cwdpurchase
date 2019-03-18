<table class="table classification_table">
	<thead>
		<tr>			
			<th width="40px">Project</th>
			<th>Item Name</th>
			<th>Unit Measure</th>
			<th>Qty</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($inventory_list as $row): ?>

			<tr>
				<td style="display:none"><?php echo $row['item_no']; ?></td>
				<td>
					<a href="javascript:void(0)" class="category"><?php echo $row['group_description'];?></a>
				</td>
				<td><?php echo $row['item_name']; ?></td>
				<td><?php echo $row['unit_measure']; ?></td>
				<td><?php echo $row['total_debit'] - $row['total_credit']; ?></td>				
			</tr>

		<?php endforeach; ?>
	</tbody>
</table>