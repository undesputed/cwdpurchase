

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
			}

		}

	$(function(){
		event_app.init();
	});
</script>