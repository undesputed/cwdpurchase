

<div class="container">
	<h4><?php echo $date ?> : <small><?php echo $shift[0]; ?> : <?php echo $shift[1]; ?></small></h4>	
	
	<?php echo $table; ?>

	<div class="row">
		<div class="col-md-4">
			<table class="table">		
			<tbody>
					<tr>					
						<td>Total Unit</td>
						<td><strong><?php echo $total_unit; ?></strong></td>
					</tr>
					<tr>					
						<td>Total trips</td>
						<td><strong><?php echo $total_trips; ?></strong></td>
					</tr>
				</tbody>
			</table>
		</div>		
	</div>
</div>