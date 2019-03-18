

<table id="tbl-received" class="table table-condensed">
	<thead>
		<tr>
			<th>Delivery Date</th>
			<th>Po Number</th>			
			<th>Invoice No</th>
			<th>Supplier</th>
			<th>Tank</th>
			<th>Qty</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($result as $row): ?>
		<tr>
			
			<td><?php echo $row['DELIVERY DATE']; ?></td>
			<td><?php echo $row['PO NUMBER']; ?></td>
			<td><?php echo $row['INVOICE NO']; ?></td>
			<td><?php echo $row['SUPPLIER NAME']; ?></td>
			<td><?php echo $row['TANK LOCATION']; ?></td>
			<td><?php echo $row['DELIVERED QTY']; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>

</table>




