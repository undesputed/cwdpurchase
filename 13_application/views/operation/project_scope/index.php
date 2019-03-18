<div class="header">
	<h2>Project Scope</h2>	
</div>

<div class="container">		
	<div class="row">
		<div class="col-md-3">
					<div class="content-title">
						<h3>Create Project Scope</h3>
					</div>	

				  <div class="panel panel-default">		
				  <div class="panel-body">
				  	<div id="alert"></div>			  			
					<form action="" method="POST" id="form">
						
			  			<div class="form-group">
			  				<input type="hidden" id="id" name="id" value="" class="clear">
					  		<div class="control-label">Project</div>				  		
					  		<select id="project" class="form-control"></select>
			  			</div>
			  			<div class="form-group">			  				
					  		<div class="control-label">Profit Center</div>				  		
					  		<select id="profit_center" class="form-control"></select>
			  			</div>
			  			<div class="form-group" style="display:none">			  				
					  		<div class="control-label">ref No</div>				  		
					  		<select type="text" id="ref_no" value="ref_no"></select>
			  			</div>
					</form>	  								  							  		
				  </div>
				</div>	
		</div>			

		<div class="col-md-9">
				<div class="content-title">
						<h3>Scope List</h3>
					</div>	
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-3">
								<div class="checkbox">								
									<input type="checkbox" name="active" value="%" id="active"> <label for="active">Show Inactive</label>
								</div>
							</div>
							<div class="col-md-5 form-horizontal">
								<div class="">
									<div class="form-group">
										<div class="control-label col-sm-4">Scope of work</div>
										<div class="col-sm-8">
											<select name="" id="scope_list" class="form-control ">
												<?php foreach($all_scope as $key => $value): ?>
													<option value="<?php echo $value['id']; ?>"><?php echo $value['type'] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<button class="btn btn-primary" id="add">Add</button>
							</div>
						</div>												
					</div>
					<div class="table-content">
					<table class="table table-striped myTable" id="table">
						<thead>
							<tr>
								<th>Project</th>
								<th>Location</th>
								<th>Type</th>
								<th>Scope</th>
								<th width="20px"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="5">No Data Added</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="5">
								<div class='save-control'>
									<button class="pull-right btn btn-primary" id="save">Save</button> <small class="note hide pull-right text-danger">Data modified but not Save</small>
								</div>
							    </td>
							</tr>
						</tfoot>
					</table>
					</div>

				</div>
		</div>	
	</div>
</div>

<script>
	var added_scope = [];
	var alphabet = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
	var app = {
		init:function(){
			var option = {
				profit_center : $('#profit_center'),
				txtprojno         : $('#ref_no'),
			}			
			$('#project').get_projects(option);
			this.bindEvents();			

			$('#scope_multiple').chosen();

		},bindEvents:function(){

			$('#profit_center').on('change',this.get_scope);
			$('#active').on('change',this.get_scope);
			$('#add').on('click',this.add_scope);
			$('#save').on('click',this.save);
			$('body').on('click','.remove',this.remove);
			
		},get_scope:function(){

			$post = {
				location : $('#profit_center option:selected').val(),
				action   : $('#active:checked').val(),
			}

			$('.table-content').content_loader(true);
			$.post('<?php echo base_url().index_page();?>/operation/project_scope/get_scope',$post,function(response){
				added_scope = response;				
				app.render();
			},'json');

		},add_scope:function(){
			$location = $('#profit_center option:selected').text().split('-');
			var scope = {
				id       : $('#scope_list option:selected').val(),
				title    : $('#scope_list option:selected').text(),
				ref_no   : $('#ref_no option:selected').text(),
				location_name : $location[1] ,
				location : $('#profit_center option:selected').val(),
				project  : $('#project option:selected').val(),
			}
			
			if(jQuery.inArray(scope,added_scope)!= -1){
				alert('Already Added');
				return false;
			}else{
				scope.type = alphabet[added_scope.length];
				added_scope.push(scope);			
			}

			$('.note').removeClass('hide');

			app.render();			
		},render:function(){
			$('#table tbody').html('');
			var row;
			$('.table-content').content_loader(true);
			if(added_scope.length==0){
				row += '<tr><td colspan="5">No Data Added</td></tr>';
			}else{

				$.each(added_scope,function(i,val){
					row += "<tr>";
				    row += "<td>"+val['ref_no']+"</td>";
				    row += "<td>"+val['location_name']+"</td>";
				    row += "<td>"+val['type']+"</td>";
				    row += "<td class='editable-td'>"+val['title']+"</td>";
				    row += "<td><span class='action'><a href='javascript:void(0)' class='remove'>Remove</a></span></td>";
					row += "</tr>";
				});

			}
			$('#table tbody').html(row);
			$('.table-content').content_loader(false);
		},save:function(){
			if(added_scope.length==0){
				alert('Please Add a Scope');
				return false;
			}					
			$post = {
				data : JSON.stringify(added_scope),
			}
			$.save();
			$.post('<?php echo base_url().index_page(); ?>/operation/project_scope/save_scope',$post,function(response){
				$.save({action :'success'});
				$('.note').addClass('hide');
			}).error(function(){
				$.save({action : 'error'});
			});

		},remove:function(){
			var index = $(this).closest('tr').index();
			added_scope.splice(index,1);
			app.render();
			
			$('.note').removeClass('hide');
		}
	};

	$(function(){		
		app.init();		
		$('.editable-td').editable_td({
			insert   : 'select',
			clone    : $('#scope_list'),
			callback : function( response ,select ){
					  var index =  $(response).closest('tr').index();
					  //console.log($(select).val());
					  added_scope[index]['id'] = $(select).val();
					  added_scope[index]['title'] = $(response).text();
					  $('.note').removeClass('hide');
			}
		});
	});
</script>
