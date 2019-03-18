<div class="container">
	<h4>Item Price per Supplier</h4>
	<hr>
	<table id="tbl-lookup" class="table table-item">
		<thead>
			<tr>
				<th>PO Date</th>
				<th>PO No</th>
				<th style="width:200px">Supplier</th>
				<th style="width:200px">Item Name</th>
				<th>Item Price</th>
			</tr>
		</thead>
		<tbody>			
			<?php
				if(count($result) > 0){
					foreach($result as $row){
			?>
			<tr>
				<td><?php echo date('m/d/Y',strtotime($row['po_date']));?></td>
				<td><?php echo $row['po_no']; ?></td>
				<td><?php echo $row['supplier'];?></td>
				<td><?php echo $row['item_name'];?></td>
				<td style="text-align:right;"><?php echo number_format($row['unit_cost'],2);?></td>
			</tr>
			<?php
					}
				}
			?>
		</tbody>
		
	</table>
</div>
