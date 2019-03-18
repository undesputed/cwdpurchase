
<div id="wrapper" style="width:1000px">
<div class="container">
	<div style="height:10px;"></div>

	<table id="item-table">
		<tbody>
			<tr class="border">
				<th colspan="6" rowspan="3">
					<span class="center-text bold" style="font-size:18px;display:block;">PURCHASE REQUEST</span>
					<span class="center-text bold" style="display:block;"><?php echo $header['title']; ?></span>
					<span class="center-text" style="display:block;"><?php echo $header['address']; ?></span>
				</th>
			</tr>
			<tr>
			</tr>
			<tr>
			</tr>
			<tr>
				<th colspan="2">Entity Name: __________________________</th>
				<th>&nbsp;</th>
				<th colspan="3">Fund Cluster: _______________________________</th>
			</tr>
			<tr class="border">
				<th colspan="2" rowspan="2">
					<span style="display:block;">Office/Section: <font style="text-decoration:underline;"><?php echo $main_data['from_projectCodeName'];?></font></span>
					<span stye="display:block;">&nbsp;&nbsp; _______________________________</span>
				</th>
    			<th colspan="2" rowspan="2">
    				<span style="display:block;">PR No: <font style="text-decoration:underline;"><?php echo $main_data['purchaseNo'];?></font>_______</span>
    				<span stye="display:block;">Responsibility Center Code: _____________________________</span>
    			</th>
    			<th colspan="2" rowspan="2">
    				<span style="display:block;">Date: <font style="text-decoration:underline;"><?php echo $main_data['purchaseDate'];?></font>_______</span>
    				<span stye="display:block;">&nbsp;&nbsp;</span>
    			</th>
			</tr>
			<tr>
			</tr>
			<tr class="border">
				<th class="center-text" style="width:20%;">Stock/Property No.</th>
				<th class="center-text">Unit</th>
				<th class="center-text">Item Description</th>
				<th class="center-text">Quantity</th>
				<th class="center-text">Unit Cost</th>
				<th class="center-text">Total Cost</th>
			</tr>
			<?php $cnt=0; foreach ($details_data as $row): $cnt++;?>
			<tr class="border">
				<td class="center-text"><?php echo $row['itemNo'];?></td>
				<td class="center-text"><?php echo $row['unitmeasure'];?></td>
				<td><?php echo $row['itemDesc'];?></td>
				<td class="td-number td-qty center-text">
					<?php if(strtoupper($main_data['status']) == 'APPROVED'): ?>
					<?php echo $row['qty']; ?>
					<?php elseif($outgoing): ?>
						-
					<?php else: ?>
					<input type="text" data-itemno="<?php echo $row['itemNo']; ?>" class="form-control approved-qty required numbers_only"  style="width:80px">
					<?php endif; ?>
				</td>
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
			</tr>
			<tr class="border">
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
			</tr>
			<tr class="border">
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
			</tr>
			<tr class="border" style="height:75px;vertical-align:top;">
				<td colspan="6" style="font-weight:bold;">Purpose: <?php echo $main_data['pr_remarks'];?></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
    			<th></th>
    			<th colspan="2">Requested By:</th>
    			<th colspan="3">Approved By:</th>
  			</tr>
  			<tr>
    			<td class="bold">Signature:</td>
    			<td colspan="2">____________________________________</td>
    			<td colspan="3">____________________________________</td>
  			</tr>
  			<tr>
    			<td class="bold">Printed Name:</td>
    			<td colspan="2"><font style="text-decoration:underline;"><?php echo $main_data['person_preparedBy']; ?></font>__________</td>
    			<td colspan="3" style="text-decoration:underline;"><font style="text-decoration:underline;"><?php if(!empty($main_data['person_approvedBy'])){ echo $main_data['person_approvedBy']; }else{ echo '___________________________'; } ?></font>________</td>
  			</tr>
  			<tr>
    			<td class="bold">Designation:</td>
    			<td colspan="2"><span style="text-decoration:underline;">OIC - Admin Division</span>______________</td>
    			<td colspan="3"><span style="text-decoration:underline;">General Manager</span>_________________</td>
  			</tr>
		</tfoot>
	</table>

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

			<div class="round padding center-text margin-top bold dark">PURCHASE REQUEST</div>	
		</div>
	</div>

	<div class="row" style="margin-top:-1em;">
		<div class="col-xs-6">
			
		</div>

		<div class="col-xs-2">
		</div>

		<div class="col-xs-4">
			<span class="po-label bold">No : </span><span><?php echo $main_data['purchaseNo']; ?></span>
			<span class="po-label bold">Date : </span><span><?php echo $main_data['purchaseDate'] ?></span>
		</div>
	</div>
		
	<div class="" style="padding:1em;">
		<div class="row">
			<div class="col-md-6">
				<div>Department:</div>
				<div><strong><?php echo $main_data['from_projectMainName']; ?></strong></div>
				<div><strong><?php echo $main_data['from_projectCodeName']; ?></strong></div>
			</div>
			<div class="col-md-6">
				 <div>Request To:</div>
				<div><strong><?php echo $main_data['to_projectMainName']; ?></strong></div>
				<div><strong><?php echo $main_data['to_projectCodeName']; ?></strong></div>
			</div>
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
					<td class="center-text"><?php echo $row['itemNo'];?></td>
					<td class="center-text"><?php echo $row['unitmeasure'];?></td>
					<td><?php echo $row['itemDesc'];?></td>
					<td class="td-number td-qty center-text">
						<?php if(strtoupper($main_data['status']) == 'APPROVED'): ?>
						<?php echo $row['qty']; ?>
						<?php elseif($outgoing): ?>
							-
						<?php else: ?>
						<input type="text" data-itemno="<?php echo $row['itemNo']; ?>" class="form-control approved-qty required numbers_only"  style="width:80px">
						<?php endif; ?>
					</td>
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
				<strong>Requested By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['preparedBy']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"><?php echo $main_data['person_preparedBy']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block;font-size:11px;">Date:</strong>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group padding" style="height:110px;">
				<strong>Noted By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['recommendedBy']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"><?php echo $main_data['person_recommendedBy'] ?></strong>
				<strong style="border-bottom:1px solid #000;display:block;font-size:11px;">Date:</strong>
			</div>
		</div>
		<div class="col-xs-4" style="float:right">
			<div class="form-group padding" style="height:110px;">
				<strong>Approved By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['approvedBy']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"><?php echo $main_data['person_approvedBy']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block;font-size:11px;">Date:</strong>
			</div>
		</div>
	</div> -->
	
	<div class="print_ft" style="margin-top:10px;">
		<div class="row">
			<div class="col-md-4"><span class="bold">FM-PUR-07</span></div>
			<div class="col-md-4" style="text-align:center;"><span class="bold">00</span></div>
			<div class="col-md-4"><span class="bold">8/20/2016</span></div>
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