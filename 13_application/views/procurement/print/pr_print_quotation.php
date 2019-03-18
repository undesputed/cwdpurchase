
<div id="wrapper" style="width:1000px">
<div class="container">
	<div style="height:10px;"></div>

	<table>
		<tbody>
			<tr class="border">
    			<th style="width:30%;height:20px;" rowspan="4">
    				<span style="display:block;font-size:18px;font-weight:bold;"><?php echo $header['title']; ?></span>
    				<span style="display:block"><?php echo $header['sub_title']; ?></span>
					<span style="display:block"><?php echo $header['address']; ?></span>
					<span style="display:block"><?php echo $header['contact']; ?></span>
    			</th>
    			<th class="center-text bold" style="font-size:18px;">REQUEST FOR QUOTATIONS</th>
  			</tr>
 	 		<tr class="border">
    			<td rowspan="4">
    				<span style="display:block;">Please quote your lowest net price to CWD for the items specified below.</span>
					<span style="display:block;font-weight:bold;">Your quotation must be submitted</span>
					<div class="row">
						<div class="col-xs-4">
							<span style="display:block;">1. FOR BIDDING</span>
							<span style="display:block;">AT ___________A.M.</span>
						</div>

						<div class="col-xs-4">
							<span style="display:block;">2. IN SEALED ENVELOPE TO BE OPENED</span>
							<span style="display:block;">AT ___________A.M.</span>
							<span style="display:block;">ON ___________</span>
						</div>

						<div class="col-xs-4">
							<span style="display:block;">3. DETAILED DESCRIPTION OF THE OFFERED EQUIPMENT, TOOLS, SUPPLIES, LABOR, MATERIALS, ETC. BE SUBMITTED TOGETHER WITH THIS REQUEST FOR QUOTATION IF REQUIRED.</span>
						</div>
					</div>
					<span><font style="font-weight:bold;">Ref. Purchase Request No:</font> <?php echo $main_data['purchaseNo'];?></span>&nbsp;&nbsp;<span><font style="font-weight:bold;">Date:</font> <?php echo $main_data['purchaseDate'];?></span></td>
    			</td>
  			</tr>
  			<tr>
  			</tr>
  			<tr>
  			</tr>
  			<tr class="border">
    			<td style="font-weight:bold;height:130px;vertical-align:top;">TO:</td>
  			</tr>
		</tbody>
	</table>

	<table id="item-table" style="margin-top:10px;">
		<thead>
		  	<tr class="border">
		    	<th rowspan="2"></th>
		    	<th rowspan="2">Description</th>
		    	<th rowspan="2">Qty</th>
		    	<th rowspan="2">Unit</th>
		    	<th colspan="2">Delivered</th>
		    	<th colspan="2">Pick Up</th>
		  	</tr>
		  	<tr class="border">
		    	<th>Unit Cost</th>
		    	<th>Amount</th>
		    	<th>Unit Cost</th>
		    	<th>Amount</th>
		  	</tr>
	  	</thead>
	  	<tbody>
		  <?php $cnt=0; foreach ($details_data as $row): $cnt++;?>
				<tr>
					<td class="center-text"><?php echo $row['itemNo'];?></td>
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
					<td class="center-text"><?php echo $row['unitmeasure'];?></td>
					<td class="right-text"></td>
					<td class="right-text"></td>
					<td class="right-text"></td>
					<td class="right-text"></td>											
				</tr>
			<?php endforeach ?>
	  	</tbody>
	</table>

	<table>
		<tfoot>
			<tr class="border">
    			<th>IMPORTANT</th>
    			<th>REQUESTED BY:</th>
    			<th>PRICE QUOTED BY:</th>
  			</tr>
  			<tr class="border">
    			<td rowspan="3" style="width:40%;">
    				<span style="display:block;font-size:11px;font-weight:bold;">1. The Award shall be to the responsive acceptable supplier who submits the most economical and advantageous offer.</span>
    				<span style="display:block;font-size:11px;font-weight:bold;">2. All quotations shall be firm and valid for acceptable for period of at least 30 days from the date of opening of quotations and shall be binding upon the supplier within.</span>
    			</td>
    			<td rowspan="3">
    				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"><?php echo $main_data['person_preparedBy']; ?></strong>
    				<strong class="center-text" style="display:block;font-size:11px;">BAC Chairperson</strong>
    			</td>
    			<td rowspan="3">
    				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;">&nbsp;</strong>
    				<strong class="center-text" style="display:block;font-size:11px;">Signature over printed name</strong>
    			</td>
  			</tr>
  			<tr>
  			</tr>
  			<tr>
  			</tr>
		</tfoot>
	</table>
	
	<div class="print_ft">
		<div class="row">
			<div class="col-md-4"><span style="font-weight:bold;">FM-PUR-08</span></div>
			<div class="col-md-4" style="text-align:center;font-weight:bold;"><span>00</span></div>
			<div class="col-md-4"><span class="time_date" style="font-weight:bold;"><span>8/20/2016</span></span></div>
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