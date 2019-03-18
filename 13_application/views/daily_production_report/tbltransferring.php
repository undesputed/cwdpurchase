

<table class="table table-condensed table-hover table-report">
		<thead>
		<tr class="cf-1">
			<td rowspan="2" style="vertical-align:middle">Material</td>
			<td colspan="3">MY to PY</td>
		</tr>
		<tr class="cf-2">
			
			<td>Today</td>
			<td>Running Monthly</td>
			<td>Running Annual</td>
		</tr>
		</thead>
		<tbody>
		<?php 

			$total['today']  = 0;
			$total['running_monthly'] = 0;	
			$total['running_annual'] = 0;

			foreach($data as $row): 

			$total['today'] += $row['TODAY'];
			$total['running_monthly'] += $row['RUNNING MONTHLY'];
			$total['running_annual']  += $row['RUNNING ANNUAL'];

		?>
		<tr>
			<td><?php echo $row['mat_code'] ?></td>
			<td><?php echo $this->extra->comma($row['TODAY']) ?></td>
			<td><?php echo $this->extra->comma($row['RUNNING MONTHLY']) ?></td>
			<td><?php echo $this->extra->comma($row['RUNNING ANNUAL']) ?></td> 
		</tr>
		<?php endforeach; ?>
		<tr>
			<td>Total</td>
			<td><?php echo $total['today']; ?></td>
			<td><?php echo $this->extra->comma($total['running_monthly']); ?></td>
			<td><?php echo $this->extra->comma($total['running_annual']); ?></td>
		</tr>
	</tbody>
</table>