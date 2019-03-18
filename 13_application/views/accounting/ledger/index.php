<div class="content-page">
 <div class="content">

<div class="header">
	<h2>General Ledger</h2>
</div>



<div class="container">

	

	<div class="row">				
		<div class="col-md-12">
			
			<div class="content-title">
				<h3>Ledger</h3>	
			</div>

			<div class="panel panel-default">		
			  <div class="panel-body">			  		
				<div class="row">

						<div class="col-md-4">							
						  		<div class="form-group">
						  			<div class="control-label">Project</div>
						  			<select name="" id="profit_center" style="width:100%">				
											<option value="%%">ALL</option>
						  				<?php foreach($project as $row): ?>
											<option value="<?php echo $row['project_id']; ?>"><?php echo $row['project_full'] ?></option>
						  				<?php endforeach; ?>
						  			</select>
						  		</div>
						</div>

						<div class="col-md-2">
								<div class="form-group inline">
						  			<div class="control-label">Year</div>
						  			<select name="" id="year" style="margin-top:2px">
										<?php $year = date('Y') ?>
										<?php for($year;$year >='2012';$year--): ?>
										<option value="<?php echo $year;?>"><?php echo $year; ?></option>
										<?php endfor; ?>
									</select>
						  		</div>
						  		<div class="form-group inline">
						  			<div class="control-label">Month</div>
						  			<select name="" id="month" style="margin-top:2px">						  				
										<option value="1">January</option>
										<option value="2">Febuary</option>
										<option value="3">March</option>
										<option value="4">April</option>
										<option value="5">May</option>
										<option value="6">June</option>
										<option value="7">July</option>
										<option value="8">August</option>
										<option value="9">September</option>
										<option value="10">October</option>
										<option value="11">November</option>
										<option value="12">December</option>
									</select>
						  		</div>
						</div>
						
						<div class="col-md-2">
								<button id="filter" class="btn btn-success" style="margin-top:10px">Apply Filter</button>
						</div>
						<div class="col-md-2">
						<!-- 	<span class="control-item-group">
								<a href="<?php echo base_url().index_page(); ?>/print/income_statement/" target="_blank" class="action-status cancel-event">Print</a>
							</span> -->	
						</div>
				</div>
				<div class="row">
						<div class="col-md-4 content-event">

							<div class="checkbox-inline">
								<input type="checkbox" class="toggle-event" id="identifier" checked>
							</div>
							
							<div class="form-group inline">
								<div class="control-label">Acct No</div>
					  			<input type="text" name="" id="account_code" style="margin-top:2px;width:60px" >
							</div>

							<div class="form-group inline">
								<div class="control-label">Acct Name</div>
					  			<select name="" id="account_name" style="margin-top:2px;width:160px">					  			
								</select>
							</div>

						</div>

						<div class="col-md-4 content-event">
							<div class="checkbox-inline" style="margin-right:10px">
								<label for="ledger"></label><input id="ledger" type="checkbox"  ><small>Per Ledger</small></label>
							</div>
							<div class="radio-inline">
								<label for="person"><input type="radio" name="supplier" id="person" value="person"> Person</label>
							</div>
							<div class="radio-inline">
								<label for="business"><input type="radio" name="supplier" checked id="business" value="business"> Business</label>
							</div>
							<div>
								<select name="" id="display_supplier" style="width:220px"></select>
							</div>
						</div>
				
				</div>
				
			  </div>
				<div class="table_content">
					<table class="table table-striped ">
						<thead>
							<tr>
								<th>Income/Expense</th>,
								<th>Previous</th>
								<th>Current</th>	
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="7">Empty Result</td>
							</tr>
						</tbody>		
					</table>
			  	</div>			
			</div>
			
		</div>
	
	</div>
		
</div>



</div>
</div>

