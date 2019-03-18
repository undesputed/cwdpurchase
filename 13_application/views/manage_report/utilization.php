


<div class="list-carousel">
	
	<div class="pull-right">
		<a id="prev2" class="prev" href="#">&lt;</a>
		<a id="next2" class="next" href="#">&gt;</a>
	</div>
	<div class="clearfix"></div>
	
	<ul id="ul-utilization">
		<li>
			<div class="panel-body">
				<span style=""><small class="text-muted">Today : </small>DAYSHIFT</span>
			</div>
			<table class="table">
						<thead>
							<tr>
								<th>Equipment</th>
								<th>PA</th>
								<th>UT</th>
								<th>SA</th>
								<th>BD</th>
								
							</tr>
						</thead>
						<tbody>
							<?php foreach($result as $row): ?>
								<tr>
									<td><?php echo $row['EQUIPMENT']; ?></td>
									<td><?php echo $row['PA']; ?></td>
									<td><?php echo $row['UTILIZED DS']; ?></td>
									<td><?php echo $row['STANDBY AVAILABLE DS']; ?></td>
									<td><?php echo $row['WITH JO']; ?></td>
									
								</tr>
							<?php endforeach; ?>				
						</tbody>				
			</table>

		</li>

		<li>
			<div class="panel-body">
				<span style=""><small class="text-muted">Today : </small> NIGHTSHIFT</span>
			</div>
			<table class="table">
						<thead>
							<tr>
								<th>Equipment</th>
								<th>PA</th>
								<th>UT</th>
								<th>SA</th>
								<th>BD</th>
								
							</tr>
						</thead>
						<tbody>
							<?php foreach($result as $row): ?>
								<tr>
									<td><?php echo $row['EQUIPMENT']; ?></td>
									<td><?php echo $row['PA']; ?></td>
									<td><?php echo $row['UTILIZED NS']; ?></td>
									<td><?php echo $row['STANDBY AVAILABLE NS']; ?></td>
									<td><?php echo $row['WITH JO']; ?></td>									
								</tr>
							<?php endforeach; ?>				
						</tbody>				
			</table>
		</li>
	</ul>

</div>