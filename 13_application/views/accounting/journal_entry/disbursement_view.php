
<h4>Disbursement Voucher </h4>
<div class="panel panel-default">		
  <div class="panel-body">
		<a href="javascript:void(0)" class="close close-info">&times;</a>
  		<div class="row">
  			<div class="col-md-6 col-info">
				<strong>Purpose: </strong>
				<br><?php echo $main['type']; ?> <?php echo $main['short_desc']; ?><br>
				<strong>Pay To: </strong><?php echo $main['pay_to']; ?><br>
				<?php echo $main['address']; ?><br>	
				<br>
				Remarks: <?php echo $main['remarks']; ?><br>
			</div>
			<div class="col-md-6 col-info">

				<strong><?php echo $main['voucher_no']; ?></strong><br>
				<strong>Date: </strong> <?php echo date('F d, Y',strtotime($main['voucher_date'])); ?><br><br><br>

				Project: <?php echo $main['project_name']; ?><br>		

			</div>
  		</div>		
  </div>	 
  <table class="table">
			<thead>
				<tr>
					<th>Particulars</th>
					<th>Amount</th>					
				</tr>
			</thead>
			<tbody>
				<?php $total = 0; ?>
				<?php foreach($details as $row): ?>
				<?php $total = $total + $row['amount']; ?>
				<tr>
					<td><?php echo $row['item_description']; ?></td>
					<td><?php echo number_format($row['amount'],2); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td style="text-align:right">Total Amount</td>
					<td style="font-size:15px"><strong><?php echo number_format($total,2);?></strong></td>
				</tr>
			</tfoot>
	</table>
	
	<div class="panel-footer">
		<div class="row">
				<div class="col-md-4 col-info">
					Prepared By: <br>
					<strong><?php echo $main['preparedBy_name'] ?></strong>
				</div>
		</div>
	</div>


</div>
