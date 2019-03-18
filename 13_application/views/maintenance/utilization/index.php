
<div class="header">
	<h2>Equipment Utilization Setup</h2>	
</div>

<div class="container">
	<div class="content-title">
		<h3>Add Checklist</h3>
	</div>		
	<div class="row">
		<div class="col-md-3">
				  <div class="panel panel-default">		
				  <div class="panel-body">
				  	<div id="alert"></div>			  			
					<form action="" method="POST" id="form">
						
			  			<div class="form-group">
			  				<input type="hidden" id="id" name="id" value="">
					  		<div class="control-label">Equipment Name</div>				  		
					  		<select name="equipment_name" id="equipment_name" class="form-control">
					  			<?php foreach($item_name as $key => $value): ?>
									<option value="<?php echo $value['group_detail_id']; ?>"><?php echo $value['description']; ?></option>
					  			<?php endforeach; ?>
					  		</select>
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

			$('body').on('change','#checkboxAll',this.checkbox);

			$('#equipment_name').on('change',this.equip_checklist);

			
		},get_list:function(args){
			$('.table-content').content_loader(true);

			$post = {
				item_no : $('#equipment_name option:selected').val(),
			}

						
			$.post('<?php echo base_url().index_page(); ?>/maintenance/utilization/get_checklist',$post,function(response){				
				$('.table-content').html(response);

				datatable_option.aoColumnDefs = [
         			{ 'bSortable': false, 'aTargets': [ 1 ] }
      			];
				$('.myTable').dataTable(datatable_option);

				
				
			});		
		},save:function(e){
			e.preventDefault();
			$('#save').addClass('disabled');

			$post = {
				equipment_name : $('#equipment_name option:selected').val(),
				checklist : app.get_nodes(),
			}
			
			$.post('<?php echo base_url().index_page(); ?>/maintenance/utilization/save_utilization',$post,function(response){
				
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

		},checkbox:function(){
			var status = ($('#checkboxAll').is(':checked'));
			$('.chk-box').prop('checked',status);

			var rows = $(".myTable").dataTable().fnGetNodes();
			for(var i=0;i<rows.length;i++){			            
		           $(rows[i]).find('.chk-box').prop('checked',status);		           		           
		    }


		},equip_checklist:function(){
			
			app.get_list($post);
		},get_nodes:function(){
			var rows = $(".myTable").dataTable().fnGetNodes();
				var cells = new Array();
				for(var i=0;i<rows.length;i++)
		        {						 
		            if($(rows[i]).find('.chk-box').is(':checked')){
		            	cells.push($(rows[i]).find('td:first').text()); 	
		            }		            
		        }		        
		        return cells;				
		}

	}

$(function(){
	app.init();
});
</script>