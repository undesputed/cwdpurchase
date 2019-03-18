


<?php if(count($pr_list)>0): ?>
	  		<?php foreach($pr_list as $key=>$row): ?>
	  			<div class="">
					 <div class="head_date">
						<strong><?php echo $key; ?></strong>
					</div> 
				 <?php foreach($row as $row1): ?>

							<div class="item_contents hover "  data-method="purchase_request" data-type="outgoing" data-value="<?php echo $row1['purchaseNo']; ?>" data-url="<?php echo base_url().index_page(); ?>/transaction_list/purchase_request/outgoing/<?php echo $row1['purchaseNo']; ?>">
								<div class="item_date col">
									<div style="display: none!important;"><?php echo $key; ?></div>
									<div><?php echo $this->lib_transaction->priority($row1['legend_']); ?></div>
								</div>
								<div class="item_request col">
									<div>
										<div><?php echo $row1['preparedbyName']; ?></div>
										<a href="javascript:void(0)"><?php echo $row1['purchaseNo']; ?></a>
									</div>
								</div>
								<div class="item_from col"><?php echo $row1['to_projectCodeName']; ?></div>
								<div class="item_itm col">
									<div>
										<span class="fa fa-tag"> <?php echo $row1['item_cnt'] ?></span>
									</div>
								</div>
								<div class="item_remarks col"><?php echo $row1['pr_remarks']; ?></div>
								<div class="item_status item_content_status col">
									<div>
										<?php echo $this->extra->label($row1['status']); ?> 
									</div>

									<?php $this->lib_transaction->hasCanvass_pr($row1['pr_id']); ?>
									<?php $this->lib_transaction->hasCanvass_po($row1['pr_id']); ?>
									<?php $this->lib_transaction->hasCanvass_rr($row1['pr_id']); ?>
									
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
		<a class="jscroll-next" href='<?php echo base_url().index_page(); ?>/transaction_list/get_pr_outgoing?p=<?php echo $next; ?>&filter=<?php echo $filter.$search_url;; ?>'>Next</a>
	  <?php endif; ?>



