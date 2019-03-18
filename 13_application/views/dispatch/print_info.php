<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>DISPATCH</title>
</head>
<body>
			<?php 
			echo "<pre>";
			print_r($assigned);
			echo "</pre>"; 
			?>
					<table id="tbl-data" class="table table-hover">
					<thead>
						<tr>
							<th>Equipment</th>
							<th class="txt-center">Total Units</th>
							<th class="txt-center">Physical Availability</th>
							<th class="txt-center">Utilized Unit</th>							
							<th class="txt-center">Standby Available</th>
							<th class="txt-center">Breakdown Units</th>
							<th class="txt-center">Mine Drivers DS</th>
							<th class="txt-center">Assigned</th>
							<th class="txt-center">Port Drivers DS</th>
							<th class="txt-center">Assigned</th>
						</tr>
					</thead>
					<tbody>
						
						<?php foreach($result as $row): ?>

						<?php 
								$port_online = 0;
								$mine_online = 0;
								foreach($get_online['port'] as $row1)
								{

									if(str_replace(' ', '_' ,trim($row['EQUIPMENT'])) == $row1['equipment'])
									{
										$port_online = $row1['ds'];
									}

								}

								foreach($get_online['mine'] as $row1)
								{
									if(str_replace(' ', '_' ,trim($row['EQUIPMENT'])) == $row1['equipment'])
									{
										$mine_online = $row1['ds'];
									}
								}

						 ?>

						<tr>							
							<td><?php echo ($row['EQUIPMENT']=='HOWO DT')? 'DUMP TRUCK' : $row['EQUIPMENT']; ?></td>
							<td class="txt-center"><?php echo $row['NUMBER OF UNIT']; ?></td>
							<td class="txt-center td-pa"><?php echo $row['PA']; ?></td>
							<td class="td-utilized  txt-center   <?php echo str_replace(' ', '_' ,trim($row['EQUIPMENT'])); ?> "></td>
							<td class="txt-center td-standby"><a href="javascript:void(0)" class="get_standby" data-shift="ds" data-equipment="<?php echo $row['EQUIPMENT']; ?>"><?php echo $row['PA']; ?></a></td>
							<td class="txt-center"><?php echo $row['WITH JO']; ?></td>
							<td class="txt-center"> <?php echo $mine_online; ?> </td>
							<td class="td-assigned-mine  txt-center  <?php echo $row['EQUIPMENT']; ?>"></td>
							<td class="txt-center"><?php echo $port_online; ?></td>
							<td class="td-assigned-port  txt-center  <?php echo $row['EQUIPMENT']; ?>"></td>
						</tr>
						<?php endforeach; ?>
						
					</tbody>
				</table>



</body>
</html>