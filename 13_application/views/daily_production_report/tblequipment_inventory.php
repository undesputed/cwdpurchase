

<table class="table table-condensed table-report">
		<thead>
		<tr class="cf-1">
			<td rowspan="2" style="vertical-align:middle">Vehicle Type</td>

			<td colspan="4">In-House</td>
			<td colspan="4">S & T</td>
			<td colspan="4">SKAFF</td>
			<td colspan="4">PBA</td>
			<td colspan="4">UMRC</td>
			<td colspan="4">CEBUROCKS</td>
			<td colspan="4">ATS</td>
			<td colspan="4">R-CUBE</td>
			<td colspan="4">MDV</td>
			<td colspan="4">XG</td>
		</tr>
		<tr class="cf-2">			
			<td>Total</td>
			<td>Operational</td>
			<td>MA</td>
			<td>PA</td>
			<!---->
			<td>Total</td>
			<td>Operational</td>
			<td>MA</td>
			<td>PA</td>
			<!---->
			<td>Total</td>
			<td>Operational</td>
			<td>MA</td>
			<td>PA</td>
			<!---->
			<td>Total</td>
			<td>Operational</td>
			<td>MA</td>
			<td>PA</td>
			<!---->
			<td>Total</td>
			<td>Operational</td>
			<td>MA</td>
			<td>PA</td>
			<!---->
			<td>Total</td>
			<td>Operational</td>
			<td>MA</td>
			<td>PA</td>
			<!---->
			<td>Total</td>
			<td>Operational</td>
			<td>MA</td>
			<td>PA</td>
				<!---->
			<td>Total</td>
			<td>Operational</td>
			<td>MA</td>
			<td>PA</td>
				<!---->
			<td>Total</td>
			<td>Operational</td>
			<td>MA</td>
			<td>PA</td>
				<!---->
			<td>Total</td>
			<td>Operational</td>
			<td>MA</td>
			<td>PA</td>
		</tr>
		</thead>
		<tbody>
		<?php foreach($data as $row): ?>
		<tr>
			<td><?php echo $row['EQUIPMENT'] ?></td>
			<td><?php echo $row['NUMBER OF UNIT'] ?></td>
			<td><?php echo $row['UTILIZED UNITS'] ?></td>
			<td><?php echo $row['MA'] ?></td>
			<td><?php echo $row['PA'] ?></td>
		
			<td><?php echo $row['NUMBER OF UNIT S&T'] ?></td>
			<td><?php echo $row['UTILIZED UNITS S&T'] ?></td>
			<td><?php echo $row['MA S&T'] ?></td>
			<td><?php echo $row['PA S&T'] ?></td>

			<td><?php echo $row['NUMBER OF UNIT SCAFF'] ?></td>
			<td><?php echo $row['UTILIZED UNITS SCAFF'] ?></td>
			<td><?php echo $row['MA SCAFF'] ?></td>
			<td><?php echo $row['PA SCAFF'] ?></td>

			<td><?php echo $row['NUMBER OF UNIT PBA'] ?></td>
			<td><?php echo $row['UTILIZED UNITS PBA'] ?></td>
			<td><?php echo $row['MA PBA'] ?></td>
			<td><?php echo $row['PA PBA'] ?></td>
			
			<td><?php echo $row['NUMBER OF UNIT UMRC'] ?></td>
			<td><?php echo $row['UTILIZED UNITS UMRC'] ?></td>
			<td><?php echo $row['MA UMRC'] ?></td>
			<td><?php echo $row['PA UMRC'] ?></td>

			<td><?php echo $row['NUMBER OF UNIT CEBUROCKS'] ?></td>
			<td><?php echo $row['UTILIZED UNITS CEBUROCKS'] ?></td>
			<td><?php echo $row['MA CEBUROCKS'] ?></td>
			<td><?php echo $row['PA CEBUROCKS'] ?></td>


			<td><?php echo $row['NUMBER OF UNIT ATS'] ?></td>
			<td><?php echo $row['UTILIZED UNITS ATS'] ?></td>
			<td><?php echo $row['MA ATS'] ?></td>
			<td><?php echo $row['PA ATS'] ?></td>

			
			<td><?php echo $row['NUMBER OF UNIT R-CUBE'] ?></td>
			<td><?php echo $row['UTILIZED UNITS R-CUBE'] ?></td>
			<td><?php echo $row['MA R-CUBE'] ?></td>
			<td><?php echo $row['PA R-CUBE'] ?></td>

			
			<td><?php echo $row['NUMBER OF UNIT MDV'] ?></td>
			<td><?php echo $row['UTILIZED UNITS MDV'] ?></td>
			<td><?php echo $row['MA MDV'] ?></td>
			<td><?php echo $row['PA MDV'] ?></td>


			<td><?php echo $row['NUMBER OF UNIT XG'] ?></td>
			<td><?php echo $row['UTILIZED UNITS XG'] ?></td>
			<td><?php echo $row['MA XG'] ?></td>
			<td><?php echo $row['PA XG'] ?></td>
			

		</tr>
		<?php endforeach; ?>
	</tbody>
</table>