<style>
	.tbl-journal tbody tr:hover{
		cursor: pointer;	
	}
	.tbl-journal tbody tr:first-child >td.remove >.remove{
		display:none;	
	}
</style>

<div class="header">
	<h2>Journal Entry</h2>
</div>

<input type="hidden" id="journal_id" value="<?php echo $main['journal_id']; ?>">

<?php 
	$transaction_type = array(
			array('title'=>'Adjustment Entry','short'=>'AE'),
			array('title'=>'Bank Recon Entry','short'=>'BRE'),
			array('title'=>'Beginning Balance','short'=>'BB'),
			array('title'=>'Cash Count Entry','short'=>'CCE'),
			array('title'=>'Closing Entry','short'=>'CE'),
			array('title'=>'Enter Advances','short'=>'EA'),
			array('title'=>'Enter Invoice','short'=>'EI'),
			array('title'=>'Enter Payable','short'=>'EP'),
			array('title'=>'Enter Receipt','short'=>'ER'),
			array('title'=>'Inventory Entry','short'=>'IE'),
			array('title'=>'Journal Entry','short'=>'JE'),
			array('title'=>'Pay Advances','short'=>'PA'),
			array('title'=>'Pay Payable','short'=>'PP'),
			array('title'=>'Petty Cash Entry','short'=>'PCE'),
			array('title'=>'Received Payment','short'=>'RP'),
		);
 ?>

