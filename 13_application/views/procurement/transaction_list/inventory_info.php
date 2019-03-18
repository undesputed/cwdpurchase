<?php 
	$project_site = $this->uri->segment(3);
 ?>
						<div class="t-content">
							<div class="t-header">
								<a href="<?php echo base_url().index_page(); ?>/transaction_list/inventory/<?php echo $project_site ?>" class="close" ><span aria-hidden="true">&times;</span><span></a>
								<h4>Transaction History</h4>
							</div>

							<div class="row">
								<div class="col-xs-9">
									<div class="control-group">
										<a href="<?php echo base_url().index_page();?>/print/inventory_stock_card/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4);?>" target="_blank" class="action-status"><i class="fa fa-print"></i> Print</a>
									</div>
								</div>
								<div class="col-xs-3">
									<div class="control-group">
										
									</div>
								</div>
							</div>
							
							<table id="tbl-transaction-history" class="table table-striped">
								<thead>
									<tr>
										<th>Reference No</th>
										<th>Reference Date</th>										
										<th>Receipt Qty</th>
										<th>Issuance Qty</th>
										<th>Balance</th>										
									</tr>
								</thead>								
								<tbody>
									<?php 
										$balance = 0;
										foreach($main_data as $row){ 
											if($row['type'] == 'RECEIVING'){
	                                          	$balance = $balance + $row['debit'];
	                                        }elseif($row['type'] == 'WITHDRAW'){
	                                          	$balance = $balance - $row['credit'];
	                                        }elseif($row['type'] == 'TRANSFER'){
	                                          	$balance = $balance - $row['credit'];
	                                        }elseif($row['type'] == 'WITHDRAW'){
	                                          	$balance = $balance - $row['credit'];
	                                        }elseif($row['type'] == 'RETURN'){
	                                        	$balance = $balance + $row['debit'];
	                                        }elseif($row['type'] == 'BEGINNING'){
	                                        	$balance = $balance + $row['debit'];
	                                        }
									?>
										<tr>
											<td><?php echo $row['reference_no'];?></td>
											<td><?php echo date('m/d/Y',strtotime($row['trans_date'])); ?></td>
											<td><?php echo $row['debit']; ?></td>
											<td><?php echo $row['credit']; ?></td>
											<td><?php echo $balance; ?></td>
										</tr>
									<?php 
										} 
									?>
								</tbody>
							</table>
						</div>

<script>
	$(function(){
		history_app = {
			init:function(){
				/*$('#tbl-transaction-history').dataTable(datatable_option);*/
			}
		};

		history_app.init();	
		
	});
</script>