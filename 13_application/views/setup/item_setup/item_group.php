<div class="container">
	<h2>Item group Setup</h2>
	<div class="row">
		<div class="col-xs-12">
			<div class="form-group">
				<div class="control-label">Input Item Group name</div>			
				<input type="text" class="form-control uppercase required2" id="item_group_name">	
			</div>	
			<div class="form-group">
				<input type="button" id="group_name" class=" pull-right btn btn-primary" value="Save">
			</div>
		</div>
	</div>

</div>

<script>

	$(function(){
		var xhr1 = '';
		var item_group_app = {
			init:function(){
				this.bindEvent();
			},bindEvent:function(){
				$('#group_name').on('click',this.save);
			},save:function(){

				if($('.required2').required()){
					return false;	
				}				

				$post = {
					group_name : $('#item_group_name').val()
				}

		        if(xhr1 && xhr1.readystate != 4){
		            xhr1.abort();
		        }
		        
				xhr1 = $.post('<?php echo base_url().index_page();?>/setup/item_setup/save_item_group',$post,function(response){
						alert('Successfully Save');
						location.reload(true);
				});

			}
		};

		item_group_app.init();

	});

</script>