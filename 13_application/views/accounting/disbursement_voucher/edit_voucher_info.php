
<div class="t-content">

	<div class="t-header">
		<a href="<?php echo base_url().index_page(); ?>/accounting/voucher/" class="close close-info"><span aria-hidden="true">&times;</span><span></a>
		<h4>Cash Voucher</h4>
	</div>
<div class="row">
	<div class="col-md-6">
		<div class="control-group">	
			<span class="control-item-group">			
				<a href="javascript:void(0)" class="action-status history_back">Back</a>
			</span>
			<span class="control-item-group">
				<a href="javascript:void(0)" class="action-status" id="save_changes">Save Changes</a>
			</span>
		</div>
	</div>
</div>

<div class="row" style="margin-top:5px">

	<div class="col-md-6">
		<table class="table">										
			<tr>

				<td>Payment Type:</td>
				<td><?php echo $payment_type; ?></td>
			</tr>
			<tr>
				<td>Invoice No:</td>
				<td><strong><?php echo $rr_main[0]['supplier_invoice'] ?></strong></td			</tr>
			<tr>
				<td style="width:80px">PO No. :</td>
				<td>
					<input type="hidden" id="po_id" value="<?php echo $po_main['po_id_main']; ?>">
					<strong><?php echo $po_main['reference_no']; ?></strong>
				</td>
			</tr>
			<tr>
				<td style="width:80px">Project :</td>
				<td><strong></strong><?php echo $po_main['from_projectCodeName']; ?></strong></td>
			</tr>
			<tr>
				<td style="width:80px">Supplier :</td>
				<td><strong></strong><?php echo $po_main['Supplier']; ?></strong></td>
			</tr>			
		</table>
	</div>

	<div class="col-md-6">
		<table class="table">
			<tr>
				<td>CV No.:</td>
				<td>
					<?php echo $voucher_info['CV NO.']; ?>
				</td>
			</tr>
			<tr>
				<td>CV Date:</td>
				<td><?php echo $voucher_info['CV DATE']; ?></td>
			</tr>
			
			<tr>
				<td>Type:</td>
				<td><strong><?php echo $type; ?></strong></td>
			</tr>	
			<tr>
				<td style="width:80px">Bank :</td>
				<td>
					<select name="" id="bank_list" class="form-control check-group">						
						<?php foreach($bank as $row): ?>	
							<option value="<?php echo $row['bank_id']; ?>"><?php echo $row['bank_name']; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width:80px">Check No :</td>
				<td>
					<input type="text" class="form-control check-group required" id="check_no">
				</td>
			</tr>
			<tr>
				<td style="width:80px">Check Date:</td>
				<td>
					<input type="text" class="form-control check-group date" id="check_date">
				</td>
			</tr>						
			
		</table>
	</div>
</div>

<div class="table-responsive" style="overflow:auto">
	
			<table class="table table-condensed">
				<thead>
					<tr>						
						<th>PO Items</th>
						<th>Account Code</th>
						<th>Account Description</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($item_list)): 
							$total  = 0;
							foreach($item_list as $row):
							$amount = str_replace(',','', $row['AMOUNT']);
							$total += $amount;
					?>							
						<tr>
							<td><?php echo $row['PO ITEMS']; ?></td>
							<td><?php echo $row['ACCOUNT CODE']; ?></td>
							<td><?php echo $row['ACCOUNT DESCRIPTION']; ?></td>
							<td><?php echo $row['AMOUNT']; ?></td>
						</tr>
					<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="5">Empty List</td>
						</tr>
					<?php endif; ?>
				</tbody>
				<tfoot>
					<tr>
						<td><?php echo count($item_list); ?> item(s)</td>
						<td></td>
						<td style="text-align:right">Total : </td>
						<td><?php echo $this->extra->number_format($total); ?></td>
					</tr>
				</tfoot>
				<input type="hidden" id="amount" value="<?php echo $total; ?>">
			</table>
</div>


</div>
<script>

	var xhr = "";
	var application ={
		init:function(){
			this.bindEvent();
			$('#date').date();
			$('.date').date();
			$.signatory({				
				prepared_by   : 'sesssion',			
			});
		},bindEvent:function(){
			$("#save_changes").on('click',this.save_changes);
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
		},save:function(){
			var bool = confirm('Are you sure?');
			if(!bool){
				return false;
			}
			
	        if(xhr && xhr.readystate != 4){
	            xhr.abort();
	        }

	        $post = {
	        	bank_id   : $('#bank_list option:selected').val(),
	        	bank_name : $('#bank_list option:selected').text(),
	        	dv_no     : $('#dv_no option:selected').val(),
	        	dv_no_name: $('#dv_no option:selected').text(),
	        	po_id     : $('#po_id').val(),
	        	date      : $('#date').val(),
	        	remarks   : $('#remarks').val(),
	        	amount    : $('#amount').val(),
	        	supplier_id : $('#supplier_id').val(),
	        	Supplier : $('#supplier_name').val(),
	        	paymentTerm : $('#paymentTerm').val(),
	        };

	        if(typeof($post.dv_no) == 'undefined')
	        {
	        	alert('Please select DV NO.');
	        	return false;
	        }
	        
	        $.save({appendTo : 'body'});
	        xhr = $.post('<?php echo base_url().index_page();?>/accounting/save_voucher',$post,function(response){
	        	switch($.trim(response))
	        	{
	        		case "1":
	        			$.save({action : 'success',reload : 'false'});
	        			$('#save').addClass('disabled');
	        			$('#save').val('Already Saved');
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
		},save_changes:function(){
			if($('.required').required()){
				return false;
			}

			var bool = confirm('Are you Sure?');
			if(!bool){
				return false;
			}



			$post = {
				bank_id    : $('#bank_list option:selected').val(),
				bank_name  : $('#bank_list option:selected').text(),
				check_no   : $('#check_no').val(),
				check_date : $('#check_date').val(),
				journal_id : '<?php echo $journal_id; ?>',
			};
			
			$.post('<?php echo base_url().index_page();?>/accounting/save_changes',$post,function(response){

				switch($.trim(response)){
					case "1":
						alert('Successfully Updated');
						History.back();
						location.reload('true');
					break;
					default:

					break;
				}

			});			

		}
	};

	$(function(){		
		application.init();
	});

</script>