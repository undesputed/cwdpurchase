
<table class="table table-condensed">
	<thead>
		<tr>
			<th>DV No</th>
			<th>Particulars</th>
			<th>Project</th>
			<th>Amount</th>
			<th>Encoded</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>

		<?php foreach($data as $row): ?>
		<tr>
			<td><?php echo $row['voucher_no']; ?></td>
			<td><?php echo $row['type']." ".$row['short_desc']; ?></td>
			<td><?php echo $row['project_name']; ?></td>
			<td><?php echo number_format($row['total_amount'],2); ?></td>
			<td><?php echo $row['preparedBy_name']; ?></td>
			<td><a href="javascript:void(0)" class="create_journal" data-id="<?php echo $row['cash_voucher_id']; ?>">Create Journal Entry</a></td>
		</tr>
		<?php endforeach; ?>

	</tbody>
</table>