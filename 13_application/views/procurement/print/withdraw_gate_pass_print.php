
<div id="wrapper" style="width:900px">
<div class="container">
		
	<div class="row">
		<div class="col-xs-8">
			<h2 class="title"><img src="<?php echo base_url('images/dcd_logo.jpg');?>" width="200"></h2>
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

			<div class="round padding center-text margin-top bold dark">GATE PASS</div>	

		</div>
	</div>

	<div class="row" style="margin-top:3em;">
		<div class="col-xs-6">
			<span class="po-label bold">Issued To : </span><span><?php echo $main_data['project'];?></span>
			<span class="po-label bold">Address : </span><span><?php echo $main_data['project_location'];?></span>
		</div>

		<div class="col-xs-2">
		</div>

		<div class="col-xs-4">
			<span class="po-label bold">No : </span><span><?php echo $main_data['WS NUMBER']; ?></span>
			<span class="po-label bold">Date : </span><span><?php echo $main_data['WS DATE'];?></span>
		</div>
	</div>

	<table id="item-table">
		<thead>
			<tr class="border">
				<th>Item No</th>
				<th>Item Description</th>
				<th>Qty</th>
				<th>Unit Measure</th>
				<th>Unit Cost</th>				
				<th>Total Cost</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($details as $row): ?>
				<tr class="border">
					<td class="center-text"><?php echo $row['item_no'];?></td>
					<td><?php echo $row['item_description']; ?></td>
					<td class="center-text"><?php echo $row['withdrawn_quantity']; ?></td>
					<td class="center-text"><?php echo $row['unit_measure'];?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>					
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
	<div class="row ">
		<div class="col-xs-12">
			<div class="round space padding">
			<strong>Remarks/Purpose :</strong>
			<div><?php echo $main_data['remarks'] ?></div>
			</div>
		</div>
	</div>
	
	<div class="row" style="margin-top:25px;">
		<div class="col-xs-3">
			<div class="form-group round padding" style="height:110px;">
				<strong>Requested By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['withdraw_person_id']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['requested_By']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		<div class="col-xs-3">
			<div class="form-group round padding" style="height:110px;">
				<strong>Guard : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['withdraw_person_incharge']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block">&nbsp;</strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		<div class="col-xs-3">
			<div class="form-group round padding" style="height:110px;">
				<strong>Issued By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['withdraw_person_incharge']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block">&nbsp;</strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		<div class="col-xs-3">
			<div class="form-group round padding" style="height:110px;">
				<strong>Received By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['withdraw_receive_by']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['received_By']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
	</div>

	<div class="print_ft">
		<div class="row">
			<div class="col-md-4"><span>FM-WHS-01</span></div>
			<div class="col-md-4" style="text-align:center;"><span>00</span></div>
			<div class="col-md-4"><span class="time_date"><span>8/20/2016</span></span></div>
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
		
	