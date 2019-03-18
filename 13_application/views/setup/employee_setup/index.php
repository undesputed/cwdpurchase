<div class="content-page">
    <div class="content">

		<div class="header">
			<h2>Employee Setup</h2>
		</div>
	
		<div class="container">

		<div class="content-title">
			<h3>Employee Setup</h3>	
		</div>

		<input type="hidden" id="id" value="" class="clear">

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">		
				  <div class="panel-body">
				  	<div class="btn-header">
				  		<button id="create" class="btn pull-right btn-primary btn-sm">Setup Employee</button>
				  	</div>  		
				  </div>	
				  <div class="classification_setup_content">
				 	 <table class="table table-striped">
						<thead>
							<tr>
								<th>EMP ID</th>
								<th>EMP NAME</th>								
								<th>POSITION</th>
								<th>STATUS </th>		
								<th>ACTION</th>
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
	var oTable = '';
	var app_sub_classification ={
		init:function(){

			var option = {
				profit_center : $('#profit_center'),
				call_back : function(){
					app_sub_classification.get_department();
					app_sub_classification.get_classification_setup();
					app_sub_classification.bindEvent();
				},
			}			
			$('#project').get_projects(option);						

		},get_classification_setup:function(){

			$('.classification_setup_content').content_loader('true');
			$post = {
				location : $('#profit_center option:selected').val(),
			};
			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/get_cumulative',$post,function(response){
				$('.classification_setup_content').html(response);
				oTable = $('.classification_table').dataTable(datatable_option);
			});

		},get_department:function(){

			$post = {
				location : $('#profit_center option:selected').val(),
			};

			$.post('<?php echo base_url().index_page();?>/setup/user_setup/get_division',$post,function(response){
				$('#department').select({
					json : response,
					attr : {
							text  : 'division_name',
							value : 'division_id',
					}
				});
			},'json');

		},bindEvent:function(){
			
			$('#create').on('click',this.create);
			$('body').on('click','.remove_class',this.remove_status);
			$('body').on('click','.signatory_class',this.signatory_class);
			
		},signatory_class:function(){
			var tr = $(this).closest('tr');
			$.fancybox.showLoading();

			$post = {
				id  : tr.find('.emp_number').text(),
			};

			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/signatory_img',$post,function(response){
				
				$.fancybox(response,{
					width     : 1000,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				});
				
			});

		},create:function(){

			$.fancybox.showLoading();
			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/create',function(response){
				$.fancybox(response,{
					width     : 1000,
					height    : 550,
					fitToView : false,
					autoSize  : true,
				});
			});

		},class_save:function(){
			if($('.required').required()){
				return false;
			}
		
			$.save();

			$post = {
				project        : $('#project option:selected').val(),
				profit_center  : $('#profit_center option:selected').val(),
				person_code    : $('#employee option:selected').attr('data-profile-no'),
				employee_id    : $('#employee option:selected').val(),
				employee_name  : $('#employee option:selected').text(),
				department     : $('#department option:selected').val(),
				username       : $('#username').val(),
				password       : $('#password').val(),
				usertype       : $('#usertype option:selected').val(),
				id             : $('#id').val(),
			};

			$.post('<?php echo base_url().index_page();?>/setup/user_setup/save_user',$post,function(response){
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

			var edit = {					
					project    : tr.find('td.proj_main').text(),
					profit_center : tr.find('td.proj_code').text(),
					employee   : tr.find('td.emp_id').text(),
					department : tr.find('td.dept_code').text(),
					username   : tr.find('td.username').text(),
					usertype   : tr.find('td.user_type').text(),
					id         : tr.find('td.id').text(),					
			};

			$.each(edit,function(i,val){
				$('#'+i).val(val);
			});
			
			$('#class_save').val('Update');

		},class_reset:function(){
			$('.required,.clear').val('');
			$('#class_save').val('Save');
		},remove_status:function(){

			var bool = confirm('Are you Sure?');
			if(!bool){
				return false;
			}

			var me = $(this);
			var emp_number = me.closest('tr').find('td.emp_number').text();
			var index = me.closest('tr').get(0);

			$post = {
				emp_number : emp_number
			};

			 			
			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/remove_emp',$post,function(response){
				switch($.trim(response)){
					case "1":
						alert('Successfully Remove!');
						  oTable.fnDeleteRow(oTable.fnGetPosition(index));
					break;
					case "default":
						alert('Something went Wrong');
					break;
				}
			});


		}
	};

	$(function(){		

		app_sub_classification.init();

	});
</script>

