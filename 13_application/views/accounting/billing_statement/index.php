<div class="content-page">
	<div class="content">
		<div class="header">
				<h2>Billing Statement</h2>	
		</div>


		<div style="margin-top:5px">
				<ul class="nav nav-tabs" role="tablist">
				    <li class="active"><a href="<?php echo base_url().index_page(); ?>/accounting_entry/billing_statement">Entry</a></li>
				    <li><a href="<?php echo base_url().index_page(); ?>/accounting_entry/billing_statement/cumulative">Cumulative Data</a></li>
			  	</ul>
		 </div>


		<div class="container">
			<div class="content-title">
					<h3>Billing Statement</h3>	
			</div>
			<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">		
				  <div class="panel-body">
				  		<form class="form-horizontal">

				  			<div class="form-group" style="display:none">
					  			<div class="control-label">Company Name</div>
					  			<select name="" id="create_project" class="form-control input-sm"></select>
					  		</div>

					  		<div class="form-group">
					  			<label class=" col-xs-3 control-label">Project Site</label>
					  			<div class="col-xs-9">
					  				<select name="" id="create_profit_center" class="form-control input-sm"></select>
					  			</div>
					  		</div>

					  		<div class="form-group">
					  			<label for="" class="col-xs-3 control-label">Project:</label>
					  			<div class="col-xs-9">
					  				<select class="form-control" id="project_category">
					  					<?php foreach($project_category as $row): ?>
											<option value="<?php echo $row['id']; ?>"><?php echo $row['project_name']; ?></option>
					  					<?php endforeach; ?>
					  				</select>
					  			</div>					  			
					  		</div>
				  		
				  			<div class="form-group">
					  			<label for="" class="col-xs-3 control-label">Invoice No:</label>
					  			<div class="col-xs-9">
					  				<input type="text" class="form-control" value="" id="invoice_no">
					  			</div>					  			
					  		</div>

					  		<div class="form-group">
					  			<label for="" class="col-xs-3 control-label">Invoice Date:</label>
					  			<div class="col-xs-9">
					  				<input type="date" class="form-control date" value="" id="invoice_date">
					  			</div>
					  		</div>

					  		<div class="form-group">
					  			<label for="" class="col-xs-3 control-label">Due Date:</label>
					  			<div class="col-xs-9">
					  				<input type="date" class="form-control date" value="" id="due_date">
					  			</div>					  			
					  		</div>

					  		<div class="form-group">
					  			<label for="" class="col-xs-3 control-label">Customer Name:</label>
					  			<div class="col-xs-9">
					  				<select name="" id="display_supplier" class="form-control"></select>					  				
					  			</div>					  			
					  		</div>
							
					  		<div class="form-group">
					  			<div class="col-xs-3"></div>
					  			<div class="col-xs-9">
					  				<label class="radio-inline">
									  <input type="radio" value="business" name="supplier"> Business
									</label>
						  			<label class="radio-inline">
									  <input type="radio" value="person" name="supplier" checked> Person
									</label>
					  			</div>
					  		</div>

				  		</form>
				  </div>	 
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">		
						  <div class="panel-body">
						  		<form class="form-horizontal">
						  			
							  		<div class="form-group">
							  			<label for="" class="col-xs-3 control-label">Billing Address :</label>
							  			<div class="col-xs-8">
							  				<input type="date" class="form-control" value="" id="billing_address">
							  			</div>							  			
							  		</div>
							  		<div class="form-group">
							  			<label for="" class="col-xs-3 control-label">Customer PO No. :</label>
							  			<div class="col-xs-8">
							  				<input type="date" class="form-control" value="" id="po_no">
							  			</div>							  			
							  		</div>

							  		<div class="form-group">
							  			<label for="" class="col-xs-3 control-label">Payments Terms :</label>
							  			<div class="col-xs-8">
							  				<div class="row">
							  					<div class="col-md-8">
							  						<select type="date" class="form-control" value="" id="payment_terms">
							  							<option value="COD">COD</option>
							  							<option value="In Days">In Days</option>
							  						</select>
							  						</div>
							  					<div class="col-md-4"><input type="number" class="form-control" value="1" id="nuterms"></div>
							  				</div>							  				
							  			</div>							  			
							  		</div>

							  		<div class="form-group">
							  			<label for="" class="col-xs-3 control-label">Outstanding Balance :</label>
							  			<div class="col-xs-8">
							  				<input type="date" class="form-control" value="" id="outstanding_balance">
							  			</div>
							  			
							  		</div>
							  		<div class="form-group">
							  			<label for="" class="col-xs-3 control-label" id="remarks">Remarks</label>
							  			
							  			<div class="col-xs-8">
							  				<textarea name="" id="remarks" cols="25" rows="1" class="form-control input-sm"></textarea>
							  			</div>
							  		</div>							  		
						  		</form>
						  </div>
						</div>
			</div>
		</div>	
		<!-- Upper Content -->
		<div class="panel panel-default">		
		  <div class="panel-body">
		  		<div class="row">
		  			<div class="col-md-6">
		  				<form class="form-horizontal">
				  			<div class="form-group">
					  			<label for="" class="col-xs-3 control-label">Type:</label>
					  			<div class="col-xs-8">
					  				<input type="date" class="form-control required" value="" id="type">
					  			</div>
					  			
					  		</div>
					  		<div class="form-group">
					  			<label for="" class="col-xs-3 control-label">Description:</label>
					  			<div class="col-xs-8">
					  				<input type="date" class="form-control required" value="" id="description">
					  			</div>
					  			
					  		</div>
					  		<div class="form-group">
					  			<label for="" class="col-xs-3 control-label">Amount:</label>
					  			<div class="col-xs-8">
					  				<input type="date" class="form-control required numbers_only comma" value="" id="amount">
					  			</div>
					  			
					  		</div>
				  		</form>
		  			</div>
		  			<div class="col-md-6"></div>
		  		</div>
		  </div>	 
		</div>
		<!-- footer -->
		<table width="100%">
			<tr>
				<td style="width:100px">Prepared By : 
					<select name="" id="prepared_by" class="form-control">
						
					</select>
				</td>
				<td style="width:200px"><input id="class_save" class="btn btn-primary col-xs-5 pull-right btn-sm" type="submit" value="Save"></td>
			</tr>		
			
		</table>
		</div>
	</div>
	<div style="margin-top:2em;"></div>
