<div class="header">
	<h2>Area Activity</h2>	
</div>

<div class="container">
	<div class="content-title">
		<h3>Create Area Activity</h3>
	</div>
		
	<div class="row">
		<div class="col-md-3">
				  <div class="panel panel-default">		
				  <div class="panel-body">
				  	<div id="alert"></div>			  			
					<form action="" method="POST" id="form">
						
			  			<div class="form-group">
			  				<input type="hidden" id="id" name="id" value="" class="clear">
					  		<div class="control-label">Description</div>				  		
					  		<input type="text" class="form-control uppercase clear" name="description" id="type" required>
			  			</div>
			  			<div class="form-group">
			  				
					  		<div class="control-label">Code</div>				  		
					  		<input type="text" class="form-control uppercase clear" name="code" id="code" required>
			  			</div>
							
						<div class="checkbox">
							<input type="checkbox" name="factor" value="YES" id="factor"> <label for="factor" >Factor</label>
						</div>	

			  			<input type="submit" id="save" class="btn btn-primary col-md-5" name="submit" value="Save">

					</form>	  								  							  		
				  </div>
				</div>	
		</div>			

		<div class="col-md-9">
			<div class="table-content">
				<table class="table">
					<thead>
						<tr>
							<th>Category</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Empty Result</td>
						</tr>
					</tbody>
				</table>	
			</div>			 
		</div>
	
	</div>

</div>

<script type="text/javascript">

	var app = {
		init:function(){					
			this.bindEvents();
			this.get_list();
			this.clear();
		},bindEvents:function(){

			$('#form').bind('submit',this.save);
			$('body').on('click','#edit',this.edit)
			$('body').on('click','#reset',this.clear);
			
		},get_list:function(){
			$('.table-content').content_loader(true);
			$.get('<?php echo base_url().index_page(); ?>/operation/area_activity/get_list',function(response){

				$('.table-content').html(response);
				$('.myTable').dataTable(datatable_option);

			});		
		},save:function(e){
			e.preventDefault();
			$('#save').addClass('disabled');
			$.save();
			$.post('<?php echo base_url().index_page(); ?>/operation/area_activity/insert_list',$('#form').serialize(),function(response){
				$.save({action :'success'});
				switch(response){
					case "0" : 
						$('#alert').my_alert('success');
						break;
					case "2" : 
						$('#alert').my_alert('update');
						break;
				}

				$('#form :input[type="text"]').val('');				
				app.get_list();
				$('#save').removeClass('disabled');
				app.clear();

			});		
		},edit:function(){
			
			var data = {
				id   : $(this).closest('tr').find('td:first').text(),
				type : $(this).closest('tr').find('td:nth-child(2) .data').text(),
				code : $(this).closest('tr').find('td:nth-child(3) .data').text(),
			}			
						
			$.each(data,function(i,value){				
				$('#'+i).val(value);
			});

			$('#save').val('Update');
			if($('#reset').length == 0){
				$('#save').after('<input type="button" id="reset" class="btn btn-link" value="Reset">');
			}
									

		},clear:function(){
			$('.clear').val('');

			$('#save').val('Save');
			$('#reset').remove();

		}
	}

$(function(){
	app.init();
});
</script>