<div class="container">

	<select name="" id="temp_accountDescription" style="display:none">
		<option value=""></option>
		<?php foreach($accountDescription as $row): ?>
			<option value="<?php echo $row['account_id']; ?>" data-accountCode="<?php echo $row['account_code']; ?>"><?php echo $row['account_description']; ?></option>
		<?php endforeach; ?>
	</select>
	
	<select name="" id="temp_subAccount" style="display:none">		
	</select>
	
	<select name="" id="temp_bank_accountNo" style="display:none">		
	</select>

	<select name="" id="temp_cv_no" style="display:none">		
	</select>

	<select name="" id="temp_bankaccount" style="display:none">		
	</select>

	<select name="" id="temp_checkno" style="display:none">		
	</select>



	<div class="row">				
		<div class="col-md-12">
												  	
					<div class="row">										
						<div class="col-md-6">	
						<div class="content-title">
								<h3>Journal Entry</h3>
						</div>
						<div class="panel panel-default">		
						  <div class="panel-body">						
						  		<div class="form-group">
						  			<div class="control-label">Projects</div>
						  			<select name="" id="project_" class="form-control input-sm" style="display:none"></select>
						  			<select name="" id="profit_center_" class="form-control input-sm"></select>
						  		</div>			
									<div class="row">
										<div class="col-md-5">
											<div class="form-group">
									  			<div class="control-label">Pay Center</div>
									  			<select name="" id="pay_center" class="form-control input-sm">
									  				<?php foreach($payCenter as $row): ?>
									  				<?php $selected = ($main['pay_center']==$row['title'])? "selected='selected'":""; ?>
									  					<option  <?php echo $selected ?> value="<?php echo $row['id']; ?>"><?php echo $row['paycenter']; ?></option>
									  				<?php endforeach; ?>
									  			</select>
									  		</div>
										</div>

										<div class="col-md-7">
											<div class="form-group">
									  			<div class="control-label">Pay Item</div>
									  			<select name="" id="pay_item" class="form-control input-sm">
									  				<?php foreach($payItem as $row): ?>
									  					<?php $selected = ($main['pay_item']==$row['title'])? "selected='selected'":""; ?>
									  					<option <?php echo $selected ?> value="<?php echo $row['id'] ?>"><?php echo $row['itemdescription']; ?></option>
									  				<?php endforeach; ?>
									  			</select>
									  		</div>
										</div>
									</div>
								</div>
						  </div>	
	
						</div>
						<div class="col-md-6">
							<div class="content-title">
								<h3>Transaction Type</h3>
							</div>
							<div class="panel panel-default">		
						 	<div class="panel-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
									  			<div class="control-label">Transaction Type</div>
									  			<select name="" id="transaction_type" class="form-control input-sm">									  				
									  				<?php foreach($transaction_type as $row): ?>
															<?php $selected = ($main['type']==$row['title'])? "selected='selected'":""; ?>
									  						<option <?php echo $selected; ?> value="<?php echo $row['short']; ?>"><?php echo $row['title']; ?></option>
									  				<?php endforeach; ?>									  				
									  			</select>
								  			</div>
								  			<div class="row">
								  				<div class="col-md-6">
								  					<div class="form-group">
											  			<div class="control-label">Reference No</div>
											  			<input type="text" name="" id="reference_no" class="form-control input-sm" value="<?php echo $main['reference_no'] ?>">
								  					</div>
								  				</div>
								  				<div class="col-md-6">
								  					<div class="form-group">
											  			<div class="control-label">Transaction Date</div>
											  			<input type="text" name="" id="date" class="form-control input-sm" value="<?php echo $main['trans_date']; ?>">
								  					</div>
								  				</div>
								  			</div>								  			
										</div>
										<div class="col-md-6">
											<div class="form-group">
									  			<div class="control-label">Memo</div>
									  			<textarea name="" id="memo" cols="30" rows="3" class="form-control"><?php echo $main['memo']; ?></textarea>
								  			</div>
										</div>
									</div>
						 					

						  	</div>
									  		
							</div>
						</div>				
			</div>

			<div class="row entry">
				<div class="col-md-12">
					<div class="panel panel-default">		
					  <div class="panel-body">
					  		<div class="row">

					  			<div class="col-md-1">
					  				<div class="radio">
					  					<input type="radio" name="pay_to" id="person" value="person"><label for="person">Person</label>
					  				</div>	
									
					  				<div class="radio">
					  					<input type="radio" name="pay_to" id="business" checked value="business"><label for="business">Business</label>
					  				</div>
					  			</div>

					  			<div class="col-md-3">
					  				<div class="form-group">
						  				<div class="control-label entry-label">Pay To</div>
						  				<select name="" id="pay_to" class="form-control input-sm"></select>
						  			</div>									
					  			</div>

					  			<div class="col-md-3">
									<div class="radio">
					  					<input type="radio" name="pay_type" id="check"  checked><label for="check">Check</label>
					  				</div>
					  				<div class="radio">
					  					<input type="radio" name="pay_type" id="cash"><label for="cash">Cash</label>
					  				</div>
					  			</div>
					  		</div>					  		
					  </div>	 
					</div>
				</div>
			</div>


			<div class="row">
			

				<div class="col-md-12">
					<div class="content-title">
							<h3>Entries</h3>
					</div>
					<div class="pull-right" style="margin-right:1em;margin-top:1em;">
						<div class="checkbox">
							<input type="checkbox" id="show_bank" ><label for="show_bank" class="text-muted" >Show Bank </label>
						</div>
					</div>
					<div class="panel panel-default">							
					  <table class="table table-striped table-hover tbl-journal tbl-event">
					  		<thead>
					  			<tr>
					  				<th style="display:none">Account_id</th>
					  				<th>Account Description</th>
					  				<th>Account Type</th>
					  				<th>Sub-Account</th>					  				
					  				<th class="bank_info">Bank Account</th>
					  				<th class="bank_info">Bank Account No</th>
					  				<th class="bank_info">CV. No</th>
					  				<th class="bank_info">Check No.</th>
					  				<th class="bank_info">Check Date</th>
					  				<th>DEBIT</th>
					  				<th>CREDIT</th>
					  				<th></th>
					  			</tr>
					  		</thead>
					  		<tbody>
					  			<?php foreach($detail as $row) : ?>
					  			<?php 
					  				$data['debit'] = "";
					  				$data['credit'] = "";
					  				if($row['CR/DR']=="DEBIT"){
					  					$data['debit'] = $row['Amount'];
					  				}else{
					  					$data['credit'] = $row['Amount'];
					  				}
									
					  			 ?>
					  			<tr>
					  				<td class="account_id" style="display:none"><?php echo $row['account_id']; ?></td>
					  				<td class="editable-account"><?php echo $row['Account']; ?></td>
					  				<td class="editable-accountType"><?php echo $row['subsidiary_type']; ?></td>
					  				<td class="editable-subaccount"><?php echo $row['subsidiary']; ?></td>						  				
					  				<td class="bank_info editable-bankaccount"><?php echo $row['bank_account_name']; ?></td>
					  				<td class="bank_info editable-bankaccountnum">-</td>
					  				<td class="bank_info editable-cvno"><?php echo $row['cv_no']; ?></td>
					  				<td class="bank_info editable-checkno">-</td>
					  				<td class="bank_info editable-checkdate"><?php echo $row['check_date']; ?></td>
					  				<td class="editable-amount debit"><?php  echo $data['debit']; ?></td>
					  				<td class="editable-amount credit"><?php echo $data['credit']; ?></td>
					  				<td class="remove"><span class="event close remove">&times;</span></td>
					  			</tr>
					  		<?php endforeach; ?>
					  		</tbody>
					  		<tfoot>
					  			<tr>
					  				<td style="display:none"></td>
					  				<td><strong>Total</strong></td>
					  				<td></td>
					  				<td></td>					  				
					  				<td class="bank_info"></td>
					  				<td class="bank_info"></td>
					  				<td class="bank_info"></td>
					  				<td class="bank_info"></td>
					  				<td class="bank_info"></td>
					  				<td class="total_debit sub-total">0.00</td>
					  				<td class="total_credit sub-total">0.00</td>
					  				<td></td>
					  			</tr>
					  		</tfoot>
					  </table>
					  <div class="form-footer">
								<div class="row">
									<div class="col-md-8"> </div>									
									<div class="col-md-4">
										<input id="save" class="disabled btn btn-success col-xs-5 pull-right " type="submit" value="Update">								
									</div>
								 </div>
					   </div>
					</div>
				</div>

			</div>
			
		</div>
	
	</div>
		
