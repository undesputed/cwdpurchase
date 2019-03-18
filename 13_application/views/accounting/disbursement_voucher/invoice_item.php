
						<table class="table table-condensed">
							<thead>
								<tr>
									<th>PO No.</th>
									<th>PO Items</th>
									<th>Account Code</th>
									<th>Account Description</th>
									<th>Amount</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$total  = 0;
								if(!empty($item_list)): 
										
										foreach($item_list as $row):
										$amount = str_replace(',','', $row['AMOUNT']);
										$total += $amount;
								?>							
									<tr>
										<td><?php echo $row['PO N0.']; ?></td>
										<td><?php echo $row['PO ITEMS']; ?></td>
										<td><?php echo $row['ACCOUNT CODE']; ?></td>
										<td><?php echo $row['ACCOUNT DESCRIPTION']; ?></td>
										<td><?php echo $row['AMOUNT']; ?></td>
									</tr>
								<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="5">Empty List</td>
									</tr>
								<?php endif; ?>
							</tbody>
							<tfoot>
								<tr>
									<td><?php echo count($item_list); ?> item(s)</td>
									<td></td>
									<td></td>
									<td style="text-align:right">Total : </td>
									<td><?php echo $this->extra->number_format($total); ?></td>									
								</tr>
							</tfoot>
							<input type="hidden" id="amount" value="<?php echo $total; ?>">
						</table>