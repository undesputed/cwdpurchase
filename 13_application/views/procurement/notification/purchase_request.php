<?php 
	$editable = '';	
	if($transaction['status'] != 'CLOSED'){
		$editable = 'editable';		
	}
		
?>
<!-- <div class="alert alert-danger" role="alert">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi, autem.</div> -->

<div class="container">	
	<input type="hidden" id="pr_id" value="<?php echo $pr_main['pr_id']; ?>">
	<input type="hidden" id="notify_id" value="<?php echo $transaction['id']; ?>">

	<div class="row" style="margin-top:5px;">
		<?php echo $this->extra->alert(); ?>	
			<div class="col-sm-8">
				<div class="panel panel-default">
				  <div class="panel-heading">
				  	<strong class="pull-right"><?php echo $pr_main['purchaseNo']; ?></strong>
				  	<h4>Purchase Requisition</h4>				  	
				  </div>

				  <div class="panel-body">
					<?php if($transaction['status']=='CLOSED'): ?>
					 <div class="alert alert-warning" role="alert">
					 		<strong>Closed Purchase Requisition</strong>
					 		<p><?php echo $transaction['remarks']; ?></p>
					 </div> 
					<?php endif; ?>
					<div class="form-group">
					
						<div class="row">
							<div class="col-sm-6">
								<div>Created From  : <?php echo $origin['project_main']; ?></div>
								<div>Profit Center : <?php echo $origin['project_code']; ?> </div>
								<div>Contact No    : </div>
								<div>Date          : <?php echo $this->extra->format_date($pr_main['purchaseDate']); ?></div>
							</div>
							<div class="col-sm-6">
								<div>Request To    : <?php echo $pr_main['project_title'] ?></div>
								<div>Profit Center : <?php echo $pr_main['project_code']; ?></div>								
								<div>Status        : <?php echo $this->extra->label($transaction['status']) ?></div>
								<div>Priority      : <?php echo $pr_main['legend_']; ?></div>
							</div>	
						</div>
						<hr>
						<div class="row">
							<div class="col-sm-12">
								Remarks:
								<p><?php echo $pr_main['pr_remarks'] ?></p>
							</div>
						</div>
												
					</div>

				  </div>

					<table id="tbl-item-list" class="table table-condensed">
						<thead>
							<tr>
								<th>#</th>
								<th>Item No</th>
								<th>Item Description</th>
								<th>Qty</th>
								<th>Unit of Measure</th>
								<th>Model No</th>
								<th>Serial No</th>
								<th>Remarks</th>
							</tr>
						</thead>
						<tbody>
							<?php $cnt = 0; foreach($pr_details as $row): $cnt++; ?>
								<tr>
									<td class="no"><?php echo $cnt; ?></td>
									<td class="itemNo"><?php echo $row['itemNo'];?></td>
									<td class="itemDesc"><?php echo $row['itemDesc'];?></td>
									<td ><span class="<?php echo $editable; ?> qty" title="click to edit"><?php echo $row['qty']; ?></span></td>
									<td class="unit_measure"><?php echo $row['unitmeasure']; ?></td>
									<td class="modelNo"><?php echo $row['modelNo']; ?></td>
									<td class="serialNo"><?php echo $row['serialNo']; ?></td>
									<td class="remarks"><?php echo $row['remarkz']; ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
						
					<div class="panel-footer">
						<div class="row">
							<div class="col-sm-4">
								<div><?php echo $pr_main['person_preparedBy']; ?></div>
								<div>Prepared by :</div>
							</div>
							<div class="col-sm-4">
								<div><?php echo $pr_main['person_recommendedBy']; ?></div>
								<div>Recommended by : </div>
							</div>
							<div class="col-sm-4">
								<div><?php echo $pr_main['person_approvedBy']; ?></div>
								<div>Approved by : </div>
							</div>	
						</div>
					</div>					 
				</div>
			</div>

			<div class="col-sm-4">
				<?php if($transaction['notify']!='creator' and $transaction['status'] !='CLOSED'): ?>
					<div class="panel panel-default">
					  <div class="panel-heading">
					  	Action
					  </div>
					  <div class="panel-body">
							
					  		<div class="form-group">
					  			<p>What do you want to do?</p>
								
								<?php if($transaction['status'] !='CANCELLED' AND $transaction['status'] !='APPROVED' AND $transaction['status'] !='CLOSED'): ?>
								
					  			<div class="btn-group">
					  				<button id="approved" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#approvedModal">Approve</button>				  				
					  			</div>
					  			<div class="btn-group">
					  				<button id="cancel" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancelModal">Decline</button>				  				
					  			</div>
							
								<?php else: ?>
								<div class="btn-group">
					  				<button id="closing" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#closingRequisition">Close Purchase Request </button>				  				
					  			</div>
								<?php endif; ?>

					  		</div>
					  
					  </div>

					</div>
				<?php endif; ?>
				
				<?php $this->lib_sidebar->sidebar(); ?>			

			</div>
					
	</div>		