</div>

<script>

	var app ={
		init:function(){

			$('#date').date();
			this.show_bank();

			var option = {
				profit_center : $('#profit_center_'),
				call_back     : function(){
					app.bindEvent();
					app.get_payTo();
					app.entry();
					get_total();
				},
				pc_id       : "<?php echo $main['location'] ?>",
			}

			$('#project_').get_projects(option);

		},bindEvent:function(){
			
			$('#save').on('click',this.save);			
			$('#show_bank').on('click',this.show_bank);		
			$('#transaction_type').on('change',this.get_journal);	
			$('body').on('click','.remove',this.remove);
			$('input[name="pay_to"]').on('change',this.get_payTo);
			$('#transaction_type').on('change',this.entry);

		},entry:function(){

			var transaction =  $('#transaction_type option:selected').text();

			
			$('.entry-label').html('Pay To');
			switch(transaction){

				case "Enter Advances":
					$('.entry').removeClass('hidden');
				break;	
				case "Enter Invoice":
					$('.entry-label').html('Customer');
					$('.entry').removeClass('hidden');
				break;	
				case "Enter Payable":
					$('.entry').removeClass('hidden');
				break;
				case "Enter Payment":
					$('.entry').removeClass('hidden');
				break;
				case "Enter Receipt":			
					$('.entry').removeClass('hidden');
				break;
				case "Pay Payable":
					$('.entry').removeClass('hidden');
				break;
				case "Received Payment":
					$('.entry').removeClass('hidden');
				break;
				default :
					$('.entry').addClass('hidden');
				break;

			}
			
		},remove:function(){
			var confirmation = confirm('Are you sure to Remove?');
			if(confirmation){
				$(this).closest('tr').remove();	
			}
			return false;
			
		},show_bank:function(){

			if($("#show_bank").is(':checked')==true){
				$('.bank_info').removeClass('hidden');
			}else{
				$('.bank_info').addClass('hidden');
			}

		},chk_all:function(){

			var checked = $(this).is(':checked');
			var rows = $(".myTable").dataTable().fnGetNodes();
			var length = rows.length;

			for(var i=0; i<length; i++){					
				$(rows[i]).find('.chk-post').prop('checked',checked);
		    }
			
		},save:function(){

			var confirmation = confirm('Are you sure to Proceed?');
			if(!confirmation){
				return false;
			}

			$.save({appendTo : '.fancybox-outer'});

			var cussup;
			var cussup_type;
			if($('.entry.hidden').length > 0){
				cussup      = $('#pay_to option:selected').val();
				cussup_type = $('input[name="pay_to"]:checked').val();

			}else{
				cussup      = 0;
				cussup_type = 0;
			}

			var trans_type = $('#transaction_type option:selected').text();
			$post = {
				ref_no :$('#reference_no').val(), 
				date   :$('#date').val(),
				transaction_type :$('#transaction_type option:selected').text(),
				memo   :$('#memo').val(),
				status :'ACTIVE',
				location : $('#profit_center option:selected').val(),
				trans_type : trans_type.toUpperCase(),
				paycenter : $('#pay_center option:selected').val(),
				payitem : $('#pay_item option:selected').val(),
				typesup :'PERSON',
				cussup  : cussup,
				cussup_type : cussup_type,
				dueday  : '0',
				details : app.get_details(),
				journal_id : $('#journal_id').val(),
			}
			
			$.post('<?php echo base_url().index_page();?>/accounting/journal_entry/update',$post,function(response){
				switch(response){
					case"1":						
						
						$.save({action : 'success',reload : 'true'});
					break;
					default:						
						$.save({action : 'hide'});
					break;
				}				
			});


		},get_journal:function(){
			$post = {
				date : $('#date').val(),
				type : $('#transaction_type option:selected').val(),
			};

			$.post('<?php echo base_url().index_page();?>/ajax/get_journalEntry',$post,function(response){
				$('#reference_no').val(response);
			});
		},get_details:function(){
			var details = new Array();
			$('.tbl-journal tbody tr').each(function(i,val){
				if($(val).find('td.account_id').text()!=""){
				
					$details = {
						account        : $(val).find('td.editable-account').text(),
						accountType    : $(val).find('td.editable-accountType').text(),
						subAccount     : $(val).find('td.editable-subaccount').text(),										
						bankAccount    : $(val).find('td.editable-bankaccount').text(),
						bankAccountNo  : $(val).find('td.editable-bankaccountnum').text(),
						cvNo           : $(val).find('td.editable-cvno').text(),
						checkNo        : $(val).find('td.editable-checkno').text(),
						checkDate      : $(val).find('td.editable-checkdate').text(),
						balance        : '',
						account_id     : $(val).find('td.account_id').text(),
						supplierType   : '',
						subAccountId   : '',
						debit          : $(val).find('td.debit').text(),
						credit         : $(val).find('td.credit').text(),
					};
					details.push($details);
				}

			});

			return details;
		},get_payTo:function(){

			$post = {
				type : $('input[name="pay_to"]:checked').val(),
			};

			$.post('<?php echo base_url().index_page(); ?>/accounting/journal_entry/get_payTo',$post,function(response){
					
					$('#pay_to').select({
						json : response,
						attr : {							
							 text  : 'Supplier Name',
							 value : 'Supplier ID',
						}
					});

			},'json');

		}

	};

