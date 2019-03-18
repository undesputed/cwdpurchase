<div class="header">
	<h2>Edit Asset Setup</h2>
</div>


<div class="container">
	
<?php 
$account_type_1 = array(
					array('title'=>'ASSETS','group'=>'1','cashflow'=>'3'),
					array('title'=>'LIABILITIES','group'=>'2','cashflow'=>'1'),
					array('title'=>'EQUITY','group'=>'3','cashflow'=>'4'),
					array('title'=>'INCOME','group'=>'4','cashflow'=>'5'),
					array('title'=>'EXPENSES','group'=>'5','cashflow'=>'2'),
				);

?>
	
	<input type="hidden" value="" id="total">	
	<input type="hidden" value="<?php echo $main['asset_id'] ?>" id="asset_id">

	<div class="row">			
			<div class="col-md-5">	
					<div class="content-title">
						<h3>Account Form</h3>	
					</div>

				<div class="panel panel-default">	
					<div class="panel-body">
												
				  		<select name="" id="project" class="form-control input-sm" style="display:none">				  			
				  		</select>
						
						<div class="form-group">
							<div class="control-label">Project</div>
				  			<select name="" id="profit_center" class="form-control input-sm"></select>	
						</div>
						
						<div class="form-group">
							<div class="control-label">Account Classification</div>
				  			<select name="" id="account_type" class="form-control input-sm">
				  			<?php foreach($account_type_1 as $row): ?>								
				  				<?php $selected = ($row['title']==$main['account_classification']); ?>
									<option <?php echo $selected; ?> value="<?php echo $row['title'] ?>"><?php echo $row['title'] ?></option>
				  				<?php endforeach; ?>
				  			</select>	
						</div>

						<hr>
						<div class="form-group">
							<div class="control-label">Classification</div>
				  			<select name="" id="classification" class="form-control input-sm"></select>
						</div>

						<div class="form-group">
							<div class="control-label">Sub Classification</div>

				  			<select name="" id="sub_classification" class="form-control input-sm">				  					
				  			</select>
				  			
						</div>	

						<div class="row">

							<div class="col-md-3">
								<div class="form-group">
									<div class="control-label">Code</div>
						  			<input name="" id="account_code" class="form-control input-sm" readonly>
								</div>	
							</div>

							<div class="col-md-9">
									<div class="form-group">
										<div class="control-label">Account Description</div>
							  			<select name="" id="account_description" class="form-control input-sm">
							  			
							  			</select>
									</div>	
							</div>

						</div>
					
					</div>
				</div>
			</div>

			<div class="col-md-7">	
				<div class="content-title">
						<h3>Account Details</h3>	
					</div>		
				<div class="panel panel-default">	
					<div class="panel-body">

						<div class="row">

							<div class="col-md-4">
								<div class="form-group">
									<div class="control-label">Bank</div>
						  			<select name="" id="bank" class="form-control input-sm">
						  				<?php foreach($bank as $row): ?>
						  					<option data-location="<?php echo $row['bank_address']; ?>" value="<?php echo $row['bank_id'] ?>"><?php echo $row['bank_name'] ?></option>
										<?php endforeach; ?>
						  			</select>
								</div>	
							</div>

							<div class="col-md-8">
								<div class="form-group">
									<div class="control-label">Address</div>
						  			<input name="" id="address" class="required form-control input-sm uppercase">
								</div>
							</div>

						</div>						
						<hr>
						
						<div class="row">
							<div class="col-md-7">
								<div class="form-group">
									<div class="control-label">Account Name</div>
						  			<input type="input" id="account_name" class="form-control input-sm required uppercase">
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<div class="control-label">Account Number</div>
						  			<input type="text" name="" id="account_number" class="form-control input-sm required ">						  									  			
								</div>
							</div>
						</div>
												
						<div class="row">
							
							<div class="col-md-6">
								<div class="form-group">
									<div class="control-label">Account Balance</div>
						  			<input name="" id="account_balance" class="form-control input-sm numbers_only comma" placeholder="0.00">
						  			
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<div class="control-label">Maintaining Balance</div>
						  			<input name="" id="maintaining_balance" class="form-control input-sm numbers_only comma" placeholder="0.00">						  			
								</div>
							</div>

						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<div class="control-label">Currency</div>
						  			<select name="" id="currency" class="form-control input-sm">
						  				<option value="PESO">PESO</option>
						  				<option value="DOLLAR">DOLLAR</option>
						  			</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<div class="control-label">Account Type</div>
						  			<select name="" id="account_type" class="form-control input-sm">
						  				<option value="saving_account">Saving Account</option>
						  				<option value="checking_account">Checking Account</option>
						  			</select>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<div class="control-label">Date Saved</div>
						  			<input name="" id="date" class="form-control input-sm" placeholder="YYYY-MM-DD">						  			
								</div>
							</div>

						</div>
											
					</div>

					<div class="form-footer">
						<div class="row">
							<div class="col-md-8"> </div>
							<div class="col-md-4">
								<input id="add_to_list" class="btn btn-primary col-xs-5 pull-right btn-sm" type="submit" value="Add to List">
								<!-- <input id="reset" class="btn btn-link pull-right btn-sm" type="reset" value="Reset"> -->
							</div>
						</div>
					</div>


				</div>
			</div>
				
	</div>

	<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">		
					  <div class="panel-body">

					  </div>
					  <table class="table table-striped table-hover tbl-list tbl-event">
					  	<thead>
					  		<tr>
					  			<th>Name</th>
					  			<th>Account Name</th>
					  			<th>Account Number</th>
					  			<th>Amount</th>
					  			<th>Action</th>
					  		</tr>
					  	</thead>
					  	<tbody>
					  		<tr>
					  			<td colspan="5">Empty List</td>					  			
					  		</tr>
					  	</tbody>
					  </table>
					  
					  <div class="form-footer">
						<div class="row">
							<div class="col-md-6"> </div>
							<div class="col-md-2"><strong>TOTAL</strong> <span style="margin-left:5px" id="total-span">0</span></div>
							<div class="col-md-4">
								<input id="save" class="btn btn-success col-xs-5 pull-right btn-sm " type="submit" value="Update">
								<!-- <input id="reset" class="btn btn-link pull-right btn-sm" type="reset" value="Reset"> -->
							</div>
						</div>
					</div>

					</div>
				</div>			
	</div>
