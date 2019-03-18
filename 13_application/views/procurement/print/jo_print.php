
<div id="wrapper" style="width:900px">
<div class="container">
	
	<!-- <div class="row">
		<div class="col-xs-8">
			<h2 class="center-text title"><?php echo $header['title']; ?></h2>
			<div class="round padding  dark"><span class="center-text" style="border-bottom:1px solid #fff;display:block"><?php echo $header['sub_title'] ?></span></div>
			<div class="round padding center-text margin-top bold">PURCHASE REQUEST</div>			
		</div>
	
		<div class="col-xs-4">

			<div style="display:block;height:60px;margin-top:2em">
				<span style="display:block" class="center-text"><?php echo $header['address']; ?></span>
				<span style="display:block" class="center-text"><?php echo $header['contact']; ?></span>
			</div>
						
			<div class="round">
				<div class="border-bottom po-content">
					<span class="po-label center-text">P.R No :</span>
					<span class="center-text po-color" ><?php echo $main_data['purchaseNo']; ?></span>
				</div>
				<div >
					<span class="po-label center-text">DATE :</span>
					<span class="center-text"><?php echo $main_data['purchaseDate'] ?></span>
				</div>
				
			</div>		

		</div>
	</div> -->

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

			<div style="display:block;height:60px;margin-top:1em">
				<span style="display:block" class="center-text"><?php echo $header['website']; ?></span>
				<span style="display:block" class="center-text"><?php echo $header['email']; ?></span>
			</div>

			<div class="round padding center-text margin-top bold dark">JOB ORDER FORM</div>	
		</div>
	</div>

	<div class="row" style="margin-top:-1em;">
		<div class="col-xs-6">
			
		</div>

		<div class="col-xs-2">
		</div>

		<div class="col-xs-4">
			<span class="po-label bold">No : </span><span><?php echo $main_data['job_order_no']; ?></span>
			<span class="po-label bold">Date : </span><span><?php echo $main_data['job_order_date'] ?></span>
		</div>
	</div>
		
	<div class="" style="padding:1em;">
		<div class="row">
			<div class="col-md-6">
				<div>To: <strong><?php echo $main_data['supplier']; ?></strong></div>
				<div>Attention: <strong><?php echo $main_data['attention']; ?></strong></div>
			</div>
			<div class="col-md-6">
				<div>&nbsp;</div>
				<div><strong>&nbsp;</strong></div>
			</div>
		</div>		
	</div>

	<div style="text-align:center;">
		<span style="font-weight:bold;"><?php echo $main_data['remarks'];?></span>
	</div>  

	<table id="item-table">

		<thead>
			<tr class="border">
				<th>Item #</th>
				<th>Item Description</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$cnt=0; 
				$total = 0;
				foreach ($details_data as $row): 
					$cnt++;
					$total = $total + $row['amount'];
			?>
				<tr>
					<td class="center-text"><?php echo $cnt;?></td>
					<td><?php echo $row['item_description'];?></td>
					<td class="right-text"><?php echo number_format($row['amount'],2);?></td>										
				</tr>
			<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" style="font-weight:bold;text-align:right;">TOTAL COST: </td>
				<td style="font-weight:bold;text-align:right;"><?php echo number_format($total,2);?></td>
			</tr>
		</tfoot>
	</table>

	<div>
		Site/Project: <strong><?php echo $main_data['project_name']; ?></strong>
	</div>

	<div>
		&nbsp;
	</div>

	<div>
		<span>Please sign below for confirmation. Favorable date of start for the project shall be on ________________________.</span>
	</div>
	
	<div class="row" style="margin-top:25px;">
		<div class="col-xs-4">
			<div class="form-group padding" style="height:110px;">
				<strong>Approved By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['preparedBy']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"><?php echo $main_data['approvedByName']; ?></strong>
				<strong style="border-bottom:1px solid #000;display:block;font-size:11px;">Date:</strong>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group padding" style="height:110px;">
				&nbsp;
			</div>
		</div>
		<div class="col-xs-4" style="float:right">
			<div class="form-group padding" style="height:110px;">
				<strong>Confirmed By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['approvedBy']);?>"></div>
				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;">&nbsp;</strong>
				<strong style="border-bottom:1px solid #000;display:block;font-size:11px;">Date:</strong>
			</div>
		</div>
	</div>
	
	<div class="print_ft" style="display:none;">
		<div class="row">
			<div class="col-md-4"><span>FM-WAR-01</span></div>
			<div class="col-md-4" style="text-align:center;"><span>REV. 01</span></div>
			<div class="col-md-4"><span class="time_date"><span>2017/06/03</span></span></div>
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