var get_total  = function(){
	var total_debit  = 0;
	var total_credit = 0;
	var has_account = false;

	$(".tbl-journal tbody tr").each(function(tr,value){

		var required = {
			account_id : ($(value).find("td.account_id").text()!="" && $(value).find("td.account_id").text()!="-"),
			debit      : ($(value).find("td.debit").text()!="" &&  $(value).find("td.debit").text()!="-"),
			credit     : ($(value).find("td.credit").text()!="" && $(value).find("td.credit").text()!="-"),
		}	

		if(required.account_id == true && (required.debit === true || required.credit === true )){
			has_account = true;
		}else if( required.account_id == false && required.debit === false && required.credit === false ){
			has_account = true;
		}else{
			has_account = false;
		}

		($(value).find("td.debit").text()!="-" || $(value).find("td.credit").text()!="-")

		if($.isNumeric($(value).find("td.debit").text().replace(",",''))){
			total_debit  += parseInt($(value).find("td.debit").text().replace(",",''));					
		}

		if($.isNumeric($(value).find("td.credit").text().replace(",",''))){
			total_credit += parseInt($(value).find("td.credit").text().replace(",",''));	
		}

	});
	
	var equalClass = "";
	var prev_class = "";
	if(total_debit.toFixed(2) == total_credit.toFixed(2)){
		equalClass = "equal";
		prev_class = "not-equal";
		
		if(has_account === true){
			$('#save').removeClass('disabled');	
		}
		
	}else{
		equalClass = "not-equal";
		prev_class = "equal";
		$('#save').addClass('disabled');
	}

	$('.total_debit').html(comma(total_debit.toFixed(2))).addClass(equalClass).removeClass(prev_class);
	$('.total_credit').html(comma(total_credit.toFixed(2))).addClass(equalClass).removeClass(prev_class);

}










	$(function(){

		var create_row  = function(){

			  $(".tbl-journal tbody tr:first").clone().find("td").each(function(){
			  	if($(this).hasClass('remove')!=true){
			  		$(this).text('-');			   	
			  	}
			  	
			  }).end().appendTo("table");
			  
		}

		var check_row = function(){
			var bool = false;
			 $(".tbl-journal tbody tr:last").find("td").each(function(i,val){	
			
				 if($(val).hasClass('account_id')){
				 	if($(val).text()!="" &&  $(val).text()!="-"){
				 		bool = true;
			 			return false;
				 	}
				 }
				
			 });

			return bool;
		}



		var get_accountType = function(account_id,td){

			$post = {
				account_id : account_id,
				location   : $('#profit_center option:selected').val(),
			};

			$.post('<?php echo base_url().index_page();?>/accounting/journal_entry/get_accountType',$post,function(response){

				$('#temp_subAccount').select({
					json : response.sub_account,
					attr :{
							text  : response.column.text,
							value : response.column.value,
					}
				});

				$('#temp_bank_accountNo').select({
					json : response.bank_accountNo,
					attr : {
							text  : response.bank_column.text,
							value : response.bank_column.value,
					}
				});

				$('#temp_cv_no').select({
					json : response.cv_no,
					attr : {
							text  : response.cv_no_column.text,
							value : response.cv_no_column.value,
					}
				});
				
				$('#temp_bankaccount').select({
					json : response.bank_account,
					attr :{
							text  : response.bank_accountColumn.text,
							value : response.bank_accountColumn.value,
					}
				});			

				td.html(response.ledger);

			},'json');
		}


		var get_checkNo = function(bank_id){

			$post = {
				bank_id   : bank_id,
				location  : $('#profit_center option:selected').val(),
			};

			$.post('<?php echo base_url().index_page();?>/accounting/journal_entry/get_checkNo',$post,function(response){
					$('#temp_checkno').select({
						json : response.check_no,
						attr :{							
								text : 'check_no',
								value : 'checkdtl_id',
						}
					});
			},'json');

		}

		app.init();

		$('.editable-amount').editable_td({
			addClass: "numbers_only comma",
			callback:function(){
				if(check_row()){
					create_row();
				}
				get_total();
			}
		});

		$('.editable-account').editable_td({
			insert : "select",
			clone  : $('#temp_accountDescription'),
			callback : function(td,option){	
				var td = $(td).closest('tr').find('td.editable-accountType');
				$(td).closest('tr').find('td.account_id').text($(option).val());
				get_accountType($(option).val(),td);
				if(check_row()){
					create_row();
				}
				get_total();		
			},
			addClass : "myChosen",
			beforeback:function(){
				$('.myChosen').attr({'data-placeholder' : 'Select Account Description'});
				$('.myChosen').chosen().trigger('chosen:open');				
			}
		});


		$('.editable-subaccount').editable_td({
			insert : "select",
			clone  : $('#temp_subAccount'),
			callback : function(){

			}
		});

		$('.editable-bankaccountnum').editable_td({
			insert : "select",
			clone  : $('#temp_bank_accountNo'),
			callback : function(){

			}
		});

		$('.editable-cvno').editable_td({
			insert : "select",
			clone  : $('#temp_cv_no'),
			callback : function(){

			}
		});

		$('.editable-bankaccount').editable_td({
			insert : "select",
			clone  : $('#temp_bankaccount'),
			callback : function(td,option){
				//console.log($(option).val());
				get_checkNo($(option).val());
			}
		});

		$('.editable-checkno').editable_td({
			insert : "select",
			clone  : $('#temp_checkno'),
			callback : function(){

			}
		});
	
		$('.editable-checkdate').editable_td({			
			addClass : "check-date",
			event : "change",
			beforeback : function(){
				$('.check-date').date();
			}
		});
		

	});
</script>