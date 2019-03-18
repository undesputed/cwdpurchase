<style>
	
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border-top:none;
	}
	
	.space1{
		padding-left:5em !important;
	}
	.space2{
		padding-left:2em !important;
	}
	.border-top{
		border-top:1px solid !important;
	}
	.text-right{
		text-align: right;
	}
	.double-border{
		border-style:double;
		border-left:none;
		border-right:none;
	}

</style>

<table class="table">
	<tbody>

		<?php 
			$debit = 0;
			$credit = 0;
			$first_description = '';
		 ?>
		<?php 
			$cnt = 0;
			foreach($result as $row): 
				if($cnt == 0){
					$first_description = $row['account_description'];
				}
				$cnt++;				
		?>
			<tr>
				<td><?php echo $row['account_description']; ?></td>
				<?php 
					if(!empty($row['CREDIT'])){
						$amount = $row['CREDIT'];
					}else{
						$amount = '-'.$row['DEBIT'];
					}
				?>					
				<td class="text-right"><?php echo number_format($amount,2); ?></td>
			</tr>		
		<?php    $credit += $amount; ?>
		<?php if(!empty($row['position'])): ?>
			<tr>
				<td>Total</td>
				
				<td class="border-top text-right"><?php echo number_format($credit,2); ?></td>
			</tr>
		<?php endif; ?>

	
		<?php endforeach; ?>
			<?php $total = $credit - $debit; ?>
			<tr>
				<td>Owner's Equity, <?php echo $date['to'] ?></td>				
				<td class="double-border border-top text-right"><strong><?php echo number_format($total,2); ?></strong></td>
			</tr>
	</tbody>
</table>