

<?php foreach($result as $key=>$row):  ?>	
	<?php
		foreach($row as $keys=>$row1){
			$trips[$keys] = $row1['trips'];
		}
		 array_multisort($trips,SORT_DESC,$row); 

	?>
<div class="content-title">
	<h3><?php echo ($key=='HOWO DT')? 'DUMP TRUCK' : $key; ?></h3>
</div>

<table class="table table-condensed table-hover">

	<thead>
		<th>No.</th>
		<th>Name</th>
		<th>Position</th>
		<th>In</th>
		<th>Out</th>
		<th>Body No</th>
		<th>Trips</th>
	</thead>

	<tbody>
		<?php $cnt = 1; $total = 0;?>
		<?php foreach($row as $row1): ?>
			<tr>
				<td><?php echo $cnt; ?></td>
				<td><?php echo $row1['name']; ?></td>
				<td><?php echo $row1['position']; ?></td>
				<td><?php echo $row1['am_in']; ?></td>
				<td><?php echo $row1['pm_out']; ?></td>
				<td><?php echo $row1['body_no']; ?></td>
				<td><?php echo $row1['trips']; ?></td>
				<?php $cnt++; 
				$total += $row1['trips'];
				?>
			</tr>
		<?php endforeach; ?>
		<tr class="total">
			<td colspan="6">Total Trips</td>			
			<td><?php echo $total; ?></td>
		</tr>
	</tbody>

</table>

<?php endforeach; ?>