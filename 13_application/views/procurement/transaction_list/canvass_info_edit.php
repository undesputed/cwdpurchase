
<?php 
	$data['main'] = array();
	$data['supplier'] = array();
	foreach($details_data as $row){
		$data['main'][] = $row;
		foreach($canvass_details as $c_row){
			if($c_row['itemNo'] == $row['itemNo']){
				$data['supplier'][$c_row['supplier_id'].'-'.$c_row['supplierType']][] = $c_row;
			}
		}
	}
?>

<div class="container">
	<div class="row">
		<div class="col-md-6">
			<h4><?php echo $main_data['c_number']; ?></h4>
			<table>
				<tr>
					<td>P.R NO :</td>
					<td><?php echo $pr_main['purchaseNo']; ?></td>
				</tr>
				<tr>
					<td>P.R Date :</td>
					<td><?php echo $pr_main['purchaseDate']; ?></td>
				</tr>
				<tr>
					<td>Created From :</td>
					<td><?php echo $pr_main['from_projectCodeName']; ?></td>
				</tr>
			</table>
		</div>
		<div class="col-md-6"></div>
	</div>


	<div class="table-responsive">

		<table id="item-table" class="table">		
		<thead>
			<tr class="border">				
					<th colspan="3"></th>
					<!---->
					<?php $cnt = 0; foreach($data['supplier'] as $row): $cnt++; ?>										
					<th class="td-number td-head-border" colspan="4">
						<span data-toggle="tooltip" data-placement="top" title="<?php echo $row[0]['c_terms'] ?>"> 	
							<span class="close"><span aria-hidden="true">&times;</span></span>				
							<?php echo $row[0]['Supplier']; ?>
						</span>
					</th>
					<?php endforeach; ?>				
			</tr>
			<tr class="border">
				<th>Item Name</th>										
				<th>Unit</th>
				<th class="td-number">Qty</th>
				<!--- -->
				<?php foreach($data['supplier'] as $row): ?>
				<th class="td-number td-border-left">Unit Price</th>
				<th class="td-number">%Less Discount</th>
				<th class="td-number">Discounted Price</th>										
				<th class="td-number td-border-right">Total</th>
				<?php endforeach; ?>
			</tr>			
		</thead>	
		<tbody>
					<?php 
					$total_amount = array();
					$total_approved = array();
					$discounted_totalamount = array();
					foreach($data['main'] as $row): $cnt = 0; $cnt1 = 0;?>
						<tr>
							<td style="white-space:nowrap;"><?php echo $row['itemDesc'];?></td>											
							<td><?php echo $row['unitmeasure'];?></td>
							<td class="td-qty td-number"><?php echo $row['qty'];?></td>										
							<?php foreach($canvass_details as $c_row): 	

								 if($c_row['itemNo'] == $row['itemNo']):
								 	$cnt1++;
								 	$approved = ($c_row['approvedSupplier']=='TRUE')? 'approved-item' : '';
								 	if($c_row['approvedSupplier']=='TRUE')
								 	{
								 		if(empty($total_approved[$cnt])){
											$total_approved[$cnt] = str_replace(',','',$c_row['discounted_total']);
										}else{
											$total_approved[$cnt] += str_replace(',','',$c_row['discounted_total']);
										}
								 	}								 
							?>
							<td data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="unit-item td-number  cv-data-items <?php echo $approved; ?>"><?php echo $c_row['supplier_cost']; ?></td>
									
								<?php

									$total = $row['qty'] * $c_row['supplier_cost'];
									$total_amount[$cnt]  = (empty($total_amount[$cnt])) ? 0 : $total_amount[$cnt];
									$total_amount[$cnt] += $total;
									if(empty($discounted_totalamount[$cnt])){
										$discounted_totalamount[$cnt] = str_replace(',','',$c_row['discounted_total']);
									}else{
										$discounted_totalamount[$cnt] += str_replace(',','',$c_row['discounted_total']);	
									}
									$cnt++;
								?>
								
							<td data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="cv-data-items td-number  <?php echo $approved; ?>"><?php echo $c_row['percentage']; ?>% Discount <?php echo $c_row['discounted']; ?></td>
							<td data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="cv-data-items td-number  <?php echo $approved; ?>"><?php echo $c_row['discounted_price']; ?></td>
							<td data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="cv-data-items td-number  <?php echo $approved; ?>"><?php echo $c_row['discounted_total']; ?></td>
							<td style="display:none" data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="cv-data-items td-number <?php echo $clickable;?> <?php echo $approved; ?>"><?php echo $this->extra->number_format($total); ?></td>
							
							<?php $cnt1++; ?>
							<?php endif; ?>
							<?php endforeach; ?>							
						</tr>
					<?php endforeach ?>
				</tbody>
				<tfoot>
					<tr class="border">
						<td colspan="3"><?php echo count($data['main']) ?> item(s)</td>
						<?php $cnt=0; foreach($data['supplier'] as $row):?>
						<td class="td-number" colspan="4">Approved Total: <span>
						<?php
						if(!empty($total_approved[$cnt])){
							echo $this->extra->number_format($total_approved[$cnt]);
						}else{
							echo "None";
						}
							
						 ?></span>
						</td>
						<?php $cnt++; ?>
						<?php endforeach; ?>
					</tr>
					<tr class="border">				
						<td class="td-number" colspan="11">Total Amount Approved: <span><?php echo $this->extra->number_format(array_sum($total_approved)); ?></span></td>
					</tr>
				</tfoot>
	</table>

	</div>


</div>


<script>
	$(function(){
		


	});
</script>








