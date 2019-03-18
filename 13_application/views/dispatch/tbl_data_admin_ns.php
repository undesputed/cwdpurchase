

				<table id="tbl-data-ns" class="table table-hover">
					<thead>
						<tr>
							<th>Equipment</th>
							<th class="txt-center">Total Units</th>
							<th class="txt-center">Physical Availability</th>
							<th class="txt-center">Utilized Unit</th>							
							<th class="txt-center">Standby Available</th>
							<th class="txt-center">Breakdown Units</th>
							<th class="txt-center">Mine Drivers NS <span id="mine-loading" class="fa"></span></th>
							<th class="txt-center">Assigned</th>
							<th class="txt-center">Port Drivers NS <span id="port-loading" class="fa"></span></th>
							<th class="txt-center">Assigned</th>
						</tr>
					</thead>
					<tbody>
						
						<?php foreach($result as $row): ?>								
						<tr>
							<td><?php echo ($row['EQUIPMENT']=='HOWO DT')? 'DUMP TRUCK' : $row['EQUIPMENT']; ?></td>
							<td class="txt-center"><?php echo $row['NUMBER OF UNIT']; ?></td>
							<td class="txt-center td-pa"><?php echo $row['PA']; ?></td>
							<td class="td-utilized  txt-center   <?php echo str_replace(' ', '_' ,trim($row['EQUIPMENT'])); ?> "></td>
							<td class="txt-center td-standby"><?php echo $row['PA']; ?></td>
							<td class="txt-center"><?php echo $row['WITH JO']; ?></td>
							<td class="txt-center"><a href="javascript:void(0)" data-shift="ns" data-department="mine" class="<?php echo str_replace(' ', '_' ,trim($row['EQUIPMENT'])); ?> get_drivers_mine get_drivers" data-equipment="<?php echo $row['EQUIPMENT']; ?>"> 0 </a></td>
							<td class="td-assigned-mine  txt-center  <?php echo $row['EQUIPMENT']; ?>"></td>
							<td class="txt-center"><a href="javascript:void(0)" data-shift="ns" data-department="port" class="<?php echo str_replace(' ', '_' ,trim($row['EQUIPMENT'])); ?> get_drivers_port get_drivers" data-equipment="<?php echo $row['EQUIPMENT']; ?>"> 0 </a></td>
							<td class="td-assigned-port  txt-center  <?php echo $row['EQUIPMENT']; ?>"></td>
						</tr>
						<?php endforeach; ?>
						
					</tbody>
				</table>