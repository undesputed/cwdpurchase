
<div>
		
	<div class="row">
		<div class="col-md-2">			

				<?php $check = "";$cash = ""; ?>
			<?php if(strtoupper($main['payment_type'])=='CHECK'){
					$check = "checked";
				}else{
					$cash = "checked";
				}
			?>
			
			
			<div style="margin-top:5em">
				<h4>Payment Type</h4>
				<div class="radio">
					<label>
						<input type="radio" name="radio_type" value="cash" class="radio" <?php echo $cash; ?>> CASH	
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="radio_type" value="check" class="radio" <?php echo $check; ?>> CHECK 
					</label>
				</div>

				<div class="div-check" style="display:none">

					<div class="form-group">
						<label>Bank</label>

						<select name="" id="bank_id" class="form-control">							
							<?php foreach($bank_setup as $row): ?>
							<?php $selected = ($main['bank_id']==$row['bank_id'])? "selected='selected'": "" ; ?>
							<option <?php echo $selected; ?> value="<?php echo $row['bank_id']; ?>"><?php echo $row['bank_name']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>					
					<div class="form-group">
						<label>Check No</label>						
						<input type="text" id="check_no" class="form-control" value="<?php echo $main['check_no']; ?>">
					</div>
					<div class="form-group">
						<label>Due Date</label>
						<input type="text" id="due_date" class="date form-control" value="<?php echo $main['check_date']; ?>">
					</div>
					
				</div>
										
			</div>
		</div>
		<div class="col-md-10">
			<div class="container">
		<h2 style="text-decoration:underline;">Journal Voucher Edit</h2>

	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				 <label for="">Purpose</label>
  				 <select name="" id="type" class="form-control">
						<?php foreach($type as $row): ?>
						    <?php $selected = ($main['type'] == $row)? 'selected="selected"' : ''; ?>
							<option <?php echo $selected; ?> value="<?php echo $row; ?>"><?php echo $row; ?></option>
						<?php endforeach; ?>
				 </select>
			</div>
				<!-- 	<div class="form-group">
					 <label for="">Pay To</label>
				  				 <input type="text" class="form-control full-text typeahead" id="pay_to" tabindex="1" style="width:300px" value="<?php echo $main['pay_to']; ?>">
				</div>
				<div class="form-group">
					 <label for="">Address</label>
				  				 <input type="text" class="form-control full-text uppercase" id="address" tabindex="2" value="<?php echo $main['address']; ?>">
				</div>
				<div class="form-group">
					 <label for="">TIN</label>
				  				 <input type="text" class="form-control full-text uppercase" id="tin" tabindex="2" value="<?php echo $main['tin']; ?>">
				</div> -->
		</div>
	
		<div class="col-md-4">
			<div class="form-group">
				<label for="">Short Description</label>
				<input type="text" class="form-control full-text required uppercase" id="short_description" value="<?php echo $main['short_desc']; ?>">
			</div>
			<div class="form-group">
				 <label for="">Project</label>
				 <select name="" id="project_id" class="form-control">
				 	<?php foreach($projects as $row): ?>
				 		<?php $selected = ($main['project_id'] == $row['project_id'])? 'selected="selected"' : ''; ?>
						<option <?php echo $selected ?> value="<?php echo $row['project_id']; ?>"><?php echo $row['project_full_name']; ?></option>
				 	<?php endforeach; ?>
				 </select>
			</div>
			<div class="form-group">
				 <label for="">Project Type</label>
				 <select name="" id="project_category" class="form-control">
				 	<option value="0">None</option>
				 	<?php foreach($project_category as $row): ?>
				 	<?php $selected = ($main['project_type'] == $row['id'])? 'selected="selected"' : ''; ?>
						<option <?php echo $selected; ?> value="<?php echo $row['id']; ?>"><?php echo $row['project_name']; ?></option>
				 	<?php endforeach; ?>
				 </select>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				 <label for="">Voucher No.</label>
				 <div>
  					<div class="" id="cv_no"><?php echo $main['voucher_no']; ?></div>
				 </div>
			</div>
			<div class="form-group">
				 <label for="">Voucher Date</label>
  				 <input id="date" type="text" class="form-control full-text required" tabindex="3" value="">
			</div>
			<div class="form-group">
				 <label for="">Remarks</label>
  				 <input type="text" class="form-control" id="remarks" value="<?php echo $main['remarks']; ?>">
			</div>
		</div>
	</div>
	
	<div class="panel panel-info">	
	<div class="panel-heading">PARTICULARS</div>	
		<table class="table">	
			<thead>
				<tr>
					<td style="width:50px"></td>
					<td class="center border" style="800px"> P A R T I C U L A R</td>
					<td class="center border"> A M O U N T</td>
				</tr>				
			</thead>
			<tbody class="particular-contents">
				<?php foreach($details as $row): ?>
				<tr>
					<td><span class="close padding-right pull-left remove">&times;</span></td>
					<td class="editable"><?php echo $row['item_description'] ?></td>					
					<td class="edit-amount"><?php echo $row['amount']; ?></td>
					<td class="item_no" style="display:none"><?php echo $row['item_no']; ?></td>
					<td class="account_id" style="display:none"><?php echo $row['account_code']; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td><a href="javascript:void(0)" class="add_items">add row</a></td>
					<td></td>
				</tr>
			</tfoot>
			<tfoot>
				<tr>
					<td class="border" colspan="2">
						<span class="pull-right">TOTAL AMOUNT</span>
					</td>
					<td class="border total_amount total_particular" style="font-weight:bold;">0.00</td>
				</tr>
			</tfoot>
		</table>
	</div>
	
	<div class="panel panel-warning">	
					<div class="panel-heading">JOURNAL ENTRIES</div>						
						<table class="table">
							<thead>
								<tr>
									<th style="width:50px"></th>
									<th class="center border" style="800px">ACCOUNT TITLE</th>
									<th class="center border"> R E M A R K S</th>
									<th class="center border"> DEBIT</th>
									<th class="center border"> CREDIT</th>
								</tr>				
							</thead>
						
							<tbody class="journal_contents">
								<?php 
									$total_debit  = 0;
									$total_credit = 0;
								 ?>
								<?php foreach($journal as $row): ?>
									<tr>
										<td><span class="close padding-right pull-left remove">&times;</span></td>
										<td class="editable_journal" data-account_id="<?php echo $row['account_id']; ?>" data-ledger='<?php echo $row['ledger']; ?>'><?php echo $row['account_description']; ?></td>
										<td class="editable-remarks"><?php echo $row['memorandum']; ?></td>
										<?php 
											$debit  = 0;
											$credit = 0;

											if($row['dr_cr'] == 'DEBIT'){
												$debit  = $row['amount'];
												$credit = "";
												$total_debit += $row['amount'];
											}else{
												$credit = $row['amount'];
												$debit  = "";												
												$total_credit += $row['amount'];
											}
										?>
										<td class="editable_amount debit"><?php  echo $debit; ?></td>
										<td class="editable_amount credit"><?php echo $credit; ?></td>

									</tr>
								<?php endforeach; ?>
							</tbody>

							<tfoot>
								<tr>
									<td></td>
									<td><a href="javascript:void(0)" class="add_row_journal">add row</a></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tfoot>
							<tfoot>
								<tr >
									<td class="border" colspan="3">
										<span class="pull-right">TOTAL AMOUNT</span>
									</td>
									<td class="border total_amount total_debit"  style="font-weight:bold;font-size:15px"><?php echo number_format($total_debit,2); ?></td>
									<td class="border total_amount total_credit" style="font-weight:bold;font-size:15px"><?php echo number_format($total_credit,2); ?></td>
								</tr>
							</tfoot>
						</table>
	</div>

	<div class="row">
		<div class="col-md-4">
				<label for="">Prepared By</label>
				<select name="" id="prepared_by" class="form-control">
						<?php foreach($signatory['prepared_by'] as $row): ?>
							<option value="<?php echo $row['emp_number']; ?>"><?php echo $row['Person Name'];?></option>
						<?php endforeach; ?>
				</select>
		</div>
		<div class="col-md-8">
			<button class="btn btn-lg pull-right btn-primary" id="save">Update</button>
		</div>
	</div>	
