
<div class="content-page">
    <div class="content">

<div class="header">
	<h2>Project Site Setup</h2>
</div>


<div class="container">

<div class="content-title">
	<h3>Project Site Info</h3>	
</div>

<input type="hidden" id="id" value="" class="clear">

<div class="row">
	<div class="col-md-4">

		<div class="panel panel-default">		
		  <div class="panel-body">
		  		
		  		<div class="form-group">
		  			<div class="control-label">Company Name</div>
		  			<select name="" id="project_main" class="form-control">
		  				<?php foreach($project_list as $row): ?>
		  				<option value="<?php echo $row['title_id']; ?>"><?php echo $row['title_name']; ?></option>
		  				<?php endforeach; ?>
		  			</select>
		  		</div>	

		  		<div class="row">
		  			<div class="col-xs-4">
		  				<div class="form-group">
				  			<div class="control-label">Type</div>
				  			<select name="" class="form-control" id="type">
				  				<option value="MAIN OFFICE">MAIN OFFICE</option>
				  				<option value="PROFIT CENTER">PROFIT CENTER</option>
				  			</select>
				  		</div>
		  			</div>
		  			<div class="col-xs-8">
		  				<div class="form-group">
				  			<div class="control-label">Project Description</div>
				  			<input type="text" class="form-control required uppercase" id="profit_name">
				  		</div>		  				
		  			</div>
		  		</div>

		  		<div class="row">		  			
		  			<div class="col-xs-12">
		  				<div class="form-group">
				  			<div class="control-label">Project Location</div>
				  			<input type="text" class="form-control required uppercase" id="profit_location">
				  		</div>		  				
		  			</div>
		  		</div>

		  		<div class="row">
		  			<div class="col-xs-8">
		  				<div class="form-group">
				  			<div class="control-label">Project Code</div>
				  			<input type="text" class="form-control required uppercase" id="profit_code">
				  		</div>		  				
		  			</div>
		  		</div>

		  		<div class="row">
		  			<div class="col-xs-8">
		  				<div class="form-group">
				  			<div class="control-label">Project Duration</div>
				  			<input type="text" class="form-control required uppercase" id="profit_duration" placeholder="100">
				  		</div>		  				
		  			</div>
		  		</div>

		  		<div class="row">
		  			<div class="col-xs-8">
		  				<div class="form-group">
				  			<div class="control-label">Date Started</div>
				  			<input type="text" class="form-control uppercase" id="date_started" value="<?php echo date('Y-m-d');?>">
				  		</div>		  				
		  			</div>
		  		</div>

		  		<div class="row">
		  			<div class="col-xs-8">
		  				<div class="form-group">
				  			<div class="control-label">Date Projected</div>
				  			<input type="text" class="form-control uppercase" id="date_projected" value="<?php echo date('Y-m-d');?>">
				  		</div>		  				
		  			</div>
		  		</div>

		  		<div class="row">
		  			<div class="col-xs-8">
		  				<div class="form-group">
				  			<div class="control-label">Date Completed</div>
				  			<input type="text" class="form-control uppercase" id="date_completed" placeholder="yyyy-mm-dd">
				  		</div>		  				
		  			</div>
		  		</div>

		  		<div class="row">
		  			<div class="col-xs-8">
		  				<div class="form-group">
				  			<div class="control-label">Project Incharged</div>
				  			<input type="text" class="form-control required uppercase" id="profit_incharged" value="">
				  		</div>		  				
		  			</div>
		  		</div>

		  		<div class="row">
		  			<div class="col-xs-8">
		  				<div class="form-group">
				  			<div class="control-label">Project Managed</div>
				  			<input type="text" class="form-control required uppercase" id="profit_managed" value="">
				  		</div>		  				
		  			</div>
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
						<th>Type</th>						
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

	var app_sub_classification ={
		init:function(){

			this.get_classification_setup();
			this.bindEvent();
			this.account_type();

			$('#date_started,#date_projected,#date_completed').date();

		},get_classification_setup:function(){
			$('.classification_setup_content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/setup/profit_center/get_data',function(response){
				$('.classification_setup_content').html(response);
				$('.classification_table').dataTable(datatable_option);
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
			
			$.save();


			$post = {
				id               : $('#id').val(),
				project          : $('#type option:selected').val(),
				project_name     : $('#profit_name').val(),
				project_location : $('#profit_location').val(),
				title_id         : $('#project_main option:selected').val(),
				project_code     : $('#profit_code').val(),
				project_duration : $('#profit_duration').val(),
				date_started	 : $('#date_started').val(),
				date_projected   : $('#date_projected').val(),
				date_completed   : $('#date_completed').val(),
				project_incharged: $('#profit_incharged').val(),
				project_managed  : $('#profit_managed').val()
			};
			

			$.post('<?php echo base_url().index_page();?>/setup/profit_center/save',$post,function(response){
					switch($.trim(response)){
						case"1": 
							$.save({action : 'success'});
						break;
						default :
							$.save({action : 'hide'});
						break;
					}

				$('.required,.clear,#date_completed').val('');
				app_sub_classification.get_classification_setup();

			}).error(function(){
				alert('Service Unavailable');
			});

			$('#class_save').val('Save');

		},update:function(){

			var tr = $(this).closest('tr');

			var edit = {
				id                 : tr.find('td.id').text(),
				project_main       : tr.find('td.title_id').text(),
				type               : tr.find('td.project').text(),
				profit_name        : tr.find('td.project_name').text(),
				profit_location    : tr.find('td.project_location').text(),
				profit_code        : tr.find('td.code').text(),
				profit_duration    : tr.find('td.project_duration').text(),
				date_started       : tr.find('td.date_started').text(),
				date_projected	   : tr.find('td.date_projected').text(),
				date_completed     : tr.find('td.date_completed').text(),
				profit_incharged   : tr.find('td.project_incharged').text(),
				profit_managed     : tr.find('td.project_managed').text()
			}
			
			console.log(edit);	

			$.each(edit,function(i,val){				
				$('#'+i).val(val);				
			});
			
			$('#class_save').val('Update');

		},class_reset:function(){
			$('.required,.clear,#date_completed').val('');
			$('#class_save').val('Save');
		},back_to:function(){
			$.save({appendTo : '.fancybox-outer',loading : 'Processing...'});
			
			$.post('<?php echo base_url().index_page();?>/setup/account_setup/new_request',function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
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

