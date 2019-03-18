			

				<table class="table-report table table-bordered table-hover ">
					<thead>

						<tr class="tbl-header ">
							<td rowspan="2">Date</td>
							<td colspan="7">Day Shift</td>
							<td colspan="7">Night Shift</td>						
							<td rowspan="2">TO-DATE WMT</td>
						</tr>

						<tr class="tbl-header">						
							<td>ADT UNIT</td>
							<td>ADT TRIP</td>
							<td>ADT WMT</td>
							<td>DT UNIT</td>
							<td>DT TRIP</td>
							<td>DT WMT</td>
							<td>TOTAL WMT</td>
							<td>ADT UNIT</td>
							<td>ADT TRIP</td>
							<td>ADT WMT</td>
							<td>DT UNIT</td>
							<td>DT TRIP</td>
							<td>DT WMT</td>
							<td>TOTAL WMT</td>						
						</tr>
					</thead>	
					<tbody>
						<?php 
							$to_date['ds_adt_unit'] = 0;
							$to_date['ds_adt_trip'] = 0;
							$to_date['ds_adt_wmt'] = 0;
							$to_date['ds_dt_unit'] = 0;
							$to_date['ds_dt_trip'] = 0;
							$to_date['ds_dt_wmt'] = 0;
							$to_date['ds_total_wmt'] = 0;
							$to_date['ns_adt_unit'] = 0;
							$to_date['ns_adt_trip'] = 0;
							$to_date['ns_adt_wmt'] = 0;
							$to_date['ns_dt_unit'] = 0;
							$to_date['ns_dt_trip'] = 0;
							$to_date['ns_dt_wmt'] = 0;
							$to_date['ns_total_wmt'] = 0;
							$to_date['total_wmt'] = 0;

							$counter = count($result);

							foreach($result as $row):

								$to_date['ds_adt_unit'] += $row['ds_adt_unit'];
								$to_date['ds_adt_trip'] += $row['ds_adt_trip'];
								$to_date['ds_adt_wmt'] += $row['ds_adt_wmt'];
								$to_date['ds_dt_unit'] += $row['ds_dt_unit'];
								$to_date['ds_dt_trip'] += $row['ds_dt_trip'];
								$to_date['ds_dt_wmt'] += $row['ds_dt_wmt'];
								$to_date['ds_total_wmt'] += $row['ds_total_wmt'];
								$to_date['ns_adt_unit'] += $row['ns_adt_unit'];
								$to_date['ns_adt_trip'] += $row['ns_adt_trip'];
								$to_date['ns_adt_wmt'] += $row['ns_adt_wmt'];
								$to_date['ns_dt_unit'] += $row['ns_dt_unit'];
								$to_date['ns_dt_trip'] += $row['ns_dt_trip'];
								$to_date['ns_dt_wmt'] += $row['ns_dt_wmt'];
								$to_date['ns_total_wmt'] += $row['ns_total_wmt'];
								$to_date['total_wmt'] += ($row['ds_total_wmt'] + $row['ns_total_wmt']);

						 ?>
							<tr>
								<td><?php echo date('F-d-Y',strtotime($row['date'])); ?></td>
								<td><?php echo $row['ds_adt_unit']; ?></td>
								<td><?php echo $row['ds_adt_trip']; ?></td>
								<td><?php echo $row['ds_adt_wmt']; ?></td>
								<td><?php echo $row['ds_dt_unit']; ?></td>
								<td><?php echo $row['ds_dt_trip']; ?></td>
								<td><?php echo $this->extra->number_format($row['ds_dt_wmt']); ?></td>
								<td><?php echo $this->extra->number_format($row['ds_total_wmt']); ?></td>
								<td><?php echo $row['ns_adt_unit']; ?></td>
								<td><?php echo $row['ns_adt_trip']; ?></td>
								<td><?php echo $row['ns_adt_wmt']; ?></td>							
								<td><?php echo $row['ns_dt_unit']; ?></td>
								<td><?php echo $row['ns_dt_trip']; ?></td>
								<td><?php echo $this->extra->number_format($row['ns_dt_wmt']); ?></td>
								<td><?php echo $this->extra->number_format($row['ns_total_wmt']); ?></td>
								<td><?php echo $this->extra->number_format(($row['ds_total_wmt'] + $row['ns_total_wmt'])); ?></td>
							</tr>
						<?php endforeach; ?>
							<tr>
								<td>TO-DATE</td>
								<td><?php echo $to_date['ds_adt_unit']; ?></td>
								<td><?php echo $to_date['ds_adt_trip']; ?></td>
								<td><?php echo $to_date['ds_adt_wmt']; ?></td>
								<td><?php echo $to_date['ds_dt_unit']; ?></td>
								<td><?php echo $to_date['ds_dt_trip']; ?></td>
								<td><?php echo $this->extra->number_format($to_date['ds_dt_wmt']); ?></td>
								<td><?php echo $this->extra->number_format($to_date['ds_total_wmt']); ?></td>
								<td><?php echo $to_date['ns_adt_unit']; ?></td>
								<td><?php echo $to_date['ns_adt_trip']; ?></td>
								<td><?php echo $to_date['ns_adt_wmt']; ?></td>
								<td><?php echo $to_date['ns_dt_unit']; ?></td>
								<td><?php echo $to_date['ns_dt_trip']; ?></td>
								<td><?php echo $this->extra->number_format($to_date['ns_dt_wmt']); ?></td>
								<td><?php echo $this->extra->number_format($to_date['ns_total_wmt']); ?></td>
								<td><?php echo $this->extra->number_format($to_date['total_wmt']); ?></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Avg.Utilized Units</td>
								<td><?php echo $avg_unit_adt =@ round($to_date['ds_adt_unit']/$counter,2); ?></td>
								<td></td>
								<td></td>
								<td><?php echo $avg_unit_dt =@ round($to_date['ds_dt_unit']/$counter,2); ?></td>
								<td></td>
								<td></td>
								<td></td>
								<td><?php echo $avg_unit_adt_ns =@ round($to_date['ns_adt_unit']/$counter,2); ?></td>
								<td></td>
								<td></td>
								<td><?php echo $avg_unit_dt_ns  =@ round($to_date['ns_dt_unit']/$counter,2); ?></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Avg.Trips per Day</td>
								<td></td>
								<td><?php echo $avg_trips_adt_ds =@ round($to_date['ds_adt_trip']/$counter,2); ?></td>
								<td></td>
								<td></td>
								<td><?php echo $avg_trips_dt_ds =@ round($to_date['ds_dt_trip']/$counter,2); ?></td>
								<td></td>
								<td></td>
								<td></td>
								<td><?php echo $avg_trips_adt_ns =@ round($to_date['ns_adt_trip']/$counter,2); ?></td>
								<td></td>
								<td></td>
								<td><?php echo $avg_trips_dt_ns  =@ round($to_date['ns_dt_trip']/$counter,2); ?></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>

							<tr>
								<td>Avg. Trips/Units</td>
								<td><?php echo @round(($avg_trips_adt_ds/$avg_unit_adt),2); ?></td>
								<td></td>
								<td></td>
								<td><?php echo @round(($avg_trips_dt_ds/$avg_unit_dt),2); ?></td>
								<td></td>
								<td></td>
								<td></td>
								<td><?php echo @round(($avg_trips_adt_ns/$avg_unit_adt_ns),2); ?></td>
								<td></td>
								<td></td>
								<td><?php echo @round(($avg_trips_dt_ns/$avg_unit_dt_ns),2); ?></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
					</tbody>
				</table>

			