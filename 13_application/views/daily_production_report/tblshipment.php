

<table class="table table-condensed table-report">
	<thead>
		<tr class="cf-1">
			<td>Vessel Name</td>
			<td>Date Commenced</td>
			<td>Target Tonnage</td>
			<td>Load Today</td>
			<td>Load ToDate</td>
			<td>Expected Date to Finish</td>
		</tr>
	</thead>
	<tbody>		
		<?php foreach($data as $row): ?>
		<tr>
			<td><?php echo $row[0]['VESSEL NAME']; ?></td>
			<td><?php echo $row[0]['DATE COMMENCED']; ?></td>
			<td><?php echo $this->extra->comma($row[0]['TARGET TONNAGE']); ?></td>
			<td><?php echo $this->extra->comma($row[0]['LOAD TODAY']); ?></td>
			<td><?php echo $this->extra->comma($row[0]['LOAD TODATE']); ?></td>
			<td><?php echo $row[0]['EXPECTED DATE TO FINISH']; ?></td>
		</tr>
		<?php endforeach; ?>
		
	</tbody>
</table>