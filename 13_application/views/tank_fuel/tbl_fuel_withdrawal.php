

<table class="table myTable table-striped">
	<thead>
		<tr>
			<th>Tank Description</th>
			<th>Requisition No.</th>
			<th>Date Withdrawn</th>
			<th>Owner</th>
			<th>Unit</th>
			<th>Qty</th>
			<th>Shift</th>
			<th>Smr</th>
			<th>Kmr</th>
			<th>Ref.no</th>
			<th>Type</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($result as $row): ?>
		<tr>				
			<td><?php  echo $row['TANK DESCRIPTION']; ?></td>
			<td><?php  echo $row['REQUISITION NO.']; ?></td>
			<td><?php  echo $row['DATE WITHDRWAN']; ?></td>
			<td><?php  echo $row['OWNER']; ?></td>
			<td><?php  echo $row['UNIT']; ?></td>
			<td><?php  echo $row['QTY'];  ?></td>
			<td><?php  echo $row['SHIFT']; ?></td>
			<td><?php  echo $row['SMR']; ?></td>
			<td><?php  echo $row['KMR']; ?></td>
			<td><?php  echo $row['REF NO.']; ?></td>
			<td><?php  echo $row['TYP']; ?></td>				
		</tr>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th>Total</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</tfoot>

</table>