<?php 

/*	
	echo "<pre>";
	print_r($supplier);
	echo "</pre>";
*/
/*
	echo "<pre>";
	print_r($details_data);
	echo "</pre>";
*/
/*
echo "<pre>";
print_r($rr_main);
echo "</pre>";
*/
?>

<style>	
	.bold{
		font-weight: bold;
	}

	.red{
		color:#f00;
	}

	.td-border{
		border-left:1px solid #000;
		border-right:1px solid #000;
	}	
</style>


<div style="padding-left:5px;margin-top:5px;margin-bottom:5px">
	
	<?php if($main_data['status'] == 'PARTIAL'): ?>

		<span class="label label-info">Partial</span>

		<span class="control-item-group">
			<a data-method="receiving" data-type="additional" data-value="<?php echo $main_data['po_number'] ?>" href="<?php echo base_url().index_page(); ?>/transaction_list/receiving_report/<?php echo $main_data['po_number'] ?>/additional" class="action-status history-link"> <i class="fa fa-plus"></i> Additional Delivery</a>
		</span>		
		<span class="control-item-group">
			<a href="javascript:void(0)" class="action-status cancel-action">Close Transaction</a>
		</span>	
		<?php elseif($main_data['status'] == 'FULL'): ?>
		<span class="control-item-group">
			<span class="action-status">Complete</span>											
		</span>
		<?php elseif($main_data['status'] == 'CANCELLED'): ?>		
	<?php endif; ?>	
	
</div>

<?php $cnt = 0; ?>
<?php $c   = 0; ?>
<?php $total_cost = array(); ?>
<?php $total_cost_po = 0; ?>
<div class="table-reponsive">
<table class="table table-condensed" style="width:100%">
	<thead>
		<tr>
			<th>
				<span style="font-size:14px"><?php echo $main_data['po_number'];  ?></span><br>
				<small><?php if($mode == 'DIRECT RECEIVING' || $mode == 'RETURN'){ echo $rr_main['Supplier'];}else{ echo $supplier['business_name']; } ?></small><br>
				Item Description
				
			</th>
			<th>Unit Cost</th>
			<th>PO Qty</th>			
			<?php
				if($mode == 'DIRECT RECEIVING' || $mode == 'RETURN'){
			?>
			<td>
				<?php echo date('M d, Y',strtotime($rr_main['date_received'])); ?><br>
				<a href="javascript:void(0)" class="rr_info" data-id="<?php echo $rr_main['receipt_id'] ?>"  data-po="<?php echo $rr_main['po_number']; ?>"><?php echo $rr_main['receipt_no']; ?><a>
			</td>
			<?php
				}else{
					foreach($rr_main  as $rr_main_row): 
				
				
			?>
						<td>
							<?php echo date('M d, Y',strtotime($rr_main_row['date_received'])); ?><br>
							<a href="javascript:void(0)" class="rr_info" data-id="<?php echo $rr_main_row['receipt_id'] ?>"  data-po="<?php echo $rr_main_row['po_number']; ?>"><?php echo $rr_main_row['receipt_no']; ?><a>
						</td>
						<?php $cnt ++; ?>
			<?php 
					endforeach; 
				}
			?>	
			<th>Total Qty</th>		
		</tr>
	</thead>
	<tbody>
		<?php foreach($details_data as $row): ?>
			<tr>
				<td><?php echo $row['item_name'];  ?></td>
				<td><?php echo $row['unit_cost'];  ?></td>
				<td><?php echo $row['quantity']; ?></td>
				<?php $total_cost_po += ($row['quantity'] * $row['unit_cost']); ?>
				<?php $total_qty = 0; ?>
				<?php
					if($mode != 'DIRECT RECEIVING' || $mode != 'RETURN'){ 
					foreach($rr_details as $k=>$rr_row): $bool = false;  ?>
					<?php foreach($rr_row as $rr1_row): ?>
						<?php
								if($rr1_row['item_id'] == $row['itemNo']): $bool = true; $total_qty +=$rr1_row['item_quantity_actual'];
						?>
								<td class="td-border">
									<?php 										
										echo ($rr1_row['item_quantity_actual'] == 0)? "" : $rr1_row['item_quantity_actual'];
										if(!isset($total_cost[$k])){
											$total_cost[$k] = 0;
										}										
										$total_cost[$k] += ($rr1_row['item_quantity_actual'] * $rr1_row['item_cost_actual']);										
									?>
								</td>
						<?php 
								endif;
						?>
					<?php endforeach; ?>
					<?php if($bool==false): ?>
							<td class="td-border"></td>
					<?php endif; ?>
				<?php 
					endforeach; 
					}else{
						$total_qty += $row['quantity'];
				?>
				<td class="td-border"><?php echo $row['item_quantity_actual']; ?></td>
				<?php
					}
				?>

				<td>
					<?php
						$bold = "";
						if($total_qty == $row['quantity']){
							$bold = "bold";
						}else if($total_qty > $row['quantity']){
							 $bold = "bold red";
						}
					 ?>
					<span class="<?php echo $bold; ?>">
						<?php echo $total_qty; ?>
					</span>
				</td>				
			</tr>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3"> <?php echo count($details_data) ?> Item(s) 
			<span class="pull-right">Total Cost :  PHP <?php echo number_format($total_cost_po,2) ?></span>
		</td>				
			<?php while($cnt > $c):?>
				<td>PHP <?php echo number_format($total_cost[$c],2); ?></td>
			<?php  $c++;
			endwhile; ?>
			<td></td>
		</tr>
	</tfoot>
</table>
</div>



