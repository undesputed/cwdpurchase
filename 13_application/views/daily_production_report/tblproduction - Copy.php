								 
<?php 
	echo "<pre>";
	print_r($data);
	echo "</pre>";
 ?>

								  <table class="table table-condensed table-report">
										<thead>
										<tr class="cf-1">
											<td rowspan="2" style="vertical-align:middle">Material</td>								
											<td colspan="3">IN-HOUSE DT</td>
											<td colspan="3">IN-HOUSE ADT</td>
											<td colspan="3">UMRC</td>
											<td colspan="3">S AND T</td>
											<td colspan="3">SKAFF</td>
											<td colspan="3">PBA</td>
											<td colspan="3">TOTAL</td>
										</tr>
										<tr class="cf-2">
											<td>Today</td>
											<td>Running Monthly</td>
											<td>Running Annual</td>
											<!---->
											<td>Today</td>
											<td>Running Monthly</td>
											<td>Running Annual</td>
											<!---->
											<td>Today</td>
											<td>Running Monthly</td>
											<td>Running Annual</td>
											<!---->
											<td>Today</td>
											<td>Running Monthly</td>
											<td>Running Annual</td>
											<!---->
											<td>Today</td>
											<td>Running Monthly</td>
											<td>Running Annual</td>
											<!---->
											<td>Today</td>
											<td>Running Monthly</td>
											<td>Running Annual</td>
											<!---->
											<td>Today</td>
											<td>Running Monthly</td>
											<td>Running Annual</td>
										</tr>
										</thead>
										<tbody>	
										<?php 
										$total_daily = 0;
										$total_monthly = 0;
										$total_yearly = 0;

										foreach($data as $row): 

											$total_daily = $row['DT TODAY'] + $row['ADT TODAY'] + $row['UMRC RUNNING TODAY'] + $row['S AND T RUNNING TODAY'] + $row['SKAFF RUNNING TODAY'] + $row['PBA RUNNING TODAY'];
											$total_monthly = $row['DT RUNNING MONTHLY'] + $row['ADT RUNNING MONTHLY'] + $row['UMRC RUNNING MONTHLY'] + $row['S AND T RUNNING MONTHLY'] + $row['SKAFF RUNNING MONTHLY'] + $row['PBA RUNNING MONTHLY'];
											$total_yearly = $row['DT RUNNING ANNUAL'] + $row['ADT RUNNING ANNUAL'] + $row['UMRC RUNNING ANNUAL'] + $row['S AND T RUNNING ANNUAL'] + $row['SKAFF RUNNING ANNUAL'] + $row['PBA RUNNING ANNUAL'];
											?>

											<tr>
												<td><?php echo $row['MAT CODE'] ?></td>

												<td><?php echo $this->extra->comma($row['DT TODAY']) ?></td>
												<td><?php echo $this->extra->comma($row['DT RUNNING MONTHLY']) ?></td>
												<td><?php echo $this->extra->comma($row['DT RUNNING ANNUAL']) ?></td>

												<td><?php echo $this->extra->comma($row['ADT TODAY']) ?></td>
												<td><?php echo $this->extra->comma($row['ADT RUNNING MONTHLY']) ?></td>
												<td><?php echo $this->extra->comma($row['ADT RUNNING ANNUAL']) ?></td>

												<td><?php echo $this->extra->comma($row['UMRC RUNNING TODAY']) ?></td>
												<td><?php echo $this->extra->comma($row['UMRC RUNNING MONTHLY']) ?></td>
												<td><?php echo $this->extra->comma($row['UMRC RUNNING ANNUAL']) ?></td>

												<td><?php echo $this->extra->comma($row['S AND T RUNNING TODAY']) ?></td>
												<td><?php echo $this->extra->comma($row['S AND T RUNNING MONTHLY']) ?></td>
												<td><?php echo $this->extra->comma($row['S AND T RUNNING ANNUAL']) ?></td>

												<td><?php echo $this->extra->comma($row['SKAFF RUNNING TODAY']) ?></td>
												<td><?php echo $this->extra->comma($row['SKAFF RUNNING MONTHLY']) ?></td>
												<td><?php echo $this->extra->comma($row['SKAFF RUNNING ANNUAL']) ?></td>

												<td><?php echo $this->extra->comma($row['PBA RUNNING TODAY']) ?></td>
												<td><?php echo $this->extra->comma($row['PBA RUNNING MONTHLY']) ?></td>
												<td><?php echo $this->extra->comma($row['PBA RUNNING ANNUAL']) ?></td>
						
												
												<td><?php echo  $this->extra->comma($total_daily); ?></td>
												<td><?php echo $this->extra->comma($total_monthly); ?></td>
												<td><?php echo  $this->extra->comma($total_yearly); ?></td>

											</tr>
											
										<?php endforeach; ?>
									</tbody>
								</table>