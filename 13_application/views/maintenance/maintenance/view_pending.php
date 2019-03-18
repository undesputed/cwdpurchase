<div class="container">
	<div class="content-title">
		<h3>Pending Job Order's</h3>
	</div>
	<div class="row">
		<div class="col-xs-2">
			<div class="panel panel-default">		
			  <div class="panel-body">
			  		<table class="table table-condensed">
			  			<thead>
			  				<tr>
			  					<th>Month</th>
			  					<th>Pending</th>
			  				</tr>
			  			</thead>
						<tbody>
						<?php  foreach($sidebar as $row): ?>
							<tr>
								<td><?php echo $row['date']; ?></td>
								<td><?php echo $row['cnt']; ?></td>
							</tr>
						<?php  endforeach; ?>
						</tbody>
					</table>
			  </div>	 
			</div>			
		</div>
		<div class="col-xs-10">
			<?php echo $table; ?>	
		</div>

	</div>	
</div>

<script>
	$(function(){
		
		$('.myTable').dataTable(datatable_option);

	});
</script>