						
						<?php 

						$data['main'] = array();
						$data['supplier'] = array();
						$data['supplier2'] = array();
						foreach($details_data as $key=>$row){
							$data['main'][$key] = $row;
							foreach($canvass_details as $c_row){
								if($c_row['itemNo'] == $row['itemNo']){
									$data['supplier'][$c_row['supplier_id'].'-'.$c_row['supplierType']][] = $c_row;
									$data['supplier2'][$c_row['supplier_id'].'-'.$c_row['supplierType']]  = $c_row['supplier_id'];
									/*$data['main'][$key]['supplier'] = $c_row['supplier_id'].'-'.$c_row['supplierType'];*/
								}
							}
						}

					
						$items =  array();
						foreach($canvass_details as $z_row){
							$items[$z_row['itemNo']][$z_row['supplier_id']] = $z_row;
						}

						
						/*$data['merge'] = array();
						foreach($canvass_details as $row)
						{

							$array = array();
							foreach($data['supplier2'] as $key=>$row1){
								$array[$key] = '';
								if($row['supplier_id'] == $row1){
									$array[$key] = $row;
								}
							}
							$data['merge'][] = $array;

						}*/
						
						
						$canvass_useronly = false;
						$clickable = '';
						if($this->lib_auth->restriction('CANVASS USER'))
						{
							if($main_data['status']!='CANCELLED')
							{
								$clickable = ($main_data['approval']!='TRUE')? 'cv-items':'';
								$canvass_useronly = true;
							}							
						}else
						{
							$clickable = '';
						}

						?>

