<div class="content-page">
	<div class="content">
		
<div class="header">
	<h2>Payroll Entry</h2>
</div>
<div class="container">

<div class="content-title">
	<h3>Entry Form</h3>	
</div>

<span id="back_to" class="pull-right btn-link" style="margin-top:2em;">Back to Account Setup</span>

<input type="hidden" id="id" value="" class="clear">

<div class="row">
	<div class="col-md-4">		

		<div class="panel panel-default">		
		  <div class="panel-body">
		  		<div class="form-group">
		  			<div class="control-label">Project</div>
		  			<select name="" id="project_id" class="form-control">
		  				<?php foreach($project as $row): ?>
						<option value="<?php echo $row['project_id']; ?>"><?php echo $row['project_full_name']; ?></option>
		  				<?php endforeach; ?>
		  			</select>
		  		</div>

		  		<div class="form-group">
		  			<div class="control-label">Project</div>
		  			<select name="" id="project_type" class="form-control">
		  				<option value="0"></option>
		  				<?php foreach($project_category as $row): ?>
						<option value="<?php echo $row['id']; ?>"><?php echo $row['project_name']; ?></option>
		  				<?php endforeach; ?>
		  			</select>
		  		</div>
		  		
		  		<div class="form-group">
		  			<div class="control-label">Payroll Period</div>
		  			<input type="text" id="payroll_date" class="form-control input-sm required date">
		  		</div>
		  		<div class="form-group">
		  			<div class="control-label">Payroll Amount</div>
		  			<input type="text" id="payroll_amount" class="numbers_only form-control input-sm required">
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
		  <div class="classification_setup_content">
		 	 <table class="table table-striped">
				<thead>
					<tr>
						<th>Classification Code</th>
						<th>Short Description</th>
						<th>Classification Name</th>
					</tr>
				</thead>
			 </table>
		  </div> 
		</div>		
	</div>


</div>

</div>

	</div>
</div>

<script>

	var app_classification ={
		init:function(){
			$('.date').date();
			this.get_data();
			this.bindEvent();
		},get_data:function(){
			$('.classification_setup_content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/accounting_entry/payroll_entry/get_data',function(response){
				$('.classification_setup_content').html(response);
				$('.classification_table').dataTable(datatable_option);
			});
		},bindEvent:function(){
			$('#class_save').on('click',this.class_save);
			$('#class_reset').on('click',this.class_reset);
			$('body').on('click','.update_class',this.update);
			$('body').on('click','.delete_class',this.delete);
			$('#back_to').on('click',this.back_to);

		},class_save:function(){

			if($('.required').required()){
				return false;
			}
			
			
			$.save({appendTo : '.fancybox-outer'});
			$post = {
				project_id      : $('#project_id option:selected').val(),
				project_name    : $('#project_id option:selected').text(),
				project_type_id : $('#project_type option:selected').val(),
				project_type    : $('#project_type option:selected').text(),
				payroll_date    : $('#payroll_date').val(),
				payroll_amount  : $('#payroll_amount').val(),
				id              : $('#id').val(),
			};

			$.post('<?php echo base_url().index_page();?>/accounting_entry/payroll_entry/save',$post,function(response){
					switch(response){
						case"1": 
							alert('Successfully Save');
							$.save({action : 'success'});
						break;
						default :
							$.save({action : 'hide'});
						break;
					}

				$('.required,.clear').val('');
				app_classification.get_data();

			}).error(function(){
				alert('Service Unavailable');
			});

			$('#class_save').val('Save');

		},update:function(){
			var tr = $(this).closest('tr');
			var edit = {
				project_id : tr.find('td.id').data('project_id'),
				payroll_date : tr.find('td.payroll_date').text(),
				payroll_amount : tr.find('td.payroll_amount').text(),
				id           : tr.find('td.id').text(),
			}
			
			console.log(edit);

			$.each(edit,function(i,val){
				$('#'+i).val(val);
			});
			
			$('#class_save').val('Update');
		},delete:function(){
			var bool = confirm('Are you sure to Delete?');
			if(!bool){
				return false;
			}
			$post = {
				id : $(this).closest('tr').find('td.id').text(),
			}
			$.post('<?php echo base_url().index_page();?>/accounting_entry/payroll_entry/delete',$post,function(response){
				if($.trim(response) == 1){
					alert('Successfully Deleted');
					app_classification.get_data();
				}
			});


		},class_reset:function(){
			$('.required,.clear').val('');
			$('#class_save').val('Save');
		}
	};

	$(function(){		
		app_classification.init();
	});
</script>