</div>

<script>
	var xhr = "";
	var business = eval(<?php echo $business; ?>);
	var person   = eval(<?php echo $person; ?>);

	var v_app = {
		init:function(){

			this.bindEvent();
			$('.date').date();

			$('#invoice_date').date({
				url : 'get_invoice_max_id',
				dom : $('#invoice_no'),
				event : 'change',
			});

			var option = {
				profit_center : $('#create_profit_center'),
				main_office : true,
			}			

			$('#create_project').get_projects(option);

			$.signatory({
				type          : 'pr',
				prepared_by   : 'sesssion',				
			});

		},bindEvent:function(){
			$('#class_save').on('click',this.save);

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

		},save:function(){
			if($('.required').required()){
				return false;
			}

			var bool = confirm('Are you sure?');
			if(!bool){
				return false;
			}

			$post = {
				txtInvoice     : $('#invoice_no').val(),
				dtpInvoiceDate : $('#invoice_date').val(),
				dtpDueDate     : $('#due_date').val(),
				cbxCustomer    : $('#customer_name option:selected').val(),
				cbxCustomer_name : $('#customer_name option:selected').text(),
				rbtnBusiness   : $('input[name="supplier"]:checked').val(),
				txtAddress     : $('#billing_address').val(),
				txtRemarks     : $('#remarks').val(),
				txtType        : $('#type').val(),
				txtDesc        : $('#description').val(),
				txtAmount      : $('#amount').val().replace(/,/g,''),
				txtPO          : $('#po_no').val(),
				cmbterms       : $('#payment_terms option:selected').text(),
				nupterms       : $('#nuterms').val(),
				txtBal         : $('#outstanding_balance').val(),
				cmbPreparedBy  : $('#prepared_by option:selected').val(),
				MAIN_FORM      : $('#create_profit_center option:selected').val(),
				project_category : $('#project_category option:selected').val(),
			};

	        if(xhr && xhr.readystate != 4){
	            xhr.abort();
	        }	        
	        $.save({appendTo : 'body'});
			xhr = $.post('<?php echo base_url().index_page();?>/accounting_entry/billing_statement/save',$post,function(response){
				switch($.trim(response)){
					case "1":
						$.save({action : 'success',reload : 'false'});
						$('.required').val('');
					break;
					default:
						alert(response);
						$.save({action : 'error',reload : 'false'});
					break;
				}
			});

		}
	}
	
	$(function(){
		v_app.init();
	});
</script>