<div id="wrapper">
<div class="container">
	<div style="height:10px;"></div>

	<table>
		<thead>
			<tr>
				<th colspan="<?php echo (count($data['supplier']) * 2) + 4;?>">
					<h2 class="center-text"><img src="<?php echo base_url('images/dcd_logo.jpg');?>" width="200"></h2>
					<span class="center-text" style="display:block"><?php echo $header['address']; ?></span>
				</th>
			</tr>
			<tr>
			</tr>
			<tr>
				<td colspan="<?php echo (count($data['supplier']) * 2) + 4;?>">
					<span style="display:block;" class="right-text"><strong>Canvass No:</strong> <?php echo $main_data['c_number']; ?></span>
					<span style="display:block;" class="right-text"><strong>Canvass Date:</strong> <?php echo $this->extra->format_date($main_data['c_date']); ?></span>
					<span style="display:block;" class="right-text"><strong>Reference No:</strong> <?php echo $main_data['purchaseNo']; ?></strong></span>
				</td>
			</tr>
			<tr>
				<th colspan="<?php echo (count($data['supplier']) * 2) + 4;?>">&nbsp;</th>
			</tr>
			<tr>
				<th colspan="<?php echo (count($data['supplier']) * 2) + 4;?>" class="bold center-text" style="font-size:16px;">A B S T R A C T  &nbsp;  O F  &nbsp;  Q U O T A T I O N</th>
			</tr>
			<tr>
			</tr>
			<tr>
				<th colspan="4"></th>
				<th colspan="<?php echo (count($data['supplier']) * 2); ?>" class="center-text">SUPPLIERS</th>
			</tr>
		</thead>
		<tbody>
			<tr class="border">
				<th colspan="4"></th>
				<!---->
				<?php $cnt = 0; foreach($data['supplier'] as $row): $cnt++; ?>										
				<th class="td-number td-head-border" colspan="2"><span data-toggle="tooltip" data-placement="top" title="<?php echo $row[0]['c_terms'] ?>"> 
				<?php if($main_data['approval']!='TRUE' && $canvass_useronly == true): ?>
					<input type="checkbox" data-supplier_id ="<?php echo $row[0]['supplier_id']; ?>" class="check-all" name="supplier_name">
				<?php endif; ?>
				<?php echo $row[0]['Supplier']; ?></span></th>
				<?php endforeach; ?>										
			</tr>
			<tr class="border">
				<th class="center-text">Item #</th>
				<th class="td-number center-text">Qty</th>										
				<th class="center-text">Unit</th>
				<th class="center-text">Description</th>
				<!--- -->
				<?php foreach($data['supplier'] as $row): ?>
				<th class="td-number td-border-left center-text">Unit Cost</th>									
				<th class="td-number td-border-right center-text">Total Cost</th>
				<?php endforeach; ?>
			</tr>
			<?php 
			$total_amount = array();
			$discounted_totalamount = array();	
			$main_cnt = 0;								
			foreach ($data['main'] as $row): $cnt1 = 0;
			?>
			<tr class="border">
				<td class="center-text"><?php echo $row['itemNo'];?></td>
				<td class="td-qty td-number center-text"><?php echo $row['qty'];?></td>
				<td class="center-text"><?php echo $row['unitmeasure'];?></td>
				<td style="white-space:nowrap; width:20%;"><?php echo $row['itemDesc'];?></td>											
				<?php
					$cnt = 0;
					foreach($data['supplier2'] as $key=>$row2):													
						if(empty($items[$row['itemNo']][$row2]) || $items[$row['itemNo']][$row2]['supplier_cost'] == ""):
				?>															
						<td class="cv-data-items"></td>
						<td class="cv-data-items"></td>
				<?php  
						else:
					
						
						/*$c_row = $data['merge'][$main_cnt][$key];*/
						$c_row = $items[$row['itemNo']][$row2];
						$approved = ($c_row['approvedSupplier']=='TRUE')? 'approved-item' : '';

						$total = $row['qty'] * $c_row['supplier_cost'];
						$total_amount[$cnt]  = (empty($total_amount[$cnt])) ? 0 : $total_amount[$cnt];
						$total_amount[$cnt] += $total;

						if($approved != ''){
							$c_row['discounted_total'] = $c_row['discounted_total'];
						}else{
							$c_row['discounted_total'] = 0;
						}

						if(empty($discounted_totalamount[$cnt])){
							$discounted_totalamount[$cnt] = str_replace(',','',$c_row['discounted_total']);
						}else{
							$discounted_totalamount[$cnt] += str_replace(',','',$c_row['discounted_total']);
						}
						
				?>												
					<td data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="unit-item td-number <?php echo $clickable ?> cv-data-items <?php echo $approved; ?>"><?php if($approved != ''){ echo $c_row['supplier_cost']; }else{ echo '-'; } ?></td>												
					<td data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="cv-data-items td-qty td-number <?php echo $clickable;?> <?php echo $approved; ?>"><?php if($approved != ''){ echo $c_row['discounted_total']; }else{ echo '-'; }  ?></td>
					<td style="display:none" data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="cv-data-items td-number <?php echo $clickable;?> <?php echo $approved; ?>"><?php echo $this->extra->number_format($total);  ?></td>
				<?php 
						$cnt1++ ;
						endif;
				 		$cnt++;												
					endforeach;

				?>
			</tr>
			<?php $main_cnt++; ?>
			<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr class="border">
				<td colspan="4"><?php echo count($data['main']) ?> item(s) <span style="float:right;">GRAND TOTAL:</span></td>
				<?php $cnt=0; foreach($data['supplier'] as $row):?>				
				<td class="td-number td-head-border" colspan="2">Total : <span><?php echo $this->extra->number_format((isset($discounted_totalamount[$cnt]))? $discounted_totalamount[$cnt] : 0); ?></span></td>
				<?php $cnt++; ?>
				<?php endforeach; ?>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="3" style="width:30%;text-indent:4em;"><span>We hereby certify to the correctness of the above quotations of prices opened at CWD on _______________ in our presence including conditions for each item.</span></td>
			</tr>
			<tr>
			</tr>
		</tfoot>
	</table>


	<table>
		<tbody>
			<tr>
				<th colspan="4" style="width:30%;"></th>
				<th colspan="2">Item No</th>
				<th colspan="2">Supplier</th>
				<th colspan="2">Amount</th>
			</tr>
			<?php
				
				foreach($approvedsupplier as $row){	
			?>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td style="text-align:left;text-decoration:underline;width:200px;">
					<?php
						$item = "";
						foreach($approveditems as $row2){
							if($row2['supplier_id'] == $row['supplier_id']){
								if(count($row2['itemNo']) > 1){
									echo $row2['itemNo'].',';
								}else{
									echo $row2['itemNo'];
								}
							}
						}
					?>
				</td>
				<td></td>
				<td style="text-decoration:underline;width:400px;text-align:left;"><?php echo $row['supplier']; ?></td>
				<td></td>
				<td style="text-decoration:underline;width:100px;text-align:right;"><?php echo number_format($row['total'],2); ?></td>
			</tr>
			<?php
				}
			?>
		</tbody>
	</table>

	<table>
		<tfoot>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
    			<th colspan="5">BIDDING COMMITTEE:</th>
    			<th>APPROVED/DISAPPROVED</th>
  			</tr>
  			<tr>
    			<td style="width:220px;">
    				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"><?php echo $chairperson['chairperson']; ?></strong>
    				<strong class="center-text" style="display:block;font-size:11px;">Chairperson</strong>
    			</td>
    			<td style="width:220px;">
    				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"><?php echo $vice_chairperson['vice_chairperson']; ?></strong>
    				<strong class="center-text" style="display:block;font-size:11px;">Vice Chairperson</strong>
    			</td>
    			<?php
    				if(count($member) > 0){
    					foreach($member as $row){
    			?>
    			<td style="width:220px;">
    				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"><?php echo $row['member']; ?></strong>
    				<strong class="center-text" style="display:block;font-size:11px;">Member</strong>
    			</td>
    			<?php
    					}
    				}
    			?>
    			<td style="width:220px;">
    				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"><?php echo $general_manager['general_manager']; ?></strong>
    				<strong class="center-text" style="display:block;font-size:11px;">General Manager</strong>
    			</td>
  			</tr>
  				<td colspan="4">&nbsp;</td>
  				<td style="width:220px;">
    				<strong class="signatory" style="border-bottom:1px solid #000;display:block;font-size:11px;"><?php echo $end_user['end_user']; ?></strong>
    				<strong class="center-text" style="display:block;font-size:11px;">End User</strong>
    			</td>
    			<td>&nbsp;</td>
  			<tr>
  			</tr>
  			<tr>
  				<td colspan="6">&nbsp;</td>
  			</tr>
		</tfoot>
	</table>

	<div class="print_ft">
		<div class="row">
			<div class="col-md-4"><span style="font-weight:bold;">FM-PUR-09</span></div>
			<div class="col-md-4" style="text-align:center;font-weight:bold;"><span>00</span></div>
			<div class="col-md-4"><span class="time_date" style="font-weight:bold;"><span>8/20/2016</span></span></div>
		</div>
	</div>

	
	<!-- <div class="row">
		<div class="col-xs-8">
			<h2 class="title"><img src="<?php echo base_url('images/dcd_logo.jpg');?>" width="200"></h2>
			<div style="display:block;height:60px;">
				
				<span style="display:block"><?php echo $header['contact']; ?></span>
				<span style="display:block"><?php echo $header['fax_no']; ?></span>
			</div>						
		</div>
	
		<div class="col-xs-4">

			<div style="display:block;height:60px;margin-top:2em">
				<span style="display:block" class="center-text"><?php echo $header['website']; ?></span>
				<span style="display:block" class="center-text"><?php echo $header['email']; ?></span>
			</div>

			<div class="round padding center-text margin-top bold dark">ABSTRACT OF QUOTATION</div>	

		</div>
	</div>

	 <div class="round" style="margin-top:2em;padding:1em;">
		<div class="row">
			<div class="col-md-6">
				<div>Created From:</div>
				<div><strong><?php echo $pr_main['from_projectMainName']; ?></strong></div>
				<div><strong><?php echo $pr_main['from_projectCodeName']; ?></strong></div>
			</div>
			<div class="col-md-6">
				<div>Canvass No:</div>
				<div><strong><?php echo $main_data['c_number']; ?></strong></div>
				<div>Canvass Date:</div>
				<div><strong><?php echo $this->extra->format_date($main_data['c_date']); ?></strong></div>
				<div>Reference No:</div>
				<div><strong><?php echo $main_data['purchaseNo']; ?></strong></div>
			</div>
		</div>		
	</div>
		<table id="tbl-canvass-info" class="table table-item">
								<thead>
									<tr>
										<th colspan="3"></th>
										
										<?php $cnt = 0; foreach($data['supplier'] as $row): $cnt++; ?>										
										<th class="td-number td-head-border" colspan="4"><span data-toggle="tooltip" data-placement="top" title="<?php echo $row[0]['c_terms'] ?>"> 
										<?php if($main_data['approval']!='TRUE' && $canvass_useronly == true): ?>
											<input type="checkbox" data-supplier_id ="<?php echo $row[0]['supplier_id']; ?>" class="check-all" name="supplier_name">
										<?php endif; ?>
										<?php echo $row[0]['Supplier']; ?></span></th>
										<?php endforeach; ?>										
									</tr>
									<tr>
										<th>Item Name</th>										
										<th>Unit</th>
										<th class="td-number">Qty</th>
										
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
									$discounted_totalamount = array();	
									$main_cnt = 0;								
									foreach ($data['main'] as $row): $cnt1 = 0;
									?>
										<tr>
											<td style="white-space:nowrap;"><?php echo $row['itemDesc'];?></td>											
											<td><?php echo $row['unitmeasure'];?></td>
											<td class="td-qty td-number"><?php echo $row['qty'];?></td>
											
											<?php
												$cnt = 0;
												foreach($data['supplier2'] as $key=>$row2):													
													if(empty($items[$row['itemNo']][$row2]) || $items[$row['itemNo']][$row2]['supplier_cost'] == ""):
											?>															
													<td class="cv-data-items"></td>
													<td class="cv-data-items"></td>
													<td class="cv-data-items"></td>
													<td class="cv-data-items"></td>
											<?php  
													else:
												
													
													/*$c_row = $data['merge'][$main_cnt][$key];*/
													$c_row = $items[$row['itemNo']][$row2];
													$approved = ($c_row['approvedSupplier']=='TRUE')? 'approved-item' : '';

													$total = $row['qty'] * $c_row['supplier_cost'];
													$total_amount[$cnt]  = (empty($total_amount[$cnt])) ? 0 : $total_amount[$cnt];
													$total_amount[$cnt] += $total;
													if(empty($discounted_totalamount[$cnt])){
														$discounted_totalamount[$cnt] = str_replace(',','',$c_row['discounted_total']);
													}else{
														$discounted_totalamount[$cnt] += str_replace(',','',$c_row['discounted_total']);
													}
													
											?>												
												<td data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="unit-item td-number <?php echo $clickable ?> cv-data-items <?php echo $approved; ?>"><?php echo $c_row['supplier_cost']; ?></td>												
												<td data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="cv-data-items td-qty td-number <?php echo $clickable;?> <?php echo $approved; ?>"><?php echo $c_row['percentage']; ?>% Discount <?php echo $c_row['discounted']; ?></td>
												<td data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="cv-data-items td-qty td-number <?php echo $clickable;?> <?php echo $approved; ?>"><?php echo $c_row['discounted_price']; ?></td>
												<td data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="cv-data-items td-qty td-number <?php echo $clickable;?> <?php echo $approved; ?>"><?php echo $c_row['discounted_total']; ?></td>
												<td style="display:none" data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="cv-data-items td-number <?php echo $clickable;?> <?php echo $approved; ?>"><?php echo $this->extra->number_format($total); ?></td>
											<?php 
													$cnt1++ ;
													endif;
											 		$cnt++;												
												endforeach;

											?>
										</tr>
									<?php $main_cnt++; ?>
									<?php endforeach ?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="3"><?php echo count($data['main']) ?> item(s)</td>
										
										<?php $cnt=0; foreach($data['supplier'] as $row):?>
										
										<td class="td-number td-head-border" colspan="4">Total : <span><?php echo $this->extra->number_format((isset($discounted_totalamount[$cnt]))? $discounted_totalamount[$cnt] : 0); ?></span></td>
										
										<?php $cnt++; ?>
										<?php endforeach; ?>
									</tr>
								</tfoot>
							</table>
		
	<div class="row" style="margin-top:25px;">
		<div class="col-xs-4">
			<div class="form-group round padding signatory-panel">
				<strong>Prepared By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['preparedBy']);?>"></div>
				<strong class="signatory"><?php echo $main_data['preparedBy_name']; ?></strong>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="form-group round padding signatory-panel">
				<strong>Approved By : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['approvedBy']);?>"></div>
				<strong class="signatory"><?php echo $main_data['approvedBy_name']; ?></strong>
			</div>
		</div>
		<div class="col-xs-4" style="float:right">
			<div class="form-group round padding signatory-panel">
				<strong>Final Approval : </strong>
				<div class="digital_signature" style="<?php //echo $this->extra->get_digital_signature($main_data['final_approval']);?>"></div>
				<strong class="signatory"><?php echo $main_data['final_approval_name']; ?></strong>
			</div>
		</div>
	</div>
	<div class="print_ft">
		<div class="row">
			<div class="col-md-9"><span class="copy" style="display:none"></span></div>
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

$(document).ready(function(){
	$('.email').on('click',function(){
		
	});
});

</script>
		
	