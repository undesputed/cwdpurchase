
<div class="content-page">
 <div class="content">

	<div class="header">
		<h2>Subsidiary Setup</h2>
	</div>

	<div class="row" style="margin-top:1em;">
		<div class="col-md-12">
			<div class="panel panel-default">		
			  	<table class="table">
			  		<tbody>
			  			<tr>
			  				<td style="width:80px">Type : </td>
			  				<td>
			  					<select name="" id="type" class="form-control">
			  						<?php foreach($account_ledger as $row): ?>
			  							<option value="<?php echo $row['type']; ?>"><?php echo $row['type']; ?></option>
			  						<?php endforeach; ?>
			  					</select>
			  				</td>
			  			</tr>			  				  		
			  		</tbody>
			  	</table> 
			  	<div class="panel panel-body" id="panel-body">
			  		
			  	</div>

			</div>
		</div>
	</div>	

</div>

<script>
	$(function(){
		var app_data  = {
			init:function(){
				this.bindEvent();
			}
			,bindEvent:function(){
				$('#type').on('change',this.type);
				$('#type').trigger('change');
			},type:function(){
				$post = {
					type : $('#type option:selected').val(),
				}
				$('#panel-body').html('Loading...');
				$.post('<?php echo base_url().index_page();?>/setup/subsidiary_setup/type',$post,function(response){
					$('#panel-body').html(response);
				});
			}
		}
		app_data.init();
	});
</script>