<style>
	table{
		font-size: 11px !important;
	}
</style>


<div id="wrapper" style="width:900px;">
	<div class="container">
		<div class="row">
			
			<div class="col-xs-8">
				<h2 class="center-text title"><?php echo $header['title']; ?></h2>
				<div class="round padding  dark"><span class="center-text" style="border-bottom:1px solid #fff;display:block"><?php echo $header['sub_title'] ?></span></div>
				<div class="round padding center-text margin-top bold">DISBURSEMENT VOUCHER</div>			
			</div>
		
			<div class="col-xs-4">

				<div style="display:block;height:60px;margin-top:2em">
					<span style="display:block" class="center-text"><?php echo $header['address']; ?></span>
					<span style="display:block" class="center-text"><?php echo $header['contact']; ?></span>
				</div>
							
				<div class="round">
					<div class="border-bottom po-content">
						<span class="po-label center-text">DV NO:</span>
						<span class="center-text po-color" ><font color="red"><?php echo $voucher_info['CV NO.']; ?></font></span>
					</div>
					<div >
						<span class="po-label center-text">DATE :</span>
						<span class="center-text"><?php echo $voucher_info['CV DATE']; ?></span>
					</div>
				</div>	

			</div>

		</div>
		
		<div class="round" style="margin-top:2em;padding:1em;">
			<div><strong>PAYEE: </strong><span style="margin-left:1em;"><?php echo $supplier[0]['business_name']; ?></span></div>
			<div><strong>ADDRESS: </strong><span style="margin-left:1em;"><?php echo $supplier[0]['address']; ?></span></div>
		</div>
		
	<table width="100%" style="margin-top:2em;">
		<tr>
			<td style="width:15%">Amount in words : </td>
			<td style="width:60%"class="bank" id="amtinwords"></td>
			<td>&nbsp;</td>
			<td style="width:20%"class="bank" id="amtinnumbers"></td>
		</tr>
	</table>

	<table id="item-table">
		<thead class="border">
			<tr class="border">
				<th>ACCOUNT DISTRIBUTION</th>
				<th>DEBIT</th>
				<th>CREDIT</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$total_debit  = 0; 
				$total_credit = 0; 
			?>
			<?php 				
				foreach($journal_details as $row): ?>
			<?php 

				  $padding = "";
				  $debit   = "";
				  $credit  = "";
				  if($row['CR/DR'] == "DEBIT"){
				  	$debit = $row['Amount'];
				  	$total_debit += $debit;
				  }else{				  	
				  	$padding = "style='padding-left:1em'";
				  	$credit = $row['Amount'];				  	
					$total_credit += $credit;
					
				  }

			 ?>
				<tr class="border">
					<td <?php echo $padding; ?>><?php echo $row['Account']; ?> (<?php echo $row['Account Code']; ?>)</td>
					<td class="td-number"><?php echo $this->extra->number_format($debit); ?></td>
					<td class="td-number"><?php echo $this->extra->number_format($credit); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
				<tr class="border">
					<td style="text-align:center;"><strong>TOTAL</strong></td>
					<td class="total td-number"> <strong><?php echo $this->extra->number_format($total_debit); ?></strong></td>
					<td class="total td-number"> <strong><?php echo $this->extra->number_format($total_credit); ?></strong></td>
				</tr>
		</tfoot>
		
	</table>
	

	<table id="item-table" >
		<thead>
			<tr class="border">
				<th>CHECK DATE</th>
				<th>BANK</th>
				<th>CHECK NO</th>
				<th>TOTAL</th>
			</tr>
		</thead>
		<tbody>
			<tr class="border">
				<td class="center-text"><?php echo $journal_credit['check_date']; ?></td>
				<td class="center-text"><?php echo $journal_credit['subsidiary']; ?></td>
				<td class="center-text"><?php echo $journal_credit['cv_no']; ?></td>
				<td class="center-text"><?php echo $this->extra->number_format($journal_credit['amount']); ?></td>
			</tr>
		</tbody>
	</table>
	
	<div class="row" style="margin-top:25px;">
		<div class="col-xs-3">
			<div class="form-group round padding signatory-panel">
				<strong>Prepared By : </strong>
				<strong class="signatory"><?php echo (isset($signatory['prepared_by'][0]['Person Name']))? $signatory['prepared_by'][0]['Person Name'] : ''; ?></strong>
			</div>
		</div>
		<div class="col-xs-3" style="float:right">
			<div class="form-group round padding signatory-panel">
				<strong>Check By : </strong>
				<strong class="signatory"><?php echo (isset($signatory['checked_by'][0]['Person Name']))? $signatory['checked_by'][0]['Person Name'] : '' ; ?></strong>
			</div>
		</div>
		<div class="col-xs-3" style="float:right">
			<div class="form-group round padding signatory-panel">
				<strong>Noted By : </strong>
				<strong class="signatory"><?php echo (isset($signatory['checked_by'][0]['Person Name']))? $signatory['checked_by'][0]['Person Name'] : ''; ?></strong>
			</div>
		</div>
		<div class="col-xs-3" style="float:right">
			<div class="form-group round padding signatory-panel">
				<strong>Approved By : </strong>
				<strong class="signatory"><?php echo (isset($signatory['approved_by'][0]['Person Name']))? $signatory['approved_by'][0]['Person Name'] : ''; ?></strong>
			</div>
		</div>
	</div>





	</div>
</div>

<script>
	$(function(){
			$('#amtinwords').html('<?php echo ucfirst($this->extra->convert_number_to_words($total_credit)); ?>' +' only');
			$('#amtinnumbers').html('<?php echo $this->extra->number_format($total_credit); ?>');
	});

</script>