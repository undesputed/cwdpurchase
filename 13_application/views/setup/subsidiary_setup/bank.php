
<div class="container">
	
	<table class="table">
		<tbody>
			<tr>
				<td style="width:130px">Bank Name:</td>
				<td><input type="text" class="form-control required uppercase" id="bank_name"></td>
			</tr>
			<tr>
				<td>Address:</td>
				<td><input type="text" class="form-control uppercase" id="address"></td>
			</tr>
			<tr>
				<td colspan="2">
					<button class="btn btn-primary" id="save">Save</button>
				</td>
			</tr>
		</tbody>
	</table>
	
</div>

<script>
	$(function(){

		var app = {
			init:function(){
				this.bindEvent();
			},
			bindEvent:function(){
				$('#save').on('click',this.save);
			},save:function(){
				if($('.required').required()){
					return false;
				}

				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}

				$post = {
					type          : $('#type option:selected').val(),
					bank_name  : $('#bank_name').val(),
					bank_address    : $('#address').val(),
				};

				$.post('<?php echo base_url().index_page();?>/setup/subsidiary_setup/save_type',$post,function(response){
					switch($.trim(response)){
						case "1":
							alert('Successfully Added');
							location.reload('true');
						break;
					}
				});

			}
		}

		app.init();
	});
</script>