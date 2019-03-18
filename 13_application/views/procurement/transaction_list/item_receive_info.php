
<input type="hidden" id="is_date">
<input type="hidden" id="is_no">
<input type="hidden" value="<?php echo $main_data['id'] ?>" id="transaction_id">
<div class="t-content">
	<div class="t-header">
		<a href="<?php echo base_url().index_page(); ?>/transaction_list/item_transfer" class="close"><span aria-hidden="true">&times;</span><span></a>
		<h4 id="ws-no"><?php echo $main_data['transfer_no']; ?></h4>
	</div>
	<div class="row">		
		<div class="col-xs-6">
			<div class="control-group">
				
				<span class="control-item-group">
					<?php if($main_data['request_status'] !='CANCELLED'): ?>
						<a class="cancel-event action-status" href="javascript:void(0)">Cancel</a>											
					<?php else: ?>
						<span class="label label-danger">CANCELLED</span>
					<?php endif; ?>
				</span>
				
				<?php if($main_data['request_status'] == 'APPROVED'): ?>
				<span class="control-item-group">
					<span class="label label-success">Approved</span>					
				</span>
				<?php endif; ?>
				
				<?php if($main_data['request_status'] != 'APPROVED'): ?>
				<span class="control-item_group">
					<a class="approved-event action-status" href="javascript:void(0)">Approve</a>
				</span>
				<?php endif; ?>			
				
			</div>
		</div>
		<div class="col-xs-6">
			<div class="control-group">
				<a href="<?php echo base_url().index_page();?>/print/item_receive_report/<?php echo $main_data['transfer_no']; ?>" target="_blank" class="action-status"><i class="fa fa-print"></i> Print</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="t-title">
				<div>Request to :</div>	
				<strong> <?php echo $main_data['request_to_name']; ?></strong>
			</div>			
		</div>
		<div class="col-xs-6">

		</div>
	</div>


	<table id="item_list" class="table table-item">
		<thead>
			<tr>							
				<th>Item Description</th>
				<th>Unit Measure</th>				
				<th>Issued Qty</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($details as $row): ?>
				<tr>
					<td><?php echo $row['item_description']; ?></td>
					<td><?php echo $row['unit_measure']; ?></td>					
					<td><?php echo $row['request_qty']; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
								
				<th>
					<div><span id="item-count"><?php echo count($details); ?></span> item(s)</div>					
				</th>
				<th></th>				
				<th></th>				
							
			</tr>			
		</tfoot>
	</table>
		
	<div class="row">
		<div class="col-xs-12">
			<div class="t-title">
				<div>Remarks/Purpose :</div>
				<strong><p><?php echo $main_data['remarks'] ?></p></strong>
			</div>
		</div>		
	</div>	
	<div class="row">
		<div class="col-xs-6">
			<div class="t-title">
				<div>Request By : </div>
				<strong><?php echo $main_data['request_by']; ?></strong>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="t-title">
				<div>Prepared By : </div>
				<strong><?php echo $main_data['preparedBy_name']; ?></strong>
			</div>
		</div>
	</div>
</div>

		<?php 
			$a = array(
				 'transaction_id'=>$main_data['id'],
				 'transaction_no'=>$main_data['transfer_no'],
				 'type'=>'transfer',
				);
			echo $this->lib_transaction->comments($a);
		?>

<script>	
		$(function(){
		var event_app = {
			init:function(){
				this.bindEvent();
			},
			bindEvent:function(){

				$('.pending-event').on('click',this.pending_event);
				$('.approved-event').on('click',this.approved_event);
				$('.reject-event').on('click',this.reject_event);
				$('.cancel-event').on('click',this.cancel_event);

			},pending_event:function(){

				$post = {
					id     : $('#transaction_id').val(),
					transaction_no : '<?php echo $main_data['transfer_no']; ?>',
					status : 'PENDING',
					type   : 'transfer',
					remarks: '',
				};
				event_app.execute_query($post);

			},approved_event:function(){
				if($('.required').required()){
					return false;
				}
				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}
				var item_container = new Array();
				$('.approved-qty').each(function(i,val){

					var item = {
						qty    : $(val).val(),
						itemNo : $(val).attr('data-itemno'),
					}
					item_container.push(item);

				});
				
				$post = {
					id      : $('#transaction_id').val(),
					status  : 'APPROVED',
					transaction_no : '<?php echo $main_data['transfer_no']; ?>',
					type    : 'transfer',
					remarks : '',
					details : item_container,
				};
					
				event_app.execute_query($post);
				
			},cancel_event:function(){
				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}

				$post = {
					id     : $('#transaction_id').val(),					
					status : 'CANCEL',
					transaction_no : "<?php echo $main_data['transfer_no']; ?>",
					remarks: '',
					type   : 'transfer',
				};
				event_app.execute_query($post);
			},execute_query:function($post){
					$.post('<?php echo base_url().index_page();?>/transaction/change_status',$post,function(response){
					switch($.trim(response)){
						case "1":
							location.reload('true');
						break;
					}
					
				});
			}

		}
		event_app.init();
	});
</script>
