

<table id="tbl-withdraw" class="table table-condensed">
	<thead>
		<tr>
			<th>Requisition No</th>
			<th>Tank Description</th>			
			<th>Unit</th>
			<th>Qty</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($result as $row): ?>
		<tr>
			
			<td><?php echo $row['REQUISITION NO.']; ?></td>
			<td><?php echo $row['TANK DESCRIPTION']; ?></td>
			<td><?php echo $row['UNIT']; ?></td>
			<td><?php echo $row['QTY']; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>

</table>




