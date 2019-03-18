
<?php $_active = $this->uri->segment(3); ?>
		<?php if(count($list)>0): ?>
	  		<?php foreach($list as $key=>$row): ?>
	  			
					 <div class="head_date">
						<strong><?php echo $key; ?></strong>
					</div> 
				 <?php foreach($row as $row1): ?>
				 <?php 	
					$active = ($row1['job_order_no'] == $_active ) ? 'act': '';
				 ?>
				
							<div class="item_contents pop_up_content hover <?php echo $active; ?>" data-method="job_order" data-type="job_order" data-value="<?php echo $row1['job_order_no']; ?>" data-url="<?php echo base_url().index_page(); ?>/transaction_list/job_order/<?php echo $row1['job_order_no']; ?>">
								<div class="item_date col">
									<div style="display: none!important;"><?php echo $key; ?></div>
									<div>&nbsp;</div>
								</div>
								<div class="item_request col">
									<div>
										<div><?php echo $row1['preparedbyName']; ?></div>
										<a href="javascript:void(0)"><?php echo $row1['job_order_no']; ?></a>
									</div>
								</div>
								<div class="item_from col"><?php echo $row1['project_name']; ?></div>
								<div class="item_itm col">
									<div>
										<span class="fa fa-tag"> <?php echo $row1['item_cnt'] ?></span>
									</div>
								</div>
								<div class="item_remarks col"><?php echo $row1['remarks']; ?></div>
								<div class="item_status item_content_status col">
									<div>
										<?php echo $this->extra->label($row1['status']); ?> 
									</div>
									
								</div>
							</div>					
				<?php endforeach; ?>	
	  		<?php endforeach; ?>
	  	<?php else: ?>
			<div class="head_date">
				<strong>Empty</strong>
			</div>			
	  	<?php endif; ?>
	  
	  	<?php if($next!="" || $next > 1): ?>
			<a class="jscroll-next" href='<?php echo base_url().index_page(); ?>/transaction_list/get_job_order?p=<?php echo $next; ?>&filter=<?php echo $filter.$search_url; ?>'>Next</a>
	  	<?php endif; ?>

