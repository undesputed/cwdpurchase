<input type="hidden" value="<?php echo $main_data['receipt_id'] ?>" id="transaction_id">

						<div class="t-content">
														
							<div class="t-header">
								<h4><?php echo $main_data['receipt_no']; ?></h4>
							</div>
							
							<div class="row">
								<div class="col-xs-5">
									<div class="control-group">										
										<a href="<?php echo base_url().index_page();?>/print/returns/<?php echo $main_data['receipt_no']; ?>" target="_blank" class="action-status"><i class="fa fa-print"></i> Print</a>
									</div>
								</div>
								
								<div class="col-md-5">
									
									<?php 										 
										 											
										echo $this->extra->label($main_data['received_status']);																
								  ?>
								  
								</div>
							</div>
													
							<div class="row" style="margin-top:10px">
								<div class="col-xs-6">
									<div class="t-title">
										<div>Division : </div> 
										<strong><?php echo $main_data['project_requestor']; ?></strong>
									</div>
									<div class="t-title">
										<div>Date: </div>
										<strong><p><?php echo $main_data['date_received'] ?></p></strong>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="t-title">
										&nbsp;
									</div>
								</div>
							</div>
							
							<div class="table-responsive" style="overflow:auto">
							<table class="table table-item long_item">
								<thead>
									<tr>										
										<th>Item Description</th>
										<th>Qty</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($details_data as $row): ?>
										<tr>
											<td><?php echo $row['item_name_actual'];?></td>
											<td class="td-number" style="text-align:center;"><?php echo $row['item_quantity_actual'];?></td>										
										</tr>
									<?php endforeach ?>
								</tbody>
								<tfoot>
									<tr>
										<td><?php echo count($details_data); ?> item(s)</td>
										<td class="td-number" style="text-align:right;">&nbsp;</td>
									</tr>
								</tfoot>
							</table>
							</div>							
					
						</div>




			