</div>
		</div>
	</div>
	
</div>

<script>
	$.editable.addInputType('select2',{
		        element : function(settings, original){
                    var select = $('<select />').addClass('select2');
                    $(this).append(select);                  
                    return(select);
                },
                content : function(data, settings, original) {
                	

                    /* If it is string assume it is json. */
                    if (String == data.constructor){
                        eval ('var json = ' + data);
                    } else {
                    /* Otherwise assume it is a hash already. */
                        var json = data;
                    }
                    for (var key in json) {
                        if (!json.hasOwnProperty(key)) {
                            continue;
                        }
                        if ('selected' == key) {
                            continue;
                        }

                        if(json[key].hasOwnProperty('account_description')){
                          var option = $('<option />').val(json[key]['account_description']).append(json[key]['account_description']);
                          if(json[key].hasOwnProperty('attr'))
                          {
                            option.attr(json[key].attr);
                          }
                        }else{
                          var option = $('<option />').val(key).append(json[key]);
                        }                        
                        $('select', this).append(option);                        
                    }
                    /* Loop option again to set selected. IE needed this... */ 
                    $('select', this).children().each(function(){
                        if ($(this).val() == json['selected'] || 
                            $(this).text() == $.trim(original.revert)) {
                                $(this).attr('selected', 'selected');
                        }
                    });

                    /* Submit on change if no submit button defined. */
                     $('select', this).chosen({ width: "300px",search_contains:true});
						                   		
                    if (!settings.submit) {
                        var form = this;
                        $('select', this).change(function() {
                            form.submit();                            
                        });
                    }                    
                }
	});


	var item_content = "";
	var data         = "";


	var get_total = function(){

		var total_debit  = 0;
		var total_credit = 0;
		$('.journal_contents tr').each(function(i,val){
				var debit  = remove_comma($(val).find('.debit').text());
				var credit = remove_comma($(val).find('.credit').text());
				total_debit  = +total_debit + +debit;
				total_credit = +total_credit + +credit;
		});

		$('.total_debit').html(comma(total_debit.toFixed(2)));
		$('.total_credit').html(comma(total_credit.toFixed(2)));

	}

	
	var xhr = "";
	var application ={
		init:function(){
			this.bindEvent();
			this.typeahead();
			$('#date').date({
				date : '<?php echo $main['voucher_date'] ?>'
			});

			this.init_editable();
		},init_editable:function(){

				$('.editable_journal').editable(function(value,settings,data){					
						var id = $(data).find(':selected').attr('data-account_id');
						$(this).attr('data-account_id',id);

						var ledger = $(data).find(':selected').attr('data-ledger');
						$(this).attr('data-ledger',ledger);						
						return value;

					},{
					 loadurl  : '<?php echo base_url().index_page(); ?>/accounting/get_account_title',					 
				     placeholder : 'Select Account title',
				     type        : "select2",
				     submit      : 'ok',
				});

				$('.editable_amount').editable_td({
					addClass: "numbers_only comma",
					callback:function(){										
						get_total();				
					}
				});

				$('.editable-remarks').editable(function(value,settings){
						return value;
					},{
				     placeholder : '-',
				     callback:function(){
						
					 }
				});

		},typeahead:function(){

			var a = '';
			$.get('<?php echo site_url('accounting/get_supplier'); ?>',function(response){				
			   	a = response.text;				
				 var states = new Bloodhound({
			        datumTokenizer: Bloodhound.tokenizers.whitespace,
			        queryTokenizer: Bloodhound.tokenizers.whitespace,
			      	local : a,
			      });
			      states.initialize();
			     $('.typeahead').typeahead({
			        highlight: true,				        
			        minLength: 0,
			      },
			      {
			        source: states
			    });

		     	$('.typeahead').on('typeahead:selected',function(){
					$('#pay_to').val(this.value);
					$('#address').val('');
					$('#address').val(response['address'][this.value]);
				});

			},'json');
					   	
		},bindEvent:function(){

			$('.radio').on('change',function(){
				if($('.radio:checked').val()=='check'){
					$('.div-check').show();
				}else{
					$('.div-check').hide();
					$('#check_no').val('');					
				}
			});

			$('.radio').trigger('change');
			
			$('#tag_transaction').chosen();
			$('#tag_transaction').trigger('chosen:updated');

			$('#sales_invoice').on('change',this.invoice_change);
			$('#sales_invoice').trigger('change');			
			$('#save').on('click',this.save);

			$('.add_items').on('click',function(){
				$div ='<tr>'
						+'<td ><span class="close pull-left remove">&times;</span><span class="padding-left editable"></span> </td>'
						+'<td class="edit-amount"></td>'
						+'<td class="item_no" style="display:none"></td>'
						+'<td class="account_id" style="display:none"></td>'
					+'</tr>';
				$('.particular-contents').append($div);

			/*	$('.editable').editable(function(value,settings){
						$(this).html(item_content[value].item_name);						
				     	$(this).closest('tr').find('.item_no').html(value);			     
			     		$(this).closest('tr').find('.account_id').html(item_content[value].account_id);
					},{
				     data    :  data,
				     type    : 'select',
				     submit  : 'OK',
				     style   : 'inherit',
				     placeholder : 'Click to Select'
				});
			*/


				$('.editable').editable(function(value,settings){
							return value;
						},{
					     placeholder : 'Enter Description',
					     callback:function(){
							
						 }
				});

				$('.edit-amount').editable(function(value,settings){
						return comma(value);
					},{
				     placeholder : '-Enter Amount-',
				     callback:function(){
						compute_total();
					 }
				});

			});

			$('body').on('click','.remove',function(response){
				$(this).closest('li').remove();
			});

			$('input[name="paymenttype"]').on('change',function(){
				if($('input[name="paymenttype"]:checked').val() == 'cash'){
					$('.check-group').prop('disabled',true);
					$('.check-group').val('');
				}else{
					$('.check-group').prop('disabled',false);
				}
			});

			$('body').on('click','.remove',function(){
				$(this).closest('tr').remove();
				get_total();
			});

			$('input[name="paymenttype"]').trigger('change');		


			/*$('.editable').editable(function(value,settings){
					$(this).html(item_content[value].item_name);
					$(this).closest('tr').find('.item_no').html(value);
			     	$(this).closest('tr').find('.account_id').html(item_content[value].account_id);
				},{
			     data    :  data,
			     type    : 'select',
			     submit  : 'OK',
			     style   : 'inherit',
			     placeholder : 'Click to Select'
			});
			*/

			$('.editable').editable(function(value,settings){
							return value;
						},{
					     placeholder : 'Enter Description',
					     callback:function(){
							
						 }
			});

			$('.edit-amount').editable(function(value,settings){
						return comma(value);
					},{
				placeholder : '-Enter Amount-',
				callback:function(){
					compute_total();
				}
			});

			$('.add_row_journal').on('click',function(){

				$div = 	'<tr>' 
						+'<td><span class="close padding-right pull-left remove">&times;</span></td>'
						+'<td class="editable_journal" data-account_id=""></td>'
						+'<td class="editable-remarks"></td>'
						+'<td class="editable_amount debit"></td>'
						+'<td class="editable_amount credit"></td>'
						+'</tr>';
				$('.journal_contents').append($div);

				application.init_editable();

			});

			/*
			$('.project-editable').editable(function(value,settings){
					$('#project_id').val(value);
					return project_content[value].project_full_name;
				},{
			     data    :  project,
			     type    : 'select',
			     submit  : 'OK',
			     style   : 'inherit',
			     placeholder : 'Click to Select Project'
			});
			*/
			
		},save:function(){

			if($('.required').required()){
				return false;
			}


			var check_journal = false;

			$('.journal_contents tr').each(function(i,val){				
				if($(val).find('.editable_journal').data('account_id') > 0){
					check_journal = false;
				}else{
					check_journal = true;
				}
			});

			if(check_journal){
				alert('Please select account Title');
				return false;
			}
			
			var total_debit  = $('.total_debit').html();
			var total_credit = $('.total_credit').html();
			var total_particular = $('.total_particular').html();


			if(total_debit != total_credit ){
				alert('DEBIT AND CREDIT IS NOT EQUAL');
				return false;
			}

			if(total_particular != total_debit){
				alert('PARTICULARS and JOUNRAL ENTRIES Total Not Equal');
				return false;
			}


			if($('#pay_to').val() == ""){
				alert('Please Fill up PAY TO');
				return false;
			}


			if($('#cv_no').text() == ''){
				alert('Please Setup your voucher No.');
				return false;
			}

			
			if($('#project_id option:selected').val()==0){
				alert('Please Select PROJECT');
				return false;
			}

			if($('.total_amount').text() == 0 || $('.total_amount').text() == 'NaN' || $('.total_amount').text() == " "){
				alert('Invalid Total Amount');
				return false;
			}
			

	        if(xhr && xhr.readystate != 4){
	            xhr.abort();
	        }

	        var details_content = new Array();
	        var has_item = false;	
	        $('.particular-contents tr').each(function(i,val){
	        	
	        	details = {
	        		name       : $(val).find('.editable').text(),
	        		amount     : remove_comma($(val).find('.edit-amount').text()),
	        		item_no    : $(val).find('.item_no').text(),
	        		account_id : $(val).find('.account_id').text(),
	        	};

	        	/*	if(details.item_no == ''){
	        		alert('Please Select an Item');
	        		has_item = true;
	        	}*/
	        	if(details.amount == '-Enter Amount-'){
	        		alert('Please Input Valid Amount');
	        		has_item = true;
	        	}
	        	details_content.push(details);       	
	        });

	        if(has_item){
	        	return false;
	        }

	        var bool = confirm('Are you sure to Proceed?');

	        if(!bool){
	        	return false;
	        }

	        $post = {
	        	pay_to     : $('#pay_to').val(),
	        	address    : $('#address').val(),
	        	tin        : $('#tin').val(),
	        	voucher_no : $('#cv_no').text(),
	        	voucher_id : $('#cv_id').val(),
	        	date       : $('#date').val(),
	        	project_id : $('#project_id option:selected').val(),
	        	total_amount : remove_comma($('.total_particular').text()),
	        	details     : details_content,
	        	prepared_by : $('#prepared_by option:selected').val(),
	        	checked_by  : $('#checked_by option:selected').val(),
	        	approved_by : $('#approved_by option:selected').val(),
	        	remarks     : $('#remarks').val(),
	        	type        : $('#type option:selected').val(),
	        	project_category : $('#project_category option:selected').val(),
	        	cash_main_id : '<?php echo $main['cash_voucher_id']; ?>',
	        	short_desc  : $('#short_description').val(),
	        	po_id : $('#tag_transaction option:selected').data('po_id'),
	        	rr_id : $('#tag_transaction option:selected').data('rr_id'),
	        	payment_type : $('.radio:checked').val(),
	        	bank_id  : $('#bank_id option:selected').val(),
	        	bank  : $('#bank_id option:selected').text(),
	        	check_no : $('#check_no').val(),
	        	due_date : $('#due_date').val(),
	        	journal_details : application.get_journal_details(),
	        };

				        
	        $.save({appendTo : 'body'});


	        xhr = $.post('<?php echo base_url().index_page();?>/accounting/update_cash_voucher',$post,function(response){

	        	switch($.trim(response))
	        	{
	        		case "1":
	        			alert('Successfully Save');
	        			$('#save').addClass('disabled');
	        			$('#save').val('Successfully Saved');
	        			$.save({action : 'success',reload : 'false'});
	        			location.reload('true');	        			
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

	        /*
	        $post = {
	        	rr_id     : $('#sales_invoice option:selected').attr('data-rr_id'),
	        	bank_id   : $('#bank_list option:selected').val(),
	        	bank_name : $('#bank_list option:selected').text(),
	        	dv_no     : $('#dv_no option:selected').val(),
	        	dv_no_name: $('#dv_no option:selected').text(),
	        	po_id     : $('#po_id').val(),
	        	date      : $('#date').val(),
	        	type      : $('#type').val(),
	        	amount    : $('#amount').val(),
	        	supplier_id : $('#supplier_id').val(),
	        	Supplier    : $('#supplier_name').val(),
	        	paymentTerm : $('#paymentTerm').val(),
	        	remarks     : $('#remarks').val(),	   
	        	check_no    : $('#check_no').val(),     	
	        };

	        if(typeof($post.dv_no) == 'undefined')
	        {
	        	alert('Please select DV NO.');
	        	return false;
	        }
			
	       if($('input[name="paymenttype"]:checked').val()=='check')
	       {
	       		if($('#check_no').val()==''){
	       			alert('Please Input Check Number');
	        		return false;
	       		}
	        			      
	       }
	        
	        var bool = confirm('Are you sure?');
			if(!bool){
				return false;
			}
			*/
	      /*  $.save({appendTo : 'body'});
	        xhr = $.post('<?php echo base_url().index_page();?>/accounting/save_cash_voucher',$post,function(response){
	        	switch($.trim(response))
	        	{
	        		case "1":
	        			alert('Successfully Save');	        			
	        			$('#save').addClass('disabled');
	        			$('#save').val('Successfully Saved');
	        			$.save({action : 'success',reload : 'false'});
	        			window.location="<?php echo base_url().index_page();?>/accounting/voucher";
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
				});*/

		},invoice_change:function(){
			$get = {
				invoice_no : $('#sales_invoice option:selected').val(),
			}
			$('#invoice_items').html('Loading...');
			$.get('<?php echo base_url().index_page(); ?>/accounting/get_invoice_item',$get,function(response){
				$('#invoice_items').html(response);
			});			
		},get_journal_details:function(){

			var data = new Array();
			$('.journal_contents tr').each(function(i,val){

				if($(val).find('.editable_journal').data('account_id') > 0)
				{
					var ledger = $(val).find('.editable_journal').data('ledger');
					if( ledger != "" )
					{	
						switch(ledger){
							case "BANK":
								var bank = {
									'type'          : $('input[name="radio_type"]:checked').val(),
									'bank'          : $('#bank_id option:selected').text(),
									'check_no'      : $('#check_no').val(),	
									'check_date'    : $('#due_date').val(),
								};
							break;
						}
						
					}else{
						var bank = {
							'type'          : '',
							'bank'          : '',
							'check_no'      : '',	
							'check_date'    : '',
						};
					}

					var details = {
						'account_title' : $(val).find('.editable_journal').text(),
						'account_id'    : $(val).find('.editable_journal').data('account_id'),
						'remarks'       : $(val).find('.editable-remarks').text(),
						'debit'         : $(val).find('.debit').text(),
						'credit'        : $(val).find('.credit').text(),
						'type'          : bank.type,
						'bank'          : bank.bank,
						'check_no'      : bank.check_no,
						'check_date'    : bank.check_date,
					}

					data.push(details);	
				}			
			});			
			return JSON.stringify(data);
		}
}

	var compute_total = function(){

		var total = 0;
		$('.edit-amount').each(function(i,val){
			total = total + parseFloat(remove_comma($(val).text()));
		});
		$('.total_particular').html(comma(total.toFixed(2)));

	}

	$(function(){
		application.init();
		compute_total();
	});
</script>