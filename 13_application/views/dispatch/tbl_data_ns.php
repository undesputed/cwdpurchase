


				<table id="tbl-data-ns" class="table table-hover">
					<thead>
						<tr>
							<th>Equipment</th>
							<th class="txt-center">Total Units</th>
							<th class="txt-center">Physical Availability</th>
							<th class="txt-center">Utilized Unit</th>							
							<th class="txt-center">Standby Available</th>
							<th class="txt-center">Breakdown Units</th>
							<th class="txt-center">Drivers NS</th>
							<th class="txt-center">Assigned / Dispatch</th>
						</tr>
					</thead>
					<tbody>
						
						<?php foreach($result as $row): ?>								
						<tr>							
							<td><?php echo ($row['EQUIPMENT']=='HOWO DT')? 'DUMP TRUCK' : $row['EQUIPMENT']; ?></td>
							<td class="txt-center"><?php echo $row['NUMBER OF UNIT']; ?></td>
							<td class="txt-center td-pa"><?php echo $row['PA']; ?></td> 
							<td class="td-utilized  txt-center <?php echo str_replace(' ', '_' ,trim($row['EQUIPMENT'])); ?>"></td>
							<td class="txt-center td-standby"><a href="javascript:void(0)" class="get_standby" data-shift="ns" data-equipment="<?php echo $row['EQUIPMENT']; ?>"><?php echo $row['PA']; ?></a></td>
							<td class="txt-center"><?php echo $row['WITH JO']; ?></td>
							<td class="txt-center"><a href="javascript:void(0)" data-shift="ns" class="get_drivers <?php echo str_replace(' ', '_' ,trim($row['EQUIPMENT'])); ?>" data-equipment="<?php echo $row['EQUIPMENT']; ?>"> 0 </a></td>
							<td class="td-assigned  txt-center  <?php echo $row['EQUIPMENT']; ?>"></td>
						</tr>
						<?php endforeach; ?>
						
					</tbody>
				</table>