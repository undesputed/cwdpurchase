

<div class="container">
	<h4><?php echo $date ?></small></h4>	
	
	<?php echo $table; ?>

	<div class="row">
		<div class="col-md-4">
			<table class="table">		
			<tbody>
					<tr>					
						<td>Total Trips</td>
						<td><strong><?php echo $total_trips; ?></strong></td>
					</tr>
					<tr>
						<td>Total WMT</td>
						<td><strong><?php echo $this->extra->comma($total_wmt); ?></strong></td>
					</tr>
				</tbody>
			</table>
		</div>		
	</div>
</div>