						
						<?php 

						$data['main'] = array();
						$data['supplier'] = array();
						foreach($details_data as $row){
							$data['main'][] = $row;
							foreach($canvass_details as $c_row){
								if($c_row['itemNo'] == $row['itemNo']){
									$data['supplier'][$c_row['supplier_id'].'-'.$c_row['supplierType']][] = $c_row;
								}
							}
						}

										
						echo "<pre>";
						print_r($data['supplier']);
						echo "</pre>";

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
								<a href="<?php echo base_url().index_page(); ?>/transaction_list/canvass_sheet" class="close" ><span aria-hidden="true">&times;</span><span></a>
								<h4><?php echo $main_data['c_number']; ?></h4>
							</div>
							
							<div class="row">
								<div class="col-xs-6">
									<?php if($main_data['status']!='CANCELLED'): ?>									
										<?php if($main_data['approval']=='TRUE'): ?>
											<span class="label label-success">Approved</span>
										<?php else: ?>
											<div class="control-group">
												<span class="control-item-group">
													<a href="javascript:void(0)" class="action-status cancel-event">Cancel</a>
												</span>
												<!-- <span class="control-item-group">
													<a href="javascript:void(0)" class="action-status edit-event">Edit</a>
												</span> -->
											</div>
										<?php endif; ?>
									<?php else: ?>
											<span class="label label-danger">CANCELLED</span>
									<?php endif;?>
									
								</div>
								<div class="col-xs-6">									
									<div class="control-group">
										<a href="<?php echo base_url().index_page();?>/print/canvass/<?php echo $main_data['c_number']; ?>" target="_blank" class="action-status"><i class="fa fa-print"></i> Print</a>
									</div>
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
							<table id="tbl-canvass-info" class="table table-item">
								<thead>
									<tr>
										<th colspan="3"></th>
										<!---->
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
									foreach ($data['main'] as $row): $cnt = 0; $cnt1 = 0;?>
										<tr>
											<td style="white-space:nowrap;"><?php echo $row['itemDesc'];?></td>											
											<td><?php echo $row['unitmeasure'];?></td>
											<td class="td-qty td-number"><?php echo $row['qty'];?></td>										
											<?php foreach($canvass_details as $c_row): 
												 if($c_row['itemNo'] == $row['itemNo']):
												 if($c_row['supplier_cost'] !='0'):
												 	$cnt1++;
												 	$approved = ($c_row['approvedSupplier']=='TRUE')? 'approved-item' : '';
											?>											
											<td data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="unit-item td-number <?php echo $clickable ?> cv-data-items <?php echo $approved; ?>"><?php echo $c_row['supplier_cost']; ?></td>
												
												<?php
													$total = $row['qty'] * $c_row['supplier_cost'];
													$total_amount[$cnt]  = (empty($total_amount[$cnt])) ? 0 : $total_amount[$cnt];
													$total_amount[$cnt] += $total;
													if(empty($discounted_totalamount[$cnt])){
														$discounted_totalamount[$cnt] = str_replace(',','',$c_row['discounted_total']);
													}else{
														$discounted_totalamount[$cnt] += str_replace(',','',$c_row['discounted_total']);
													}
													$cnt++;
												?>
												
											<td data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="cv-data-items td-qty td-number <?php echo $clickable;?> <?php echo $approved; ?>"><?php echo $c_row['percentage']; ?>% Discount <?php echo $c_row['discounted']; ?></td>
											<td data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="cv-data-items td-qty td-number <?php echo $clickable;?> <?php echo $approved; ?>"><?php echo $c_row['discounted_price']; ?></td>
											<td data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="cv-data-items td-qty td-number <?php echo $clickable;?> <?php echo $approved; ?>"><?php echo $c_row['discounted_total']; ?></td>
											<td style="display:none" data-counter="<?php echo $cnt1; ?>" data-itemno="<?php echo $c_row['itemNo']; ?>"  data-supplier_id ="<?php echo $c_row['supplier_id']; ?>" class="cv-data-items td-number <?php echo $clickable;?> <?php echo $approved; ?>"><?php echo $this->extra->number_format($total); ?></td>

											<?php $cnt1++; ?>
											<?php else: ?>
												<td class="cv-data-items"></td>
												<td class="cv-data-items"></td>
												<td class="cv-data-items"></td>
												<td class="cv-data-items"></td>
											<?php endif; ?>
											<?php endif; ?>											
											<?php endforeach; ?>
										</tr>
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
													<option name="" value="<?php echo $row['emp_number']; ?>"><?php echo $row['Person Name']; ?></option>
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

						<?php 
							$a = array(
								 'transaction_id'=>$main_data['can_id'],
								 'transaction_no'=>$main_data['c_number'],
								 'type'=>'Canvass',
								);
							echo $this->lib_transaction->comments($a);
						?>

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

				$('body').on('click','.cancel-event',this.cancel_event);
				$('body').on('click','.edit-event',this.edit_event);
				
			},cancel_event:function(){

				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}

				$post = {
					type     : "CANVASS_CANCEL",
					id       : $('#can_id').val(),
					can_no   : "<?php echo $main_data['c_number']; ?>",
				};

				$.post('<?php echo base_url().index_page();?>/transaction/change_status',$post,function(response){
					alert('Successfully Cancelled!');
					location.reload(false);
				});

			},edit_event:function(){				
				$.fancybox.showLoading();
				$post = {
					id       : $('#can_id').val(),
					can_no   : "<?php echo $main_data['c_number']; ?>",
				}

				$.post('<?php echo base_url().index_page();?>/transaction/canvass_edit',$post,function(response){
					$.fancybox(response,{
						width     : 1000,
						height    : 550,
						fitToView : false,
						autoSize  : false,
					})
				});

			},check_all:function(){				
				var me = $(this);
				
				var data = {
					supplier_id : '',
					can_id : '',
					status : '',
				};
				
				if(me.is(':checked')){
					$('.check-all').prop('checked',false);
					me.prop('checked',true);
					data.supplier_id = $(this).attr('data-supplier_id');
					data.can_id      = $('#can_id').val();
					data.status      = 'TRUE';
				}else{
					data.supplier_id = $(this).attr('data-supplier_id');
					data.can_id      = $('#can_id').val();
					data.status      = 'FALSE';
				}

				$.post('<?php echo base_url().index_page();?>/transaction/ap_canvass_supplier',data,function(response){

					$.each(response,function(i,val){
						$('.unit-item').each(function(i,value){

							if($(value).attr('data-itemno') == val.itemNo && $(value).attr('data-supplier_id') == val.supplier_id){

								if(val.approvedSupplier =='TRUE'){
									
									$(value).closest('tr').find('.approved-item').removeClass('approved-item');
									
									var counter  = $(value).attr('data-counter');
									$(value).closest('tr').find("td[data-counter='"+counter+"']").addClass('approved-item');
								}else{
									var counter  = $(value).attr('data-counter');
									$(value).closest('tr').find("td[data-counter='"+counter+"']").removeClass('approved-item');									
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
							var counter  = me.attr('data-counter');
							me.closest('tr').find("td[data-counter='"+counter+"']").removeClass('approved-item');							
						}else{
							me.closest('tr').find('.approved-item').removeClass('approved-item');
							var counter  = me.attr('data-counter');
							me.closest('tr').find("td[data-counter='"+counter+"']").addClass('approved-item');
							/*me.addClass('approved-item');*/
						}

					}else{

						if(me.hasClass('approved-item')){
							var counter  = me.attr('data-counter');
							me.closest('tr').find("td[data-counter='"+counter+"']").removeClass('approved-item');
						}else{
							var counter  = me.attr('data-counter');
							me.closest('tr').find("td[data-counter='"+counter+"']").addClass('approved-item');
							/*me.addClass('approved-item');*/
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
					can_no   : "<?php echo $main_data['c_number']; ?>",
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