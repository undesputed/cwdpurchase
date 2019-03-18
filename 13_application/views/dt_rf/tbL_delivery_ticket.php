

<?php 

	$cnt = 0;
	$total_trips = 0;
	$total_wmt = 0;
 ?>



<table  class="table table-condensed tbl-delivery-ticket">
	<thead>
		<tr>
			<th style="width:30px">No</th>
			<th style="width:100px">Body No</th>
			<th>No of Trips</th>			
			<th>Total Wmt</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($data as $row): $cnt++;?>

			<tr>
				<td><?php echo $cnt; ?></td>
				<td><a href="javascript:void(0)" class="details-delivery-ticket"><?php echo trim($row['equipment_brand']);?></a></td>
				<td><?php echo $row['trips']; ?></td>				
				<td><?php echo $row['wmt']; ?></td>				
			</tr>
		<?php  
			$total_trips += $row['trips'];
			$total_wmt   += $row['wmt'];
		?>
		<?php endforeach;?>
	</tbody>
	<tfoot>
		<tr>
			<td></td>			
			<td>Total Trips</td>
			<td><strong><?php echo $total_trips ?></strong></td>
			<td><strong><?php echo $total_wmt; ?></strong></td>
		</tr>
	</tfoot>
</table>