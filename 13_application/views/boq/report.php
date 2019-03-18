<style>
	
	#boq-report{
		table-layout: auto;
		font-size:11px !important;
	}
	.head{
		text-align: center;
		font-weight: bold;
		border: 2px solid #ccc !important;
	}
	.center{
		text-align: center;
	}
	.vertical{
		vertical-align: middle !important;
		text-align: center;
	}
	.left-border{ 
		border-left: 2px solid #ccc !important;
	}
	.bottom{
		border-bottom: 2px solid #ccc !important;
	}
	.right{
		border-right: 2px solid #ccc !important;
	}
	.po{
		text-align: right;
		border: 2px solid #ccc !important;
		font-weight: bold;
	}
	.amount{
		text-align: right;
	}
	.items{
		padding-left:20px !important;
	}
	
	.sub-total{
		font-weight: bold;
	}
	.red-text{
		color:#f00;
	}

</style>
	
				
	
		<div class="table-responsive">
		<table id="boq-report" class="table-sieve table table-bordered table-condensed" style="margin-top:15px;">
			    <colgroup>
			        <col style="width:40px" />
			        <col style="width:380px" />
			        <col style="width:45px" />
			        <col style="width:45px"/>
			        <col/>
			        <col/>
			        <col/>
			        <col/>
			        <col/>
			        <col style="width:45px" />
			        <col style="width:45px" />
			        <col/>			      
    			</colgroup>
			<thead>

				<tr>
					<td colspan='9' class="head">BOQ</td>
					<td colspan='3' class="head">Actual PO</td>
					<td class="head" >Rem/Discrepancy</td>
				</tr>

				<tr>
					<td rowspan='2' class="vertical left-border">Item No.</td>
					<td rowspan='2' class="vertical">Description</td>
					<td rowspan='2' class="vertical">Qty</td>
					<td rowspan='2' class="vertical">Unit</td>
					<td colspan='5' class="center">Unit Cost</td>
					<td rowspan='2' class="vertical left-border">Qty</td>
					<td rowspan='2' class="vertical">Unit</td>
					<td rowspan='2' class="vertical">Amount</td>
					<td rowspan='2' class="vertical left-border right" style="width:150px;">+/-</td>					
				</tr>

				<tr>
					<td class="center">Material</td>
					<td class="center">Labor</td>
					<td class="center">Others</td>
					<td class="center">Total</td>
					<td class="center">Amount</td>
				</tr>

			</thead>
			<tbody>
				<?php 
					$check_dup_item_no = array();
					$total_boq = 0;
					$total_po  = 0;
					$total_discrepancy = 0;

				?>
				<?php foreach($boq as $row): ?>
						<?php 
							$sub_total_total  = 0;
							$sub_total_amount = 0;		
							$sub_total_po_amount = 0;

						?>
						<tr >
							
							<td colspan="2" class="left-border"><?php echo $row['main_title']; ?></td>
							<td><?php echo $row['qty']; ?></td>
							<td><?php echo $row['unit']; ?></td>
							<td class="amount"><?php echo $this->extra->number_format($row['material']); ?></td>
							<td class="amount"><?php echo $this->extra->number_format($row['labor']); ?></td>
							<td class="amount"><?php echo $this->extra->number_format($row['others']); ?></td>
							<td class="amount"><?php echo $this->extra->number_format($row['total']); ?></td>
							<td class="amount"><?php echo $this->extra->number_format($row['amount']); ?></td>
							<td class="left-border"><?php echo $row['po_qty']; ?></td>
							<td><?php echo $row['po_unit']; ?></td>
							<td><?php echo $this->extra->number_format($row['po_amount']); ?></td>
							<?php 
								$discrepancy_value = $row['amount'] - $row['po_amount']; 
								$red = ($discrepancy_value < 0)? "red-text": '' ; 
							?>
							<td class="left-border right amount <?php echo $red; ?>"><?php echo $discrepancy_value; ?></td>
							<?php $total_discrepancy += $discrepancy_value; ?>
						</tr>

						<?php 

							$sub_total_total  += $row['total'];
							$sub_total_amount += $row['amount'];
							$total_po += $row['po_amount'];
							
						?>

						<?php foreach ($row['sub'] as $row1): ?>
						
						<tr>							
							<td class="left-border"><?php echo $row1['no']; ?></td>
							<td><?php echo $row1['main_title']; ?></td>
							<td><?php echo $row1['qty']; ?></td>
							<td><?php echo $row1['unit']; ?></td>
							<td class="amount"><?php echo $this->extra->number_format($row1['material']); ?></td>
							<td class="amount"><?php echo $this->extra->number_format($row1['labor']); ?></td>
							<td class="amount"><?php echo $this->extra->number_format($row1['others']); ?></td>
							<td class="amount"><?php echo $this->extra->number_format($row1['total']); ?></td>
							<td class="amount"><?php echo $this->extra->number_format($row1['amount']); ?></td>
							<td class="left-border"><?php echo $row1['po_qty'] ?></td>
							<td><?php echo $row1['po_unit'] ?></td>
							<td class="amount"><?php echo $row1['po_amount'] ?></td>
							<?php 
								$discrepancy_value = $row1['amount'] - $row1['po_amount']; 
								$red = ($discrepancy_value < 0)? "red-text": '' ; 
							?>
							<td class="left-border right amount <?php echo $red; ?>"><?php echo $this->extra->number_format($discrepancy_value); ?></td>
							<?php $total_discrepancy += $discrepancy_value; ?>
						</tr>
						
						<?php
							$sub_total_total  += $row1['total'];
							$sub_total_amount += $row1['amount'];
							$total_po += $row1['po_amount'];							
						?>
						
							<?php 
								foreach ($row1['items'] as $row2):									
									$check_dup_item_no[$row2['main_id']]['display'] = (!isset($check_dup_item_no[$row2['main_id']]['display'])) ? "" : $check_dup_item_no[$row2['main_id']]['display'];	
									
									if($check_dup_item_no[$row2['main_id']]['display'] == ""){
										
										if($row2['qty'] > $row2['po_qty']){
											$check_dup_item_no[$row2['main_id']]['display'] = $row2['po_qty'];
											$check_dup_item_no[$row2['main_id']]['rem'] = "";
											$amount = $row2['unit_cost'] * $row2['po_qty'];
											$check_dup_item_no[$row2['main_id']]['amount'] = $amount;
										}else{
											$remaining = $row2['po_qty'] - $row2['qty'];
											$check_dup_item_no[$row2['main_id']]['display'] = $row2['qty'];
											$check_dup_item_no[$row2['main_id']]['rem'] = $remaining;
											$amount = $row2['unit_cost'] * $row2['qty'];
											$check_dup_item_no[$row2['main_id']]['amount'] = $amount;
										}

									}else{										
										if($row2['qty'] > $check_dup_item_no[$row2['main_id']]['rem']){
											$remaining = $check_dup_item_no[$row2['main_id']]['rem'] - $row2['qty'];
											$check_dup_item_no[$row2['main_id']]['display'] = "0";
											$check_dup_item_no[$row2['main_id']]['rem'] = $remaining;
											$amount = $row2['unit_cost'] * 0;
											$check_dup_item_no[$row2['main_id']]['amount'] = $amount;
										}else{											
											$check_dup_item_no[$row2['main_id']]['display'] = $check_dup_item_no[$row2['main_id']]['rem'];
											$amount = $row2['unit_cost'] * $check_dup_item_no[$row2['main_id']]['rem'];
											$check_dup_item_no[$row2['main_id']]['rem'] = "";											
											$check_dup_item_no[$row2['main_id']]['amount'] = $amount;
										}
									}									
									

							?>
							 <tr>
							 	<td class="left-border"><?php echo $row2['no']; ?></td>
							 	<td class="items"><?php echo $row2['main_title']; ?></td>
							 	<td><?php echo $row2['qty']; ?></td>
								<td><?php echo $row2['unit']; ?></td>
								<td class="amount"><?php echo $this->extra->number_format($row2['material']); ?></td>
								<td class="amount"><?php echo $this->extra->number_format($row2['labor']); ?></td>
								<td class="amount"><?php echo $this->extra->number_format($row2['others']); ?></td>
								<td class="amount"><?php echo $this->extra->number_format($row2['total']); ?></td>
							 	<td class="amount"><?php echo $this->extra->number_format($row2['amount']); ?></td>
							 	<td class="left-border"><a href="javascript:void(0)" class="item-history" data-itemno="<?php echo $row2['main_id']; ?>"><?php echo $check_dup_item_no[$row2['main_id']]['display']; ?></a></td>
								<td><?php echo $row2['po_unit'] ?></td>
								<td class="amount "><?php echo $this->extra->number_format($check_dup_item_no[$row2['main_id']]['amount']); ?></td>
								<?php $discrepancy_value = $row2['amount'] - $check_dup_item_no[$row2['main_id']]['amount'];
									$red = ($discrepancy_value < 0)? "red-text": '' ; 
								 ?>
							 	<td class="left-border right amount <?php echo $red; ?>"><?php echo $this->extra->number_format($discrepancy_value); ?></td>
							 	<?php $total_discrepancy += $discrepancy_value; ?>
							 </tr>
							<?php 
								$sub_total_total  += $row2['total'];
								$sub_total_amount += $row2['amount'];
								/*$total_po += $row2['po_amount'];*/
								/*$sub_total_po_amount += $row2['po_amount'];*/
								$total_po += $check_dup_item_no[$row2['main_id']]['amount'];
								$sub_total_po_amount += $check_dup_item_no[$row2['main_id']]['amount'];
							?>
							<?php endforeach; ?>
						<?php endforeach; ?>
						<tr class="sub-total">
							<td class="left-border" colspan="2" >SUBTOTAL FOR <?php echo $row['main_title']; ?></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td class="amount"><?php echo $this->extra->number_format($sub_total_total); ?></td>
							<td class="amount"><?php echo $this->extra->number_format($sub_total_amount); ?></td>
							<td class="left-border"></td>
							<td></td>
							<td class="amount"><?php echo $this->extra->number_format($sub_total_po_amount); ?></td>							
							<td class="left-border right"></td>
						</tr>
						<?php 
							$total_boq += $sub_total_amount;							
						?>
						<tr>
							<td colspan="12" style="border-left-border:0px;border-right:0px"></td>
						</tr>
				<?php endforeach; ?> 



				<?php if(count($item_no_boq) > 0): ?>
				<tr>
					<td class="left-border" colspan="2">UNPLANNED ITEMS</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="left-border"></td>
					<td></td>
					<td></td>
					<td class="left-border"></td>
				</tr>

				<?php $sub_total = 0;$item_cnt = 0; ?>
				<?php foreach($item_no_boq as $row): $item_cnt++; ?>
						<tr>
							<td class="left-border"><?php echo $item_cnt; ?></td>
							<td class="items"><?php echo $row['item_name']; ?></td>							
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td class="left-border"><a href="javascript:void(0)" class="item-history" data-itemno="<?php echo $row['itemNo']; ?>"><?php echo $row['total_quantity']; ?></a></td>
							<td><?php echo $row['unit_msr']; ?></td>
							<td class="amount"><?php echo $this->extra->number_format($row['total_unitcost']); ?></td>
							<td class="left-border"></td>							
						</tr>	
				<?php 
					$total_po  += $row['total_unitcost']; 
					$sub_total += $row['total_unitcost'];
				?>			
				<?php endforeach; ?>
				<tr class="sub-total">
					<td class="left-border" colspan="2">SUBTOTAL FOR UNPLANNED ITEMS</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="left-border"></td>
					<td></td>
					<td class="amount"><?php echo $this->extra->number_format($sub_total); ?></td>
					<td class="left-border"></td>
				</tr>		
				<?php endif; ?>

				<?php if(count($tenant) > 0): ?>
				<tr>
					<td class="left-border" colspan="2">TENANTS WITHDRAWALS</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="left-border"></td>
					<td></td>
					<td></td>
					<td class="left-border"></td>
				</tr>
				<?php $sub_total = 0; ?>
				<?php foreach($tenant as $key=>$row): ?>
				<tr>
						<td class="left-border"></td>
						<td class=""><?php echo $key; ?></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><?php echo $row['amount']; ?></td>
						<td class="left-border"></td>
						<td></td>
						<td class="amount"></td>
						<td class="left-border"></td>
				</tr>

					<?php 
						foreach($row['items'] as $row1): 						
					?>
							
							<tr>
								<td class="left-border"></td>
								<td class="items"><?php echo $row1['item_description']; ?></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td class="left-border"><?php echo $row1['total_withdraw_qty']; ?></td>
								<td><?php echo $row1['unit_measure']; ?></td>
								<td class="amount"><?php echo $row1['total_cost']; ?></td>
								<td class="left-border"></td>
							</tr>
							<?php 
								$total_po  += $row1['total_cost'];
								$sub_total += $row1['total_cost'];
							 ?>
					<?php endforeach; ?>
				<?php endforeach; ?>				
				<tr class="sub-total">
					<td class="left-border" colspan="2">SUBTOTAL FOR TENANT WITHDRAWALS</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="left-border"></td>
					<td></td>
					<td class="amount"><?php echo $this->extra->number_format($sub_total); ?></td>
					<td class="left-border"></td>					
				</tr>
				<?php endif; ?>
							

				<?php if(count($cost_result) > 0): ?>

				<tr>
					<td class="left-border" colspan="2">COST / EXPENSES</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="left-border"></td>
					<td></td>
					<td></td>
					<td class="left-border"></td>
				</tr>

				<?php $sub_total = 0; ?>
				<?php foreach($cost_result as $key=>$row): ?>
				<tr>
						<td class="left-border"></td>
						<td class=""><?php echo $row['description']; ?></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="left-border"></td>
						<td></td>
						<td class="amount"><?php echo $this->extra->number_format($row['cost']); ?></td>
						<td class="left-border"></td>
				</tr>
				<?php 
								$total_po  += $row['cost'];
								$sub_total += $row['cost'];
				?>
				<?php endforeach; ?>

				<tr class="sub-total">
					<td class="left-border" colspan="2">SUBTOTAL FOR COST/EXPENSE</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="left-border"></td>
					<td></td>
					<td class="amount"><?php echo $this->extra->number_format($sub_total); ?></td>
					<td class="left-border"></td>
				</tr>
				<?php endif; ?>
				
			</tbody>
			<tfoot>
				<tr>
						<td colspan='9' style="text-align:right">GRAND TOTAL === <?php echo number_format($total_boq,2,'.',','); ?></td>
						<td colspan='3' style="text-align:right"><?php echo number_format($total_po,2,'.',','); ?></td>
						<td style="text-align:right"><?php echo number_format($total_boq - $total_po,2,'.',','); ?></td>
				</tr>
			</tfoot>
		</table>
		</div>



<script>
	
	$(function(){

		$('.table-sieve').sieve();

		$('.header').click(function(){
     		$(this).toggleClass('expand').nextUntil('tr.header').slideToggle(100);
		});



	});

</script>