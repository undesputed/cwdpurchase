
<div class="t-content">
	<input type="hidden" id="cash_voucher_id" value="<?php echo $main['cash_voucher_id'] ?>">
		<div class="t-header">
			<a href="<?php echo base_url().index_page(); ?>/accounting/voucher/" class="close close-info"><span aria-hidden="true">&times;</span><span></a>
			<h4>Journal Voucher</h4>
		</div>
		
		<?php
			$user_id = $this->session->userdata('emp_id');				
		?>
		
		<div class="row">
			<div class="col-xs-5">				
				<div class="control-group">

					<?php if($main['preparedby'] == $user_id || $this->extra->is_admin()): ?>
					<?php if($main['status'] !='CANCELLED'): ?>
					<?php if($main['status']=='ACTIVE'): ?>

					<span class="control-item-group">
						<a href="javascript:void(0)" class="approved action-status" data-id="<?php echo $main['cash_voucher_id']; ?>">Approved</a>
					</span>					
					<span class="control-item-group">
						<a href="javascript:void(0)" class="edit action-status" data-payment_type="<?php echo $main['payment_type']; ?>">Edit</a>
					</span>
					<?php endif; ?>

					<?php if($main['status']=='APPROVED'): ?> 
					<span class="control-item-group">
						<span class="label label-success">Approved</span>						
					</span>	
					
					<!--
					<span class="control-item-group">
						<a href="javascript:void(0)" class="check action-status" data-status="CHECK" data-id="<?php echo $main['cash_voucher_id']; ?>" data-payment_type="<?php echo $main['payment_type']; ?>">Assign Check</a>
					</span>
					-->
					
					<?php endif; ?>
					
					<span class="control-item-group">						
						<a href="javascript:void(0)" class="cancel action-status" data-status="CANCELLED" data-payment_type="<?php echo $main['payment_type']; ?>">Cancel</a>						
					</span>

					<?php if(empty($main['rr_id'])): ?>
					<span class="control-item-group">						
						<a href="javascript:void(0)" class="tag action-status" data-voucher_id="<?php echo $main['cash_voucher_id'] ?>" data-payment_type="<?php echo $main['payment_type']; ?>">Tag</a>						
					</span>						
					<?php endif; ?>
										
					<?php else: ?>
						<span class="control-item-group">
							<a href="javascript:void(0)" class="activate action-status" data-status="ACTIVE"  data-payment_type="<?php echo $main['payment_type']; ?>">Activate</a>
						</span>
						<span class="control-item-group">
							<span class="label label-danger">Cancelled</span>
						</span>
					<?php endif; ?>
				 <?php endif; ?>
				</div>
			</div>
			<div class="col-xs-5">

				<div class="form-group inline pull-right">
					 
					<span class="control-item-group">
						<a href="<?php echo base_url().index_page(); ?>/print/journal_voucher/<?php echo $main['cash_voucher_id'] ?>" class="action-status" target="_blank">Print</a>				
					</span>
					|
					<?php $checked = (!empty($main) && $main['is_print'] == 'true')? 'checked="checked"' : ''; ?>
					<label for="is_print"><input id="is_print" type="checkbox" <?php echo $checked; ?> class="is_already"> Already Printed</label>
				</div>
			</div>
		</div>
		
		<div class="row" style="margin-top:5px">
			<div class="col-md-6 col-info">
				
				<?php 					
					if(!empty($po_main['reference_no'])){
						echo "<label class='label label-success'> TAG : PO {$po_main['reference_no']}</label><br>";	
					}					
				?>


				<strong>Purpose: </strong>
				<br><?php echo $main['type']; ?> <?php echo $main['short_desc']; ?><br>				
				<br>
				<br>
				Remarks: <?php echo $main['remarks']; ?><br>
			</div>
			
			<div class="col-md-6 col-info">

				<strong><?php echo $main['voucher_no']; ?></strong><br>
				<strong>Date: </strong> <?php echo date('F d, Y',strtotime($main['voucher_date'])); ?><br><br><br>

				Project: <?php echo $main['project_name']; ?><br><br>
				<?php if(strtoupper($main['payment_type']) == 'CHECK'): ?>
					<strong>CHECK : </strong><?php echo $main['bank']." ".$main['check_no']; ?><br>
				<?php endif; ?>

			</div>
		</div>
		
		<table class="table">
			<thead>
				<tr>
					<th>Particulars</th>
					<th style="">Amount</th>					
				</tr>
			</thead>
			<tbody>
				<?php $total = 0; ?>
				<?php foreach($details as $row): ?>
				<?php $total = $total + $row['amount']; ?>
				<tr>
					<td><?php echo $row['item_description']; ?></td>
					<td><?php echo number_format($row['amount'],2); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>	
			<tfoot>
				<tr>
					<td style="text-align:right">Total Amount</td>
					<td style="font-size:15px"><strong><?php echo number_format($total,2);?></strong></td>
				</tr>
			</tfoot>
		</table>


		<table class="table">
			<thead>
				<tr>
					<th>Journal Entries</th>
					<th>Subsidiary</th>					
					<th>DEBIT</th>
					<th>CREDIT</th>
				</tr>
			</thead>
			<tbody>

				<?php $total = 0;$total_debit = 0;$total_credit = 0; ?>
				<?php foreach($journal as $row): ?>
				<?php $total = $total + $row['amount']; ?>
				<?php $debit  = 0; $credit = 0;$style=""; ?>			
				<?php 

					if($row['dr_cr'] == 'DEBIT'){
						$debit  = number_format($row['amount'],2);
						$credit = "";
						$total_debit += $row['amount'];
					
					}else{
						$credit = number_format($row['amount'],2);
						$debit = "";
						$total_credit += $row['amount'];
						$style = 'padding-left:3em;';
					}
				 ?>
				<tr>
					<td style="<?php echo $style; ?>"><?php echo $row['account_description']; ?></td>
					<td><?php echo $row['memorandum']; ?></td>
					<td><?php echo $debit; ?></td>
					<td><?php echo $credit; ?></td>

				</tr>
				<?php endforeach; ?>
			</tbody>	
			<tfoot>
				<tr>
					<td></td>
					<td style="text-align:right">Total Amount</td>
					<td style="font-size:15px"><strong><?php echo number_format($total_debit,2);?></strong></td>
					<td style="font-size:15px"><strong><?php echo number_format($total_credit,2);?></strong></td>
				</tr>
			</tfoot>
		</table>



				
		<div class="row">
			<div class="col-md-4 col-info">
				Prepared By: <br>
				<strong><?php echo $main['preparedBy_name'] ?></strong>
			</div>
		</div>



