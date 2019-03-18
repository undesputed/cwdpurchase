						
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

						
						/*
						$data['merge'] = array();
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

						}
						*/
						
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
						<input type="hidden" id="can_id" value="<?php echo $main_data['can_id']; ?>">	
											
						<div class="t-content">
							<div class="t-header">
								<a href="<?php echo base_url().index_page(); ?>/transaction_list/purchase_order/for_po" class="close close-info" ><span aria-hidden="true">&times;</span><span></a>
								<h4><?php echo $main_data['c_number'].'-'.$pr_main['project_no']; ?></h4>
							</div>

							<div class="row">
								<div class="col-xs-6">
								<?php echo $this->extra->for_po_status($main_data['status_code']); ?>								
								</div>
								<div class="col-xs-6">
									<?php if($main_data['approval']=='TRUE'): ?>
										<?php if($this->lib_auth->restriction('PO USER')): ?>
											<div class="control-group">
												<span>
													<a href="<?php echo base_url().index_page() ?>/transaction/create_po/<?php echo $main_data['c_number']; ?>" class="action-status">Create P.O</a>	
												</span>
											</div>
										<?php endif; ?>
									<?php endif; ?>																									
								</div>
							</div>							
													
							<div class="row" style="margin-top:10px">
								<div class="col-xs-6">
									<div class="t-title">
										<div>Reference No : </div>
										<strong><?php echo $main_data['purchaseNo']; ?></strong>
									</div>
									<div class="t-title">
										<div>Created From : </div> 
										<strong><?php echo $pr_main['from_projectMainName']; ?></strong>
										<strong><?php echo $pr_main['from_projectCodeName']; ?></strong>
									</div>								
								</div>
								<div class="col-xs-6">
									<div class="t-title">
										<div>Canvass Date : </div> 
										<strong><?php echo $this->extra->format_date($main_data['c_date']); ?></strong>										
									</div>
								</div>
							</div>
							
							<div class="table-responsive" style="overflow:auto">
							<table id="tbl-canvass-info" class="table table-item long_item">
								<thead>
									<tr>
										<th colspan="3"></th>
										<!---->
										<?php $cnt = 0; foreach($data['supplier'] as $row): $cnt++; ?>										
										<th class="td-number td-head-border" colspan="4"><span data-toggle="tooltip" data-placement="top" title="<?php echo $row[0]['c_terms'] ?>"> 
										<?php if($main_data['approval']!='TRUE' && $canvass_useronly == true): ?>
											<input type="checkbox" data-supplier_id ="<?php echo $row[0]['supplier_id']; ?>" class="check-all" name="supplier_name">
										<?php endif; ?>
										<?php if(!empty($row[0]['po_id'])): ?>
											<label for="" class="label label-success">Already PO</label>
										<?php else: ?>
											<label for="" class="label label-warning">For PO</label>
										<?php endif ?>
										<?php echo $row[0]['Supplier']; ?></span></th>
										<?php endforeach; ?>										
									</tr>
									<tr>
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
													$approved = ($c_row['approvedSupplier']=='TRUE')? 'approved-item' : '';
													$c_row = $items[$row['itemNo']][$row2];

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
										<td class="td-number td-head-border" colspan="4">Total : <span><?php echo $this->extra->number_format($discounted_totalamount[$cnt]); ?></span></td>
										<!-- <td class="td-number td-head-border">Total : <span><?php echo $this->extra->number_format($total_amount[$cnt]); ?></span></td> -->
										<?php $cnt++; ?>
										<?php endforeach; ?>
									</tr>
								</tfoot>
							</table>
							</div>

							<div class="row">
								<div class="col-xs-6">
									<div class="t-title">
										<div>Prepared By : </div> 
										<strong><?php echo $main_data['preparedBy_name']; ?></strong>										
									</div>									
								</div>
								<div class="col-xs-6">
									<div class="t-title">
										<div>Approved By : </div> 
										<strong><?php echo $main_data['approvedBy_name']; ?></strong>										
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-6"></div>
								<div class="col-xs-6">
									<?php if($canvass_useronly == true): ?>
									<div class="t-title">										
										<div>Final Approval :</div>
										<?php if($main_data['approval'] =='TRUE'): ?>
											<strong><?php echo $main_data['final_approval_name']; ?></strong>
										<?php else: ?>
											<select name="" id="final_approval" class="form-control">
												<?php foreach($all_employee as $row): ?>
													<option name="" value="<?php echo $row['pp_person_code']; ?>"><?php echo $row['person_name']; ?></option>
												<?php endforeach; ?>
											</select>
										<?php endif ?>
									</div>
									<?php endif; ?>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-xs-12">
									<?php if($main_data['approval']!='TRUE' && $canvass_useronly == true): ?>
										<button class="btn pull-right btn-primary" id="final-save">Save</button>
									<?php endif ?>
								</div>
							</div>
						</div>

