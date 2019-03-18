<div class="header">
	<h2>Add Position</h2>
</div>


<div class="container">

<div class="content-title">
	<h3>Employee Position</h3>	
</div>

<div class="pull-right" style="margin-top:2em;">
	<span id="back_to" class="btn-link">Back to employee setup</span>
</div>


<input type="hidden" id="id" value="" class="clear">

<div class="row">
	<div class="col-md-4">

		<div class="panel panel-default">		
		  <div class="panel-body">
		  				  		

		  		<div class="form-group">
		  			<div class="control-label">Job Title</div>
		  			<input type="text" id="job_title" class="form-control input-sm required uppercase">
		  		</div>

		  		<div class="form-group">
		  			<div class="control-label">Job Description</div>
		  			<input type="text" id="job_description" class="form-control input-sm required uppercase">
		  		</div>

		  		<div class="form-group" style="display:none">
		  			<div class="control-label">Salary Grade</div>
		  			<select id="salary_grade" class="form-control input-sm required uppercase">
		  				<?php foreach($salary_grade as $row): ?>
		  					<option value="<?php echo $row['sal_grade_code'] ?>"><?php echo $row['sal_grade_name'] ?></option>
		  				<?php endforeach; ?>
		  			</select>
		  		</div>
				
		  		<div class="form-group">
		  			<div class="control-label">Status</div>
		  			<select id="status" class="form-control input-sm required uppercase">
		  				<option value="1">Active</option>
		  				<option value="0">Inactive</option>
		  			</select>
		  		</div>
				

		  </div>
		  <div class="form-footer">
				<div class="row">
					<div class="col-md-7"> </div>
					<div class="col-md-5">
						<input id="class_save" class="btn btn-success col-xs-5 pull-right btn-sm" type="submit" value="Save">						
						<input id="class_reset" class="btn btn-link col-xs-5 pull-right btn-sm" type="submit" value="Reset">						
					</div>
				</div>
		   </div>
		</div>
	
	</div>

	<div class="col-md-8">

		<div class="panel panel-default">		
		  <div class="panel-body">		  		
		  </div>	
		  <div class="popup-content">
		 	 <table class="table table-striped">
				<thead>
					<tr>
						<th>Type</th>						
					</tr>
				</thead>
			 </table>
		  </div> 
		</div>		
	</div>


</div>

</div>

<script>

	var app_sub_classification ={
		init:function(){

			this.get_classification_setup();
			this.bindEvent();
			this.account_type();

		},get_classification_setup:function(){
			$('.popup-content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/get_position',function(response){
				$('.popup-content').html(response);
				$('.sub-table').dataTable(datatable_option);
			});
		},bindEvent:function(){

			$('#class_save').on('click',this.class_save);
			$('#class_reset').on('click',this.class_reset);
			$('body').on('click','.update_class',this.update);
			$('#back_to').on('click',this.back_to);

			$('#txtShortDesc').on('change',this.account_type);

		},class_save:function(){

			if($('.required').required()){
				return false;
			}
						
			$.save({appendTo : '.fancybox-outer'});

			$post = {
				id          : $('#id').val(),
				name        : $('#job_title').val(),
				description : $('#job_description').val(),
				salary_grade: $('#salary_grade option:selected').val(),
				status      : $('#status option:selected').val(),
			}
			
			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/save_position',$post,function(response){

					switch(response){
						case"1": 
							$.save({action : 'success'});
						break;
						default :
							$.save({action : 'hide'});
						break;
					}


				$('.required,.clear').val('');
				app_sub_classification.get_classification_setup();

			}).error(function(){
				alert('Service Unavailable');
			});
			$('#class_save').val('Save');

		},update:function(){

			var tr = $(this).closest('tr');
						
			if(tr.find('td.status').text()=="Active"){
				var status = 1;
			}else{
				var status = 0;
			}
			var edit = {
				id                 : tr.find('td.id').text(),
				job_title          : tr.find('td.title').text(),
				job_description    : tr.find('td.description').text(),
				salary_grade       : tr.find('td.grade_code').text(),
				status	           : status,
			}
			

			$.each(edit,function(i,val){
				$('#'+i).val(val);
			});
			
			$('#class_save').val('Update');

		},class_reset:function(){
			$('.required,.clear').val('');
			$('#class_save').val('Save');
		},back_to:function(){
			$.save({appendTo : '.fancybox-outer',loading : 'Processing...'});			
			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/create',function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : true,
				})
			}).error(function(){
				alert('Service Unavailable');
			});

		},account_type:function(){

			$post = {
				account_type : $('#txtShortDesc option:selected').val(),
			};

			$.post('<?php echo base_url().index_page();?>/setup/account_setup/get_classification',$post,function(response){
						$('#cmbClassification').select({
							json : response,
							attr : {
								text : 'full_description',
								value : 'id',
							}
						});			
			},'json');

		}
	};

	$(function(){		
		app_sub_classification.init();
	});
</script>

