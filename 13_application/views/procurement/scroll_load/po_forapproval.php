			

			<?php if(count($po_list) > 0): ?>
		  		<?php foreach($po_list as $key=>$row): ?>
		  		
					<div class="head_date">
						<strong><?php echo $key; ?></strong>
					</div>
					 <?php foreach($row as $row1): ?>
					 
							<div class="item_contents hover pop_up_content" data-method="purchase_order" data-type="for_approval" data-value="<?php echo $row1['po_number']; ?>" data-url="<?php echo base_url().index_page(); ?>/transaction_list/purchase_order/for_approval/<?php echo $row1['po_number']; ?>" >
								
								<div class="item_date col">
									<div style="display:none"><?php echo $key; ?></div>
									<div>&nbsp;</div>
								</div>
								<div class="po col">
									<div>
										<div><?php echo $row1['preparedBy_name']; ?></div>
										<div><a href="javascript:void(0)"><?php echo $row1['po_number']; ?> </a></div>
										<div><?php echo $row1['purchaseNo']; ?></div>
										<div>Total : <?php echo $this->extra->number_format($row1['total_cost']); ?></div>
									</div>
								</div>
								<div class="po_supplier col">							
									<?php echo $row1['Supplier']; ?>
									<div>
										<?php echo $row1['po_remarks']; ?>
									</div>
									<div>
										<small><?php echo $row1['from_projectCodeName']; ?></small>
									</div>
									<div>
										<?php if($row1['is_print']=='true'): ?>
										<span class="inline text-success">
											<i class="fa fa-check"></i> Printed
										</span>
										<?php endif; ?>
										<?php if($row1['is_email']=='true'): ?>
										<span class="inline text-success">
											<i class="fa fa-check"></i> Emailed
										</span>
										<?php endif; ?>
									</div>
								</div>
								<div class="po_itm col"><span class="fa fa-tags"> <?php echo $row1['total_item']; ?></span></div>
								<div class="po_delivery col"><?php echo $row1['dtDelivery']; ?></div>
								<div class="po_status item_content_status col">
									<?php echo $this->extra->label6($row1['p_status']); ?>
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
			  <a class="jscroll-next" href='<?php echo base_url().index_page(); ?>/transaction_list/get_po_forapproval?p=<?php echo $next; ?>&filter=<?php echo $filter.$search_url; ?>'>Next</a>
			<?php endif; ?>