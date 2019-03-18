
<input type="hidden" id="is_date">
<input type="hidden" id="is_no">

<div class="t-content">
	<div class="t-header">
		<a href="<?php echo base_url().index_page(); ?>/transaction_list/item_withdrawal" class="close"><span aria-hidden="true">&times;</span><span></a>
		<h4 id="ws-no"><?php echo $main_data['transfer_no']; ?></h4>
	</div>
	<div class="row">
		<div class="col-xs-12">
			
			<div class="control-group">
				<a href="<?php echo base_url().index_page();?>/print/item_transfer_report/<?php echo $main_data['transfer_no']; ?>" target="_blank" class="action-status"><i class="fa fa-print"></i> Print</a>
				<!-- <a href="<?php echo base_url().index_page();?>/print/item_transfer_report/<?php echo $main_data['transfer_no']; ?>/transfer_sheet" target="_blank" class="action-status"><i class="fa fa-print"></i> Print Transfer Sheet</a>
				<a href="<?php echo base_url().index_page();?>/print/item_transfer_gate_pass/<?php echo $main_data['transfer_no']; ?>" target="_blank" class="action-status"><i class="fa fa-print"></i> Print Gate Pass</a> -->
			</div>
		
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="t-title">
				<div>Request to :</div>	
				<strong> <?php echo $main_data['request_to_name']; ?></strong>
			</div>			
		</div>
		
	</div>
	<table id="item_list" class="table table-item">
		<thead>
			<tr>							
				<th>Item Description</th>
				<th>Unit Measure</th>				
				<th>Issued Qty</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($details as $row): ?>
				<tr>
					<td><?php echo $row['item_description']; ?></td>
					<td><?php echo $row['unit_measure']; ?></td>					
					<td><?php echo $row['request_qty']; ?></td>
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
		<div class="col-xs-12">
			<div class="t-title">
				<div>Remarks/Purpose :</div>
				<strong><p><?php echo $main_data['remarks'] ?></p></strong>
			</div>
		</div>		
	</div>	
	<div class="row">
		<div class="col-xs-6">
			<div class="t-title">
				<div>Request By : </div>
				<strong><?php echo $main_data['request_by']; ?></strong>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="t-title">
				<div>Prepared By : </div>
				<strong><?php echo $main_data['preparedBy_name']; ?></strong>
			</div>
		</div>
	</div>
		
</div>
<script>	
	$(function(){



	});
</script>
