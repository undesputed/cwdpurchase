<div class="header">
	<h2>Employee Setup</h2>
</div>


<input type="hidden" id="emp_id" value="<?php echo $get_emp_no['max'] ?>" >

<?php  $emp_id = date('Ymd').$get_emp_no['max']; ?>


<div class="container">

	<input type="hidden" value="" id="total">
		
	<div class="row">			
			<div class="col-md-6">

				<div class="content-title">
					<h3>Employee information</h3>	
				</div>
				
				<div class="panel panel-default">	
					<div class="panel-body">
						
						<div class="form-group">
							<div class="control-label">Company Name</div>
							<select name="" id="project" class="form-control input-sm">				  			
				  			</select>
						</div>
				  		
						
						<div class="form-group">
							<div class="control-label">Project Site</div>
				  			<select name="" id="profit_center" class="form-control input-sm"></select>	
						</div>
						
						<hr>
						<div class="form-group">
							<div class="control-label">Employee ID</div>
							<input type="text" value="<?php echo $emp_id ?>" class="form-control" id="employee_id" >				  			
						</div>
						
						<div class="form-group">
							<div class="control-label">Name</div>
				  			<select name="" id="name" class="form-control input-sm">
				  			<?php foreach($get_person as $row): ?>
								<option value="<?php echo $row['pp_person_code'] ?>"><?php echo $row['pp_fullname']; ?></option>
				  			<?php endforeach; ?>
				  			</select>				  			
						</div>

					<div class="form-group" style="display:none">
						<div class="control-label">Status</div>
									  			<select name="" id="status" class="form-control input-sm">
									  			<?php foreach($get_jobStatus as $row): ?>
							<option value="<?php echo $row['jobStatusCode'] ?>"><?php echo $row['jobStatusName']; ?></option>
									  			<?php endforeach; ?>			  					
									  			</select>				  			
					</div> 
						
					</div>

				</div>
			</div>

			<div class="col-md-6">
				<div class="content-title">
					<h3>Position</h3>	
				</div>
				<div class="btn-group pull-right" style="margin-top:1em;">
							
					  <button type="button" class="btn btn-default dropdown-toggle " data-toggle="dropdown">
					    <i class="fa fa-gear"></i>
					  </button>

					  <ul class="dropdown-menu" role="menu">
					   <!--  <li class=""><a href="javascript:void(0)" id="add_status">Add Status</a></li> -->
					    <li class=""><a href="javascript:void(0)" id="add_position">Add Position</a></li>
					    <!-- <li class=""><a href="javascript:void(0)" id="add_division">Add Division</a></li>
					    <li class=""><a href="javascript:void(0)" id="add_salary_grade">Add Salary Grade</a></li> -->
					  </ul>			

				</div>


						<div class="panel panel-default">		
						  <div class="panel-body">
					
						<div class="form-group">
							<div class="control-label">Position</div>							
				  			<select name="" id="position" class="form-control input-sm">
				  				<?php foreach($get_jobPosition as $row): ?>
									<option value="<?php echo $row['jobtit_code'] ?>"><?php echo $row['jobtit_name']; ?></option>
				  				<?php endforeach; ?>
				  			</select>				  			
						</div>
						
						<div class="form-group" style="display:none">
							<div class="control-label">Division</div>
				  			<select name="" id="division" class="form-control input-sm">
				  				
				  			</select>				  			
						</div> 
											
						<div class="form-group" style="display:none">
							<div class="control-label">Salary Grade</div>
				  			<select name="" id="salary_grade" class="form-control input-sm">
				  				
				  			</select>				  			
						</div> 

						<div class="form-group" style="display:none">
							<div class="control-label">Joined Date</div>
								<input type="text" value="" name="" id="date" class="form-control input-sm">
						</div> 
											  
						 </div>	 
					</div>
						
			</div>			
			
			<div class="col-md-6" style="display:none;">
				<div class="content-title">
						<h3>Account Details</h3>																		
				</div>	
				
				<div class="panel panel-default">	
					<div class="panel-body">
						
						<div class="row">
							<div class="col-md-5">
								
								<div class="form-group">
									<div class="control-label">Email</div>
						  			<input type="input" id="email" class="form-control input-sm required " placeholder="-Email Address-">
								</div>

								<div class="form-group">									
						  			<div class="checkbox-inline">
						  				<input type="checkbox" id="termination"><label for="termination">Termination</label>
						  			</div>
								</div>

							</div>

							<div class="col-md-7">
								<div class="form-group">
									<div class="control-label">Remarks</div>
									<textarea name="" id="remarks" cols="30" rows="2" class="form-control input-sm "></textarea>						  								  									  		
								</div>
							</div>

						</div>
						
						<div class="form-group">
									<div class="control-label">Rate Per Day</div>
						  			<input name="" id="rate_per_day" class="form-control input-sm numbers_only" placeholder="0.00">
						</div>

						<div class="row">
							
							<div class="col-md-6">
								<div class="form-group">
									<div class="control-label">SSS</div>
						  			<input name="" id="sss" class="form-control input-sm numbers_only" placeholder="0.00">
								</div>
							</div>							
							
							<div class="col-md-6">
								<div class="form-group">
									<div class="control-label">Philhealth</div>
						  			<input name="" id="philhealth" class="form-control input-sm numbers_only" placeholder="0.00">
								</div>
							</div>

						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<div class="control-label">HDMF</div>
						  			<input name="" id="hdmf" class="form-control input-sm numbers_only" placeholder="0.00">
								</div>
							</div>							

							<div class="col-md-6">
								<div class="form-group">
									<div class="control-label">W/TAX</div>
						  			<input name="" id="tax" class="form-control input-sm numbers_only" placeholder="0.00">
								</div>
							</div>
						</div>					
					</div>									
				</div>			
			</div>
			
				
	</div>
	<div class="row">
		<div class="col-xs-12">
			<input id="save" class="btn btn-primary pull-right btn-sm" type="submit" value="Save">	
		</div>
	</div>
	