<script>

	$(function(){
		var canvass_info = {
			init:function(){
				this.bindEvent();
				$(function () {
				  $('[data-toggle="tooltip"]').tooltip()
				})
			},
			bindEvent:function(){

				$('body').on('click','.cv-items',this.toggle_items);
				$('#final-save').on('click',this.final_save);

				$('.check-all').on('click',this.check_all);

			},check_all:function(){
				var me = $(this);
				var data = {
					supplier_id : '',
					can_id : '',
					status : '',
				};
				console.log(me.is(':checked'));
				if(me.is(':checked')){

					$('.check-all').prop('checked',false);
					me.prop('checked',true);
					data.supplier_id = $(this).attr('data-supplier_id');
					data.can_id      = $('#can_id').val();
					data.status = 'TRUE';
				}else{
					data.supplier_id = $(this).attr('data-supplier_id');
					data.can_id      = $('#can_id').val();
					data.status = 'FALSE';
				}

				$.post('<?php echo base_url().index_page();?>/transaction/ap_canvass_supplier',data,function(response){

					$.each(response,function(i,val){
						$('.unit-item').each(function(i,value){

							if($(value).attr('data-itemno') == val.itemNo && $(value).attr('data-supplier_id') == val.supplier_id){

								if(val.approvedSupplier =='TRUE'){
									$(value).closest('tr').find('.approved-item').removeClass('approved-item');
									$(value).addClass('approved-item');
								}else{
									$(value).removeClass('approved-item');
								}

								/*
								if($(value).hasClass('approved-item')){
									$(value).removeClass('approved-item');
								}else{
									$(value).closest('tr').find('.approved-item').removeClass('approved-item');
									$(value).addClass('approved-item');
								}*/

							}

						});
					});

				},'json');

			},
			toggle_items:function(){
				var me = $(this);
				var status = '';
				if(me.hasClass('approved-item')){
					status = 'FALSE';
				}else{
					status = 'TRUE';
				}

				$post = {
					status   	: status,					
					can_id  	: $('#can_id').val(),
					item_no  	: me.attr('data-itemno'),
					supplier_id	: me.attr('data-supplier_id'),
				}
									
				$.post('<?php echo base_url().index_page();?>/transaction/ap_canvass_item',$post,function(response){

					if(me.closest('tr').find('.approved-item').length > 0){					
						if(me.hasClass('approved-item')){
							me.removeClass('approved-item');
						}else{
							me.closest('tr').find('.approved-item').removeClass('approved-item');
							me.addClass('approved-item');
						}

					}else{

						if(me.hasClass('approved-item')){
							me.removeClass('approved-item');
						}else{
							me.addClass('approved-item');
						}
					}

				});

			},final_save:function(){
				var bool =  confirm('Are you sure?');
				
				if(!bool){
					return false;
				}

				$post = {
					can_id   : $('#can_id').val(),
					approval : $('#final_approval option:selected').val(),
				};
				
				if(canvass_info.checker()){
					alert('Please check an Item to be Approved');
					return false;
				}

				$.post('<?php echo base_url().index_page();?>/transaction/ap_canvass_main',$post,function(response){
					switch($.trim(response)){
						case"1":
							alert('Successfully Save');
							location.reload(true);
						break;
					}
				});

			},checker:function(){	
				var checker = 0;
				$('#tbl-canvass-info tbody tr').each(function(i,val){					
					if($(val).find('.approved-item').length > 0){						
						checker++;				
					}
				});				
				return (checker == 0);
			}
		}
		canvass_info.init();		
	});	

</script>