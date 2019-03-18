
<div id="wrapper" style="width:900px">
<div class="container">
	<div style="height:10px;"></div>

	<table id="item-table">
		<tbody>
			<tr>
				<th colspan="8" rowspan="3">
					<span class="center-text bold" style="font-size:18px;display:block;">REQUISITION AND ISSUANCE SLIP</span>
					<span class="center-text bold" style="display:block;"><?php echo $header['title']; ?></span>
					<span class="center-text" style="display:block;"><?php echo $header['address']; ?></span>
				</th>
			</tr>
			<tr>
			</tr>
			<tr>
			</tr>
			<tr style="height:30px;">
			</tr>
			<tr>
				<th colspan="4">Entity Name: __________________________</th>
				<th>&nbsp;</th>
				<th colspan="3">Fund Cluster: _______________________________</th>
			</tr>
			<tr class="border">
				<td colspan="4">
					<span style="display:block;"><strong>Division:</strong> <font style="text-decoration:underline;"><?php echo $main_data['request_to_name'];?></font>_______</span>
    				<span style="display:block;"><strong>Office:</strong> _______________________________</span>
				</td>
				<td colspan="4">
					<span style="display:block;"><strong>Responsibility Center Code:</strong> _______________________</span>
					<span style="display:block;"><strong>RIS No:</strong> <font style="text-decoration:underline;"><?php echo $main_data['transfer_no'];?></font>_______</span>
				</td>
			</tr>
			<tr class="border">
				<th colspan="4" class="center-text">Requisition</th>
				<th colspan="2" class="center-text">Stock Available?</th>
				<th colspan="2" class="center-text">Issue</th>
			</tr>
			<tr class="border">
				<th class="center-text">Stock No.</th>
				<th class="center-text">Unit</th>
				<th class="center-text">Description</th>
				<th class="center-text">Quantity</th>
				<th class="center-text">Yes</th>
				<th class="center-text">No</th>
				<th class="center-text">Quantity</th>
				<th class="center-text">Remarks</th>
			</tr>
			<?php foreach($details as $row): ?>
			<tr class="border">
				<td class="center-text"><?php echo $row['item_no']; ?></td>
				<td class="center-text"><?php echo $row['unit_measure']; ?></td>
				<td><?php echo $row['item_description']; ?></td>
				<td class="center-text"><?php echo $row['request_qty']; ?></td>				
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
			</tr>
			<?php endforeach; ?>
			<tr class="border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<th>&nbsp;</th>
				<th colspan="2" class="center-text" style="font-weight:bold;">Recapitulation</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th colspan="3" class="center-text" style="font-weight:bold;">Recapitulation</th>
			</tr>
			<tr class="border">
				<th>&nbsp;</th>
				<th class="center-text" style="font-weight:bold;">Stock No.</th>
				<th class="center-text" style="font-weight:bold;">Quantity</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th class="center-text" style="font-weight:bold;">Unit Cost</th>
				<th class="center-text" style="font-weight:bold;">Total Cost</th>
				<th class="center-text" style="font-weight:bold;">UACS Object Code</th>
			</tr>
			<tr class="border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td colspan="8"><strong>Purpose:</strong> <font style="text-decoration:underline;"><?php echo $main_data['remarks'];?></font></td>
			</tr>
		</tbody>
		<tfoot>
			<tr class="border">
				<th colspan="2">&nbsp;</th>
				<th style="font-weight:bold;">Requested by:</th>
				<th colspan="2" style="font-weight:bold;">Approved by:</th>
				<th colspan="2" style="font-weight:bold;">Issued by:</th>
				<th style="font-weight:bold;">Received by:</th>
			</tr>
			<tr class="border">
				<td colspan="2">Signature:</td>
				<td>&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td colspan="2">Printed Name:</td>
				<td>&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td colspan="2">Designation:</td>
				<td>&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td colspan="2">Date:</td>
				<td>&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</tfoot>
	</table>

	<div class="print_ft">
		<div class="row" style="font-weight:bold;">
			<div class="col-md-4"><span>FM-WHS-01</span></div>
			<div class="col-md-4" style="text-align:center;"><span>00</span></div>
			<div class="col-md-4"><span class="time_date"><span>8/20/2016</span></span></div>
		</div>
	</div>




	<!-- <div class="row">
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

			<div class="round padding center-text margin-top bold dark">MATERIAL/SUPPLIES REQUISITION SLIP</div>	
		</div>
	</div>

	<div class="row" style="margin-top:2em;">
		<div class="col-xs-6">
			
		</div>

		<div class="col-xs-2">
		</div>

		<div class="col-xs-4">
			<span class="po-label bold">No : </span><span><?php echo $main_data['transfer_no']; ?></span>
			<span class="po-label bold">Date : </span><span><?php echo $main_data['transaction_date'];?></span>
		</div>
	</div>

	<table id="item-table" style="margin-top:1em;">
		<thead>
			<tr class="border">
				<th>Item #</th>
				<th>Qty</th>
				<th>Unit</th>
				<th>Material(s) / Description</th>
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
				<strong>Requested By: </strong>
				<div class="digital_signature" style=""></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['request_by']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:110px;">
				<strong>Noted By: </strong>
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
	</div>
	<div class="print_ft">
		<div class="row">
			<div class="col-md-6"><span class="copy"></span></div>
			<div class="col-md-6" ><span class="time_date"><span id="date">No Date</span> | <span id="time">No Time</span></span></div>
		</div>
	</div> -->
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





