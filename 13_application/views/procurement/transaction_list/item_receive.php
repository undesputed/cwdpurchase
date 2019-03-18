  				  <table class="table table-sieve">
				  	<thead>
				  		<tr>
				  			<th>Date</th>
				  			<th>Transfer No.</th>				  							  			
				  			<th>Request to</th>
				  			<th>Items</th>
				  			<th>Remarks</th>
				  			<th>Status</th>
				  		</tr>
				  	</thead>
				  	<tbody>
				  		<?php if(count($main_list) > 0): ?>
				  		<?php foreach($main_list as $key=>$row): ?>
							<tr>
								<td colspan="6"><strong><?php echo $key; ?></strong></td>
							</tr>
							 <?php foreach($row as $row1): ?>
							  <?php 	
									$active = ($row1['transfer_no'] == $_active ) ? 'act': '';
								 ?>
									<tr class="hover <?php echo $active; ?>">
										<td style="display:none"><?php echo $key; ?></td>
										<td></td>
										<td>
											<div><?php echo $row1['preparedBy_name']; ?></div>										
											<a href="<?php echo base_url().index_page(); ?>/transaction_list/item_transfer/for_receiving/<?php echo $row1['transfer_no']; ?>"><?php echo $row1['transfer_no']; ?></a>
										</td>																				
										
										<td>
											<div><?php echo $row1['request_to_name']; ?></div>
											<span> Request by : <?php echo $row1['request_by']; ?></span>
										</td>
										<td>
											<div>
												<span class="fa fa-tags"><?php echo $row1['no_items'] ?></span>												
											</div>
										</td>
										<td>	
											<p><?php echo $row1['remarks']; ?></p>												
										</td>	
										<td>
											<?php echo $this->extra->label($row1['request_status']); ?>
										</td>
									</tr>
							<?php endforeach; ?>
				  		<?php endforeach; ?>
				  		<?php else: ?>
							  <tr>
							  	<td colspan="6">Empty</td>
							  </tr>
				  		<?php endif; ?>
				  	</tbody>				  	
				  </table>