<script>
	
	var bankTransaction = false
	var accountsPayable  = 139;
	var accountsReceivable  = 10;
	var advancesCASL  = 24;
	var advancesCASSD  = 25;
	var CASL_officersEmployees  = 393;
	var CASSD_officersEmployees  = 394;
	var dueFromAffiliates  = 396;	
	var business = eval(<?php echo $business; ?>);
	var person = eval(<?php echo $person; ?>);
	var app ={
		init:function(){
			$('#date').date();
				
			app.get_account();
			app.bindEvent();
						
		},get_classification_setup:function(){

			$('.table_content').content_loader('true');

			var identifier = 2;
			if($('#identifier').is(':checked')){
				identifier = 2;
			}else{
				identifier = 1;
			}
			var str = "";
			if($('#ledger').is(":checked")){

					var classificationID = $('#account_name option:selected').attr('data-classification_id');
					var txtAccountNo_Tag = $('#account_name option:selected').val();
					
					if(  classificationID == 3 || classificationID == 4 || txtAccountNo_Tag == dueFromAffiliates){
						//'<< TRANSFER FUNDS >>
						var str = "CALL Ledger_display_all_accounts_subsidiary_Transfer(?,?,?,?,?,?)";
						var transfer = true;

					}else{
						if(txtAccountNo_Tag == accountsPayable){
							//'<< ACCOUNTS PAYABLE >>
							var str = "CALL Ledger_display_all_accounts_subsidiary_AP(?,?,?,?,?,?)";
						}else if( txtAccountNo_Tag == accountsReceivable){
							//'<< ACCOUNTS RECEIVABLE >>
							var str = "CALL Ledger_display_all_accounts_subsidiary_AR(?,?,?,?,?,?)";
						}else if( bankTransaction == true){
							//'<< BANK TRANSACTION >>
							var str = "CALL Ledger_display_all_accounts_subsidiary_Bank(?,?,?,?,?,?)";
						}else if( txtAccountNo_Tag == advancesCASL || txtAccountNo_Tag == advancesCASSD){
							//'<< ADVANCES >>
							var str = "CALL Ledger_display_all_accounts_subsidiary_Adv(?,?,?,?,?,?)";
						}
					}

			}

			var supplier = ($('#display_supplier option:selected').length > 0) ? $('#display_supplier option:selected').val() : 0;
				

			$post = {
				account_id : $('#account_name option:selected').val(),
				year       : $('#year option:selected').val(),
				month      : $('#month option:selected').val(),
				location   : $('#profit_center option:selected').val(),
				identifier : identifier,
				account_name : $('#account_name option:selected').text(),
				per_ledger : $('#ledger').is(":checked"),
				account_code : $('#account_code').val(),
				str        : str,
				supplier   : supplier,
			}

			$.post('<?php echo base_url().index_page();?>/accounting_entry/ledger/ledger_display',$post,function(response){
				$('.table_content').html(response);
				//$('.myTable').dataTable(datatable_option);
			}).error(function(){
				alert('Service Unavailable');
				$('.table_content').content_loader('false');			
			});

		},bindEvent:function(){
			$('#filter').on('click',this.apply_filter);
			$('.toggle-event').on('change',this.toggle_event);
			$('#ledger').on('change',this.ledger_toggle);

			$('.toggle-event').trigger('change');
			$('#ledger').trigger('change');

			$('input[name="supplier"]').on('change',function(){				
				if($('input[name="supplier"]:checked').val()=='business'){
					$('#display_supplier').html(' ');					
					$.each(business,function(i,val){
						$('<option></option>').val(val['Supplier ID']).html(val['Supplier Name']).appendTo('#display_supplier');
					});					
				}else{
					$('#display_supplier').html(' ');		
					$.each(person,function(i,val){
						$('<option></option>').val(val['Supplier ID']).html(val['Supplier Name']).appendTo('#display_supplier');
					});		
				}
			});
			$('input[name="supplier"]').trigger('change');


		},apply_filter:function(){
			app.get_classification_setup();
		},get_account:function(){
			$get = {
				project_id : $('#profit_center option:selected').val()
			}
			$.get('<?php echo base_url().index_page(); ?>/accounting_entry/ledger/get_account',$get,function(response){

				$('#account_name').html(response);
				$('#account_name').on('change',function(){
					var code = $('#account_name option:selected').attr('data-code');
					$('#account_code').val(code);						
				});			
				$('#account_name').trigger('change');
				app.get_classification_setup();
			});
		},toggle_event:function(){


			var me = $(this);
			if(me.is(':checked')){
				
				me.closest('.content-event').find('select,input').attr({disabled:'disabled'});
			}else{
				me.closest('.content-event').find('select,input').removeAttr('disabled');
			}
			me.removeAttr('disabled');

		},ledger_toggle:function(){

			var me = $(this);
			if(me.is(':checked')){				
				me.closest('.content-event').find('select,input').removeAttr('disabled');
			}else{				
				me.closest('.content-event').find('select,input').attr({disabled:'disabled'});
			}
			me.removeAttr('disabled');

		}

	};

	$(function(){		
		app.init();
	});
</script>