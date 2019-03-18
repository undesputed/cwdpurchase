			
			<?php if(count($main_list) > 0): ?>
				  		<?php foreach($main_list as $key=>$row): ?>
				  		<div class="">
							<div class="head_date">
								<strong><?php echo $key; ?></strong>
							</div>
							 <?php foreach($row as $row1): ?>
							 
									<div class="item_contents hover" data-method="purchase_order" data-type="for_po" data-value="<?php  echo $row1['c_number']; ?>" data-url="<?php echo base_url().index_page(); ?>/transaction_list/purchase_order/for_po/<?php  echo $row1['c_number']; ?>">
										<div class="can_date col">
											<div style="display:none"><?php echo $key; ?></div>
											<div>&nbsp;</div>
										</div>
										<div class="canvass_no col">
											<div><?php echo $row1['preparedBy_name']; ?></div>										
											<a href="javascript:void(0)"><?php echo $row1['c_number']; ?></a>
										</div>
										<div class="pr_no col">
											<div><?php echo $row1['purchaseNo']; ?></div>
											<div><?php echo $row1['from_projectCodeName']; ?></div>
										</div>
										<div class="can_supplier col">
											<div>
												<span class="fa fa-truck"> <?php echo $row1['no_supplier']; ?></span>												
											</div>
										</div>	
										<div class="can_item col">
											<div>
												<span class="fa fa-tags"> <?php echo $row1['no_items']; ?></span>												
											</div>
										</div>						
										<div class="can_status col">
											<?php echo $this->extra->for_po_status($row1['status_code']); ?>
										</div >
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
						  <a class="jscroll-next" href='<?php echo base_url().index_page(); ?>/transaction_list/get_po_forpo?p=<?php echo $next; ?>&filter=<?php echo $filter.$search; ?>'>Next</a>
						<?php endif; ?>