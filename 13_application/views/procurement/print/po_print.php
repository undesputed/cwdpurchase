
<div id="wrapper" style="width:1000px">
<div class="container">
	<div style="height:10px;"></div>

	<table id="item-table">
		<tbody>
			<tr>
				<th colspan="6" rowspan="3">
					<span class="center-text bold" style="font-size:18px;display:block;">PURCHASE ORDER</span>
					<span class="center-text bold" style="display:block;"><?php echo $header['title']; ?></span>
					<span class="center-text" style="display:block;"><?php echo $header['address']; ?></span>
				</th>
			</tr>
			<tr>
			</tr>
			<tr>
			</tr>
			<tr>
				<th colspan="6" style="height:50px;"></th>
			</tr>
			<tr>
				<th colspan="6" style="text-align:center;">_____________________________________</th>
			</tr>
			<tr>
				<th colspan="6" style="text-align:center;">Entity Name:</th>
			</tr>
			<tr>
				<th colspan="6">&nbsp;</th>
			</tr>
			<tr class="border">
				<td colspan="3">
					<span style="display:block;"><strong>Supplier:</strong> <font style="text-decoration:underline;"><?php echo $supplier['business_name'];?></font>_______</span>
    				<span style="display:block;"><strong>Address:</strong> <font style="text-decoration:underline;"><?php echo $supplier['address'];?></font>_______</span>
    				<span style="display:block;"><strong>TIN:</strong> <font style="text-decoration:underline;"><?php echo $supplier['tin_number'];?></font>_______</span>
				</td>
				<td colspan="3">
					<span style="display:block;"><strong>PO No:</strong> <font style="text-decoration:underline;"><?php echo $main_data['po_number'];?></font>_______</span>
					<span style="display:block;"><strong>PO Date:</strong> <font style="text-decoration:underline;"><?php echo $main_data['po_date'];?></font>_______</span>
					<span style="display:block;"><strong>Mode of Procurement:</strong> ________________________________</span>
				</td>
			</tr>
			<tr class="border">
				<td colspan="6">
					<span style="display:block;text-indent:1em;">Gentlemen:</span>
					<span style="display:block;text-indent:4em;">Please furnish this Office the following articles subject to the terms and conditions contained herein:</span>
				</td>
			</tr>
			<tr class="border">
				<td colspan="3">
					<span style="display:block;"><strong>Place of Delivery:</strong> <font style="text-decoration:underline;"><?php echo $main_data['placeDelivery'];?></font>_______</span>
    				<span style="display:block;"><strong>Date of Delivery:</strong> <font style="text-decoration:underline;"><?php echo $main_data['dtDelivery'];?></font>_______</span>
				</td>
				<td colspan="3">
					<span style="display:block;"><strong>Delivery Term:</strong> <font style="text-decoration:underline;"><?php echo $main_data['deliverTerm'];?></font>_______</span>
					<span style="display:block;"><strong>Payment Term:</strong> <font style="text-decoration:underline;"><?php echo $main_data['paymentTerm'];?></font>_______</span>
				</td>
			</tr>
			<tr class="border">
				<th class="center-text">Stock/Property No.</th>
				<th class="center-text">Unit</th>
				<th class="center-text">Description</th>
				<th class="center-text">Quantity</th>
				<th class="center-text">Unit Cost</th>
				<th class="center-text">Amount</th>
			</tr>
			<?php
				$grand_total = 0;
				foreach ($details_data as $row): 
			?>
			<tr>
				<td class="center-text" style="width:10%;"><?php echo $row['itemNo'];?></td>
				<td class="center-text" style="width:10%;"><?php echo $row['unit_msr'];?></td>
				<td style="width:30%;"><?php echo $row['item_name'];?></td>
				<td class="center-text"><?php echo $this->extra->comma($row['quantity']);?></td>
				<td class="right-text"><?php echo $this->extra->number_format($row['unit_cost']);?></td>
				<td class="right-text"><?php echo $this->extra->number_format($row['total_unitcost']);?></td>
				<?php $grand_total += $row['total_unitcost'];?>
			</tr>
			<?php
				endforeach
			?>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td colspan="6"><strong>(Total Amount in Words)</strong>&nbsp;&nbsp;&nbsp;<?php echo $amountinwords.' pesos only';?></td>
			</tr>
			<tr class="border">
				<td colspan="6">
					<span style="display:block;text-indent:4em;">In case of failure to make the full delivery within the time specified above, a penalty of one-tenth(1/10) of one percent for every day of delay shall be imposed on the undelivered item/s</span>
					<span style="display:block;">&nbsp;</span>
					<div class="row">
						<div class="col-md-6">
							<span style="display:block;text-indent:4em;">Conforme:</span>
							<span style="display:block;">&nbsp;</span>
							<span style="display:block;text-align:center;">_______________________________________</span>
							<span style="display:block;text-align:center;font-size:11px;">Signature Over Printed Name of Supplier</span>
							<span style="display:block;">&nbsp;</span>
							<span style="display:block;text-align:center;">_______________________________________</span>
							<span style="display:block;text-align:center;font-size:11px;">Date</span>
						</div>

						<div class="col-md-6">
							<span style="display:block;">Very truly yours:</span>
							<span style="display:block;">&nbsp;</span>
							<span style="display:block;text-align:center;">_______________________________________</span>
							<span style="display:block;text-align:center;font-size:11px;">Signature Over Printed Name of Official</span>
							<span style="display:block;">&nbsp;</span>
							<span style="display:block;text-align:center;">_______________________________________</span>
							<span style="display:block;text-align:center;font-size:11px;">Designation</span>
						</div>
					</div>
					<span style="display:block;">&nbsp;</span>
				</td>
			</tr>
			<tr class="border">
				<td colspan="3">
					<span style="display:block;"><strong>Fund Cluster:</strong> ________________________</span>
    				<span style="display:block;"><strong>Funds Available:</strong> _______________________</span>
    				<span style="display:block;">&nbsp;</span>
    				<span style="display:block;text-align:right;margin-right:10px;"> _____________________________________________________________________________</span>
    				<span style="display:block;text-align:right;font-size:11px;margin-right:10px;">Signature over Printed Name of Chief Accountant/Head of Accounting Division/Unit</span>
    				<span style="display:block;">&nbsp;</span>
				</td>
				<td colspan="3">
					<span style="display:block;"><strong>ORS/BURS No:</strong> ________________________</span>
    				<span style="display:block;"><strong>Date of the ORS/BURS:</strong> _______________________</span>
    				<span style="display:block;">&nbsp;</span>
    				<span style="display:block;"><strong>Amount:</strong> _______________________</span>
    				<span style="display:block;">&nbsp;</span>
				</td>
			</tr>
		</tbody>
	</table>




	<div class="print_ft">
		<div class="row" style="font-weight:bold;">
			<div class="col-md-4"><span>FM-PUR-05</span></div>
			<div class="col-md-4" style="text-align:center;"><span>00</span></div>
			<div class="col-md-4"><span class="time_date"><span>2017/06/03</span></span></div>
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

			<div class="round padding center-text margin-top bold dark">PURCHASE ORDER</div>	

		</div>
	</div>
		
	<div class="" style="margin-top:1em;">
		<table  style="width:100%">
			<tbody>
				<tr>
					<td rowspan="2" style="width:420px">
						<div class="form-group" style="margin-left:6px">
							<strong style="border-bottom:1px solid #000;display:block">To Supplier : </strong>
							<strong><?php echo $supplier['business_name'] ?></strong>
						</div>
						<div class="form-group" style="margin-left:6px">
							<strong>&nbsp;</strong>
							<strong style="display:block"></strong>
							<strong style="display:block"></strong>
						</div>						
					</td>
					<td rowspan="2">
						<div class="form-group" style="margin-left:6px">
							<strong style="border-bottom:1px solid #000;display:block">Deliver To : </strong>
							<strong><?php echo '('.$pr_main['project_no'].')'.$pr_main['from_projectCodeName']; ?></strong>
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

	<div class="row" style="margin-top:-2em;">
		<div class="col-xs-4">
			<div class="form-group padding center-text">
				<strong>P.O. No. : </strong>
				<?php echo $main_data['po_number']; ?>
			</div>
		</div>
		
		<div class="col-xs-4">
			<div class="form-group padding center-text">
				<strong>P.O. Date : </strong>
				<?php echo $main_data['po_date']; ?>
			</div>
		</div>
		
		<div class="col-xs-4">
			<div class="form-group padding center-text">
				<strong>Expected Delivery Date : </strong>
				<?php echo $main_data['dtDelivery']; ?>
			</div>
		</div>
		
	</div>

	<div  style="margin-top:-2em;">
		<strong style="display:block">Dear Sir/Madam,</strong>
		<strong style="margin-left:25px;">Please furnish us the below-listed materials at the delivery site the earnest possible time.</strong>
	</div>

	<table id="item-table">

		<thead>
			<tr class="border">
				<th>Qty</th>
				<th>Units</th>
				<th>Items</th>
				<th>Brand</th>				
				<th>Unit Price</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php 				
				$grand_total = 0;
				foreach ($details_data as $row): 
			?>
			<tr>
				<td class="center-text"><?php echo $this->extra->comma($row['quantity']);?></td>
				<td class="center-text"><?php echo $row['unit_msr'];?></td>
				<td><?php echo $row['item_name'];?></td>
				<td><?php echo $row['brand'];?></td>				
				<td class="right-text"><?php echo $this->extra->number_format($row['unit_cost']);?></td>
				<td class="right-text"><?php echo $this->extra->number_format($row['total_unitcost']);?></td>
				<?php $grand_total += $row['total_unitcost'];?>
			</tr>
			<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="6"><div class="padding" style="margin-top:1em;margin-bottom:1em;">PO Remarks: <span style="font-weight:bold"><?php echo $main_data['po_remarks']; ?></span> <span style="margin-left:8px;font-weight:bold;"><?php echo $pr_main['from_projectCodeName']; ?></span></div></td>
			</tr>
			<tr class="border">
				<td colspan="2" class="bold center-text">Project</td>
				<td colspan="2" class="center-text"><?php echo $pr_main['from_projectCodeName'];?></td>
				<td class="bold center-text">TOTAL</td>
				<td class="right-text bold"><?php echo $this->extra->number_format($grand_total); ?></td>
			</tr>
		</tfoot>
	</table>
		
	<div class="row" style="margin-top:2em;">
		
		<div class="col-xs-4">
			<div class="form-group padding" style="height:160px;">
				<strong class="center-text" style="display:block">Prepared By: </strong>			
				<img class="digital_signature" style="margin-top:50px;;height:50px;width:100px;" src="<?php echo $this->extra->get_digital_signature($main_data['preparedBy']);?>">
				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"><?php echo $main_data['preparedBy_name']; ?></strong>
				<span class="center-text" style="display:block;font-size:9px;">QCON has the right to automatically cancel this P.O. should the supplier failed to deliver on agreed date without prior notice.</span>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group padding" style="height:160px;">
				<strong class="center-text" style="display:block">Noted By: </strong>			
				<img class="digital_signature" src="">
				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"></strong>
				<span class="center-text" style="display:block;font-size:9px;">Terms &amp; conditions herein stated are accepted and we confirm that delivery goods/items will be effected:</span>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group padding" style="height:160px;">
				<strong class="center-text" style="display:block">Approved By: </strong>
				<img class="digital_signature" style="margin-top:50px;height:50px;width:100px;" src="<?php echo $this->extra->get_digital_signature($main_data['approvedBy']);?>">				
				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"><?php echo $main_data['approvedBy_name'] ?></strong>
				<strong style="border-bottom:1px solid #000;display:block;font-size:9px;">P.O. Received by:</strong>
				<strong style="border-bottom:1px solid #000;display:block;font-size:9px;">Position:</strong>
				<strong style="border-bottom:1px solid #000;display:block;font-size:9px;">Date:</strong>
			</div>
		</div>
	</div>
	 -->
	

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





