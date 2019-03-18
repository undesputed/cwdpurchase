
<div class="content-page">
 <div class="content">


<div class="header">
	<h2>User Setup</h2>
</div>

<div class="container">

		<div class="content-title">
			<h3>User Setup</h3>	
		</div>


		<input type="hidden" id="id" value="" class="clear">

		<div class="row">
			<div class="col-md-4">

				<div class="panel panel-default">		
				  <div class="panel-body">
				  		
						<div class="form-group">
				  			<div class="control-label">Company Name</div>
				  			<select  id="project" class="form-control input-sm "></select>
				  		</div>

				  		<div class="form-group">
				  			<div class="control-label">Project Site</div>
				  			<select id="profit_center" class="form-control input-sm  "></select>
				  		</div>
						<hr>
				  		<div class="form-group">
				  			<div class="control-label">Employee</div>
				  			<select  id="employee" class="form-control input-sm required">
				  				<?php foreach($employee as $row): ?>
				  					<option value="<?php echo $row['emp_number']; ?>" data-profile-no="<?php echo $row['person_profile_no']; ?>"><?php echo $row['pp_fullname']; ?></option>
				  				<?php endforeach ?>
				  			</select>
				  		</div>
						
				  <!-- 		
				  		<div class="form-group">
						  	<div class="control-label">Department</div>
						  	<select  id="department" class="form-control input-sm required"></select>
						</div>
				   -->
						
						<div class="form-group">
				  			<div class="control-label">Username</div>
				  			<input type="text" id="username" class="form-control input-sm required ">
				  		</div>

				  		<div class="form-group">
				  			<div class="control-label">Password</div>
				  			<input type="password" id="password" class="form-control input-sm required ">
				  		</div>

						<?php 
							$usertype  = array(
									array('name'=>'USER'),							
									array('name'=>'ADMIN'),
									array('name'=>'CANVASS USER'),
									array('name'=>'PO USER'),						
									array('name'=>'ACCOUNTANT'),
								);
						 ?>

				  		<div class="form-group">
				  			<div class="control-label">User Type</div>
				  			<select  id="usertype" class="form-control input-sm required">
				  				<?php foreach($usertype as $row): ?>
				  					<option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
				  				<?php endforeach; ?>
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

				<div class="panel panel-default">		
				  <div class="panel-body">		  		
				  </div>	
				  <div class="classification_setup_content">
				 	 <table class="table table-striped">
						<thead>
							<tr>
								<th>Type</th>						
							</tr>
						</thead>
					 </table>
				  </div> 
				</div>		
			</div>


		</div>

</div>


</div>
</div>
<script>

	var app_sub_classification ={
		init:function(){

			var option = {
				profit_center : $('#profit_center'),
				call_back : function(){
					app_sub_classification.get_department();
					app_sub_classification.get_classification_setup();	
					app_sub_classification.bindEvent();
				},
			}

			$('#project').get_projects(option);

			
		
			this.account_type();

		},get_classification_setup:function(){

			$('.classification_setup_content').content_loader('true');
			$post = {
				location : $('#project option:selected').val(),
			};
			$.post('<?php echo base_url().index_page();?>/setup/user_setup/get_cumulative',$post,function(response){
				$('.classification_setup_content').html(response);
				$('.classification_table').dataTable(datatable_option);
			});

		},get_department:function(){
			$post = {
				location : $('#project option:selected').val(),
			};

			$.post('<?php echo base_url().index_page();?>/setup/user_setup/get_division',$post,function(response){
				$('#department').select({
					json : response,
					attr : {
							text  : 'division_name',
							value : 'division_id',
					}
				});
			},'json');
		},bindEvent:function(){
			$('#profit_center').on('change',this.get_classification_setup);
			$('#class_save').on('click',this.class_save);
			$('#class_reset').on('click',this.class_reset);
			$('body').on('click','.update_class',this.update);
			$('#back_to').on('click',this.back_to);
			$('#txtShortDesc').on('change',this.account_type);

		},class_save:function(){

			if($('.required').required()){
				return false;
			}
			
			$.save();

			$post = {
				project        : $('#project option:selected').val(),
				profit_center  : $('#profit_center option:selected').val(),
				person_code    : $('#employee option:selected').attr('data-profile-no'),
				employee_id    : $('#employee option:selected').val(),
				employee_name  : $('#employee option:selected').text(),
				department     : $('#department option:selected').val(),
				username       : $('#username').val(),
				password       : $('#password').val(),
				usertype       : $('#usertype option:selected').val(),
				id             : $('#id').val(),
			};

			$.post('<?php echo base_url().index_page();?>/setup/user_setup/save_user',$post,function(response){
					switch(response){
						case"1": 
							$.save({action : 'success'});
						break;
						default :
							$.save({action : 'hide'});
						break;
					}

				$('.required,.clear').val('');
				app_sub_classification.get_classification_setup();

			}).error(function(){
				alert('Service Unavailable');
			});

			$('#class_save').val('Save');

		},update:function(){

			var tr = $(this).closest('tr');

			var edit = {					
					project    : tr.find('td.proj_main').text(),
					profit_center : tr.find('td.proj_code').text(),
					employee   : tr.find('td.emp_id').text(),
					department : tr.find('td.dept_code').text(),
					username   : tr.find('td.username').text(),
					usertype   : tr.find('td.user_type').text(),
					id         : tr.find('td.id').text(),					
			};

			$.each(edit,function(i,val){
				$('#'+i).val(val);
			});
			
			$('#class_save').val('Update');

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

		}
	};

	$(function(){		
		app_sub_classification.init();
	});
</script>

