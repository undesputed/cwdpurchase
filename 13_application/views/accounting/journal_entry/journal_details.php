
<h2>Journal Details</h2>
<table class="table">
	<thead>
		<tr>
			<th>Account Code</th>
			<th>Account</th>
			<th>Subsidiary Ledger</th>
			<th>DEBIT</th>
			<th>CREDIT</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($result as $row): ?>			
			<tr>
				<td><?php echo $row['Account Code']; ?></td>
				<td><?php echo $row['Account']; ?></td>				
				<td><?php echo $row['subsidiary']; ?></td>				
				<?php $row['Amount']; ?>
				<?php if($row['CR/DR']=="DEBIT"): ?>
					<td><?php echo $this->extra->number_format($row['Amount']); ?></td>
					<td></td>
				<?php else: ?>
					<td></td>
					<td><?php echo $this->extra->number_format($row['Amount']); ?></td>
				<?php endif; ?>				
			</tr>		
		<?php endforeach; ?>
	</tbody>
</table>