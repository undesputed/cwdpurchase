	<table class="sidebar-table" >
		<tbody>
					<?php foreach($sidebar as $row): ?>
						<tr>
							<td><?php echo $row['equipment']; ?></td>
							<td><span class="dt-nominator"><?php echo $row['nominator']; ?></span> / <?php echo $row['denominator']; ?></td>
						</tr>

				   <?php endforeach; ?>
		</tbody>			    	
	</table>