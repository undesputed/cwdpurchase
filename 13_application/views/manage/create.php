

<div class="container">
	<div class="content-title">
		<h3>Create User</h3>
	</div>	

	<div class="row">
		<div class="col-md-12">

				<div class="form-group">
			  			<div class="control-label">First Name</div>
			  			<input type="text" name="" id="firstname" class="form-control input-sm required" placeholder="Enter Firstname">
			  	</div>

			  	<div class="form-group">
			  			<div class="control-label">Last Name</div>
			  			<input type="text" name="" id="lastname" class="form-control input-sm required" placeholder="Enter Lastname">
			  	</div>
				<hr>				
		  		<div class="form-group">
			  			<div class="control-label">Username</div>
			  			<input type="text" name="" id="username" class="form-control input-sm required" placeholder="Enter Username">
			  	</div>

				<div class="form-group">
			  			<div class="control-label">Password</div>
			  			<input type="password" name="" id="password" class="form-control input-sm required" placeholder="Enter Password">
			  	</div>
			  	<div class="form-group">
			  			<div class="control-label">User Type</div>
			  			<select name="" id="userType" class="form-control input-sm">			  				
			  				<option value="user">Admin</option>
			  				<option value="user">User</option>
			  				<option value="user">dispatcher</option>
			  			</select>
			  	</div>			  	
				<hr>

				<div class="form-group">
					<input type="submit" id="save" value="Save User" class="btn btn-success pull-right disabled">
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
				$('.required').on('keyup',function(){
					$('.required').each(function(i,value){
						if($(value).val()!=""){
								$('#save').removeClass('disabled');
						}else{
								$('#save').addClass('disabled');
						}

					});		
				});
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
					};

					$.save({appendTo : '.fancybox-outer'});
					$.post('<?php echo base_url().index_page();?>/manage/save',$post,function(response){
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