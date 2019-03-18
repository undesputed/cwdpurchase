
<?php echo "<pre>";
print_r($pr_items);
echo "</pre>"; ?>
<table class="table">
<thead>
	<tr>
		<th>PO No</th>
		<th>PO Date</th>
		<th>QTY</th>
		<th>Unit</th>
		<th>Description</th>
		<th>Unit Price</th>
		<th>Total Amount</th>
		<th>Project</th>
		<th>Supplier</th>
	</tr>
</thead>	
<tbody>	

	<?php foreach($pr_items as $row): ?>
	<tr>
		<td><?php echo $row['reference_no']; ?></td>
		<td><?php echo $row['po_date']; ?></td>
		<td><?php echo $row['quantity']; ?></td>
		<td><?php echo $row['unit_msr']; ?></td>
		<td><?php echo $row['item_name']; ?></td>
		<td><?php echo $row['unit_cost']; ?></td>
		<td><?php echo $row['total_unitcost']; ?></td>
		<td><?php echo $row['from_projectCodeName']; ?></td>
		<td><?php echo $row['supplier']; ?></td>
	</tr>
	<?php endforeach; ?>
</tbody>
</table>