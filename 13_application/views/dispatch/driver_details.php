
<?php foreach($group as $key=>$row): ?>
<div id="<?php echo $key; ?>" name="<?php echo $key; ?>">
	<div class="content-title">
		<h3><?php echo ($key=='HOWO DT')? 'DUMP TRUCK': $key; ?></h3>
	</div>
	<table id="tbl-employee" class="table table-condensed table-hover">
		<thead>
			<tr>
				<th>No.</th>
				<th>Name</th>			
				<th>Equipment</th>
				<th>Remarks</th>
			</tr>
		</thead>
		<tbody>
			<?php $cnt = 1; foreach($row as $row1): ?>
				<tr>
					<td><?php echo $cnt; ?></td>
					<td><?php echo $row1['name']; ?></td>				
					<td><?php echo $row1['equipment_name']; ?></td>
					<td><?php echo $row1['remarks'];?></td>
				</tr>
			<?php $cnt++; endforeach; ?>
		</tbody>
	</table>
</div>
<?php endforeach; ?>

