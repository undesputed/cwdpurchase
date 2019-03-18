
				<?php if(count($main_list) > 0): ?>
				  		<?php foreach($main_list as $key=>$row): ?>
							<div class="head_date">
								<strong><?php echo $key; ?></strong>
							</div>
							 <?php foreach($row as $row1): ?>
							
									<div class="item_contents hover " data-method="item_withdrawal" data-type="view" data-value="<?php echo $row1['WS NUMBER']; ?>" data-url="<?php echo base_url().index_page(); ?>/transaction_list/item_withdrawal/<?php echo $row1['WS NUMBER']; ?>">
										<div class="item_date col">
											<div style="display:none"><?php echo $key; ?></div>
											<div>&nbsp;</div>
										</div>
										<div class="ws_no">
											<div><?php echo $row1['requested_By']; ?></div>										
											<a href="javascript:void(0)"><?php echo $row1['WS NUMBER']; ?></a>
										</div>																				
										<div class="ws_itm">
											<div>
												<span class="fa fa-tags"><?php echo $row1['no_item_withdrawn'] ?></span>												
											</div>
										</div>
										<div class="ws_remarks">	
											<p><?php echo $row1['remarks']; ?></p>												
										</div>	
										<div class="ws_status">
											<?php echo $this->extra->label($row1['WS STATUS']); ?>
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
							<a class="jscroll-next" href='<?php echo base_url().index_page(); ?>/transaction_list/get_itemwithdrawal_list?p=<?php echo $next; ?>'>Next</a>
						<?php endif; ?>