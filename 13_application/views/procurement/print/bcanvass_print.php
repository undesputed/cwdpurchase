
<div id="wrapper" style="width:900px">
<div class="container">
	<div class="row">
		<div class="col-xs-8">
			<h2 class="center-text title"><?php echo $header['title']; ?></h2>
			<div class="round padding  dark"><span class="center-text" style="border-bottom:1px solid #fff;display:block"><?php echo $header['sub_title'] ?></span></div>
			<div class="round padding center-text margin-top bold">CANVASS SHEET</div>			
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
	</div>

	<div class="round" style="margin-top:2em;padding:1em;">
		<div class="row">
			<div class="col-md-6">
				<div><strong>Supplier:</strong></div>
				<div><strong>Supplier Address:</strong></div>
			</div>
			<div class="col-md-6">	
				<div><strong>Remarks:</strong></div>
			</div>
		</div>		
	</div> 
<!-- end Head -->
<table id="item-table">

		<thead>
			<tr class="border">
				<th>Item Name</th>
				<th>Quantity</th>
				<th>Unit</th>				
				<th>Orig. Price</th>
				<th>Discount Price</th>
				<th style="width:100px">Total</th>
			</tr>
		</thead>
		<tbody>
			<?php $cnt=0; foreach ($details_data as $row): $cnt++;?>
				<tr class="border">
					<th><?php echo $row['itemDesc'];?></th>
					<th class="gor"><?php echo $row['req_qty'];?></th>
					<th class="gor"><?php echo $row['unitmeasure'];?></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<td> <?php echo count($details_data); ?> item(s)</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="td-number"></td>
				
			</tr>
		</tfoot>
		
	</table>
<!-- End Content -->

<div class="row" style="margin-top:25px;">
		<div class="col-xs-6">
			<div class="form-group round padding signatory-panel">
				<strong>Prepared By : </strong>
				<strong class="signatory"></strong>
			</div>
		</div>
		<div class="col-xs-6" style="float:right">
			<div class="form-group round padding signatory-panel">
				<strong>Approved By : </strong>
				<strong class="signatory"></strong>
			</div>
		</div>
	</div>

<!-- End Footer -->
	<div class="print_ft">
		<div class="row">
			<div class="col-md-9"><span class="copy" style="display:none"></span></div>
			<div class="col-md-3"><span class="time_date"><span id="date">No Date</span> | <span id="time">No Time</span></span></div>
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