<div class="content-page">

	<div class="content">
		<div class="header">
			<h2>Cost Entry</h2>
		</div>

		<div class="container">
			<div class="content-title">
				<h3>Cost Entry</h3>	
			</div>
					
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-8">
						  		<input type="hidden" id="id" class="form-control">
								<div class="form-group" style="display:none">
						  			<div class="control-label">Company Name</div>
						  			<select name="" id="create_project" class="form-control input-sm"></select>
						  		</div>
						  		<div class="form-group">
						  			<div class="control-label">Project Site</div>
						  			<select name="" id="create_profit_center" class=""></select>
						  		</div>				
						  		<div class="form-group">
						  			<div class="control-label">Project</div>
						  			<select name="" id="main-category" >
							  			<?php foreach($project_category as $row): ?>
								  			<option value="<?php echo $row['id']; ?>"><?php echo $row['project_name']; ?></option>					  			
							  			<?php endforeach; ?>
							  		</select>
						  		</div>
						  		<div class="form-group">
						  			<div class="control-label">Description</div>
						  			<input type="text" id="description" class="form-control input-sm required uppercase">
						  		</div>
						  		<div class="form-group">
						  			<div class="control-label">Cost</div>
						  			<input type="text" id="cost" class="form-control numbers_only comma" style="width:150px" placeholder="0.00">
						  		</div>
						  		<input id="save" class="btn btn-success col-xs-2 pull-right btn-sm" type="submit" value="Save">
							</div>
							<div class="col-xs-4">
								
							</div>
						</div>
						
					</div>
				</div>
					

				<div class="panel panel-default">
					<div class="classification_cost" style="margin-top:10px;">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Description</th>
									<th>Cost</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
		</div>

	</div>
</div>

<script>
	var labor_cost_app = {
		init:function(){
			this.get_labor_cost();
			this.bindEvent(); 

			var option = {
				profit_center : $('#create_profit_center'),
				main_office   : false,				
			}			

			$('#create_project').get_projects(option);


		},get_labor_cost:function(){
			$('.classification_cost').content_loader('true');
			$post = {
				project_id : $('#create_profit_center option:selected').val(),
			}
			$.post('<?php echo base_url().index_page();?>/boq/get_cost',$post,function(response){
			$('.classification_cost').html(response);
		    $('.classification_table').dataTable(datatable_option);
		});
		},bindEvent:function(){
			$('#save').on('click',this.save);
			$('body').on('click', '.update', this.update);
			$('body').on('click', '.remove', this.remove);

			$('#create_profit_center').on('change',function(){
				labor_cost_app.get_labor_cost();
			});

		},save:function(){

			$.save();

			$post = {
				id               : $('#id').val(),
				description      : $('#description').val(),
				cost             : remove_comma($('#cost').val()),
				project_id       : $('#create_profit_center option:selected').val(),
				title_id         : $('#create_project option:selected').val(),
				main_category    : $('#main-category option:selected').val(),
				main_category_name : $('#main-category option:selected').text(),
			}

			$.post('<?php echo base_url().index_page();?>/boq/save_labor_cost', $post, function(response){
				switch(response){
					case"1" :
						$.save({action : 'success'});
					break;
					default :
						$.save({action :'hide'});
					break;
				}
				labor_cost_app.get_labor_cost();
			}).error(function(){
				alert('Service Unavailable');
			});

		},update:function(){

			var tr = $(this).closest('tr');
			 
			var edit = {
				
				id               : tr.find('td.id').text(),
				description      : tr.find('td.description').text(),
				cost             : tr.find('td.cost').text(),
				project_id       : tr.find('td.project_id').text(),
				title_id         : tr.find('td.title_id').text(),
				main_category    : tr.find('td.project_category_id').text(),
			}

			$.each(edit,function(i,val){
				$('#'+i).val(val);
			});

			$('#save').val('Update');
		},remove:function(){

			var bool = confirm('Are you sure?');

			if(!bool){				
				return false;
			}

			var tr = $(this).closest('tr');			
			$post = {
				id  : tr.find('td.id').text(),
			}
			
			$.post('<?php echo base_url().index_page();?>/boq/remove_cost', $post, function(response){
				switch(response){
					case"1" :
						$.save({action : 'success'});
					break;
					default :
						$.save({action :'hide'});
					break;
				}
				labor_cost_app.get_labor_cost();
			}).error(function(){
				alert('Service Unavailable');
			});

		}
	};
	$(function(){
		labor_cost_app.init();
	});
	
</script>