
<div class="row">
	<div class="col-md-12">
		<div class="content-title">
			<h3><?php echo $name; ?></h3>
		</div>
		<table class="table table-condensed">
			<thead>
				<tr>
					<th>No.</th>
					<th>Equipment Name</th>
					<th>Status</th>
				</tr>				
			</thead>				
			<tbody>
				<?php 
				$cnt = 0;
				if(count($result)>0):
				foreach($result as $row): $cnt++; ?>
					<tr>
						<td style="width:20px"><?php echo $cnt; ?></td>
						<td><?php echo $row['equipment_brand'];?></td>
						<td><?php echo $row['equipment_status'];?></td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr colspan="4">
					<td>Empty Result</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>

	</div>
</div>
