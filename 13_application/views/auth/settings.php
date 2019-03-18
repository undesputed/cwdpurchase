



<?php 	
	$name = explode(',',$user['UserFull_name']);	
?>

<div class="content-page">
	<div class="content">

<div class="header">
	<h2>Account Settings</h2>
</div>
<div class="container">
	
	<div class="row">
		<div class="col-md-4">
			<div class="content-title">
				<h3>User Account</h3>
			</div>
			<span style="color:#f00"><?php echo $message; ?></span>
			<form action="" method="post">

				<div class="form-group">
					<h5>Firstname</h5>
					<input type="text" name="firstname" placeholder="First Name" class="form-control input-sm" value="<?php echo $name[0]; ?>">
				</div>
				
				<div class="form-group">
					<h5>Middle Name</h5>
					<input type="text" name="middlename" placeholder="Middle Name" class="form-control input-sm" value="<?php echo $name[2]; ?>">
				</div>

				<div class="form-group">
					<h5>Lastname</h5>
					<input type="text" name="lastname" placeholder="Last Name" class="form-control input-sm" value="<?php echo $name[1]; ?>">
				</div>

				<hr>

				<div class="form-group">
					<h5>Username</h5>
					<input type="text" name="username" placeholder="Username" class="form-control input-sm" value="<?php echo $user['UserName_User']; ?>">
				</div>
				
				<div class="form-group">
					<h5>New Password</h5>
					<input type="password" name="password" placeholder="New Password" class="form-control input-sm">
				</div>
				<div class="form-group">
					<h5>Confirm New Password</h5>
					<input type="password" name="conf_password" placeholder="Confirm Password" class="form-control input-sm">
				</div>
				
				<div class="form-group">
					<input type="submit" value="Save Changes" class="btn btn-primary btn-sm">
				</div>
			</form>
		</div>
	</div>
	
</div>

</div>
</div>


