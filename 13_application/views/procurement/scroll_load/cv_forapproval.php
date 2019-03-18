

		
	  	<?php if(count($main_list) > 0): ?>
	  		<?php foreach($main_list as $key=>$row): ?>
				
				<div class="head_date">
					<strong><?php echo $key; ?></strong>
				</div>
				 <?php foreach($row as $row1): ?>
	
						<div class="item_contents hover pop_up_content" data-method="canvass_sheet" data-type="for_approval" data-value="<?php  echo $row1['c_number']; ?>" data-url="<?php echo base_url().index_page(); ?>/transaction_list/canvass_sheet/for_approval/<?php  echo $row1['c_number']; ?>">
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
							<div class="can_status item_content_status col">
								<?php if($row1['status'] !="CANCELLED"): ?>
								<?php echo $this->extra->label3($row1['approval']); ?>
								<?php else: ?>	
									<span class="label label-danger">CANCELLED</span>										
								<?php endif; ?>
							</div>
						</div>

				<?php endforeach; ?>
	  		<?php endforeach; ?>
	  		<?php else: ?>
				  <tr>
				  	<td colspan="6">Empty</td>
				  </tr>
	  		<?php endif; ?>
	

	<?php if($next!="" || $next > 1): ?>
	  <a class="jscroll-next" href='<?php echo base_url().index_page(); ?>/transaction_list/get_canvass_forapproval?p=<?php echo $next; ?>&filter=<?php echo $filter.$search_url; ?>'>Next</a>
	<?php endif; ?>
