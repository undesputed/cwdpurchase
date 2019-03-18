

<div class="container">

<div class="row">
	<div class="col-md-12">
		<h4><b>PO NO :</b> <?php echo $main_data['reference_no']; ?></h4>
	</div>
</div>

<div class="row">
	<div class="col-md-4 col-info">		
		<b>PO Date:</b> <?php echo $main_data['po_date']; ?><br>
		<b>Remarks:</b> <?php echo $main_data['po_remarks']; ?><br>
		<?php 
			switch($main_data['status']){
				case "APPROVED":
					$label = "No Delivery";
				break;
				case "PARTIAL":
					$label = "Partial Delivery";
				break;
				case "COMPLETE":
					$label = "Full Delivery";
				break;
			}			
		 ?>
		<b>Status:</b> <?php echo $label; ?><br>
	</div>
	<div class="col-md-4 col-info">
		<b>Supplier:</b> <?php echo $main_data['Supplier']; ?><br>
		<b>Delivery Date:</b> <?php echo $main_data['po_date']; ?><br>
	</div>
	<div class="col-md-4 col-info">
		<b>Invoice:</b> <br>		
	</div>
</div>


<table class="table" style="margin-top:3em">
	<thead>
		<tr>
			<th>Item Name</th>
			<th>Unit</th>
			<th>P.O Qty</th>			
			<th>Unit Cost</th>
			<th>Qty Received</th>
			<th>Discrepancy</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($result as $row): ?>
		<tr>
			<td><?php echo $row['item_name']; ?></td>
			<td><?php echo $row['unit_msr']; ?></td>
			<td><?php echo $row['quantity']; ?></td>			
			<td><?php echo $row['unit_cost']; ?></td>			
			<td></td>
			<td></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<td><?php echo count($result) ?> Item(s)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tfoot>
</table>


</div>