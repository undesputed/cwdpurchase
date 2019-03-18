

<div id="wrapper" style="width:900px">
<div class="container">
	<div style="height:10px;"></div>

	<table id="item-table">
		<tbody>
			<tr>
				<th colspan="6" rowspan="3">
					<span class="center-text bold" style="font-size:18px;display:block;">INSPECTION AND ACCEPTANCE REPORT</span>
					<span class="center-text bold" style="display:block;"><?php echo $header['title']; ?></span>
					<span class="center-text" style="display:block;"><?php echo $header['address']; ?></span>
				</th>
			</tr>
			<tr>
			</tr>
			<tr>
			</tr>
			<tr>
			</tr>
			<tr>
				<th colspan="6" style="height:50px;"></th>
			</tr>
			<tr>
				<th colspan="3">Entity Name: __________________________</th>
				<th colspan="3">Fund Cluster: _______________________________</th>
			</tr>
			<tr>
			</tr>
			<tr class="border">
				<td colspan="3">
					<span style="display:block;"><strong>Supplier:</strong> <font style="text-decoration:underline;"><?php echo $supplier['business_name'];?></font>_______</span>
    				<span style="display:block;"><strong>PO No/Date:</strong> <font style="text-decoration:underline;"><?php echo $main_data['po_number'].'/'.$main_data['po_date'];?></font>_______</span>
    				<span style="display:block;"><strong>Requisitioning Office/Dept:</strong> <font style="text-decoration:underline;"><?php ?></font>_______</span>
    				<span style="display:block;"><strong>Responsibility Center Code:</strong> <font style="text-decoration:underline;"><?php ?></font>_______</span>
				</td>
				<td colspan="3">
					<span style="display:block;"><strong>IAR No:</strong> <font style="text-decoration:underline;"><?php echo $rr_main['receipt_no'];?></font>_______</span>
					<span style="display:block;"><strong>Date:</strong> <font style="text-decoration:underline;"><?php echo $rr_main['date_received'];?></font>_______</span>
					<span style="display:block;"><strong>Invoice No:</strong> ________________________________</span>
					<span style="display:block;"><strong>Date:</strong> ________________________________</span>
				</td>
			</tr>
			<tr class="border">
				<th class="center-text">Stock/Property No</th>
				<th class="center-text" colspan="2" style="width:200px;">Description</th>
				<th class="center-text">Unit</th>
				<th class="center-text" colspan="2">Quantity</th>
			</tr>
			<?php 
				$grand_total = 0;
				$cnt = 1;
				foreach ($rr_details as $row): 
					$complete = '';
					if($row['item_quantity_ordered'] == $row['item_quantity_actual'])
					{
						$complete = 'success';
					}else{												
					}										
			?>
			<tr class="<?php echo $complete; ?> border">
				<td class="center-text"><?php echo $row['item_id']; ?></td>											
				<td class="itemName" colspan="2"><?php echo $row['item_name_ordered'];?></td>
				<td class="unit_msr center-text"><?php echo $row['unit_msr'];?></td>
				<td class="td-qty td-number center-text" colspan="2"><?php echo $row['item_quantity_actual']; ?></td>									
				<?php $total = $row['item_quantity_actual'] * $row['item_cost_ordered']; ?>
				<?php $grand_total += $total;?>											
			</tr>
			<?php $cnt++; endforeach ?>
			<tr class="border">
				<td class="center-text">&nbsp;</td>
				<td class="center-text" colspan="2">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text" colspan="2">&nbsp;</td>
			</tr>
			<tr class="border">
				<td class="center-text">&nbsp;</td>
				<td class="center-text" colspan="2">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text" colspan="2">&nbsp;</td>
			</tr>
			<tr class="border">
				<td class="center-text">&nbsp;</td>
				<td class="center-text" colspan="2">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text" colspan="2">&nbsp;</td>
			</tr>
			<tr class="border">
				<td class="center-text">&nbsp;</td>
				<td class="center-text" colspan="2">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text" colspan="2">&nbsp;</td>
			</tr>
			<tr class="border">
				<td class="center-text">&nbsp;</td>
				<td class="center-text" colspan="2">&nbsp;</td>
				<td class="center-text">&nbsp;</td>
				<td class="center-text" colspan="2">&nbsp;</td>
			</tr>
			<tr class="border">
				<th colspan="3" class="center-text">INSPECTION</th>
				<th colspan="3" class="center-text">ACCEPTANCE</th>
			</tr>
		</tbody>
		<tfoot>
			<tr class="border">
				<td colspan="3">
					<span style="display:block;"><strong>Date Inspected:</strong>_______________________________</span>
					<span style="display:block;">&nbsp;</span>
					<span style="display:block;">Inspected, Verified and found in order as to quantity and specifications</span>
					<span style="display:block;">___________</span>
					<span style="display:block;">&nbsp;</span>
					<span style="display:block;">&nbsp;</span>
					<span style="display:block;" class="center-text">_________________________________________________</span>
					<span style="display:block;" class="center-text">Inspection Officer/Inspection Committee</span>
				</td>
				<td colspan="3">
					<span style="display:block;"><strong>Date Received:</strong>_______________________________</span>
					<span style="display:block;">&nbsp;</span>
					<span style="display:block;">Complete</span>
					<span style="display:block;">__________</span>
					<span style="display:block;">Partial(pls. specify quantity)</span>
					<span style="display:block;">__________</span>
					<span style="display:block;" class="center-text">___________________________________________</span>
					<span style="display:block;" class="center-text">Supplier and/or Property Custodian</span>
				</td>
			</tr>
		</tfoot>
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

			<div class="round padding center-text margin-top bold dark">MATERIAL RECEIVING REPORT</div>	
		</div>
	</div>

	<div class="row" style="margin-top:2em;">
		<div class="col-xs-6">
			
		</div>

		<div class="col-xs-2">
		</div>

		<div class="col-xs-4">
			<span class="po-label bold">No : </span><span><?php echo $rr_main['receipt_no'] ?></span>
			<span class="po-label bold">Date : </span><span><?php echo $rr_main['date_received']; ?></span>
		</div>
	</div>
	

	<table id="item-table">

		<thead>
			<tr class="border">
				<th>Item No</th>
				<th class="td-number">Qty</th>
				<th>Unit</th>
				<th>Material(s)/Description</th>										
				<th>Charges</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$grand_total = 0;
				$cnt = 1;
				foreach ($rr_details as $row): 
					$complete = '';
					if($row['item_quantity_ordered'] == $row['item_quantity_actual'])
					{
						$complete = 'success';
					}else{												
					}										
				?>
				<tr class="<?php echo $complete; ?>">
					<td class="center-text"><?php echo $row['item_id']; ?></td>											
					<td class="td-qty td-number center-text"><?php echo $row['item_quantity_actual']; ?></td>
					<td class="unit_msr center-text"><?php echo $row['unit_msr'];?></td>
					<td class="itemName"><?php echo $row['item_name_ordered'];?></td>
					<td>&nbsp;</td>											
					<?php $total = $row['item_quantity_actual'] * $row['item_cost_ordered']; ?>
					<?php $grand_total += $total;?>											
				</tr>
			<?php $cnt++; endforeach ?>
		</tbody>
		<tfoot>

		</tfoot>
	</table>


	<div class="row" style="margin-top:2em;">
		<div class="col-xs-12">
			<div class="form-group round padding">
				<strong>Delivered By: </strong>
				<strong class="signatory" style="text-align:left"><?php echo $rr_main['delivered_by']; ?></strong>
			</div>
		</div>
	</div>
	
	<div class="row" style="margin-top:5px;">
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:110px;">
				<strong>Checked By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($rr_main['employee_checker_id']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $rr_main['rr_checked_by']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:110px;">
				<strong>Noted By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($rr_main['posted_by']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $rr_main['rr_posted_by']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group round padding" style="height:110px;">
				<strong>Received By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($rr_main['employee_receiver_id']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block"><?php echo $rr_main['rr_received_by']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block">Date:</strong>
			</div>
		</div>
	</div>
	<div class="print_ft">
		<div class="row">
			<div class="col-md-9"><span class="copy"></span></div>
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