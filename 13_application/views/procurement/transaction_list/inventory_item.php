

			<?php if(count($item_list) > 0): ?>
			<?php foreach($item_list as $row): ?>
			  <?php $active = ($row['item_no']==$itemNo)? 'act' : ''; ?>
				<tr class="<?php echo $active; ?>">
					<td><?php echo $row['item_no']; ?></td>
					<td><a href="<?php echo base_url().index_page(); ?>/transaction_list/inventory/<?php echo $location; ?>/<?php echo $row['item_no']; ?>"><?php echo $row['item_name']; ?></a></td>
					<!-- <td><?php //echo $row['item_name']; ?></a></td> -->
					<td><?php echo $row['total_debit'] - $row['total_credit']; ?></td>
					<td><?php echo $row['unit_measure']; ?></td>
					
				</tr>
			<?php endforeach; ?>		
			<?php endif; ?>