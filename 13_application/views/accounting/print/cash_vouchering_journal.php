<style>
	.border_bottom{
		border-bottom: 1px solid #333;
	}
	.margin-left{
		margin-left:5em;
	}
</style>



<div style="width:900px" >
	<div class="container">
		<h2 style="text-decoration:underline;text-align:center;">Cash Voucher</h2>
	<table style="margin-top:3em;width:100%">

		<tr>
			<td></td>
			<td style="width:450px"></td>
			<td></td>
			<td style="width:200px">NO. <div class="border_bottom pull-right" style="width:170px;height:20px"><?php echo $main['voucher_no']; ?></div> </td>
		</tr>
		
		<tr>
			<td>PAY TO:</td>
			<td class="border_bottom" style="width:450px"><?php echo $main['pay_to']; ?></td>
			<td>&nbsp;DATE:</td>
			<td class="border_bottom" style="width:200px"><?php echo $main['voucher_date']; ?></td>
		</tr>
		
		<tr>
			<td>ADDRESS:</td>
			<td class="border_bottom" style="width:450px"><?php echo $main['address']; ?></td>
			<td>&nbsp;TIN:</td>
			<td class="border_bottom" style="width:200px"><?php echo $main['tin']; ?></td>
		</tr>		
	</table>
	
	
	
	<table style="width:100%;margin-top:1em">
		<tr style="border-top:1px solid #333;">
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr style="border:1px solid #333;border-left:0px;border-right:0px">
			<td style="width:500px;text-align:center;">P A R T I C U L A R</td>
			<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px"></td>
			<td style="width:150px;text-align:center;">AMOUNT</td>
		</tr>
		<tbody>
			<tr>
				<td style="width:500px;border-bottom:1px solid #333;vertical-align:top;position:relative">				
				</td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;border-bottom:1px solid #333;"></td>
				<td style="width:150px;text-align:center;border-bottom:1px solid #333;"></td>
			</tr>
			<tr>				
				<td>
					<div style="margin-bottom:2em;margin-top:2em;"><?php echo $main['type']." ".$main['short_desc']; ?></div>
					<div class="margin-left">
															
					</div>
				</td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;"></td>
				<td style="text-align:center"></td>
			</tr>
			<?php $total = 0; ?>
			<?php foreach($details as $row): ?>
			<?php $total = $total + $row['amount']; ?>
			<tr style="">				
				<td>
					<span style="margin-left:2em;"><?php echo $row['item_description']; ?></span>
				</td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;"></td>
				<td style="text-align:center"><?php echo number_format($row['amount'],2); ?></td>
			</tr>
			<?php endforeach;?>
			<tr>				
				<td>
					<table style="width:100%;margin-top:3em;">
						<tr>
							<td>PROJECT: </td>
							<td><strong><?php echo $main['project_name']." - ".$main['project_category']; ?></strong></td>
							<td>TOTAL P </td>
						</tr>
					</table>			
				</td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;"></td>
				<td style="text-align:center"><strong><?php echo number_format($total,2); ?></strong></td>
			</tr>
			<tr>
				<td style="width:500px;text-align:center;height:2px;border-bottom:1px solid #333;"></td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;border-bottom:1px solid #333;"></td>
				<td style="width:150px;text-align:center;border-bottom:1px solid #333;"></td>
			</tr>
			<tr>
				<td style="width:500px;text-align:center;height:2px;border-bottom:1px solid #333;"></td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;border-bottom:1px solid #333;"></td>
				<td style="width:150px;text-align:center;border-bottom:1px solid #333;"></td>
			</tr>

			<tr>
				<td><?php if(strtoupper($main['payment_type']) == 'CHECK'): ?>
					<span>BANK : </span><span style="border-bottom:1px solid #000;width:150px;display:inline-block;"><?php echo $main['bank']; ?></span>
					<span>CHECK NO :</span><span style="border-bottom:1px solid #000;width:150px;display:inline-block;"><?php echo $main['check_no']; ?></span>
					<span>DUE DATE :</span><span style="border-bottom:1px solid #000;width:150px;display:inline-block;"><?php echo $main['check_date']; ?></span></span>
					<?php else: ?>
					<span>BANK : </span><span style="border-bottom:1px solid #000;width:150px;display:inline-block;"></span>
					<span>CHECK NO :</span><span style="border-bottom:1px solid #000;width:150px;display:inline-block;"></span>
					<span>DUE DATE :</span><span style="border-bottom:1px solid #000;width:150px;display:inline-block;"></span>
					<?php endif; ?>
				</td>
				<td></td>
				<td></td>
			</tr>
		</tbody>
	</table>

	<table style="width:100%;margin-top:1em">
		<tr style="border-top:1px solid #333;">
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr style="border:1px solid #333;border-left:0px;border-right:0px">
			<td style="width:500px;text-align:center;">J O U R N A L  &nbsp; E N T R I E S </td>
			<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px"></td>
			<td style="width:150px;text-align:center;">DEBIT</td>
			<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px"></td>
			<td style="width:150px;text-align:center;">CREDIT</td>
		</tr>
		<tbody>
			<tr>
				<td style="width:500px;border-bottom:1px solid #333;vertical-align:top;position:relative">				
				</td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;border-bottom:1px solid #333;"></td>
				<td style="width:150px;text-align:center;border-bottom:1px solid #333;"></td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;border-bottom:1px solid #333;"></td>
				<td style="width:150px;text-align:center;border-bottom:1px solid #333;"></td>
			</tr>
			<?php   $total = 0;
					
					$total_debit = 0;
					$total_credit = 0;
					
			 ?>
			<?php foreach($journal as $row): ?>
			<?php $total = $total + $row['amount']; 
					$debit = 0;
					$credit = 0;
				   $margin = "2em";
			?>

			<?php if($row['dr_cr'] == 'DEBIT'){
					$debit  = $row['amount'];
					$total_debit += $debit;
					
				  }else{
				  	$credit = $row['amount'];
				  	$total_credit += $credit;
				  	$margin = "4em";
				  }

				  if($debit == 0){
				  	$debit = "";
				  }else{
				  	$debit = number_format($debit,2);	
				  }


				  if($credit == 0){
				  	$credit = "";
				  }else{
				  	$credit = number_format($credit,2);	
				  }
				  
			?>
						
			<tr style="">				
				<td>
					<span style="margin-left:<?php echo $margin; ?>;"><?php echo $row['account_description']; ?></span>
				</td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;"></td>
				<td style="text-align:center"><?php echo $debit; ?></td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;"></td>
				<td style="text-align:center"><?php echo $credit; ?></td>
			</tr>

			<?php endforeach;?>
			<tr>				
				<td>
				</td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;"></td>
				<td style="text-align:center"><strong><?php echo number_format($total_debit,2); ?></strong></td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;"></td>
				<td style="text-align:center"><strong><?php echo number_format($total_credit,2); ?></strong></td>
			</tr>
			<tr>
				<td style="width:500px;text-align:center;height:2px;border-bottom:1px solid #333;"></td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;border-bottom:1px solid #333;"></td>
				<td style="width:150px;text-align:center;border-bottom:1px solid #333;"></td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;border-bottom:1px solid #333;"></td>
				<td style="width:150px;text-align:center;border-bottom:1px solid #333;"></td>
			</tr>
			<tr>
				<td style="width:500px;text-align:center;height:2px;border-bottom:1px solid #333;"></td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;border-bottom:1px solid #333;"></td>
				<td style="width:150px;text-align:center;border-bottom:1px solid #333;"></td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;border-bottom:1px solid #333;"></td>
				<td style="width:150px;text-align:center;border-bottom:1px solid #333;"></td>
			</tr>		
		</tbody>
	</table>

	<table style="margin-top:1em;width:100%;">
		<tr>
			<td>
					<table border=1 style="width:100%">
						<tr style="text-align:center">
							<td>PREPARED BY</td>
							<td>CHECKED BY</td>
							<td>APPROVED BY</td>
						</tr>
						<tr style="height:60px">
							<td><?php echo $main['preparedBy_name']; ?></td>
							<td><?php echo $main['checkedBy_name']; ?></td>
							<td><?php echo $main['approvedBy_name']; ?></td>
						</tr>
					</table>				
			</td>
			<td>
				<table style="width:100%">
					<tr>
						<td>RECEIVED THE FULL PAYMENT AMOUNT DESCRIBED ABOVE BY:</td>
					</tr>
					<tr>
						<td style="border-bottom:1px solid #333;height:40px"></td>
					</tr>
					<tr>
						<table style="width:100%">
							<tr>
								<td>RES. CERT.#</td>
								<td class="border_bottom" style="width:140px"></td>
								<td>DATE: </td>
								<td class="border_bottom" style="width:100px"></td>
								<td>PLACE</td>
								<td class="border_bottom" style="width:100px"></td>
							</tr>
						</table>
					</tr>
				</table>
			</td>
		</tr>
	</table>

</div>
</div>