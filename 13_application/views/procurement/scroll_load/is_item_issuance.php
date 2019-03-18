				

				<?php if(count($main_list) > 0): ?>				
				  		<?php foreach($main_list as $key=>$row): ?>
							<div class="head_date">
								<strong><?php echo $key; ?></strong>
							</div>
							 <?php foreach($row as $row1): ?>
			
									<div class="item_contents hover" data-method="issuance" data-type="view" data-value="<?php echo $row1['issuance_no']; ?>" data-url="<?php echo base_url().index_page(); ?>/transaction_list/item_issuance/<?php echo $row1['issuance_no']; ?>">
										<div class="item_date col">
											<div style="display:none"><?php echo $key; ?></div>
											<div>&nbsp;</div>
										</div>
										
										<div class="si_no">
											<div><?php echo $row1['preparedBy_name']; ?></div>										
											<a href="javascript:void(0)"><?php echo $row1['issuance_no']; ?></a>
										</div>
										<div class="si_no">
											<div>
												<span class="fa fa-tags"><?php echo $row1['no_items'] ?></span>												
											</div>
										</div>
										<div class="si_itm">
											<span><?php echo $row1['issued_to']; ?></span>
										</div>
										<div class="si_remarks">	
											<p><?php echo $row1['remarks']; ?></p>												
										</div>	
										<div>
											<?php echo $this->extra->label($row1['status']); ?>
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
							<a class="jscroll-next" href='<?php echo base_url().index_page(); ?>/transaction_list/get_item_issuance_list?p=<?php echo $next; ?>'>Next</a>
						<?php endif; ?>