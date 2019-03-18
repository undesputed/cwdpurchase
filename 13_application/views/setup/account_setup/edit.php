
<div class="header">
	<h2>Edit an Account </h2>
</div>

<?php 
$account_type_1 = array(
				'ASSETS',
				'LIABILITIES',
				'EQUITY',
				'INCOME',
				'EXPENSES',
				); 
?>

<input type="hidden" id="account_id" value="<?php echo $main['account_id']; ?>">

<div class="container">
	<div class="content-title">
					<h3>Account Form</h3>
	</div>	
	<div class="row">			
			<div class="col-md-5">	
						
				<div class="panel panel-default">	
					<div class="panel-body">

						<div class="form-group">
							<div class="control-label">Account Type</div>

				  			<select name="" id="account_type" class="form-control input-sm">
				  				<?php foreach($account_type_1 as $row): ?>
								<?php $selected = ($row==$main['Short Desc'])? 'selected="selected"': '' ;?>
								<option <?php echo $selected; ?> value="<?php echo $row ?>"><?php echo $row ?></option>
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
						  			<input name="" id="account_header" class="form-control input-sm" readonly>
								</div>	
							</div>
							<div class="col-md-9">
									<div class="form-group">
										<div class="control-label">Header Description</div>
							  			<select name="" id="header_description" class="form-control input-sm">
							  				<?php foreach($account_setup as $row): ?>
							  					<option data-account-code="<?php echo $row['account_code']; ?>" value="<?php echo $row['account_id'] ?>"><?php echo $row['account_description']; ?></option>
							  				<?php endforeach; ?>
							  			</select>
									</div>	
							</div>

						</div>

						
					
					</div>
				</div>
			</div>

			<div class="col-md-7">	
						
				<div class="panel panel-default">	
					<div class="panel-body">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<div class="control-label">Account Code</div>
						  			<input name="" id="account_code" class="required form-control input-sm" value="<?php echo $main['Account Code']; ?>">
								</div>	
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<div class="control-label">Description</div>
						  			<input name="" id="description" class="required form-control input-sm" value="<?php echo $main['Account Description'] ?>">
								</div>
							</div>
						</div>
						
						<hr>
						
						<div class="row">
							<div class="col-md-1">
								<div class="form-group">
									<div class="control-label">Bank?</div>
									<?php $checked = ($main['Bank']=='Yes')? 'checked' : '' ; ?>
						  			<input type="checkbox" id="bank" <?php echo $checked ?>>
								</div>
							</div>
							<div class="col-md-11">
								<div class="form-group">
									<div class="control-label">Subsidiary Ledger</div>
						  			<select name="" id="ledger" class="form-control input-sm">
						  				<?php foreach($ledger as $row): ?>
						  					<?php $selected = ($main['Ledger']==$row['TYPE'])? 'selected="selected"' : '';?>
						  					<option <?php echo $selected; ?> value="<?php echo $row['ID'] ?>"><?php echo $row['TYPE']; ?></option>
						  				<?php endforeach; ?>
						  			</select>
								</div>
							</div>
						</div>

						

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<div class="control-label">Account Default</div>
						  			<select name="" id="account_default" class="form-control input-sm">
						  				<?php $selection = array('DEBIT'=>'selected="selected"','CREDIT'=>'selected="selected"');?>
						  				<option <?php echo $selection[$main['Account Classification']] ?> value="DEBIT">DEBIT</option>
						  				<option <?php echo $selection[$main['Account Classification']] ?> value="CREDIT">CREDIT</option>
						  			</select>
								</div>
							</div>
							<div class="col-md-8">
								<div class="form-group">
									<div class="control-label">Account Type</div>									
						  			<select name="" id="account_type" class="form-control input-sm">
						  				<?php foreach($account_type as $row): ?>
						  					<?php $selected = ($main['Account Type']==$row['TYPE'])? 'selected="selected"' : '' ; ?>
						  					<option <?php echo $selected ?> value="<?php echo $row['ID'] ?>"><?php echo $row['TYPE']; ?></option>
						  				<?php endforeach; ?>
						  			</select>
								</div>
							</div>
						</div>

						



					</div>
					<div class="form-footer">
						<div class="row">
							<div class="col-md-8"> </div>
							<div class="col-md-4">
								<input id="save" class="btn btn-success col-xs-5 pull-right btn-sm" type="submit" value="Update">
								<!-- <input id="reset" class="btn btn-link pull-right btn-sm" type="reset" value="Reset"> -->
							</div>
						</div>
					</div>
				</div>
			</div>
					

	</div>

</div>

<script>
	
	var app_create = {
		init:function(){
			this.bindEvents();
			this.account_type();
			this.account_header();
		},bindEvents:function(){

			$('#account_type').on('change',this.account_type);
			$('#classification').on('change',this.sub_classification);
			$('#header_description').on('change',this.account_header);
			$('#save').on('click',this.save);

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
							selected : '<?php echo $main["Classification"]; ?>',
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
							selected : '<?php echo $main["Sub Classification"]; ?>',
						});
			},'json');

		},account_header:function(){
			$('#account_header').val($('#header_description option:selected').attr('data-account-code'));
		},save:function(){
			var bool = false;
			$('.required').each(function(i,value){
				if($(value).val()==""){
					$(value).effect('highlight');
					bool = true;
				}
			});

			if(bool){
				return false;				
			}

			$post = {
			  txtAccountCode : $('#account_code').val(),
			  txtDescription : $('#description').val(),
			  cmb_subClassification : $('#sub_classification').val(),
			  cmbClassification : $('#classification').val(),
			  cbxaccounttype : $('#account_type').val(),
			  txtShortDesc   : $('#account_default option:selected').val(),
			  contra_account : $('#header_description option:selected').val(),
			  chkBank   : $('#bank').is(':checked'),
			  cbxledger : $('#ledger').val(),
			  txtAccountID : $('#account_id').val(),
			}

			$.save({appendTo : '.fancybox-outer'});
			$.post('<?php echo base_url().index_page();?>/setup/account_setup/update_account_setup',$post,function(response){
					switch(response){
						case "1":
							$.save({action : 'success',reload : 'true'});
							app.get_accounts();
						break;
						case "exist":							
							$.save({success : 'Account Already Exist',action : 'delay-hide',delay : '2000'});
						break;
					}
			});
		}

	}


	$(function(){
		app_create.init();
	});


</script>