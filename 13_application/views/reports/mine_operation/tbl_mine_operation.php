<style>

	.h-1{
		font-weight: bold;
		text-align: center;
		background:#f2f2f2;

	}	
	.h-2{
		font-weight: bold;
		background:#f2f2f2;
	}
	.h-total{
		font-weight: bold;
	}



</style>

<div class="content-title">
	<h3><?php echo $title; ?></h3>
</div>

<table class="table table-condensed table-bordered table-hover">
	<tbody>

		<tr class="h-1">
			<td rowspan="2" style="vertical-align:middle">Date</td>
			<td colspan="7">Dayshift</td>
			<td colspan="7">NightShift</td>
			<td rowspan="2" style="vertical-align:middle">Total Wmt</td>				
		</tr>

		<tr class="h-2">
			<td>ADT Unit</td>
			<td>ADT Trip</td>
			<td>ADT Wmt</td>
			<td>DT Unit</td>
			<td>DT Trip</td>
			<td>DT Wmt</td>
			<td>Total Wmt</td>
			
			<td>ADT Unit</td>
			<td>ADT Trip</td>
			<td>ADT Wmt</td>
			<td>DT Unit</td>
			<td>DT Trip</td>
			<td>DT Wmt</td>
			<td>Total Wmt</td>
		</tr>
		<?php 
			$counter = 0;
			$total['ADT UNIT DAY'] = 0;
			$total['ADT TRIP DAY'] = 0;
			$total['ADT WMT DAY'] = 0;

			$total['DT UNIT DAY'] = 0;
			$total['DT TRIP DAY'] = 0;
			$total['DT WMT DAY'] = 0;
			$total['TOTAL WMT DAY'] = 0;


			$total['ADT UNIT NIGHT'] =  0;
			$total['ADT TRIP NIGHT'] = 0;
			$total['ADT WMT NIGHT'] = 0;

			$total['DT UNIT NIGHT'] = 0;
			$total['DT TRIP NIGHT'] = 0;
			$total['DT WMT NIGHT'] = 0;
			$total['TOTAL WMT NIGHT'] = 0;
			$total['TOTAL WMT'] = 0;



		 ?>

		<?php foreach($result as $row): ?>
		<tr>
			<td style="text-align:center"><?php echo date('M d',strtotime($row['DATE'])); ?></td>
			
			<td><?php echo $this->extra->comma($row['ADT UNIT DAY']); ?></td>
			<td><?php echo  $this->extra->comma($row['ADT TRIP DAY']); ?></td>
			<td><?php echo  $this->extra->comma($row['ADT WMT DAY']); ?></td>
			<td><?php echo  $this->extra->comma($row['DT UNIT DAY']); ?></td>
			<td><?php echo  $this->extra->comma($row['DT TRIP DAY']); ?></td>
			<td><?php echo  $this->extra->comma($row['DT WMT DAY']); ?></td>
			<td><?php echo  $this->extra->comma($row['TOTAL WMT DAY']); ?></td>
			
			<td><?php echo  $this->extra->comma($row['ADT UNIT NIGHT']); ?></td>
			<td><?php echo  $this->extra->comma($row['ADT TRIP NIGHT']); ?></td>
			<td><?php echo  $this->extra->comma($row['ADT WMT NIGHT']); ?></td>
			<td><?php echo  $this->extra->comma($row['DT UNIT NIGHT']); ?></td>
			<td><?php echo  $this->extra->comma($row['DT TRIP NIGHT']); ?></td>
			<td><?php echo  $this->extra->comma($row['DT WMT NIGHT']); ?></td>
			<td><?php echo  $this->extra->comma($row['TOTAL WMT NIGHT']); ?></td>
			
			<td class="h-total"><?php echo $this->extra->comma($row['TOTAL WMT']); ?></td>

			<?php 
				$counter ++;
				$total['ADT UNIT DAY'] += $row['ADT UNIT DAY'];
				$total['ADT TRIP DAY'] += $row['ADT TRIP DAY'];
				$total['ADT WMT DAY'] += $row['ADT WMT DAY'];

				$total['DT UNIT DAY'] += $row['DT UNIT DAY'];
				$total['DT TRIP DAY'] += $row['DT TRIP DAY'];
				$total['DT WMT DAY'] += $row['DT WMT DAY'];
				$total['TOTAL WMT DAY'] += $row['TOTAL WMT DAY'];

				$total['ADT UNIT NIGHT'] += $row['ADT UNIT NIGHT'];
				$total['ADT TRIP NIGHT'] += $row['ADT TRIP NIGHT'];
				$total['ADT WMT NIGHT'] += $row['ADT WMT NIGHT'];

				$total['DT UNIT NIGHT'] += $row['DT UNIT NIGHT'];
				$total['DT TRIP NIGHT'] += $row['DT TRIP NIGHT'];
				$total['DT WMT NIGHT'] += $row['DT WMT NIGHT'];
				$total['TOTAL WMT NIGHT'] += $row['TOTAL WMT NIGHT'];

				$total['TOTAL WMT'] += $row['TOTAL WMT'];

			 ?>
						
		</tr>
		<?php endforeach; ?>
		<?php 

			$avg['adt_unit_day'] =@ ($total['ADT UNIT DAY'] / $counter);
			$avg['adt_trips_day'] =@ ($total['ADT TRIP DAY'] / $counter);
			$avg['adt_trip_unit_day'] =@ ($avg['adt_trips_day'] / $avg['adt_unit_day']);

			$avg['dt_unit_day'] =@ ($total['DT UNIT DAY'] / $counter);
			$avg['dt_trips_day'] =@ ($total['DT TRIP DAY'] / $counter);
			$avg['dt_trip_unit_day'] =@ ($avg['dt_trips_day'] / $avg['dt_unit_day']);

			$avg['adt_unit_night']      =@ ($total['ADT UNIT NIGHT'] / $counter);
			$avg['adt_trips_night']     =@ ($total['ADT TRIP NIGHT'] / $counter);
			$avg['adt_trip_unit_night'] =@ ($avg['adt_trips_night'] / $avg['adt_unit_night']);

			$avg['dt_unit_night']      =@ ($total['DT UNIT NIGHT'] / $counter);
			$avg['dt_trips_night']     =@ ($total['DT TRIP NIGHT'] / $counter);
			$avg['dt_trip_unit_night'] =@ ($avg['dt_trips_night'] / $avg['dt_unit_night']);



		 ?>
		<tr class=h-total>
			<td style="text-align:center">Total</td>

			<td></td>
			<td><?php echo $this->extra->comma($total['ADT TRIP DAY']); ?></td>
			<td><?php echo $this->extra->comma($total['ADT WMT DAY']); ?></td>
			<td></td>
			<td><?php echo $this->extra->comma($total['DT TRIP DAY']); ?></td>
			<td><?php echo $this->extra->comma($total['DT WMT DAY']); ?></td>
			<td><?php echo $this->extra->comma($total['TOTAL WMT DAY']); ?></td>
			<td></td>
			<td><?php echo $this->extra->comma($total['ADT TRIP NIGHT']); ?></td>
			<td><?php echo $this->extra->comma($total['ADT WMT NIGHT']); ?></td>
			<td></td>
			<td><?php echo $this->extra->comma($total['DT TRIP NIGHT']); ?></td>
			<td><?php echo $this->extra->comma($total['DT WMT NIGHT']); ?></td>
			<td><?php echo $this->extra->comma($total['TOTAL WMT NIGHT']); ?></td>

			<td><?php echo $this->extra->comma($total['TOTAL WMT']); ?></td>
		</tr>

		<tr>
			<td colspan="16"></td>
		</tr>

		<tr>
			<td>Ave.Utilized Units</td>
			<td><?php echo $this->extra->number_format($avg['adt_unit_day']); ?></td>
			<td></td>
			<td></td>
			<td><?php echo $this->extra->number_format($avg['dt_unit_day']); ?></td>
			<td></td>
			<td></td>
			<td></td>
			<td><?php echo $this->extra->number_format($avg['adt_unit_night']); ?></td>
			<td></td>
			<td></td>
			<td><?php echo $this->extra->number_format($avg['dt_unit_night']); ?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Ave.Trips per Shift</td>
			<td></td>
			<td><?php echo $this->extra->number_format($avg['adt_trips_day']); ?></td>
			<td></td>
			<td></td>
			<td><?php echo $this->extra->number_format($avg['dt_trips_day']); ?></td>
			<td></td>
			<td></td>
			<td></td>
			<td><?php echo $this->extra->number_format($avg['adt_trips_night']) ?></td>
			<td></td>
			<td></td>
			<td><?php echo $this->extra->number_format($avg['dt_trips_night']) ?></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Ave.Trips / Unit</td>
			<td><?php echo $this->extra->number_format($avg['adt_trip_unit_day']); ?></td>
			<td></td>
			<td></td>
			<td><?php echo $this->extra->number_format($avg['dt_trip_unit_day']); ?></td>
			<td></td>
			<td></td>
			<td></td>
			<td><?php echo $this->extra->number_format($avg['adt_trip_unit_night']); ?></td>
			<td></td>
			<td></td>
			<td><?php echo $this->extra->number_format($avg['dt_trip_unit_night']); ?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>


	</tbody>
	
</table>