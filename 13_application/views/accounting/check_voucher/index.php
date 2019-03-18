
<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Check Voucher Setup</h2>
</div>

<?php $date = explode('-',date('Y-m-d')); ?>

<div class="container">

	<input type="hidden" id="cv_id" value="">	

	<div class="row">	
		<div class="col-md-4">
				<div class="content-title">
					<h3>Check Voucher Setup</h3>	
				</div>
				<div class="panel panel-default">
				  <div class="panel-body">	

				  		<div class="row">
				  			<div class="col-md-6">
				  				<div class="form-group">
						  			<div class="control-label">Date Issued</div>
						  			<input type="text" id="date" class="form-control input-sm " readonly>
						  		</div>
				  			</div>
				  			<div class="col-md-6">
				  				<div class="form-group ">
						  			<div class="control-label">CV Year</div>
						  			<select name="" id="year" class="form-control input-sm">
						  				<?php for ($date['0']; $date['0'] > 1980 ; $date['0']--): ?>
						  					<option value="<?php echo $date['0']; ?>"><?php echo $date['0']; ?></option>
						  				<?php endfor; ?>
						  			</select>
						  		</div>
				  			</div>
				  		</div>		  				  	
				  												  											  								
						<div class="row">
							
							<div class="col-md-4">
								<div class="form-group">
						  			<div class="control-label">CV#No From</div>
						  			<input type="text" id="serial_no_from" class="form-control serial_from serial input-sm required numbers_only pad" maxlength="6" placeholder="000000">
						  		</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
						  			<div class="control-label">CV#No To</div>
						  			<input type="text" id="serial_to_to" class="form-control serial_to serial input-sm required numbers_only pad" maxlength="6" placeholder="000000">
						  		</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
						  			<div class="control-label">Quantity</div>
						  			<input type="text" id="quantity" class="form-control input-sm required " placeholder="" readonly>
						  		</div>
							</div>

						</div>
				  		<hr>

				  		<div class="form-group">
				  			<div class="control-label">Remarks</div>
				  			<textarea name="" id="remarks" cols="30" rows="1" class="form-control input-sm"></textarea>				  			
				  		</div>

				  		<div class="form-group">
				  			<div class="control-label">Setup By</div>
				  			<select type="text" id="setup_by" class="form-control input-sm  ">
				  				<option value="<?php echo $this->session->userdata('user'); ?>"><?php echo $this->session->userdata('username'); ?></option>
				  			</select>
				  		</div>
						
				  </div>
			
				  <div class="form-footer">
						<div class="row">
							<div class="col-md-7"> </div>
							<div class="col-md-5">
								<input id="class_save" class="btn btn-success col-xs-5 pull-right btn-sm" type="submit" value="Save">						
								<input id="class_reset" class="btn btn-link col-xs-5 pull-right btn-sm" type="submit" value="Reset">						
							</div>
						</div>
				   </div>
				</div>

		</div>	
		
		<div class="col-md-8">
			<div class="content-title">
					<h3>Check Voucher List</h3>	
				</div>
			<div class="panel panel-default">		
			  <div class="panel-body">			  		
			  </div>
				<div class="table_content table-responsive">
					<table class="table table-striped ">
						<thead>
							<tr>
								<th>Serial From</th>
								<th>Serial To</th>
								<th>Quantity</th>
								<th>Date Issued</th>
								<th>Remarks</th>
								<th>Employee Name</th>
								<th>Year</th>
								<th>Action</th>
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

	var app ={
		init:function(){
			$('#date').date();

			this.get_classification_setup();
			this.bindEvent();		

		},get_classification_setup:function(){

			$('.table_content').content_loader('true');
			$post = {
				date : $('#year').val(),
			}

			$.post('<?php echo base_url().index_page();?>/accounting_entry/check_voucher/get_cumulative',$post,function(response){
				$('.table_content').html(response);
				$('.myTable').dataTable(datatable_option);
			});

		},bindEvent:function(){

			$('#bank_name').on('change',this.get_account);
			$('#class_save').on('click',this.class_save);
			$('.serial').on('change',this.serial);
			$('#class_reset').on('click',this.reset);
			$('body').on('click','.update',this.update);

			$('#year').on('change',this.get_classification_setup);

		},serial:function(){

			var serial  = $('.serial_from').val();			
			var serial2 = $('.serial_to').val();
			var to;
			var qty;
			if($(this).hasClass('serial_to')){
				to = +serial2;
							
			}else{
				to = +serial + 49;	
				$('.serial_to').val(pad(to,6));
				qty = to;
			}
			if(serial!="" && serial2!=""){
				qty = +to - +serial;	
				qty = qty + 1;
			}
						
			$('#quantity').val(qty);

		},get_account:function(){


			$post = {				
				bank_id : $('#bank_name option:selected').val(),
			}

			$.post('<?php echo base_url().index_page();?>/accounting_entry/check_number/get_account',$post,function(response){
					
					$('#account_no').select({
						json : response.account_no,
						attr : {
							text  : 'account_no',
							value : 'dtl_id',
						}
					});
					
					$('#account_no').on('change',function(){
						$('#account_name').val($(this).val())
					});


					$('#account_name').select({
						json : response.account_name,
						attr : {
							text  : 'account_name',
							value : 'dtl_id',
						}
					});


					$('#account_name').on('change',function(){
						$('#account_no').val($(this).val())
					});
					

			},'json');

		},class_save:function(){

			if($('.required').required()){
				return false;
			}
			
			$.save();
			

			$post = {
				serial_no_from :  $('#serial_no_from').val(),
				serial_no_to   :  $('#serial_to_to').val(),
				quantity       :  $('#quantity').val(),
				date_issued    :  $('#date').val(),
				remarks        :  $('#remarks').val(),
				employee_id    :  $('#setup_by option:selected').val(),				
				cv_id		   :  $('#cv_id').val(),
				year           :  $('#year option:selected').val(),
			}

			$.post('<?php echo base_url().index_page();?>/accounting_entry/check_voucher/save_check_voucher',$post,function(response){
					switch(response){

						case"1": 
							$.save({action : 'success'});
						break;
						default :
							$.save({action : 'hide'});
						break;

					}
				$('.required,.clear').val('');
				app.get_classification_setup();

			}).error(function(){
				alert('Service Unavailable');
				$.save({action : 'hide'})
			});

			$('#class_save').val('Save');

		},class_reset:function(){
			$('.required,.clear').val('');
			$('#class_save').val('Save');
		},back_to:function(){
			$.save({appendTo : '.fancybox-outer',loading : 'Processing...'});
			
			$.post('<?php echo base_url().index_page();?>/setup/account_setup/new_request',function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			}).error(function(){
				alert('Service Unavailable');
			});

		},account_type:function(){

			$post = {
				account_type : $('#txtShortDesc option:selected').val(),
			};

			$.post('<?php echo base_url().index_page();?>/setup/account_setup/get_classification',$post,function(response){
						$('#cmbClassification').select({
							json : response,
							attr : {
								text : 'full_description',
								value : 'id',
							}
						});			
			},'json');

		},update:function(){
			var tr = $(this).closest('tr');

			/*
			$data = {
				
				bank_name      : tr.find('td.bank_id').text(),
				date           : tr.find('td.date').text(),
				remarks        : tr.find('td.remarks').text(),
				account_no     : tr.find('td.asset_dtlID').text(),
				account_name   : tr.find('td.asset_dtlID').text(),
				serial_no      : tr.find('td.stub_no').text(),
				serial_no_from : tr.find('td.serial_no_from').text(),
				serial_to_to   : tr.find('td.serial_no_to').text(),
				quantity       : tr.find('td.quantity').text(),
				check_id       : tr.find('td.check_id').text(),
				
			}
			*/

			var serial_from = tr.find('td.serial_from').text().split('-');
			var serial_to   = tr.find('td.serial_to').text().split('-');
						
			$data = {
				cv_id        : tr.find('td.cv_id').text(),
				employee_id  : tr.find('td.employee_id').text(),
				serial_from  : tr.find('td.serial_from').text(),
				serial_to    : tr.find('td.serial_to').text(),
				quantity     : tr.find('td.quantity').text(),
				remarks      : tr.find('td.remarks').text(),
				year         : tr.find('td.year').text(),
				date         : tr.find('td.date').text(),
				serial_no_from : serial_from[1] ,
				serial_to_to : serial_to[1],
			}

			$.each($data,function(i,val){
				$('#'+i).val(val);
			});

			app.get_account();

			$('#class_save').val('Update');

		},reset:function(){

			$('.required').val('');
			app.get_account();
			$('#class_save').val('Save');


		}
	};

	$(function(){		
		app.init();
	});
</script>