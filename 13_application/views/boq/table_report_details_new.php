<style>
	.t1{
		display:block;
		font-size:11px;
	}
	.table-boq-report thead th{
		text-align: center;
		font-size:20px;
	}
	.table-boq-report tbody td{
		vertical-align: middle !important;
		text-align: center;
	}
	.number{
		font-size:15px;
	}

	.table-boq-report td{
		border : 1px solid #ccc;
	}
</style>

<table class="table table-bordered table-responsive">
	<thead>
		<tr>
			<th rowspan="2" style="vertical-align:middle;text-align:center;">ITEM NO.</th>
			<th rowspan="2" style="vertical-align:middle;text-align:center;">DESCRIPTION</th>
			<th rowspan="2" style="vertical-align:middle;text-align:center;">UNIT</th>
			<th rowspan="2" style="vertical-align:middle;text-align:center;">QTY</th>

			<th colspan="3" style="text-align:center;">UNIT COST</th>

			<th rowspan="2" style="vertical-align:middle;text-align:center;">AMT</th>
			
			<th colspan="3" style="text-align:center;">ACTUAL PO</th>

			<th rowspan='2' style="vertical-align:middle;text-align:center;">TOTAL BALANCE</th>
		</tr>
		<tr>
			<th style="text-align:center;">MATERIAL</th>
			<th style="text-align:center;">LABOR &amp; OTHER COST</th>
			<th style="text-align:center;">TOTAL</th>
			
			<th style="text-align:center;">QTY</th>
			<th style="text-align:center;">AMT</th>
			<th style="text-align:center;">DISC QTY</th>
	</thead>
	<tbody>
		<?php
			if(count($result) > 0){
				foreach($result as $row){
		?>
		<tr>
			<td><?php echo $row['item_no'];?></td>
			<td><?php echo $row['description'];?></td>
			<td style="text-align:center;"><?php echo $row['unit'];?></td>
			<td style="text-align:center;"><?php echo $row['qty'];?></td>
			<td style="text-align:center;"><?php echo $row['material'];?></td>
			<td style="text-align:right;"><?php echo $row['labor'];?></td>
			<td style="text-align:right;"><?php echo $row['total'];?></td>
			<td style="text-align:right;"><?php echo $row['amount'];?></td>
			<?php
				if(!empty($row['actual_qty'])){
			?>
			<td style="text-align:center;"><a data-id="<?php echo $row['id']?>" class="boq_details" style="cursor:pointer;"><?php echo $row['actual_qty'];?></a></td>
			<td style="text-align:right;"><?php echo number_format(round(($row['actual_qty'] * $row['actual_cost']),2),2);?></td>
			<td style="text-align:center;"><?php echo $row['qty'] - $row['actual_qty'];?></td>
		
			<td style="text-align:right;"><?php echo number_format(round((str_replace(',','',$row['amount']) - ($row['actual_qty'] * $row['actual_cost'])),2),2);?></td>
			<?php
				}else{
			?>
			<td></td>
			<td></td>
			<td></td>
			
			<td></td>
			<?php
				}
			?>
		</tr>
		<?php
				}
			}
		?>
	</tbody>
</table>