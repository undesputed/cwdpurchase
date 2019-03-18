<div class="header">
	<h2>Update Person</h2>
</div>


<div class="container">

<div class="content-title">
	<h3>Update Person</h3>	
</div>

<input type="hidden" id="person_code" value="<?php echo $person['pp_person_code']; ?>" class="clear">

<div class="row">
	<div class="col-md-2">
	

	</div>
	<div class="col-md-10">
		<div class="panel panel-default">		
				  <div class="panel-body">
				  		<div class="row">
				  			<div class="col-md-2">
				  				<div class="form-group">
				  					<div class="control-label">Prefix</div>
									<input type="text" id="prefix" class="form-control input-sm uppercase clear" value="<?php echo $person['pp_prefix']; ?>">
				  				</div>
				  			</div>
							
				  			<div class="col-md-3">
				  				<div class="form-group">
				  					<div class="control-label">First Name</div>
									<input type="text" id="first_name" class="form-control input-sm required uppercase" value="<?php echo $person['pp_firstname']; ?>">
				  				</div>
				  			</div>
							
				  			<div class="col-md-2">
				  				<div class="form-group">
				  					<div class="control-label">Middle Name</div>
									<input type="text" id="middle_name" class="form-control input-sm required uppercase" value="<?php echo $person['pp_middlename']; ?>">
				  				</div>
				  			</div>

				  			<div class="col-md-3">
				  				<div class="form-group">
				  					<div class="control-label">Last Name</div>
									<input type="text" id="last_name" class="form-control input-sm required uppercase" value="<?php echo $person['pp_lastname'] ?>">
				  				</div>
				  			</div>

				  			<div class="col-md-2">
				  				<div class="form-group">
				  					<div class="control-label">Suffix</div>
									<input type="text" id="suffix" class="form-control input-sm uppercase clear" value="<?php echo $person['pp_suffix']; ?>">
				  				</div>
				  			</div>
				  		</div>
						<hr>
				  		<div class="row">

				  			<div class="col-md-3">
				  				<div class="form-group">
				  					<div class="control-label">Date of Birth</div>
									<input type="text" id="date" class="form-control input-sm required" value="<?php echo $person['pp_dob']; ?>">
				  				</div>
				  			</div>

				  			<div class="col-md-4">
				  				<div class="form-group">
				  					<div class="control-label">Birthplace</div>
									<input type="text" id="birthPlace" class="form-control input-sm uppercase" value="<?php echo $person['pp_birthplace'] ?>">
				  				</div>
				  			</div>
							
				  			<div class="col-md-3">
				  				<div class="form-group">
				  					<div class="control-label">Gender</div>						  
									<select type="text" id="gender" class="form-control input-sm">										
										<?php $option = array('Male','Female'); ?>
										<?php foreach($option as $row): ?>
										<?php $selected = ($person['pp_sex']==$row)? "selected='selected'" : "selected='selected'" ; ?>
											<option <?php echo $selected; ?> value="<?php echo $row; ?>"><?php  echo $row; ?></option>
										<?php endforeach; ?>
									</select>
				  				</div>
				  			</div>
							
				  			<div class="col-md-2">
				  				<div class="form-group">
				  					<div class="control-label">Civil Status</div>
				  						<?php				  						
										$selected2 = array(
											'Single',
											'Married',
											'Windowed',
											);
										?>
									<select type="text" id="civil_status" class="form-control input-sm">
										<?php foreach($selected2 as $row): ?>
										<?php $selected = ($person['pp_civilstatus']==$row)? "selected='selected'" : "selected='selected'" ; ?>
											<option value="<?php echo $row; ?>"><?php echo $row; ?></option>
										<?php endforeach; ?>										
									</select>
				  				</div>
				  			</div>
				  					  			
				  		</div>

				  </div>
				 <div class="form-footer">
						<div class="row">
							<div class="col-md-7"> </div>
							<div class="col-md-5">
								<input id="class_save" class="btn btn-success col-xs-5 pull-right btn-sm" type="submit" value="Update">						
													
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
				id              : $('#person_code').val(),
				prefix          : $('#prefix').val(),
				first_name      : $('#first_name').val(),
				middle_name     : $('#middle_name').val(),
				last_name       : $('#last_name').val(),
				suffix          : $('#suffix').val(),
				date            : $('#date').val(),
				birthPlace      : $('#birthPlace').val(),
				gender          : $('#gender').val(),
				civil_status    : $('#civil_status').val(),
			}

			$.post('<?php echo base_url().index_page();?>/setup/person_setup/update_person',$post,function(response){

					switch($.trim(response)){
						case"1": 
							$.save({action : 'success',reload :'true'});
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

