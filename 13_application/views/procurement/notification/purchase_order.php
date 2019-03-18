
<input type="hidden" id="pr_id" value="<?php echo $pr_main['pr_id']; ?>">
<input type="hidden" id="c_number" value="">

<div class="container">
	
	<div class="row" style="margin-top:5px">
		
		<div class="col-xs-2">
			<?php echo $this->lib_sidebar->po_sidebar(); ?>
		</div>
		<div class="col-xs-10">
			<div class="panel panel-default">
			  <div class="panel-heading">
			  	 Create Purchase Order 
			  	 <span class="pull-right"><input type="text" readonly class="required" id="po_date" value="" size="11"> :  <input type="text" id="po_no" value="" disabled size="14"></span>
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
										<td><input type="text" value="" id="delivery_term" class="required"></td>
									</tr>
									<tr>
										<td>Date of Delivery : </td>
										<td><input type="text" value="" id="date_of_delivery" class="required date"></td>
									</tr>
									<tr>
										<td>Terms of Payment : </td>
										<td><input type="text" value="" id="terms_payment" class="required"></td>
									</tr>
									<tr>
										<td>Place of Delivery :</td>
										<td><input type="text" id="pod" value=""></td>
									</tr>
									<tr>
										<td>P.O Remarks : </td>
										<td><textarea name="" id="remarks" cols="30" rows="1"></textarea></td>
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
											<div class="radio-inline">
												<input type="radio" name="supplier-type" value="PERSON" id="person"><label for="person">Person</label>
											</div>
											<div class="radio-inline">
												<input type="radio" name="supplier-type" value="BUSINESS" id="business" checked><label for="business">Business</label>
											</div>
										</td>
									</tr>
									<tr>
										<td>Supplier Name : </td>
										<td>
											<select name="" id="supplier">
												
											</select>
										</td>
									</tr>
									<tr>
										<td>Supplier Address : </td>
										<td><span id="supplier-address"> - </span></td>
									</tr>
									<tr>
										<td>Tel No : </td>
										<td><span id="supplier-telno"> - </span></td>
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
						<?php $cnt = 0; foreach($pr_details as $row): $cnt++; ?>
						<tr>
							<td><?php echo $cnt; ?></td>
							<td class="item-no"><?php echo $row['itemNo'];?></td>
							<td class="item-desc"><?php echo $row['itemDesc'];?></td>
							<td class="unit"><?php echo $row['unitmeasure'];?></td>
							<td><input type="text" class="numbers_only required qty compute" value="<?php echo $row['qty'] ?>"></td>
							<td><input type="text" class="numbers_only required unit-price compute" value=""></td>
							<td><span class="total"></span></td>
							<td><input type="text" value="" class="remarks"></td>
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
						<td><h4 class="grand-total"></h4></td>
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

				$('#po_date').date({
					url : 'get_ccv_code',
					dom : $('#c_number'),
					event : 'change',
				});

				var cmbproject = "<?php echo $this->session->userdata('Proj_Main'); ?>";
				
				$.signatory({
					approved_by    : ["6","4","1",cmbproject],
					recommended_by : ["6","5","1",cmbproject],
				});

				this.supplier();
				//$('#supplier').chosen();
				this.bindEvents();

			},bindEvents:function(){

				$('#supplier').on('change',this.supplier_details);
				$('input[name="supplier-type"]').on('change',this.supplier);
				$('#save').on('click',this.save);

			
				$('.compute').on('blur',function(){
					var value = 1;
					$(this).closest('tr').find('.compute').each(function(i,val){
						value = value * $(val).val();						
					});
					$(this).closest('tr').find('.total').text(value);
						var total = 0;
					$('.total').each(function(i,val){
						total = total + +$(val).text();
					});
					$('.grand-total').text(total);
				});	

				$('.compute').trigger('blur');

			},supplier:function(){

				$post = {
					type : $('input[name="supplier-type"]:checked').val(),
				}
				
				$.post('<?php echo base_url().index_page();?>/procurement/canvass_sheet/supplier',$post,function(response){
					$('#supplier').html('');
						$.each(response,function(i,val){
							$('#supplier').append($('<option>').val(val['Supplier ID']).text(val['Supplier']).attr({'data-address':val['address'],'data-contact':val['contact_no']}));	
						})					
						//$("#supplier").trigger("chosen:updated");
						app_po.supplier_details();
				},'json');

			},supplier_details:function(){
					var address = $('#supplier option:selected').attr('data-address');
					var contact = $('#supplier option:selected').attr('data-contact');
						$('#supplier-address').html(address);
						$('#supplier-telno').html(contact);
			},save :function(){

				if($('.required').required()){
					return false;
				}				
				var details = new Array();
				var details_main = new Array();
				$('#tbl_pr_details tbody tr').each(function(i,val){

					details = {
						itemNo   : $(val).find('.item-no').text(),
						itemDesc : $(val).find('.item-desc').text(),
						unit     : $(val).find('.unit').text(),
						qty      : $(val).find('.qty').val(),
						unit_price : $(val).find('.unit-price').val(),
						total    : $(val).find('.total').text(),
						remarks  : $(val).find('.remarks').val(),
					}

					details_main.push(details);
					details = [];

				});

				$post = {

					c_number      : $('#c_number').val(),					
					po_number     : $('#po_no').val(),
					po_date       : $('#po_date').val(),
					pr_id         : $('#pr_id').val(),
					supplierID    : $('#supplier option:selected').val(),
					supplierType  : $('input[name="supplier-type"]:checked').val(),
					placeDelivery : $('#pod').val(),
					deliverTerm   : $('#delivery_term').val(),
					paymentTerm   : $('terms_payment').val(),
					indays        : '',
					dtDelivery    : '',
					approvedBy    : $('#approved_by').val(),
					preparedBy    : $('#prepared_by').val(),					
					recommendedBy : $('#recommended_by').val(),
					title_id      : '<?php echo $this->session->userdata("Proj_Main"); ?>',
					project_id    : '<?php echo $this->session->userdata("Proj_Code"); ?>',
					po_remarks    : $('#remarks').val(),
					data          : details_main,

				}

				$.post('<?php echo base_url().index_page();?>/procurement/purchase_order/save_purchaseOrder2',$post,function(response){
					switch($.trim(response)){
						case "1":
							alert('Successfully Save');
							location.reload(true);
						break;
						case "2":
							alert('Already Save!');
							location.reload(true);						
						break;
					}
				});
									
			},
			get_total:function(){
				var total = 0;
				
				$('.total').each(function(i,val){					
					total =+ $(val).text();
				});
				
			}
		}

		app_po.init();
	});

</script>