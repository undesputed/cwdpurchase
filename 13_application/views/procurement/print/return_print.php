
<div id="wrapper" style="width:900px">
<div class="container">
	<div style="height:10px;"></div>

	<table id="item-table">
		<tbody>
			<tr>
				<th colspan="6" rowspan="3">
					<span class="center-text bold" style="font-size:18px;display:block;">RETURN MATERIAL SLIP</span>
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
			<tr class="border">
				<td colspan="4">
					<span style="display:block;"><strong>Division:</strong> <font style="text-decoration:underline;"><?php echo $main_data['project_requestor'];?></font></span>
					<span style="display:block;"><strong>Section:</strong> __________________________________</span>
				</td>
				<td colspan="3">
					<span style="display:block;"><strong>RMS No.:</strong> <font style="text-decoration:underline;"><?php echo $main_data['receipt_no'];?></font></span>
					<span style="display:block;"><strong>Date:</strong> <font style="text-decoration:underline;"><?php echo $main_data['date_received'];?></font></span>
				</td>
			</tr>
			<tr class="border">
				<td colspan="7">&nbsp;</td>
			</tr>
			<tr class="border">
				<th class="center-text">STOCK NO</th>
				<th class="center-text">UNIT</th>
				<th class="center-text">DESCRIPTION</th>
				<th class="center-text">ACC#</th>
				<th class="center-text">QTY</th>
				<th class="center-text">Unit Price</th>
				<th class="center-text">Total</th>
			</tr>
			<?php $cnt=0; foreach ($details_data as $row): $cnt++;?>
			<tr>
				<td class="center-text"><?php echo $row['item_id'];?></td>
				<td class="center-text"><?php echo $row['unit_measure'];?></td>
				<td><?php echo $row['item_name_actual'];?></td>
				<td>&nbsp;</td>
				<td class="td-number td-qty center-text"><?php echo $row['item_quantity_actual']; ?></td>
				<td class="right-text">0.00</td>
				<td class="right-text">0.00</td>											
			</tr>
			<?php endforeach ?>
			<tr class="border">
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
			</tr>
			<tr class="border">
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
			</tr>
			<tr class="border">
				<td colspan="5"><strong>Purpose:</strong> <font style="text-decoration:underline;"><?php echo $main_data['po_remarks'];?></font></td>
				<td class="right-text"><strong>TOTAL: </strong></td>
				<td class="right-text"><strong>0.00</strong></td>
			</tr>
		</tbody>
	</table>

	<table>
		<tfoot>
			<tr class="border">
				<th style="width:150px;">&nbsp;</th>
				<th colspan="2" class="center-text" style="width:250px;">Returned By</th>
				<th colspan="2" class="center-text" style="width:250px;">Received By</th>
				<th colspan="2" class="center-text" style="width:350px;">Posted By:</th>
			</tr>
			<tr class="border">
				<td style="font-weight:bold;">Signature</td>
				<td colspan="2">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			<tr>
			<tr class="border">
				<td style="font-weight:bold;">Name</td>
				<td colspan="2" class="center-text" style="font-weight:bold;font-size:12px;">CABANSAG, ANGELICA</td>
				<td colspan="2" class="center-text" style="font-weight:bold;font-size:12px;">BACON, JESSA MAY</td>
				<td class="center-text" style="font-weight:bold;font-size:12px;">BACON, JESSA MAY</td>
				<td class="center-text" style="font-weight:bold;font-size:12px;">LAYAN, MART QUEENY</td>
			<tr>
			<tr class="border">
				<td style="font-weight:bold;">Designation</td>
				<td colspan="2">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td class="center-text" style="font-size:11px;">Warehouse</td>
				<td class="center-text" style="font-size:11px;">Accounting Clerk</td>
			<tr>
		</tfoot>
	</table>

	<div class="print_ft">
		<div class="row" style="font-weight:bold;">
			<div class="col-md-4"><span>FM-WHS-07</span></div>
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

			<div style="display:block;height:60px;margin-top:1em">
				<span style="display:block" class="center-text"><?php echo $header['website']; ?></span>
				<span style="display:block" class="center-text"><?php echo $header['email']; ?></span>
			</div>

			<div class="round padding center-text margin-top bold dark">RETURN MATERIAL SLIP</div>	
		</div>
	</div>

	<div class="row" style="margin-top:-1em;">
		<div class="col-xs-6">
			
		</div>

		<div class="col-xs-2">
		</div>

		<div class="col-xs-4">
			<span class="po-label bold">No : </span><span><?php echo $main_data['receipt_no']; ?></span>
			<span class="po-label bold">Date : </span><span><?php echo $main_data['date_received'] ?></span>
		</div>
	</div>
		

	<table id="item-table">

		<thead>
			<tr class="border">
				<th>Item #</th>
				<th>Unit</th>
				<th>Description</th>
				<th>Qty</th>
				<th>Unit Cost</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php $cnt=0; foreach ($details_data as $row): $cnt++;?>
				<tr>
					<td class="center-text"><?php echo $row['item_id'];?></td>
					<td class="center-text"><?php echo $row['unit_measure'];?></td>
					<td><?php echo $row['item_name_actual'];?></td>
					<td class="td-number td-qty center-text"><?php echo $row['item_quantity_actual']; ?></td>
					<td class="right-text">0.00</td>
					<td class="right-text">0.00</td>											
				</tr>
			<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
				<td style="font-weight:bold;">Remarks:</td>
				<td colspan="4"><?php echo $main_data['pr_remarks'];?></td>
			</tr>
		</tfoot>
	</table>
	
	<div class="row" style="margin-top:25px;">
		<div class="col-xs-4">
			<div class="form-group padding" style="height:110px;">
				<strong>Returned By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['preparedBy']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"><?php echo $main_data['person_preparedBy']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block;font-size:11px;">Date:</strong>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group padding" style="height:110px;">
				<strong>Received By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['recommendedBy']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"><?php echo $main_data['person_receivedBy'] ?></strong>
				<strong style="border-bottom:1px solid #000;display:block;font-size:11px;">Date:</strong>
			</div>
		</div>
		<div class="col-xs-4" style="float:right">
			<div class="form-group padding" style="height:110px;">
				<strong>Posted By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['approvedBy']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"></strong>
				<strong style="border-bottom:1px solid #000;display:block;font-size:11px;">Date:</strong>
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