</div>

<!-- MODAL -->

<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Decline Request</h4>
      </div>
      <div class="modal-body">
        Are you sure you want to decline this request?
        <textarea class="form-control" name="" id="decline_comment" cols="30" rows="5" placeholder="Add comment"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
        <button type="button" id="final-cancel" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>

<!---->

<div class="modal fade" id="approvedModal" tabindex="-1" role="dialog" aria-labelledby="approveModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="approveModal">Approved Request</h4>
      </div>
      <div class="modal-body">
        Are you sure you want to Approved this request?       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
        <button type="button" id="final-approved" class="btn btn-primary">Yes</button>
      </div>
    </div>
  </div>
</div>


<!-- MODAL -->

<div class="modal fade" id="closingRequisition" tabindex="-1" role="dialog" aria-labelledby="closingRequisition" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Closing Purchase Requisition</h4>
      </div>
      <div class="modal-body">
        Reason for Closing
        <textarea class="form-control" name="" id="reason_closing" cols="30" rows="5" placeholder="Add Reason"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
        <button type="button" id="final-closing" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>

<!---->




<script>
	$(function(){

		var transaction = {
			init:function(){
				this.bindEvents();
			},bindEvents:function(){
				$('#final-approved').on('click',this.approved);
				$('#final-cancel').on('click',this.decline);

				$(".editable").editable("<?php echo base_url().index_page(); ?>/ajax/display", { 
				      indicator : "<img src='<?php echo base_url().index_page();?>/asset/img/indicator.gif'>",
				      tooltip   : "Click to edit...",
				      style  : "inherit"
				});

				$('#final-closing').on('click',this.final_closing);



			},approved:function(){
				$post = {
					pr_id   : $('#pr_id').val(),
					id      : $('#notify_id').val(),
					type    : 'pr',
					data    : transaction.pr_details(),
				};


				$.post('<?php echo base_url().index_page();?>/transaction/do_approve',$post,function(response){
					alert(response.msg);
					location.reload(true);
				},'json');

			},decline:function(){
				$post = {
					pr_id   : $('#pr_id').val(),
					id      : $('#notify_id').val(),
					type    : 'pr',
					remarks : $('#decline_comment').val()
				};

				$.post('<?php echo base_url().index_page();?>/transaction/do_decline',$post,function(response){
					alert(response.msg);
					location.reload(true);
				},'json');

			},pr_details:function(){
				var data = new Array();
				$('#tbl-item-list tbody tr').each(function(i,val){

					var details = {
						itemNo : $(val).find('.itemNo').text(),
						qty    : $.trim($(val).find('.qty').text()),
					}
					data.push(details);

				});

				return data;
			},final_closing:function(){

				$post = {
					pr_id   : $('#pr_id').val(),
					id      : $('#notify_id').val(),
					type    : 'pr',
					remarks : $('#reason_closing').val()
				};

				$.post('<?php echo base_url().index_page();?>/transaction/do_closing',$post,function(response){
					alert(response.msg);
					location.reload(true);
				},'json');

			}
		};

		transaction.init();
	});



</script>