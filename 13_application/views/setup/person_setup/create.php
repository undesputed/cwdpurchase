<div class="header">
	<h2>Add Person</h2>
</div>


<div class="container">

<div class="content-title">
	<h3>Create New Person</h3>	
</div>

<input type="hidden" id="id" value="" class="clear">

<div class="row">
	<div class="col-md-2">
			<div class="panel panel-default">		
			  <div class="panel-body">
			  			<div class="form-group">
				  					<div class="control-label">Type</div>
									<select name="" id="type" class="form-control">
										<option value="EMPLOYEE">EMPLOYEE</option>
										<option value="SUPPLIER">SUPPLIER AFFILIATE</option>
									</select>
				  		</div>
			  </div>	 
			</div>
	</div>
	<div class="col-md-10">
		<div class="panel panel-default">		
				  <div class="panel-body">
				  		<div class="row">
				  			<!-- <div class="col-md-2">
				  				<div class="form-group">
				  					<div class="control-label">Prefix</div>
									<input type="text" id="prefix" class="form-control input-sm uppercase">
				  				</div>
				  			</div> -->
							
				  			<div class="col-md-4">
				  				<div class="form-group">
				  					<div class="control-label">First Name</div>
									<input type="text" id="first_name" class="form-control input-sm required uppercase">
				  				</div>
				  			</div>
							
				  			<div class="col-md-4">
				  				<div class="form-group">
				  					<div class="control-label">Middle Name</div>
									<input type="text" id="middle_name" class="form-control input-sm required uppercase">
				  				</div>
				  			</div>

				  			<div class="col-md-4">
				  				<div class="form-group">
				  					<div class="control-label">Last Name</div>
									<input type="text" id="last_name" class="form-control input-sm required uppercase">
				  				</div>
				  			</div>

				  			<!-- <div class="col-md-2">
				  				<div class="form-group">
				  					<div class="control-label">Suffix</div>
									<input type="text" id="suffix" class="form-control input-sm uppercase">
				  				</div>
				  			</div> -->
				  		</div>
						<hr>
				  		<div class="row">

				  			<div class="col-md-3">
				  				<div class="form-group">
				  					<div class="control-label">Date of Birth</div>
									<input type="text" id="date" class="form-control input-sm required">
				  				</div>
				  			</div>

				  			<div class="col-md-4">
				  				<div class="form-group">
				  					<div class="control-label">Birthplace</div>
									<input type="text" id="birthPlace" class="form-control input-sm uppercase">
				  				</div>
				  			</div>
							
				  			<div class="col-md-3">
				  				<div class="form-group">
				  					<div class="control-label">Gender</div>									
									<select type="text" id="gender" class="form-control input-sm">
										<option value="Male">Male</option>
										<option value="Female">Female</option>
									</select>
				  				</div>
				  			</div>
							
				  			<div class="col-md-2">
				  				<div class="form-group">
				  					<div class="control-label">Civil Status</div>
									<select type="text" id="civil_status" class="form-control input-sm">
										<option value="Single">Single</option>
										<option value="Married">Married</option>
										<option value="Windowed">Widowed</option>
									</select>
				  				</div>
				  			</div>
				  					  			
				  		</div>

				  </div>
				 <div class="form-footer">
						<div class="row">
							<div class="col-md-7"> </div>
							<div class="col-md-5">
								<input id="class_save" class="btn btn-success col-xs-5 pull-right btn-sm" type="submit" value="Save">						
													
							</div>
						</div>
		  	 	 </div>	 
		</div>		
	</div>
	
</div>

</div>

<script>

	var app_create ={
		init:function(){

			$('#date').date({
				option : {
					changeYear: true,
					dateFormat:'yy-mm-dd',
				}
			});
			this.bindEvent();

		},get_classification_setup:function(){
			$('.popup-content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/get_position',function(response){
				$('.popup-content').html(response);
				$('.sub-table').dataTable(datatable_option);
			});
		},bindEvent:function(){

			$('#class_save').on('click',this.class_save);

		},class_save:function(){

			if($('.required').required()){
				return false;
			}

			var con = confirm('Do you want to Proceed?');
			if(!con){
				return false;
			}
						
			$.save({appendTo : '.fancybox-outer'});
			
			$post = {
				// prefix          : $('#prefix').val(),
				first_name      : $('#first_name').val(),
				middle_name     : $('#middle_name').val(),
				last_name       : $('#last_name').val(),
				// suffix          : $('#suffix').val(),
				date            : $('#date').val(),
				birthPlace      : $('#birthPlace').val(),
				gender          : $('#gender').val(),
				civil_status    : $('#civil_status').val(),
				pp_type         : $('#type option:selected').val(),
			}

			$.post('<?php echo base_url().index_page();?>/setup/person_setup/save_person',$post,function(response){

					switch($.trim(response)){
						case "1": 
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

		}
	};

	$(function(){		
		app_create.init();
	});
</script>

