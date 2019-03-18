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
		<th style="text-align:center;">PO No</th>
		<th style="text-align:center;">PO Date</th>
		<th style="text-align:center;">Supplier</th>
		<th style="text-align:center;">Item Name</th>
		<th style="text-align:center;">Quantity</th>
		<th style="text-align:center;">Unit</th>
		<th style="text-align:center;">Unit Cost</th>
		<th style="text-align:center;">Total</th>
	</thead>
	<tbody>
		<?php
			if(count($result) > 0){
				foreach($result as $row){
		?>
		<tr>
			<td style="text-align:center;"><?php echo 'PO '.$row['reference_no'];?></td>
			<td style="text-align:center;"><?php echo date('m/d/Y',strtotime($row['po_date']));?></td>
			<td><?php echo $row['supplier'];?></td>
			<td><?php echo $row['item_name'];?></td>
			<td style="text-align:center;"><?php echo $row['quantity'];?></td>
			<td style="text-align:center;"><?php echo $row['unit_msr'];?></td>
			<td style="text-align:right;"><?php echo number_format(round($row['unit_cost'],2),2);?></td>
			<td style="text-align:right;"><?php echo number_format(round($row['total_unitcost'],2),2);?></td>
		</tr>
		<?php
				}
			}
		?>
	</tbody>
</table>