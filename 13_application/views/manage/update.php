<?php 
	$name = explode(',', $update['UserFull_name']);

 ?>

<div class="container">
	<div class="content-title">
		<h3>Update User</h3>
	</div>	

	<div class="row">
		<div class="col-md-12">
				<input type="hidden" id="user_id" value="<?php echo $update['SysPK_User'] ?>">
				<div class="form-group">
			  			<div class="control-label">First Name</div>
			  			<input type="text" name="" id="firstname" class="form-control input-sm required" placeholder="Enter Firstname" value="<?php echo $name[1]; ?>">
			  	</div>

			  	<div class="form-group">
			  			<div class="control-label">Last Name</div>
			  			<input type="text" name="" id="lastname" class="form-control input-sm required" placeholder="Enter Lastname" value="<?php echo $name[0]; ?>">
			  	</div>
				<hr>				
		  		<div class="form-group">
			  			<div class="control-label">Username</div>
			  			<input type="text" name="" id="username" class="form-control input-sm required" placeholder="Enter Username" value="<?php echo $update['UserName_User'] ?>">
			  	</div>

				<div class="form-group">
			  			<div class="control-label">Password</div>
			  			<input type="password" name="" id="password" class="form-control input-sm required" placeholder="Enter Password">
			  	</div>

			  	<?php $option = array(
			  				array('value'=>'USER'),
			  				array('value'=>'ADMIN')
			  	); ?>
			  	<div class="form-group">
			  			<div class="control-label">User Type</div>
			  			<select name="" id="userType" class="form-control input-sm">
							<?php foreach($option as $row): 
								$selected = ($update['Type_User']==$row['value'])? "selected='selected'" : "";
							?>			  				
							<option <?php echo $selected; ?> value="<?php echo $row['value']?>"><?php echo $row['value']?></option>
							<?php endforeach; ?>
			  			</select>
			  	</div>
				<hr>

				<div class="form-group">
					<input type="submit" id="save" value="Update User" class="btn btn-success pull-right ">
				</div>
						  	

		</div>
	</div>

	
</div>

<script>
	$(function(){
		var create_app = {
			init:function(){
				this.bindEvent();

			},
			bindEvent:function(){
				$('#save').on('click',function(){
					if($('.required').required()){
						return false;						
					}

					$post = {
						firstname : $('#firstname').val(),
						lastname  : $('#lastname').val(),
						username  : $('#username').val(),
						password  : $('#password').val(),
						usertype  : $('#userType option:selected').val(),
						id  	  : $('#user_id').val(),
					};

					$.save({appendTo : '.fancybox-outer'});
					$.post('<?php echo base_url().index_page();?>/manage/run_update',$post,function(response){
						$.save({action:'success',reload :'true'});	
					}).error(function(){
						alert('Failed to Save');
					});



				});
			}

		};

		create_app.init();

	});


</script>