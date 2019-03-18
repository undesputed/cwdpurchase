<div class="container">
	<h2>Edit item group Setup</h2>
	<div class="row">
		<div class="col-xs-12">
	
			<input type="hidden" value="<?php echo $item_group; ?>" id="item_group_id">
			<div class="form-group">
				<div class="control-label">Edit Item Group name</div>			
				<input type="text" class="form-control uppercase required2" id="item_group_name" value="<?php echo $item_group_name; ?>">	
			</div>	
			<div class="form-group">
				<input type="button" id="group_name" class=" pull-right btn btn-primary" value="Update">
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
					group_name : $('#item_group_name').val(),
					group_id   : $('#item_group_id').val(),
				}

		        if(xhr1 && xhr1.readystate != 4){
		            xhr1.abort();
		        }
		        $('#group_name').addClass('disabled');
				xhr1 = $.post('<?php echo base_url().index_page();?>/setup/item_setup/update_item_group',$post,function(response){					
						alert('Successfully Updated');
						$('#group_name').removeClass('disabled');
						$.fancybox.close();
						app_sub_classification.get_items($post.group_id);
						app_sub_classification.get_classification_setup();
				});

			}
		};

		item_group_app.init();

	});

</script>