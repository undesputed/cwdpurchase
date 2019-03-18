

<div class="container" style="min-width:100px;margin-top:3em">
		<div class="row">
			<div class="col-xs-12">			
					<h1 style="text-align:center"><?php echo $this->config->item('site_title') ?></h1>
			</div>
		</div>
		<div class="login-content" style="width:300px;margin: 2em auto;">
		<div class="row">
			<div class="col-xs-12">
					<form action="" method="post" class="form-signin">
						<h4  style="text-align:center">LOGIN</h4>
						<span class="login-message"><?php echo $message;?></span>
							<div class="form-group">
								<input type="text" class="form-control input-block-level" placeholder="Username" name="username" >	
							</div>
							<div class="form-group">
								<input class="form-control input-block-level" type="password" placeholder="Password" name="password">	
							</div>
						<div class="row">
							<div class="col-xs-6">
								<label class="checkbox">
									<input type="checkbox" name="remember" value="remember-me"> Remember
								</label>
							</div>
							<div class="col-xs-6">
								<input type="submit" class="btn btn-primary btn-block btn-large" value="Submit">
							</div>
						</div>											
					</form>
			</div>
		</div>
		</div>

</div>