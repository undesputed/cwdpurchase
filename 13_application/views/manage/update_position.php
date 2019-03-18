

<div class="container">
	<div class="content-title">
		<h3>Update Position</h3>
	</div>	

	<div class="row">
		<div class="col-md-12">
			<h3><?php echo $update['firstname']; ?></h3>
				<input type="hidden" id="user_id" value="<?php echo $update['id'] ?>">				
			  	<?php $option = array(
			  				array('value'=>'user'),
			  				array('value'=>'admin'),
			  				array('value'=>'dispatcher')
			  	); ?>
			  	<div class="form-group">
			  			<div class="control-label">User Type</div>
			  			
			  			<select name="" id="userType" class="form-control input-sm">
							<?php foreach($option as $row): 
								$selected = ($update['position']==$row['value'])? "selected='selected'" : "";
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
						usertype  : $('#userType option:selected').val(),
						id  	  : $('#user_id').val(),
					};

					$.save({appendTo : '.fancybox-outer'});
					$.post('<?php echo base_url().index_page();?>/manage/run_update_position',$post,function(response){
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