
			<?php if(count($po_list) > 0): ?>
	  		<?php foreach($po_list as $key=>$row): ?>
	  		<div class="">
				<div class="head_date">
					<strong><?php echo $key; ?></strong>
				</div>
				 <?php foreach($row as $row1): ?>				 

					<?php 

						$data_type = "cash_voucher_info";
						if($row1['transaction_type']=='journal_voucher'){
							$data_type = "journal_voucher"; 
						}

					?>


						<div class="item_contents hover " data-method="cash_voucher" data-type="<?php echo $data_type; ?>" data-value="<?php echo $row1['cash_voucher_id']; ?>" data-value="<?php echo $row1['cash_voucher_id']; ?>" data-url="<?php echo base_url().index_page(); ?>/accounting/voucher/" data-title="<?php echo $row1['voucher_no']; ?>">
							<div class="item_date col">
								<div style="display:none"><?php echo $key; ?></div>
								<?php if($row1['is_print']=='true'): ?>
									<div class="label label-success" title="Already Printed"><i class="fa fa-print"></i></div>
									<?php endif; ?>
							</div>
							<div class="po col">
								<div>
									<div><?php echo $row1['preparedBy_name']; ?></div>
									<div><a href="javascript:void(0)"><?php echo $row1['voucher_no']; ?> </a></div>
									<?php $label_info = ($row1['payment_type'] == "CASH")? "label-success" : "label-info" ?>									
									<div><span class="label <?php echo $label_info; ?>"><?php echo $row1['payment_type']; ?></span> 
										<?php if(!empty($row1['rr_id'])): ?>
											<span class="label label-success" data-rr_id ="<?php echo $row1['rr_id'] ?>">TAG</span>
										<?php endif; ?>
									</div>
									<?php if(strtoupper($row1['payment_type']) == "CHECK"): ?>									
									<?php endif; ?>
									
								</div>
							</div>
							<div class="po_supplier col">
										<div><?php echo $row1['type'].' '.$row1['short_desc'] ?></div>
										<?php if($row1['transaction_type']!='journal_voucher'): ?>
												<div><strong><?php echo $row1['pay_to'] ?></strong></div>
												<div><?php echo $row1['address'] ?></div>
										<?php endif; ?>
										<div>
											Amount : <?php echo $this->extra->number_format($row1['total_amount']); ?>
										</div>
										<div>
										</div>
							</div>
							<div class="po_supplier col">
								<div><?php echo $row1['project_name']; ?></div>	<br>
								<?php if(strtoupper($row1['payment_type']) == "CHECK"): ?>
									<div>CHECK : <span style="font-weight:bold"><?php echo $row1['bank']." ".$row1['check_no']; ?></span></div>
								<?php endif; ?>

							</div>
							<div class="po_delivery item_content_status col"><div><?php echo $this->extra->label($row1['status']); ?></div></div>							
						</div>
			</div>
				<?php endforeach; ?>
	  		<?php endforeach; ?>	  		
	  		<?php else: ?>
					<div class="head_date">
						<strong>Empty</strong>
					</div>			
	  		<?php endif; ?>
						
			<?php if($next!=""): ?>
				<a class="jscroll-next" href='<?php echo base_url().index_page(); ?>/transaction_list/get_voucher_list?p=<?php echo $next; ?>&filter=<?php echo $filter.$search_url; ?>'>Next</a>
	  		<?php endif; ?>