</div>

<script>
	
	var list = JSON.parse('<?php echo $details ?>');	
	var app_create = {
		init:function(){

			$('#date').date();

			var option = {
				profit_center : $('#profit_center'),
				call_back  :function(){
						app_create.bindEvents();
						app_create.account_type();
						app_create.account_header();
				}
			}			
			$('#project').get_projects(option);

		
			
			this.render();

		},bindEvents:function(){

			$('#account_type').on('change',this.account_type);
			$('#classification').on('change',this.sub_classification);
			$('#header_description').on('change',this.account_header);
			$('#save').on('click',this.save);

			$('#add_to_list').on('click',this.add_to_list);

			$('body').on('click','.tbl-event .remove',this.remove);

			$('#address').val($('#bank option:selected').attr('data-location'));
			$('#bank').on('change',function(){
				$('#address').val($('#bank option:selected').attr('data-location'));
			});

		},account_type:function(){
			$post = {
				account_type : $('#account_type option:selected').val(),
			};			
			$.post('<?php echo base_url().index_page();?>/setup/account_setup/get_classification',$post,function(response){

						$('#classification').select({
							json : response,
							attr : {
								text : 'full_description',
								value : 'id',
							},
							selected : '<?php echo $main["classification_id"]; ?>',
						});

						app_create.sub_classification();

			},'json');
		},sub_classification:function(){

			$post = {
				class_id : $('#classification option:selected').val(),
			};

			$.post('<?php echo base_url().index_page();?>/setup/account_setup/get_sub_classification',$post,function(response){
						$('#sub_classification').select({
							json : response,
							attr : {
								text :  'sub_classification_name',
								value : 'sub_classification_id',
							},
							selected : '<?php echo $main["sub_classID"]; ?>',
						});

						app_create.get_account_description();

			},'json');

		},account_header:function(){
			$('#account_header').val($('#header_description option:selected').attr('data-account-code'));
		},save:function(){

			$post = {

				  classification         : $('#classification option:selected').val(),
				  classification_sub     : $('#sub_classification option:selected').val(),
				  account_id             : $('#account_code').val(),
				  account_description    : $('#account_description option:selected').text(),
				  total                  : $('#total').val(),
				  account_classification : $('#account_type option:selected').val(),
				  category               : 'bank',
				  payee_type             : 'bank',
				  project                : $('#project option:selected').val(),
				  profit_center          : $('#profit_center option:selected').val(),
				  status                 : 'ACTIVE',
				  journal_id             : '0',
				  details                : list,
				  asset_id               : $('#asset_id').val(),
				  
			};
			
			$.save({appendTo : '.fancybox-outer'});
			$.post('<?php echo base_url().index_page();?>/setup/asset_setup/update_asset_setup',$post,function(response){
					switch(response){
						case "1":
							$.save({action : 'success',reload : 'true'});							
						break;
						case "exist":							
							$.save({success : 'Account Already Exist',action : 'delay-hide',delay : '2000'});
						break;
					}
			});

		},add_to_list:function(){

			if($('.required').required()){
				return false;
			}

			var data = {				
				bank_id                : $('#bank option:selected').val(),
				bank                   : $('#bank option:selected').text(),
				address                : $('#bank').val(),
				account_name           : $('#account_name').val(),
				account_number         : $('#account_number').val(),
				account_balance        : $('#account_balance').val(),
				maintaining_balance    : $('#maintaining_balance').val(),
				currency               : $('#currency option:selected').val(),
				account_type           : $('#account_type option:selected').val(),
				date                   : $('#date').val(),
			};

			list.push(data);
			app_create.render();

		},render:function(){
			
			$('.tbl-list tbody').html('');
			var td = "";
			var total = 0;
			if(list.length==0){
				td = "<tr><td colspan='5'>Empty List</td></tr>";
			}

			$.each(list,function(i,value){				

				td += "<tr><td>"+value.bank+"</td>";
				td += "<td>"+value.account_name+"</td>";
				td += "<td>"+value.account_number+"</td>";
				td += "<td>"+value.account_balance+"</td>";
				td += "<td><span class='event remove btn-link'>Remove</span></td></tr>";

				total = +total + +value.account_balance.replace(/,/g,'');
				
			});
			$('#total').val(total.toFixed(2));
			$('#total-span').html(comma(total.toFixed(2)));
			$('.tbl-list tbody').html(td);


		},remove:function(){

			var index = $(this).closest('tr').index();
			list.splice(index,1);
			app_create.render();

		},get_account_description :function(){

			$post = {
				location         : $('#profit_center option:selected').val(),
				classification   : $('#classification option:selected').val(),
				sub_classification: $('#sub_classification option:selected').val(),
			}
			$.post('<?php echo base_url().index_page();?>/setup/asset_setup/account_description',$post,function(response){

					$('#account_description').select({
						json : response,
						attr : {
							text  : 'account_description',
							value : 'account_id',							
						},
						data :{
							'data-code' : 'account_code',
						},
						selected  : '<?php echo $main["account_code"]; ?>',
					});

					$('#account_code').val($('#account_description option:selected').attr('data-code'));
					$('#account_description').on('change',function(){
						$('#account_code').val($('#account_description option:selected').attr('data-code'));
					});

			},'json');

		}
	}

	$(function(){
		app_create.init();
	});


</script>