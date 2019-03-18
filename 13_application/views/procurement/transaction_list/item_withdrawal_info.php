<?php 
	
?>

<input type="hidden" id="ws_date">
<input type="hidden" id="ws_no">

<div class="t-content">
	<div class="t-header">
		<a href="<?php echo base_url().index_page(); ?>/transaction_list/item_withdrawal" class="close close-info"><span aria-hidden="true">&times;</span><span></a>
		<h4 id="ws-no"><?php echo $main_data['WS NUMBER']; ?></h4>
	</div>	
	<div class="row">
		<div class="col-md-12">
			<div class="control-group">
				<a href="<?php echo base_url().index_page();?>/print/withdrawal/<?php echo $main_data['WS NUMBER']; ?>" target="_blank" class="action-status"><i class="fa fa-print"></i> Print</a>
			</div>
		</div>
	</div>
	<table id="item_list" class="table table-item">
		<thead>
			<tr>
				<th>Item Description</th>
				<th>Unit Measure</th>				
				<th>Withdrawn Qty</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($details as $row): ?>
				<tr>
					<td><?php echo $row['item_description']; ?></td>
					<td><?php echo $row['unit_measure']; ?></td>					
					<td><?php echo $row['withdrawn_quantity']; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
								
				<th>
					<div><span id="item-count"><?php echo count($details); ?></span> item(s)</div>					
				</th>
				<th></th>				
				<th></th>				
							
			</tr>			
		</tfoot>
	</table>
		
	<div class="row">
		<div class="col-xs-6">
			<div class="t-title">
				<div>Remarks/Purpose :</div>
				<strong><p><?php echo $main_data['remarks'] ?></p></strong>
			</div>
		</div>	
		<div class="col-xs-6">
			<div class="t-title">
				<div>Tenant :</div>
				<strong><p><?php echo $main_data['tenant_name'] ?></p></strong>
			</div>
		</div>	
	</div>	
	<div class="row">
		<div class="col-xs-6">
			<div class="t-title">
				<div>Requested By : </div>
				<strong><?php echo $main_data['requested_By']; ?></strong>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="t-title">
				<div>Approved By : </div>
				<strong><?php echo $main_data['approved_By']; ?></strong>
			</div>
		</div>
	</div>
		
</div>
<script>	
	$(function(){



	});
</script>
