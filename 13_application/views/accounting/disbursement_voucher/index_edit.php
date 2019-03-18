<div class="header">
	<h2>Disbursement Voucher</h2>
</div>

<input type="hidden" value="<?php echo $po_main['supplierID'] ?>"  id="supplier_id">
<input type="hidden" value="<?php echo $po_main['Supplier'] ?>"    id="supplier_name">
<input type="hidden" value="<?php echo $po_main['paymentTerm'] ?>" id="paymentTerm">

<input type="hidden" value="<?php echo $po_main['from_projectCode'] ?>" id="from_projectCode">
<input type="hidden" value="<?php echo $po_main['from_projectMain'] ?>" id="from_projectMain">

<input type="hidden" value="<?php echo $cash_main['voucher_no']; ?>" id="dv_no">

<div class="container">

		<div class="content-title">
			<h3>Menus</h3>	
		</div>
		
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-4">
						<table class="table">								
							<tr>
								<td>Voucher No.:</td>
								<td>
									<?php echo $cash_main['voucher_no']; ?>
								</td>
							</tr>
							<tr>
								<td>Voucher Date:</td>
								<td><input type="text" class="form-control date" id="date" value=""></td>
							</tr>
							<tr>
								<td>Payment Type:</td>
								<td>
									<div class="radio-inline">																		
										<label for="cash"><input id="cash" type="radio" name="paymenttype" value="cash">Cash</label>
									</div>
									<div class="radio-inline">																		
										<label for="check"><input id="check" type="radio" name="paymenttype" checked value="check">Check</label>
									</div>
								</td>
							</tr>
							<tr class="">
								<td style="width:80px">Bank :</td>
								<td>
									<select name="" id="bank_list" class="form-control check-group">
										<option value="">-To be assign-	</option>
										<?php foreach($bank as $row): ?>	
										<?php echo $selected  = ($row['bank_id'] == $cash_main['bank_id'])? "selected='selected'" : "" ?>
											<option <?php echo $selected; ?> value="<?php echo $row['bank_id']; ?>"><?php echo $row['bank_name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>	
							<tr class="">
								<td style="width:80px">Check No :</td>
								<td>
									<input type="text" class="form-control check-group" id="check_no" value="<?php echo $cash_main['check_no']; ?>">
								</td>
							</tr>
							<tr class="">
								<td style="width:80px">Due Date :</td>
								<td>
									<input type="text" class="form-control check-group date" id="due_date">
								</td>
							</tr>
						</table>
					</div>
					<div class="col-md-4">
						<table class="table">							
							<tr>
								<td style="width:80px">PO No. :</td>
								<td>
									<input type="hidden" id="po_id" value="<?php echo $po_main['po_id_main']; ?>">									
									<input type="text" id="po_number" class="form-control" value="<?php echo $po_main['reference_no']; ?>">																	
								</td>
							</tr>
							<tr>
								<td>Invoice No:</td>
								<td>									
									<select name="" id="sales_invoice" class="form-control">
										<?php foreach($rr_main as $row): ?>
											<option data-rr_id='<?php echo $row['receipt_id']; ?>' value="<?php echo $row['supplier_invoice']; ?>"><?php echo $row['supplier_invoice']; ?></option>
										<?php endforeach; ?>
									</select>									
								</td>
							</tr>
							<tr>
								<td>Remarks</td>
								<td><input type="text" class="form-control" id="remarks"></td>
							</tr>						
						</table>
					</div>
					<div class="col-md-4">
						<table class="table">	
							<tr>
								<td>Project :</td>
								<td><?php echo $po_main['from_projectCodeName']; ?></td>
							</tr>							
							<tr>
								<td style="width:80px">Supplier. :</td>
								<td><input type="text" class="form-control" value="<?php echo $po_main['Supplier']; ?>"></td>
							</tr>
							<tr>
								<td>Type:</td>
								<td><input type="text" class="form-control" id="type" value="<?php echo $type; ?>"></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12" id="invoice_items">
						
					</div>
				</div>
			</div>
			 <div class="form-footer">			  	
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
					  			<div class="control-label">Prepared by : </div>
					  			<select name="prepared_by" id="prepared_by" class="form-control input-sm"></select>
							</div>
						</div>
						<div class="col-md-5"></div>
						<div class="col-md-4">
							<input id="update" class="btn btn-success  col-xs-5 pull-right" type="submit" value="Update">
							<!-- <input id="reset" class="btn btn-link  pull-right" type="reset" value="Reset"> -->
						</div>
					</div>					
			  </div>
		</div>

</div>

<script>
	var xhr = "";
	var application ={
		init:function(){
			this.bindEvent();
			$('.date').date();
			$.signatory({				
				prepared_by   : 'sesssion',			
			});
		},bindEvent:function(){
			$('#sales_invoice').on('change',this.invoice_change);
			$('#sales_invoice').trigger('change');
			$('#btn_po_list').on('click',this.open_po_list);
			$('#update').on('click',this.update);

			$('input[name="paymenttype"]').on('change',function(){
				if($('input[name="paymenttype"]:checked').val() == 'cash'){
					$('.check-group').prop('disabled',true);
					$('.check-group').val('');
				}else{
					$('.check-group').prop('disabled',false);
				}
			});

			$('input[name="paymenttype"]').trigger('change');

		},open_po_list:function(){

			$( "#dialog" ).dialog({
				modal :  true, 
				title : 'Received PO List',
				width :  '700px'
			});

			$('#dialog').html('Loading...');

			$.post('<?php echo base_url().index_page();?>/accounting/get_po_list',function(response){
				$('#dialog').html(response);
			}).error(function(){
				$('#dialog').html('ERROR!, FAILED TO LOAD DATA');
			});
		},update:function(){
							
	        if(xhr && xhr.readystate != 4){
	            xhr.abort();
	        }

	        $post = {
	        	rr_id     : $('#sales_invoice option:selected').attr('data-rr_id'),
	        	bank_id   : $('#bank_list option:selected').val(),
	        	bank_name : $('#bank_list option:selected').text(),	        	
	        	dv_no     : $('#dv_no').val(),
	        	po_id     : $('#po_id').val(),
	        	date      : $('#date').val(),
	        	type      : $('#type').val(),
	        	amount    : $('#amount').val(),
	        	supplier_id : $('#supplier_id').val(),
	        	Supplier    : $('#supplier_name').val(),
	        	paymentTerm : $('#paymentTerm').val(),
	        	remarks     : $('#remarks').val(),
	        	check_no    : $('#check_no').val(),
	        	due_date    : $('#due_date').val(),
	        	from_projectCode : $('#from_projectCode').val(),
	        	from_projectMain : $('#from_projectMain').val(),
	        	cash_main_id : '<?php echo $cash_main_id; ?>',

	        };

	        if(typeof($post.dv_no) == 'undefined')
	        {
	        	alert('Please select DV NO.');
	        	return false;
	        }
			
	       if($('input[name="paymenttype"]:checked').val()=='check')
	       {
	       		if($('#check_no').val()==''){
	       			/*alert('');*/
	        		/*return false;*/
	       		}
	       }

	        var bool = confirm('Are you sure?');
			if(!bool){
				return false;
			}
			
	        $.save({appendTo : 'body'});
	        xhr = $.post('<?php echo base_url().index_page();?>/accounting/update_voucher',$post,function(response){
	        	switch($.trim(response))
	        	{
	        		case "1":
	        			alert('Successfully Updated');	        			
	        			$('#save').addClass('disabled');	        			
	        			$.save({action : 'success',reload : 'true'});
	        			$.fancybox.close();	        			
	        			/*window.location="<?php echo base_url().index_page();?>/accounting/voucher";*/
	        			/*updateContent();*/
	        		break;
	        		default:
	        			$.save({action : 'error',reload : 'false'});
	        		break;
	        	}

	        }).error(function(x,e){ 
						 if(x.status==0){
			                alert('You are offline!!\n Please Check Your Network.');
			            }else if(x.status==404){
			                alert('Requested URL not found.');
			            }else if(x.status==500){
			                alert('Internel Server Error.');
			            }else if(e=='parsererror'){
			                alert('Error.\nParsing JSON Request failed.');
			            }else if(e=='timeout'){
			                alert('Request Time out.');
			            }else {
			                alert('Unknow Error.\n'+x.responseText);
			            }
					$.save({action : 'error',reload : 'false'});
				});
		},invoice_change:function(){
			$get = {
				receipt_id : $('#sales_invoice option:selected').attr('data-rr_id'),
				po_id      : '<?php echo $po_main['po_id_main']; ?>',
			}
			$('#invoice_items').html('Loading...');
			$.get('<?php echo base_url().index_page(); ?>/accounting/get_invoice_item',$get,function(response){
				$('#invoice_items').html(response);
			});			
		}
	};

	$(function(){		
		application.init();
	});
</script>