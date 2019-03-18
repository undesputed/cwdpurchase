
<div id="wrapper" style="width:1000px">
<div class="container">
	<div style="height:10px;"></div>

	<table id="item-table">
		<tbody>
			<tr>
				<th colspan="8" rowspan="3">
					<span class="center-text bold" style="display:block;"><?php echo $header['title']; ?></span>
					<span class="center-text bold" style="font-size:18px;display:block;">GATE PASS</span>
				</th>
			</tr>
			<tr>
			</tr>
			<tr>
			</tr>
			<tr style="height:50px;">
			</tr>
			<tr>
				<td colspan="6" style="border:0px;"></td>
				<td colspan="2" class="border"><strong>RIS No.</strong> <font style="text-decoration:underline;"><?php echo $main_data['WS NUMBER'];?></font>__________</td>
			</tr>
			<tr>
			</tr>
			<tr class="border">
				<th colspan="3">
					<span style="display:block;"><strong>Issued To:</strong> <font style="text-decoration:underline;"><?php echo $request_main['project_name'];?></font>_____________</span>
					<span style="display:block;"><strong>Address:</strong> <font style="text-decoration:underline;"><?php echo $request_main['project_location'];?></font>______________</span>
				</th>
				<th colspan="3">
					<span style="display:block;"><strong>Division:</strong></span>
					<span style="display:block;"><font style="text-decoration:underline;"><?php echo $main_data['project'];?></font></span>
				</th>
				<th colspan="2">
					<span style="display:block;"><strong>Date:</strong> <font style="text-decoration:underline;"><?php echo date('m/d/Y',strtotime($main_data['WS DATE']))?></font></span>
					<span style="display:block;"><strong>OR No:</strong> ___________________________________________</span>
				</th>
			</tr>
			<tr class="border">
				<th class="center-text" style="width:10%;">STOCK NO</th>
				<th class="center-text" style="width:30%;" colspan="2">ITEM/MATERIALS</th>
				<th class="center-text" style="width:5%;">ACCT</th>
				<th class="center-text" style="width:5%;">QTY</th>
				<th class="center-text" style="width:10%">UNIT/MEASURE</th>
				<th class="center-text" style="width:20%;">UNIT COST</th>
				<th class="center-text" style="width:20%;">TOTAL UNIT COST</th>
			</tr>
			<?php foreach($details as $row): ?>
			<tr class="border">
				<td class="center-text"><?php echo $row['item_no'];?></td>
				<td colspan="2"><?php echo $row['item_description'];?></td>
				<td>&nbsp;</td>
				<td class="center-text"><?php echo $row['withdrawn_quantity']; ?></td>
				<td class="center-text"><?php echo $row['unit_measure']; ?></td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>					
			</tr>
			<?php endforeach; ?>
			<tr class="border">
				<td class="center-text">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>					
			</tr>
			<tr class="border">
				<td class="center-text">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>					
			</tr>
			<tr class="border">
				<td class="center-text">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>					
			</tr>
			<tr class="border">
				<td class="center-text">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text">&nbsp;</td>					
			</tr>
			<tr>
				<td colspan="8"><strong>Purpose:</strong> <?php echo $main_data['remarks']; ?></td>				
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" class="center-text">________<font style="text-decoration:underline;font-weight:bold;text-align:center;"><?php echo $request_main['request_by'];?></font>_________</td>
				<td colspan="2" class="center-text"><font style="text-decoration:underline;font-weight:bold;">________________________________</font></td>
				<td colspan="2" class="center-text"><font style="text-decoration:underline;font-weight:bold;">________________________________</font></td>
				<td colspan="2" class="center-text"><font style="text-decoration:underline;font-weight:bold;">________________________________</font></td>
			</tr>
			<tr>
				<td colspan="2" class="center-text" style="font-size:11px;">Requested By</td>
				<td colspan="2" class="center-text" style="font-size:11px;">Guard</td>
				<td colspan="2" class="center-text" style="font-size:11px;">Issued By</td>
				<td colspan="2" class="center-text" style="font-size:11px;">Received By</td>
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

			<div class="round padding center-text margin-top bold dark">MATERIAL WITHDRAWAL SLIP</div>	

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
				<th>Qty</th>
				<th>Item Description</th>
				<th>Charges</th>				
			</tr>
		</thead>
		<tbody>
			<?php foreach($details as $row): ?>
				<tr class="border">
					<td class="center-text"><?php echo $row['item_no'];?></td>
					<td class="center-text"><?php echo $row['withdrawn_quantity']; ?></td>
					<td><?php echo $row['item_description']; ?></td>
					<td class="center-text">&nbsp;</td>					
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
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:110px;">
				<strong>Requested By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['withdraw_person_id']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['requested_By']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:110px;">
				<strong>Approved By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['withdraw_person_incharge']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['approved_By']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:110px;">
				<strong>Received By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['withdraw_receive_by']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['received_By']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
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
		
	