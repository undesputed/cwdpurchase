
<div id="wrapper" style="width:900px">
<div class="container">
	
	<div class="row">
		<div class="col-xs-8">
			<h2 class="center-text title"><?php echo $header['title']; ?></h2>
			<div class="round padding  dark"><span class="center-text" style="border-bottom:1px solid #fff;display:block"><?php echo $header['sub_title']; ?></span></div>
			<div class="round padding center-text margin-top bold">Item Receiving</div>			
		</div>
	
		<div class="col-xs-4">

			<div style="display:block;height:60px;margin-top:2em">
				<span style="display:block" class="center-text"><?php echo $header['address']; ?></span>
				<span style="display:block" class="center-text"><?php echo $header['contact']; ?></span>
			</div>
						
			<div class="round">
				<div class="border-bottom po-content">
					<span class="po-label center-text">Ref. No:</span>
					<span class="center-text po-color" ><?php echo $main_data['transfer_no']; ?></span>
				</div>
				<div class="po-content">
					<span class="po-label center-text">Status:</span>
					<span class="center-text" ><?php echo $main_data['request_status']; ?></span>
				</div>
			</div>		

		</div>
	</div>
	

	<div class="round" style="margin-top:2em;">
		<table  style="width:100%">
			<tbody>
				<tr>
					<td rowspan="2" style="border-right:1px solid #999;width:420px">
						<div class="form-group" style="margin-left:6px">
							<strong style="display:block">Created from :</strong>
							<span><?php echo $main_data['created_from']; ?></span>
						
						</div>
						<div class="form-group" style="margin-left:6px">
							<strong>Designation :</strong>
							<span style="display:block"><?php echo $main_data['request_to_name']; ?></span>
						</div>
					</td>
					<td colspan="2" style="vertical-align:top">
						<strong>Transaction Date:</strong>
						<p><?php echo $main_data['transaction_date']; ?></p>
						
					</td>
				</tr>			
			</tbody>
		</table>
	</div>

	<table id="item-table">
		<thead>
			<tr class="border">
				<th>Item Description</th>
				<th>Qty</th>
				<th>Unit Measure</th>								
			</tr>
		</thead>
		<tbody>
			<?php foreach($details as $row): ?>
				<tr>
					<td><?php echo $row['item_description']; ?></td>										
					<td class="center-text"><?php echo $row['request_qty']; ?></td>
					<td class="center-text"><?php echo $row['unit_measure']; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr class="border">
				<th colspan="3">
					<div><span id="item-count"><?php echo count($details); ?></span> item(s)</div>					
				</th>				
			</tr>			
		</tfoot>
	</table>

	<div class="row" style="margin-top:5px">
		<div class="col-xs-12">
			<div class="round space">
				<strong>Remarks/Purpose :</strong>
				<div><?php echo $main_data['remarks'] ?></div>
			</div>
		</div>
	</div>

	<div class="row" style="margin-top:2em;">
		<div class="col-xs-4">
			
		</div>
		<div class="col-xs-4">
			<div class="form-group round padding signatory-panel">
				<strong>Requested By: </strong>
				<div class="digital_signature" style=""></div>
				<strong class="signatory"><?php echo $main_data['request_by']; ?></strong>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group round padding signatory-panel">
				<strong>Prepared By: </strong>
				<div class="digital_signature" style="<?php echo $this->extra->get_digital_signature($main_data['prepared_by']);?>"></div>
				<strong class="signatory"><?php echo $main_data['preparedBy_name']; ?></strong>
			</div>
		</div>
	</div>
	<div class="print_ft">
		<div class="row">
			<div class="col-md-9"><span class="copy"></span></div>
			<div class="col-md-3" ><span class="time_date"><span id="date">No Date</span> | <span id="time">No Time</span></span></div>
		</div>
	</div>
</div>
</div>
<script>
$(function(){

	var d = new Date();

	var month = d.getMonth()+1;
	var day = d.getDate();

	var output = d.getFullYear() + '/' +
	    (month<10 ? '0' : '') + month + '/' +
	    (day<10 ? '0' : '') + day;

	var dt = new Date();
	var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();

	    $('#date').html(output);
	    $('#time').html(time);


});



</script>





