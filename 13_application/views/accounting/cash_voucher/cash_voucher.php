<input type="hidden" value="" id="cv_id">	
<div>
	<div class="row">
		<div class="col-md-2">
			<h4>Tag Transaction</h4>			
			
			<select name="" id="tag_transaction" class="form-control" style="width:190px">
					<option value="">-NONE-</option>
						<?php foreach($po_list as $row): ?>
					<option value="" data-po_id="<?php echo $row['po_id']; ?>" data-rr_id="<?php echo $row['receipt_id']; ?>"><?php echo "PO ".$row['reference_no']." - DR/SI ".$row['supplier_invoice']; ?></option>
				<?php endforeach; ?>					
			</select> 
			
			<div style="margin-top:5em">
				<h4>Payment Type</h4>
				
				<!-- <div class="radio">
					<label>
						<input type="radio" name="radio_type" value="cash" class="radio"> CASH
					</label>
				</div> -->

				<div class="radio">
					<label>
						<input type="radio" name="radio_type" value="check" class="radio" checked> CHECK 
					</label>
				</div>

				<div class="div-check" style="display:none">

					<div class="form-group">
						<label>Bank</label>
						<select name="" id="bank_id" class="form-control">
							<?php foreach($bank_setup as $row): ?>
							<option value="<?php echo $row['bank_id']; ?>"><?php echo $row['bank_name']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label>Check No</label>						
						<input type="text" id="check_no" class="form-control required">
					</div>
					<div class="form-group">
						<label>Due Date</label>
						<input type="text" id="due_date" class="date form-control">
					</div>

				</div>
				
			</div>
		</div>
		<div class="col-md-10">
					<div class="container">
						<h2 style="text-decoration:underline;">DISBURSEMENT VOUCHER</h2>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								 <label for="">Purpose</label>
				  				 <select name="" id="type" class="form-control">
										<?php foreach($type as $row): ?>
											<option value="<?php echo $row; ?>"><?php echo $row; ?></option>
										<?php endforeach; ?>
								 </select>
							</div>
							<div class="form-group">
								 <label for="">Pay To</label>
				  				 <input type="text" class="form-control full-text typeahead" id="pay_to" tabindex="1" style="width:300px">
							</div>
							<div class="form-group">
								 <label for="">Address</label>
				  				 <input type="text" class="form-control full-text uppercase" id="address" tabindex="2">
							</div>
							<div class="form-group">
								 <label for="">TIN</label>
				  				 <input type="text" class="form-control full-text uppercase" id="tin" tabindex="2">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="">Short Description</label>
								<input type="text" class="form-control full-text required uppercase" id="short_description">
							</div>
							<div class="form-group">
								 <label for="">Project</label>
								 <select name="" id="project_id" class="form-control">
								 	<?php foreach($projects as $row): ?>
								 		<option value="<?php echo $row['project_id']; ?>"><?php echo $row['project_full_name']; ?></option>
								 	<?php endforeach; ?>
								 </select>
							</div>
							<div class="form-group">
								 <label for="">Project Type</label>
								 <select name="" id="project_category" class="form-control">
								 	<option value="0">None</option>
								 	<?php foreach($project_category as $row): ?>
										<option value="<?php echo $row['id']; ?>"><?php echo $row['project_name']; ?></option>
								 	<?php endforeach; ?>
								 </select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								 <label for="">Voucher No.</label>
								 <div>
				  					<div class="" id="cv_no"><?php echo $dv_no; ?></div>
								 </div>
							</div>
							<div class="form-group">
								 <label for="">Date</label>
				  				 <input id="date" type="text" class="form-control col-md-7 col-xs-12 date-picker">
							</div>
							<div class="form-group">
								 <label for="">Remarks</label>
				  				 <input type="text" class="form-control" id="remarks">
							</div>
						</div>
					</div>
					
					<div class="panel panel-info">	
					<div class="panel-heading">PARTICULARS</div>						
						<table class="table">
							<thead>
								<tr>
									<th style="width:50px"></th>
									<th class="center border" style="800px"> P A R T I C U L A R</th>
									<th class="center border"> R E M A R K S</th>
									<th class="center border"> A M O U N T</th>
								</tr>				
							</thead>
							
							<tbody class="particular-contents">
								<tr>
									<td><span class="close padding-right pull-left remove">&times;</span></td>
									<td class="editable"></td>
									<td class="editable-remarks"></td>
									<td class="edit-amount"></td>
									<td class="item_no" style="display:none"></td>
									<td class="account_id" style="display:none"></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td></td>
									<td><a href="javascript:void(0)" class="add_items">add row</a></td>
									<td></td>
									<td></td>
								</tr>
							</tfoot>
							<tfoot>
								<tr >
									<td class="border" colspan="3">
										<span class="pull-right">TOTAL AMOUNT</span>
									</td>
									<td class="border total_amount total_particular" style="font-weight:bold;font-size:15px">0.00</td>
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
								<tr>
									<td><span class="close padding-right pull-left remove">&times;</span></td>
									<td class="editable_journal" data-account_id=""></td>
									<td class="editable-remarks"></td>
									<td class="editable_amount debit"></td>									
									<td class="editable_amount credit"></td>
								</tr>
								<tr>
									<td><span class="close padding-right pull-left remove">&times;</span></td>
									<td class="editable_journal" data-account_id="4" data-ledger="BANK">Cash In Bank - Local Currency, Savings Account</td>
									<td class="editable-remarks"></td>
									<td class="editable_amount debit"></td>									
									<td class="editable_amount credit"></td>
								</tr>
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
									<td class="border total_amount total_debit" style="font-weight:bold;font-size:15px">0.00</td>
									<td class="border total_amount total_credit" style="font-weight:bold;font-size:15px">0.00</td>
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
							<button class="btn btn-lg pull-right btn-primary" id="save">Save</button>
						</div>
					</div>
				</div>
		</div>		
	</div><!--/row-->
	
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
	var data = "";

	var xhr = "";

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

	var application ={
		init:function(){
			this.bindEvent();
			this.typeahead();			
			$('#date').date();
			$('.date').date();			
			this.init_editable();			
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

		     	$('.typeahead').on('typeahead:selected',function(event,data){

					$('#pay_to').val(this.value);
					$('#address').val('');
					$('#tin').val('');
					$('#address').val(response['address'][this.value]);
					$('#tin').val(response['tin'][this.value]);
				});
						   		
			},'json');
			
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

		},bindEvent:function(){

			$('.radio').on('change',function(){
				if($('.radio:checked').val()=='check'){
					$('.div-check').show();
				}else{
					$('.div-check').hide();
					$('#check_no').val('');
					$('#bank_id').prop('selectedIndex',0);
				}
			});

			$('.radio').trigger('change');

			$('#tag_transaction').chosen();
					
			$('#sales_invoice').on('change',this.invoice_change);
			$('#sales_invoice').trigger('change');			
			$('#save').on('click',this.save);

			/*$('.typeahead').on('change',function(){				
				$('#pay_to').val(this.value);
				alert('test');
			});*/
						
			$('#tag_transaction').on('change',function(){

				$post = {
					po_id : $('#tag_transaction option:selected').data('po_id'),
					rr_id : $('#tag_transaction option:selected').data('rr_id'),
				};

				$.post('<?php echo base_url().index_page();?>/accounting/tag_details',$post,function(response){
						
					$('#type').val('PAYMENT FOR');
					$('#pay_to').val(response.business_name);
					$('#address').val(response.address);
					$('#tin').val(response.tin_number);
					$('.particular-contents').find('.editable').html('PO '+ response.reference_no);
					$('.particular-contents').find('.edit-amount').html(comma(parseFloat(response.total).toFixed(2)));					
					compute_total();					
				},'json');

			});
					
			$('.add_items').on('click',function(){
				$div ='<tr>'
						+'<td><span class="close pull-left padding-right remove">&times;</span></td>'
						+'<td class="editable"></td>'
						+'<td class="editable-remarks"></td>'
						+'<td class="edit-amount"></td>'
						+'<td class="item_no" style="display:none"></td>'
						+'<td class="account_id" style="display:none"></td>'
					+'</tr>';
				$('.particular-contents').append($div);

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
				     placeholder : '0.00',
				     callback:function(){
						compute_total();
					 }
				});

				$('.editable-remarks').editable(function(value,settings){
						return value;
					},{
				     placeholder : '-',
				     callback:function(){
						
					 }
				});

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
				get_total
			});

			$('input[name="paymenttype"]').trigger('change');

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
				placeholder : '0.00',
				callback:function(){
					compute_total();
				}
			});

			$('.editable-remarks').editable(function(value,settings){
					return value;
				},{
			     placeholder : '-',
			     callback:function(){
					
				 }
			});
			
		},save:function(){

			if($('.required').required()){
				alert('Fill up Required Fields');
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


			if($('#cv_no').text() == ''){
				alert('Please Setup your voucher No.');
				return false;
			}
						
			if($('#project_id option:selected').val()==0){
				alert('Please Select PROJECT');
				return false;
			}


			if($('#pay_to').val() == ""){
				alert('Please Fill up PAY TO');
				return false;
			}


			if($('.total_amount').text() == 0 || $('.total_amount').text() == 'NaN' || $('.total_amount').text() == " "){
				alert('Invalid Total Amount');
				return false;
			}


			if(application.get_journal_details_length <= 1){
				alert('Invalid Journal Entry');
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
	        		remarks    : $(val).find('.editable-remarks').text(),
	        	};

				/*
	        	if(details.item_no == ''){
	        		alert('Please Select an Item');
	        		has_item = true;
	        	}
	        	*/
	        	
	        	if(details.amount == '-Enter Amount-'){
	        		alert('Please Input Valid Amount');
	        		has_item = true;
	        	}
	        	if(details.name == 'Enter Description'){
	        		alert('Please Enter Description');
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
	        	short_desc  : $('#short_description').val(),
	        	po_id : $('#tag_transaction option:selected').data('po_id'),
	        	rr_id : $('#tag_transaction option:selected').data('rr_id'),
	        	payment_type : $('.radio:checked').val(),
	        	bank_id  : $('#bank_id option:selected').val(),
	        	bank     : $('#bank_id option:selected').text(),
	        	check_no : $('#check_no').val(),
	        	due_date : $('#due_date').val(),
	        	journal_details : application.get_journal_details(),

	        };
	        
	        $.save({appendTo : 'body'});	       
	        xhr = $.post('<?php echo base_url().index_page();?>/accounting/save_cash_voucher',$post,function(response){

	        	switch($.trim(response))
	        	{	

	        		
	        		case "1":
	        			alert('Successfully Save');
	        			$('#save').addClass('disabled');
	        			$('#save').val('Successfully Saved');
	        			$.save({action : 'success',reload : 'false'});
	        			location.reload('true');	        			
	        		break;
	        		/*
	        		default:
	        			$.save({action : 'error',reload : 'false'});
	        		break;	        		
	        		*/
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
			            }else{
			                alert('Unknow Error.\n'+x.responseText);
			            }
					$.save({action : 'error',reload : 'false'});
			 });
			
	    

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
						var bank = {
							'type'          : '',
							'bank'          : '',
							'check_no'      : '',	
							'check_date'    : '',
						};
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
		},get_journal_details_length:function(){
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
			return data.length;
		}
	};


	var compute_total = function(){

		var total = 0;
		$('.edit-amount').each(function(i,val){
			total = total + parseFloat(remove_comma($(val).text()));
		});
		$('.total_particular').html(comma(total.toFixed(2)));
	}

	$(function(){		
		application.init();
	});
</script>