</div>

<script>
	
	var app_create = {
		init:function(){

			$('#date').date();
			var option = {
				profit_center : $('#profit_center'),
				call_back : function(){
					app_create.bindEvents();
				}
			}			
			$('#project').get_projects(option);

		},bindEvents:function(){
			$('#save').on('click',this.save);
			$('#add_status').on('click',this.add_status);
			$('#add_position').on('click',this.add_position);
			$('#add_salary_grade').on('click',this.add_salaryGrade);
			$('#add_division').on('click',this.add_division);
		},save:function(){

			var bool = confirm('Do you want to Proceed?');
			if(!bool){
				return false;
			}
						
			$post = {
				emp_number           : $('#emp_id').val(),
				person_profile_no    : $('#name option:selected').val(),
				employee_id          : $('#employee_id').val(),
				emp_status           : $('#status option:selected').val(),
				emp_position         : $('#position option:selected').val(),
				department_code      : $('#division option:selected').val(),
				sal_grd_code         : $('#salary_grade option:selected').val(),
				joined_date          : $('#date').val(),
				emp_email            : $('#email').val(),
				terminated_date      : "1901-01-01",
				termination_reason   : "",
				record_status        : "ACTIVE",
				emp_remarks          : $('#remarks').val(),
				current_assignDept   : $('#division option:selected').text(),
				rate_per_day         : $('#rate_per_day').val(),
				sss                  : $('#sss').val(),
				hdmf                 : $('#hdmf').val(),
				philhealth           : $('#philhealth').val(),
				wholdingTax          : $('#tax').val(),
				title_id             : $('#profit_center option:selected').val(),
			}

			$.save({appendTo : '.fancybox-outer'});
			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/save_user',$post,function(response){
					switch($.trim(response)){
						case "1":
							$.save({action : 'success',reload : 'true'});							
						break;
						case "exist":							
							$.save({success : 'Account Already Exist',action : 'delay-hide',delay : '2000'});
						break;
						default:
							$.save({action : 'hide'});
						break;
					}
			}).error(function(){
				alert("Service Unavailable..");
				$.save({action : 'hide'});
			});

		},add_status : function(){
			$.save({appendTo : '.fancybox-outer','loading':'Processing...'});

			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/add_status',$post,function(response){

				$.fancybox(response,{
					width     : 1000,
					height    : 550,
					fitToView : false,
					autoSize  : true,
				})


			});
		},add_position : function(){

			$.save({appendTo : '.fancybox-outer','loading':'Processing...'});
			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/add_position',$post,function(response){

				$.fancybox(response,{
					width     : 1000,
					height    : 550,
					fitToView : true,
					autoSize  : false,
				})

			});

		},add_salaryGrade:function(){

			$.save({appendTo : '.fancybox-outer','loading':'Processing...'});
			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/add_salaryGrade',$post,function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})

			});
		},add_division:function(){

			$.save({appendTo : '.fancybox-outer','loading':'Processing...'});
			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/add_division',$post,function(response){

				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			
			});
		}

	}


	$(function(){
		app_create.init();
	});


</script>