</div>

<?php 							
	$a = array(
		 'transaction_id'=>$main['cash_voucher_id'],
		 'transaction_no'=>$main['voucher_no'],
		 'type'=>'DISBURSEMENT VOUCHER',
		);
	echo $this->lib_transaction->comments($a);
?>


<script>
	$(function(){
		var xhr = "";
		var app = {
			init:function(){
				this.bindEvent();
				$('#check_date').date();
			},bindEvent:function(){
				/*$('#update').on('click',this.update);*/
				$('.edit').on('click',this.edit);
				$('.approved').on('click',this.approved);
				$('#is_print').on('click',this.print);
				$('.cancel').on('click',this.status);
				$('.activate').on('click',this.status);
				$('.tag').on('click',this.tag);

			},update:function(){

				if($('.required1').required()){
					return false;
				}

				var bool = confirm('Are you sure?');

				if(!bool){
					return false;
				}

				$.save({appendTo : 'body'});
				$post = {
					bank_id         : $('#bank_list option:selected').val(),
					bank_name       : $('#bank_list option:selected').text(),
					check_no        : $('#check_no').val(),
					check_date      : $('#check_date').val(),
					cash_voucher_id : $('#cash_voucher_id').val(),
				};

				$.post('<?php echo base_url().index_page();?>/accounting/update_cash_voucher',$post,function(response){

				});

			},edit:function(){				
				/*
				if($(this).attr('data-payment_type')=="CHECK")
				{
					$.fancybox.showLoading();
					$post = {
						id    : $('#cash_voucher_id').val(),
						po_id : '<?php echo $main['po_id']; ?>'
					};
					
					$.post('<?php echo base_url().index_page();?>/accounting/edit_1',$post,function(response){
						$.fancybox(response,{
							width     : 1000,
							height    : 550,
							fitToView : true,
							autoSize  : false,
						})
					});
					
				}else{
				
				}				

				*/

				$.fancybox.showLoading();
				$post = {
					id : $('#cash_voucher_id').val(),
				};

				$.post('<?php echo base_url().index_page();?>/accounting/journal_voucher_edit',$post,function(response){
					$.fancybox(response,{
						width     : 1200,
						height    : 550,
						fitToView : true,
						autoSize  : false,
					})
				});

				
			},print:function(e){
				e.preventDefault();
				var status = $(this).is(":checked");

				var me = $(this);
				
				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}
				
		        if(xhr && xhr.readystate != 4){
		            xhr.abort();
		        }

				$post = {
						id          : '<?php echo $main['cash_voucher_id'];?>',
						status      : $('#is_print').is(':checked'),						
				}

				xhr = $.post('<?php echo base_url().index_page();?>/accounting/is_status',$post,function(){
					me.prop('checked',status);					
				});
			},status:function(){
				var remarks = "";
				if($(this).attr('data-status') == 'CANCELLED'){
					var bool = confirm('Are you sure you want to cancel?');
					if(!bool){
						return false;
					}

					var reason = prompt("Reason for Cancellation", "");
					if (reason != null) {
					   remarks = reason
					}
				}else{
					var bool = confirm('Are you sure you want to activate?');
					if(!bool){
						return false;
					}
				}
				
				$post = {
					id      : $('#cash_voucher_id').val(),
					no      : '<?php echo $main['voucher_no']; ?>',
					status  : $(this).attr('data-status'),
					remarks : remarks
				}
				
				$.post('<?php echo base_url().index_page();?>/accounting/cancel',$post,function(response){
					updateContent();
					updateStatus($post.status);
				});
				
			},approved:function(){
				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}
				$post = {
					id     : $('#cash_voucher_id').val(),
				};

				$.post('<?php echo base_url().index_page();?>/accounting/approved_voucher',$post,function(response){
					alert('Successfully Approved');
					updateContent();
					updateStatus('Approved');
				});

			},tag:function(){
				

				$.fancybox.showLoading();
				$post = {
					voucher_id : $(this).data('voucher_id'),
				}

				$.post('<?php echo site_url('/accounting/tag')?>',$post,function(response){ 
					$.fancybox(response,{
						width     : 300,
						height    : 350,
						fitToView : false,
						autoSize  : false,
					})
				});

			}
		}
		app.init();		
	});
</script>