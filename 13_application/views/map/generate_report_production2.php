						
								<table id="tbl-production" class="tbl-report table table-condensed table-hover ">
									<thead class="">
										<tr>
											<th style="width:30px">No</th>
											<th style="width:100px">Body No</th>
											<th>No of Trips</th>
											<th>Truck Factor</th>
											<th>Total Wmt</th>
										</tr>
									</thead>			
									<tbody>
										<?php										
											$cnt = 0;
											$total_wmt = 0;
											$total_trips = 0;
											foreach($dt as $row): 
											
											
											$total_wmt = $total_wmt + $row['wmt'];
											$total_trips = $total_trips + $row['trips'];
											$status = ($row['trips'] == 0)? true : false;
											if(!$status):
											$cnt++;
										 ?>
											<tr class="">
												<td><?php echo $cnt; ?></td>
												<td><a href="javascript:void(0)" class="dt-details"><?php echo $row['dt']; ?></a></td>
												<td class=""><?php echo $row['trips']; ?></td>												
												<td class=""><?php echo $row['factor']; ?></td>
												<td class=""><?php echo $row['wmt'] ?></td>
											</tr>
										<?php 
											endif;
										endforeach; ?>
									</tbody>
									<tfoot>
										<tr>
											<td></td>
											<td>Total Trips</td>
											<td><strong><?php echo $total_trips; ?></strong></td>
											<td></td>											
											<td><strong><?php echo $total_wmt; ?></strong></td>
										</tr>
									</tfoot>
								</table>

							
						