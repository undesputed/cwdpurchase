										

							<table id="tbl-rf" class="table table-condensed table-bordered table-nowrap ">
								<thead class="">
									<tr>
										<th style="width:30px">No</th>
										<th style="width:100px">Truck</th>
										<th>MINE YARD 2</th>
										<th>MINE YARD</th>
										<th>LOCATION J</th>
										<th>LOCATION I</th>
										<th>SAMPLING 9</th>
										<th>SAMPLING 6</th>
										<th>SAMPLING 5</th>
										<th>MINE BASE</th>
										<th>SAMPLING 7</th>																			
										<th>SAMPLING B</th>
										<th>SAMPLING A</th>									
									</tr>
								</thead>			
								<tbody>
									<?php $cnt=0;foreach($tags as $row): $cnt++; ?>
										<tr class="<?php echo $row; ?>">
											<td><?php echo $cnt; ?></td>
											<td><a href="javascript:void(0)" class="dt-details"><?php echo $row; ?></a></td>
											<td class="mineyard2"></td>
											<td class="mineyard"></td>
											<td class="location_j"></td>
											<td class="location_i"></td>
											<td class="sampling_9"></td>											
											<td class="sampling_6"></td>											
											<td class="sampling_5"></td>
											<td class="mine_base"></td>
											<td class="sampling_7"></td>											
											<td class="sampling_b"></td>
											<td class="sampling_a"></td>										
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>	
