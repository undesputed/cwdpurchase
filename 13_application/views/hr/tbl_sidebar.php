	<table class="sidebar-table" >
		<tbody>
					<?php foreach($sidebar as $key=>$row): ?>
						<tr>
							<td><?php echo ($key == 'HOWO DT')? 'DUMP TRUCK' : $key ; ?></td>
							<td><span class="dt-nominator"><?php echo $row['no_unit']; ?></span> / <?php echo $row['total']; ?></td>
						</tr>

				   <?php endforeach; ?>
		</tbody>			    	
	</table>