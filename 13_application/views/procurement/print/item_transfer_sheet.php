
<div id="wrapper" style="width:900px">
<div class="container">

	<!-- <div class="row">
		<div class="col-xs-8">
			<h2 class="title"><img src="<?php echo base_url('images/dcd_logo.jpg');?>"></h2>
			<div style="display:block;height:60px;">
				<span style="display:block"><?php echo $header['address']; ?></span>
				<span style="display:block"><?php echo $header['contact']; ?></span>
				<span style="display:block"><?php echo $header['fax_no']; ?></span>
				<span style="display:block"><?php echo $header['contact']; ?></span>
			</div>						
		</div>
	
		<div class="col-xs-4">

			<div style="display:block;height:60px;margin-top:1em">
				<span style="display:block" class="center-text"><?php echo $header['website']; ?></span>
				<span style="display:block" class="center-text"><?php echo $header['email']; ?></span>
			</div>

			<div class="round padding center-text margin-top bold dark">TRANSFER SHEET</div>	

			<div style="display:block;height:60px;">
				<span style="display:block"><strong>To :</strong> <?php echo $main_data['request_to']; ?></span>
				<span style="display:block"><strong>Attn :</strong> <?php echo $main_data['request_by']; ?></span>
				<span style="display:block"><strong>Date :</strong> <?php echo $main_data['transaction_date']; ?></span>
			</div>

		</div>
	</div> -->

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

			<div style="display:block;height:60px;margin-top:2em">
				<span style="display:block" class="center-text"><?php echo $header['website']; ?></span>
				<span style="display:block" class="center-text"><?php echo $header['email']; ?></span>
			</div>

			<div class="round padding center-text margin-top bold dark">TRANSFER SHEET</div>	

		</div>
	</div>

	<div class="round" style="margin-top:2em;">
		<table  style="width:100%">
			<tbody>
				<tr>
					<td rowspan="2" style="border-right:1px solid #999;width:420px">
						<div class="form-group" style="margin-left:6px">
							<strong style="border-bottom:1px solid #000;display:block">From : </strong>
							<strong><?php echo $main_data['created_from'] ?></strong>
						</div>
						<div class="form-group" style="margin-left:6px">
							<strong>&nbsp;</strong>
							<strong style="display:block"></strong>
							<strong style="display:block"></strong>
						</div>						
					</td>
					<td rowspan="2">
						<div class="form-group" style="margin-left:6px">
							<strong style="border-bottom:1px solid #000;display:block">To : </strong>
							<strong><?php echo $main_data['request_to_name']; ?></strong>
						</div>
						<div class="form-group" style="margin-left:6px">
							<strong>&nbsp;</strong>
							<strong style="display:block"></strong>
							<strong style="display:block"></strong>
						</div>
					</td>
				</tr>
			</tbody>
		</table>		
	</div>

	<div class="row" style="margin-top:1em;">
		<div class="col-xs-4">
			<div class="form-group round padding center-text">
				<strong>T.S. No. : </strong>
				<?php echo $main_data['transfer_no']; ?>
			</div>
		</div>
		
		<div class="col-xs-4">
			<div class="form-group round padding center-text">
				<strong>T.S. Date : </strong>
				<?php echo $main_data['transaction_date']; ?>
			</div>
		</div>
		
		<div class="col-xs-4">
			<div class="form-group round padding center-text">
				<strong>Expected Delivery Date : </strong>
				&nbsp;
			</div>
		</div>
		
	</div>

	<table id="item-table" style="margin-top:-1em;">
		<thead>
			<tr class="border">
				<th>Qty</th>
				<th>Unit</th>
				<th>Item Description</th>
				<th>Unit Cost</th>
				<th>Amount</th>								
			</tr>
		</thead>
		<tbody>
			<?php foreach($details as $row): ?>
				<tr>
					<td class="center-text"><?php echo $row['request_qty']; ?></td>
					<td class="center-text"><?php echo $row['unit_measure']; ?></td>
					<td><?php echo $row['item_description']; ?></td>										
					<td class="center-text">0.00</td>
					<td class="center-text">0.00</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="5">
					<div class="round padding">
						<strong>Remarks/Purpose :</strong>
						<span><?php echo $main_data['remarks'] ?></span>
					</div>
				</th>
			</tr>
			<tr class="border">
				<th colspan="5">
					<div><span id="item-count"><?php echo count($details); ?></span> item(s)</div>					
				</th>				
			</tr>
			<tr class="border">
				<td colspan="2" class="bold center-text">Project</td>
				<td class="center-text"><?php echo $main_data['created_from'];?></td>
				<td class="bold center-text">TOTAL</td>
				<td class="right-text bold"><?php echo '0.00'; ?></td>
			</tr>		
		</tfoot>
	</table>

	<div class="row" style="margin-top:2em;">
		
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:160px;">
				<strong class="center-text" style="border-bottom:1px solid #000;display:block">Prepared by</strong>			
				<img class="digital_signature" src="<?php //echo $this->extra->get_digital_signature($main_data['preparedBy']);?>">
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['preparedBy_name']; ?></strong>
				<span class="center-text" style="display:block;">DCD has the right to automatically cancel this P.O. should the supplier failed to deliver on agreed date without prior notice.</span>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:160px;">
				<strong class="center-text" style="border-bottom:1px solid #000;display:block">Noted by</strong>			
				<img class="digital_signature" src="<?php //echo $this->extra->get_digital_signature($main_data['notedby']);?>">
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php //echo $main_data['request_by']; ?></strong>
				<span class="center-text" style="display:block;">Terms &amp; conditions herein stated are accepted and we confirm that delivery goods/items will be effected:</span>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:160px;">
				<strong class="center-text" style="border-bottom:1px solid #000;display:block">Approved By: </strong>
				<img class="digital_signature" src="<?php //echo $this->extra->get_digital_signature($main_data['approvedBy']);?>">				
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['approvedBy_name'] ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">P.O. Received by:</strong>
				<strong style="border-bottom:1px solid #000;display:block">Position:</strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
	</div>

	<!-- <div class="row" style="margin-top:2em;">
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:110px;">
				<strong>Transmitted By: </strong>
				<div class="digital_signature" style=""><?php //echo $this->extra->get_digital_signature($main_data['prepared_by']);?></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['preparedBy_name']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		<div class="col-xs-4">
			
		</div>
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:110px;">
				<strong>Received By: </strong>
				<div class="digital_signature" style=""></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['request_by']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		
	</div> -->
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