<script>
	
	$(function(){
		var rr_app = {
			init:function(){
				this.bindEvent();
			},bindEvent:function(){
				$('.cancel-action').on('click',function(){
					

				    $.confirm({
					    title: 'Confirm!',
					    content: 'Are you sure to Close this Transaction?',
					    confirm: function(){
					    	$post = {
								type  : 'PO_CANCEL',
								po_id : '<?php echo $main_data['po_id']; ?>',
								pr_id : '<?php echo $main_data['pr_id']; ?>',
							};
					  		$.post('<?php echo base_url().index_page();?>/transaction/change_status',$post,function(response){
								
							});
					    },
					    cancel: function(){
					   		
					  	}
				    });

					

				});
			}
		}
		rr_app.init();
	});


</script>

<script>
		var xhr = "";
		var event_app = {
			init:function(){				
				$('.date').date();
				this.bindEvent();
			},
			bindEvent:function(){				
				$('.pending-event').on('click',this.pending_event);				
				$('.approved-event').on('click',this.approved_event);
				$('.reject-event').on('click',this.reject_event);				
				$('.cancel-event').on('click',this.cancel_event);
				$('.received-event').on('click',this.received_event);
				$('body').on('click','.rr_info',this.rr_info);				
				$('#save').on('click',this.save);

			},received_event:function(){
				$post = {
					id     : $('#transaction_id').val(),					
					status : 'PENDING',
					type   : 'PR',
				};
				event_app.execute_query($post);

			},pending_event:function(){

				$post = {
					id     : $('#transaction_id').val(),					
					status : 'PENDING',
					type   : 'PO',
				};
				event_app.execute_query($post);

			},approved_event:function(){

				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}

				$post = {
					id     : $('#transaction_id').val(),					
					status : 'APPROVED',
					type   : 'PO',
				};
				event_app.execute_query($post);

			},reject_event:function(){
				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}
				$post = {
					id     : $('#transaction_id').val(),					
					status : 'REJECTED',
					type   : 'PO',
				};
				event_app.execute_query($post);

			},cancel_event:function(){
				var bool = confirm('Are you sure to Cancel?');
				if(!bool){
					return false;
				}

				$post = {
					id     : $(this).data('value'),
					po_id  : $('#transaction_id').val(),
					status : 'CANCELLED',
					type   : 'RR',
				};
			
				 if(xhr && xhr.readystate != 4){
		            xhr.abort();
		        }
				xhr = $.post('<?php echo base_url().index_page();?>/transaction/change_status',$post,function(response){
					if($.trim(response.msg) == 1){
						alert('Successfully Cancelled');
						updateContent();
						updateStatus(response.status,'label2');
					}					
				},'json');

			},execute_query:function($post){
		        if(xhr && xhr.readystate != 4){
		            xhr.abort();
		        }
				xhr = $.post('<?php echo base_url().index_page();?>/transaction/change_status',$post,function(response){
					if($.trim(response) == 1){
						alert('Successfully Updated');
					}
					location.reload('true');
				});

			},save:function(){
				if($('.required').required()){
					alert('Please fill up the Required Fields');
					return false;
				}	
				    var bool = alert('Do you want to Proceed?');

					$post = {
						receipt_no      :   $('#rr_no').val(),
						supplier_id     :   $('#supplier_id').val(),
						employee_receiver_id:   $('#received_by option:selected').val(),
						employee_checker_id :   $('#checked_by option:selected').val(),
						delivered_by    :   $('#delivered_by').val(),
						date_received   :   $('#rr_date').val(),
						project_id      :   '<?php echo $this->session->userdata("Proj_Code"); ?>',
						supplier_invoice:   $('#invoice_no').val(),
						title_id        :   '<?php echo $this->session->userdata("Proj_Main"); ?>',
						posted_by       :   $('#noted_by').val(),
						invoice_date    :   $('#invoice_date').val(),
						status          :   $('#status').val(),
						details         :   event_app.get_details(),
					};
					
					$.post('<?php echo base_url().index_page();?>/procurement/received_purchase/save_receiving_2',$post,function(response){
						 switch($.trim(response)){
						 	case "1":
						 	 alert('Successfully Save');
						 	 update_content();
						 	 updateStatus($post.status,'label2');
						 	break;
						 	default:
						 		alert('Internal Server Error');
						 	break;
						 }
					});
						
			},get_details:function(){
				var item_list = new Array();
				$('#receiving_table tbody tr').each(function(i,val){

					var data = {
						po_id                 : $('#transaction_id').val(),
						po_number             : $('#po_number').val(),
						item_id  			  : $(val).find('.itemNo').text(),
						item_quantity_ordered : $(val).find('.qty').text(),
						item_quantity_actual  : $(val).find('.rr-qty').val(),
						item_name_ordered  	  : $(val).find('.itemName').text(),
						item_name_actual  	  : $(val).find('.itemName').text(),
						item_cost_ordered  	  : $(val).find('.unit_cost').text(),
						item_cost_actual  	  : $(val).find('.unit_cost').text(),
						discrepancy  		  : $(val).find('.discrepancy').val(),
						discrepancy_remarks   : '',
						unit_msr	  		  : $(val).find('.unit_msr').text(),
					}
					item_list.push(data);					
				});
				return item_list;				
			},rr_info:function(){

				$post = {
					id : $(this).data('id'),
					po : $(this).data('po'),
				}

		        if(xhr && xhr.readystate != 4){
		            xhr.abort();
		        }

				xhr = $.post('<?php echo base_url().index_page();?>/procurement/received_purchase/rr_info',$post,function(response){
					$('#dialog').html(response);
					$('#dialog').dialog({
						modal : true,
						title : "RR INFO",	
						zIndex: 9000,
						width:1000,
						height:720,
						close:function(){
							/*History.back();*/							
						}
					});	
				});
								
			}

		}

	$(function(){
		event_app.init();
	});
</script>