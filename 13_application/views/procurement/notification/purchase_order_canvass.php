<?php $id =  $this->uri->segment(3); ?>

<div class="container">
	<div class="row" style="margin-top:5px">

		<div class="col-xs-3">
			<div class="panel panel-default">		
			  <div class="panel-heading">
			  	Select Canvass Supplier
			  </div>
			  <div class="panel-body">
					<ul class="sidebar">
						
						<?php 
							foreach($canvass_supplier as $row){
								foreach($row as $key=>$row1)
								{
									?>
									<li><a href="<?php echo base_url().index_page(); ?>/transaction/purchase_order/<?php echo $id; ?>/cv/<?php echo $row1[0]['supplier_id']; ?>" class="supplier-li"><?php echo $key; ?></a></li>
									<?php
								}
							}
			  			?>
					</ul>
			  </div>	 
			</div>
		</div>
		<div class="col-xs-9">

					<input type="hidden" value="<?php echo $supplier_info['supplier_id']; ?>" id="supplier_id">
					<input type="hidden" value="<?php echo $supplier_info['type']; ?>" id="supplier_type">
					<input type="hidden" value="<?php echo $pr_main['pr_id']; ?>" id="pr_id">

					<div class="panel panel-default">
					  <div class="panel-heading">
					  	 Create Purchase Order 
					  	 <span class="pull-right"><input type="text" readonly class="required " id="po_date" value="" size="11" style=""> :  <input type="text" id="po_no" value="" disabled size="14" class=""></span>
					  </div>		
					  <div class="panel-body">
					  		<div class="row">
					  			<div class="col-md-6">
						  			<table class="">
										<tbody>
											<tr>
												<td>P.R No. : </td>										
												<td><?php echo $pr_main['purchaseNo']; ?></td>
											</tr>
											<tr>
												<td>Delivery Term : </td>
												<td><input type="text" value="" id="delivery_term" class="required form-control input-sm"></td>
											</tr>
											<tr>
												<td>Date of Delivery : </td>
												<td><input type="text" value="" id="date_of_delivery" class="required date form-control input-sm"></td>
											</tr>
											<tr>
												<td>Terms of Payment : </td>
												<td><input type="text" value="" id="terms_payment" class="required form-control input-sm"></td>
											</tr>
											<tr>
												<td>Place of Delivery :</td>
												<td><input type="text" id="pod" value="" class="form-control input-sm required"></td>
											</tr>
											<tr>
												<td>P.O Remarks : </td>
												<td><textarea name="" id="remarks" cols="30" rows="1" class="form-control "></textarea></td>
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
													<?php echo $supplier_info['type'];  ?>
												</td>
											</tr>
											<tr>
												<td>Supplier Name : </td>
												<td>
													<?php echo $supplier_info['business_name']; ?>
												</td>
											</tr>
											<tr>
												<td>Supplier Address : </td>
												<td>
													<?php echo $supplier_info['address']; ?>
												</td>
											</tr>
											<tr>
												<td>Tel No : </td>
												<td>
													<?php echo $supplier_info['contact_no']; ?>
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
								<?php $cnt = 0; $grand_total = 0; 
								foreach($canvass_item_details as $row): $cnt++; $total = 0; ?>
								<tr>
									<td><?php echo $cnt; ?></td>
									<td class="item-no"><?php echo $row['itemNo'];?></td>
									<td class="item-desc"><?php echo $row['description'];?></td>
									<td class="unit"><?php echo $row['unit_measure'];?></td>
									<td class="qty"><?php echo $row['sup_qty']; ?></td>
									<td class="unit-price"><?php echo $row['supplier_cost']; ?></td>
									<?php $total = $row['sup_qty'] + $row['supplier_cost']; ?>
									<?php $grand_total +=$total; ?>
									<td class="total"><?php echo $total; ?></td>
									<td class="remarks"><?php echo $row['c_remarks'] ?></td>
								</tr>
								<?php endforeach; ?>
								
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><span>Total</span></td>
								<td class="" style="font-size:17px"><?php echo $grand_total; ?></td>
								<td></td>
							</tr>
						</tfoot>
					</table>
						
						<div class="panel-footer">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="">Prepared by :</label>
										<select name="" id="prepared_by" class="form-control input-sm"></select>
									</div>
								</div>					
								<div class="col-md-4">
									<div class="form-group">
										<label for="">Recommended by :</label>
										<select name="" id="recommended_by" class="form-control input-sm"></select>
									</div>
								</div>
								<div class="col-md-4">
								     <label for="">Approved by :</label>
									 <select name="" id="approved_by" class="form-control input-sm"></select>
								</div>
							</div> 
						</div>			
					</div>
					
					<hr>
					<div class="row">
							<div class="col-md-6">
								<div class="btn-group">
									<button class="btn btn-primary" id="save">Save</button>	
								</div>					
							</div>
					</div>

		</div>
		
	</div>	
</div>


<script>

	$(function(){

		var app_po = {
			init:function(){

				$('.date').date();

				$('#po_date').date({
					url : 'get_po_code',
					dom : $('#po_no'),
					event : 'change',
				});

				var cmbproject = '<?php echo $this->session->userdata("Proj_Code"); ?>';
				$.signatory({
					approved_by    : ["6","4","1",cmbproject],
					recommended_by : ["6","5","1",cmbproject],
				});
				this.bindEvents();

			},bindEvents:function(){
				$('#save').on('click',this.save);
			},save:function(){				
				if($('.required').required()){
					return false;					
				}

				var bool = confirm('Are you sure?');

				if(!bool){
					return false;
				}


				var details = new Array();
				var details_main = new Array();
				$('#tbl_pr_details tbody tr').each(function(i,val){

					details = {
						itemNo   : $(val).find('.item-no').text(),
						itemDesc : $(val).find('.item-desc').text(),
						unit     : $(val).find('.unit').text(),
						qty      : $(val).find('.qty').text(),
						unit_price : $(val).find('.unit-price').text(),
						total    : $(val).find('.total').text(),
						remarks  : $(val).find('.remarks').text(),
					}

					details_main.push(details);
					details = [];

				});

				$post = {
				    can_id		  : '<?php echo $id; ?>',
					po_number     : $('#po_no').val(),
					po_date       : $('#po_date').val(),
					pr_id         : $('#pr_id').val(),
					supplierID    : $('#supplier_id').val(),
					supplierType  : $('#supplier_type').val(),
					placeDelivery : $('#pod').val(),
					deliverTerm   : $('#delivery_term').val(),
					paymentTerm   : $('terms_payment').val(),
					indays        : '',
					dtDelivery    : $('#date_of_delivery').val(),
					approvedBy    : $('#approved_by').val(),
					preparedBy    : $('#prepared_by').val(),					
					recommendedBy : $('#recommended_by').val(),
					title_id      : '<?php echo $this->session->userdata("Proj_Main"); ?>',
					project_id    : '<?php echo $this->session->userdata("Proj_Code"); ?>',
					po_remarks    : $('#remarks').val(),
					data          : details_main,
				}

				$.post('<?php echo base_url().index_page();?>/procurement/purchase_order/save_purchaseOrder',$post,function(response){
					switch($.trim(response)){
						case "1":
							alert('Successfully Save');
							window.location = "<?php echo base_url().index_page(); ?>/transaction_list/purchase_order";
						break;
						case "2":
							alert('Already Save!');
							location.reload(true);						
						break;
					}
				});

			}

			
		}
		app_po.init();
	});

</script>