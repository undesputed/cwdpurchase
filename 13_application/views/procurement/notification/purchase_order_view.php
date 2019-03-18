
<?php 


if($po_main['status'] == 'ACTIVE')
{
	$alert_style = "alert-success";	
}else{
	$alert_style = "alert-danger";	
}
 
?> 

<input type="hidden" id="po_id" value="<?php echo $po_main['po_id']; ?>">
<input type="hidden" id="pr_id" value="<?php echo $po_main['pr_id']; ?>">

<div class="container">
	
	<div class="row" style="margin-top:5px">
		
		<div class="col-xs-2">
			<?php echo $this->lib_sidebar->po_sidebar(); ?>
		</div>
		<div class="col-xs-10">
			<div>
				
				<div class="alert <?php echo $alert_style; ?>">
					<?php if($po_main['status'] == 'ACTIVE'): ?>				
					<button class="btn btn-danger pull-right" id="cancel" data-toggle="modal" data-target="#cancelPoModal" title="Change status to Cancel">Cancel</button>				
					<?php endif; ?>
					<span class="status"><h3>Status : <?php echo $po_main['status'] ?></h3></span>							
				</div>
			</div>
			<div class="panel panel-default" style="margin-top:5px;">
			  <div class="panel-heading">
			  	Purchase Order 
			  	<span class="pull-right"> <?php echo $po_main['po_date']; ?>  :  <?php echo $po_main['po_number']; ?></span>
			  </div>		
			  <div class="panel-body">
			  		<div class="row">
			  			<div class="col-md-6">
				  			<table class="">
								<tbody>
									<tr>
										<td>P.R No. : </td>
										<td><?php echo $po_main['purchaseNo']; ?></td>
									</tr>
									<tr>
										<td>Delivery Term : </td>
										<td><?php echo $po_main['deliverTerm']; ?></td>
									</tr>
									<tr>
										<td>Date of Delivery : </td>
										<td><?php echo $po_main['dtDelivery']; ?></td>
									</tr>
									<tr>
										<td>Terms of Payment : </td>
										<td><?php echo $po_main['paymentTerm']; ?></td>
									</tr>
									<tr>
										<td>Place of Delivery :</td>
										<td><?php echo $po_main['placeDelivery']; ?></td>
									</tr>
									<tr>
										<td>P.O Remarks : </td>
										<td><p><?php echo $po_main['po_remarks']; ?></p></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-6">
				  			<table class="">
								<tbody>
									<tr>
										<td>Supplier Type : </td>
										<td>
										<?php echo $po_main['supplierType']; ?>
										</td>
									</tr>
									<tr>
										<td>Supplier Name : </td>
										<td>
										<?php echo $supplier['business_name']; ?>
										</td>
									</tr>
									<tr>
										<td>Supplier Address : </td>
										<td>
										<?php echo $supplier['address']; ?>
										</td>
									</tr>
									<tr>
										<td>Tel No : </td>
										<td>
										<?php echo $supplier['contact_no']; ?>
										</td>
									</tr>
									
								</tbody>
							</table>
						</div>
			  		</div>			  		
			  </div>
			
			<table id="tbl_pr_details" class="table table-condensed">
				<thead>
					<tr>
						<th>#</th>
						<th>ItemNo</th>
						<th>Item Description</th>
						<th>Unit</th>
						<th>Qty</th>
						<th>Unit Price</th>
						<th>Total</th>
						<th>Remarks</th>
					</tr>
				</thead>	
				<tbody>						
					<?php 
						$cnt = 0;						
						$grand_total = 0 ;
						foreach($po_details as $row): 
						$cnt++;
						$total = 0;
						$total = $row['quantity'] * $row['unit_cost'];
						$grand_total += $total;
					?>
						<tr>
							<td><?php echo $cnt; ?></td>
							<td><?php echo $row['itemNo']; ?></td>
							<td><?php echo $row['item_name']; ?></td>
							<td><?php echo $row['unit_msr']; ?></td>
							<td><?php echo $row['quantity']; ?></td>
							<td><?php echo $row['unit_cost']; ?></td>
							<td><?php echo $total;?></td>
							<td><?php echo $row['remarks']; ?></td>
						</tr>
					<?php endforeach; ?>		
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><h4>Total</h4></td>
						<td></td>
						<td><h4 class="grand-total"><?php echo $this->extra->number_format($grand_total); ?></h4></td>
						<td></td>
					</tr>
				</tfoot>
			</table>
						
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="">Prepared by :</label>
								<div><?php echo $preparedBy['Person Name']; ?></div>
							</div>
						</div>					
						<div class="col-md-4">
							<div class="form-group">
								<label for="">Recommended by :</label>
								<div><?php echo $recommendedBy['Person Name']; ?></div>
							</div>
						</div>
						<div class="col-md-4">
						     <label for="">Approved by :</label>
							 <div><?php echo $approvedBy['Person Name']; ?></div>
						</div>
					</div> 
				</div>			
			</div>
			<hr>
			
		</div>

	</div>
	
</div>

<!-- MODAL -->
<div class="modal fade" id="cancelPoModal" tabindex="-1" role="dialog" aria-labelledby="cancelPoModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Cancel Purchase Order</h4>
      </div>
      <div class="modal-body">
        Reason for Cancellation
        <textarea class="form-control" name="" id="reason_closing" cols="30" rows="5" placeholder="Add Reason"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
        <button type="button" id="final-cancel" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>
<!---->

<script>
	
	$(function(){
		var app_po = {
			init:function(){				
			$('#po_date').date({
				url : 'get_po_code',
				dom : $('#po_no'),
				event : 'change',
			});

			
				//$('#supplier').chosen();
				this.bindEvents();

			},bindEvents:function(){

				$('#supplier').on('change',this.supplier_details);
				$('input[name="supplier-type"]').on('change',this.supplier);
				$('#final-cancel').on('click',this.cancel_po);			

			},
			cancel_po:function(){

				$post = {
				 type  : 'po',
				 po_id : $('#po_id').val(),
				 pr_id : $('#pr_id').val(),
				 remarks : $('#reason_closing').val(),
				}				
				$.post('<?php echo base_url().index_page();?>/transaction/do_decline',$post,function(response){
					alert(response.msg);
					location.reload('true');
				},'json');
			}
		}

		app_po.init();
	});

</script>