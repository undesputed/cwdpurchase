
<div id="wrapper" style="width:900px">
<div class="container">

	<div class="row">
		<div class="col-xs-8">
			<h2 class="title"><img src="<?php echo base_url('images/dcd_logo.jpg');?>" width="500"></h2>
			<div style="display:block;height:60px;">
				<span style="display:block"><?php echo $header['address']; ?></span>
				<span style="display:block"><?php echo $header['contact']; ?></span>
				<span style="display:block"><?php echo $header['fax_no']; ?></span>
			</div>						
		</div>
	
		<div class="col-xs-4">

			<div style="display:block;height:60px;margin-top:1em">
				<span style="display:block" class="center-text"><?php echo $header['website']; ?></span>
				<span style="display:block" class="center-text"><?php echo $header['email']; ?></span>
			</div>

			<div class="round padding center-text margin-top bold dark">GATE PASS</div>	
		</div>
	</div>

	<div class="row" style="margin-top:2em;">
		<div class="col-xs-6">
			<span style="display:block"><strong>To :</strong> <?php echo $main_data['request_to']; ?></span>
			<span style="display:block"><strong>Attention :</strong> <?php echo $main_data['request_by']; ?></span>
			<span style="display:block"><strong>From :</strong> <?php echo $main_data['created_from']; ?></span>
		</div>

		<div class="col-xs-2">
		</div>

		<div class="col-xs-4">
			<span style="display:block"><strong>Vehicle No. :</strong></span>
			<span style="display:block"><strong>Date :</strong> <?php echo $main_data['transaction_date']; ?></span>
		</div>
	</div>

	<table id="item-table" style="margin-top:1em;">
		<thead>
			<tr class="border">
				<th>Item #</th>
				<th>Qty</th>
				<th>Unit</th>
				<th>Material(s) Description</th>
				<th>Charges</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($details as $row): ?>
				<tr>
					<td class="center-text"><?php echo $row['item_no']; ?></td>
					<td class="center-text"><?php echo $row['request_qty']; ?></td>
					<td class="center-text"><?php echo $row['unit_measure']; ?></td>
					<td><?php echo $row['item_description']; ?></td>										
					<td class="center-text">&nbsp;</td>
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
			<div class="form-group round padding" style="height:110px;">
				<strong>Prepared By: </strong>
				<div class="digital_signature" style=""><?php //echo $this->extra->get_digital_signature($main_data['prepared_by']);?></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['preparedBy_name']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:110px;">
				<strong>Approved By: </strong>
				<div class="digital_signature" style=""><?php //echo $this->extra->get_digital_signature($main_data['approved_by']);?></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['approvedBy_name']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:110px;">
				<strong>Received By: </strong>
				<div class="digital_signature" style=""></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		
	</div>
	<div class="print_ft">
		<div class="row">
			<div class="col-md-6"><span class="copy"></span></div>
			<div class="col-md-6" ><span class="time_date"><span id="date">No Date</span> | <span id="time">No Time</span></span></div>
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





