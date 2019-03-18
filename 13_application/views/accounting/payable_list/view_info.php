<div class="container">
	<h4><?php echo $result[0]['Supplier']; ?> <small>payables</small></h4>
	<table class="table" id="tbl_view_info">
		<thead>
			<tr>
				<th>PO Date</th>				
				<th>PO No.</th>	
				<th>PO Total</th>			
				<th>Project Site</th>
				<th>SI / DR</th>
				<th style="text-align:right;">Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php $total = 0; ?>
			<?php foreach($projects as $key=>$r): ?>
			<?php $sub_total = 0; ?>
				<tr>
					<th colspan="2"><?php echo $key; ?></th>					
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			<?php foreach($r as $row): ?>
				<tr>
					<td><?php echo date('F d, Y',strtotime($row['po_date'])); ?></td>
					<td><?php echo $row['reference_no']; ?></td>
					<td><?php echo $this->extra->number_format($row['total_cost']); ?></td>
					<td><?php echo $row['project_requestor']; ?></td>
					<td><?php echo $row['supplier_invoice']; ?></td>
					<td style="text-align:right;"><?php echo $this->extra->number_format($row['si_amount']); ?></td>
				</tr>
			<?php  $total = $total + $row['si_amount'];?>
			<?php  $sub_total = $sub_total + $row['si_amount'];?>
			<?php endforeach; ?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>					
					<td style="text-align:right;">Sub total : <?php echo $this->extra->number_format($sub_total); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td style="text-align:right;">Total Amount :   <strong> <?php echo $this->extra->number_format($total); ?></strong>	</td>
			</tr>
		</tfoot>

	</table>

	<?php 
	/*	echo "<pre>";
		print_r($result);
		echo "</pre>";*/
	?>

</div>

<script>
	$(function(){

		/*$('#tbl_view_info').dataTable(datatable_option);*/

	});
</script>