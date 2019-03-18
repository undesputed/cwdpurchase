

<div id="wrapper" style="width:1000px">
<div class="container">
	<div style="height:10px;"></div>
	
	<table id="item-table">
		<tbody>
			<tr>
				<th colspan="8" rowspan="3">
					<span class="center-text bold" style="font-size:18px;display:block;">REPORT OF SUPPLIER AND MATERIALS ISSUED</span>
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
				<th colspan="4">Serial No.: <font style="text-decoration:underline;"><?php echo $main_data['issuance_no'];?></font>___________</th>
			</tr>
			<tr>
				<th colspan="4">Fund Cluster: __________________________</th>
				<th colspan="4">Date: <font style="text-decoration:underline;"><?php echo $main_data['date_issued'];?></font>________</th>
			</tr>
			<tr class="border">
				<td colspan="6" class="center-text" style="font-style:italic;">To be filled up by the Supply and/or Property Division/Unit</td>
				<td colspan="2" class="center-text" style="font-style:italic;">To be filled up by the Accounting Division/Unit</td>
			</tr>
			<tr class="border">
				<th class="center-text">RIS No.</th>
				<th class="center-text">Responsibility Center Code</th>
				<th class="center-text">Stock No.</th>
				<th class="center-text">Item</th>
				<th class="center-text">Unit</th>
				<th class="center-text">Quantity Issued</th>
				<th class="center-text">Unit Cost</th>
				<th class="center-text">Amount</th>
			</tr>
			<?php
				if(count($details) > 0){
					foreach($details as $row){
			?>
			<tr class="border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="center-text"><?php echo $row['item_no'];?></td>
				<td><?php echo $row['item_description'];?></td>
				<td class="center-text"><?php echo $row['unit_measure'];?></td>
				<td class="center-text"><?php echo $row['issued_qty'];?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?php
					}
				}
			?>
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
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</tbody>
	</table>

	<div class="print_ft" style="margin-top:10px;">
		<div class="row">
			<div class="col-md-4"><span class="bold">FM-PUR-07</span></div>
			<div class="col-md-4" style="text-align:center;"><span class="bold">00</span></div>
			<div class="col-md-4" style="text-align:right;"><span class="bold">8/20/2016</span></div>
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

			<div class="round padding center-text margin-top bold dark">MATERIAL ISSUANCE SLIP</div>	
		</div>
	</div>

	<table id="item-table">
		<thead>
		<tr class="border">	
			<th>Item #</th>
			<th>Qty</th>
			<th>Unit</th>						
			<th>Material(s)/Description</th>
			<th>Charges</th>				
		</tr>
		</thead>
		<tbody>
			<?php foreach($details as $row): ?>
				<tr class="border">
					<td class="center-text"><?php echo $row['item_no']; ?></td>
					<td class="center-text"><?php echo $row['issued_qty']; ?></td>
					<td class="center-text"><?php echo $row['unit_measure']; ?></td>
					<td><?php echo $row['item_description']; ?></td>
					<td>&nbsp;</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="5">
					<div><span id="item-count"><?php echo count($details); ?></span> item(s)</div>					
				</th>
			</tr>			
		</tfoot>
	</table>	
	<div class="row space">
		<div class="col-xs-12">
			<div class="round padding">
				<div>Remarks/Purpose :</div>
				<strong><p><?php echo $main_data['remarks'] ?></p></strong>
			</div>
		</div>			
	</div>
	
	<div class="row" style="margin-top:25px;">
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:110px;">
				<strong>Issued To:</strong>
				<div class="digital_signature" style=""></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['issued_to'] ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:110px;">
				<strong>Prepared By : </strong>
				<div class="digital_signature" style="<?php echo $this->extra->get_digital_signature($main_data['prepared_by']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['preparedBy_name']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:110px;">
				<strong>Approved By : </strong>
				<div class="digital_signature" style="<?php echo $this->extra->get_digital_signature($main_data['approved_by']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $main_data['approvedBy_name']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		
	</div>
	<div class="print_ft">
		<div class="row">
			<div class="col-md-9"><span class="copy" style="display:none"></span></div>
			<div class="col-md-3" ><span class="time_date"><span id="date">No Date</span> | <span id="time">No Time</span></span></div>
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
		
	