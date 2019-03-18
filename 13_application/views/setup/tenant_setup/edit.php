<style>
#tbl-contact tr td{
	cursor: pointer
}
</style>


<div class="header">
	<h2>Add Tenant</h2>
</div>

<div class="container">

<input type="hidden" id="id_main" value="<?php echo $main['id']; ?>" class="clear">

<div class="row">
	<div class="col-md-4">
			<div class="content-title">
				<h3>Create New Tenant</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">

			  		<div class="form-group" style="display:none">
			  			<div class="control-label">Company Name</div>
			  			<select name="" id="create_project" class="form-control input-sm"></select>
			  		</div>

			  		<div class="form-group">
			  			<div class="control-label">Project Site</div>
			  			<select name="" id="create_profit_center" class="form-control input-sm"></select>
			  		</div>
								  		
			  		<div class="form-group">
			  			<div class="control-label">Tenant Name</div>		  			
			  			<input type="text" id="tenant_name" class="form-control input-sm required uppercase" value="<?php echo $main['name'] ?>">
			  		</div>
					
			  		<div class="form-group">
			  			<div class="control-label">Address</div>		  			
			  			<input type="text" id="address" class="form-control input-sm required uppercase" value="<?php echo $main['address'] ?>">
			  		</div>

			  		<div class="form-group">
			  			<div class="control-label">Contact No</div>		  			
			  			<input type="text" id="contact_no" class="form-control input-sm required uppercase" value="<?php echo $main['contact_no'] ?>">
			  		</div>
			  </div>
			</div>
	</div>
	
	<div class="col-md-6">

		<div class="content-title">
			<h3>Contract Amount</h3>
		</div>

		<div class="panel panel-default">		
				  <div class="panel-body">
				  		<div class="row">
				  			<div class="col-md-6">
				  				<div class="form-group">
				  					<div class="control-label">Contact Amount</div>
									<input type="text" id="contract_amount" class="form-control input-sm required numbers_only" value="<?php echo $details[0]['amount']; ?>">
				  				</div>
				  			</div>
				  			<div class="col-md-6">
				  				<div class="form-group">
				  					<div class="control-label">Remarks</div>
									<input type="text" id="remarks" class="form-control input-sm uppercase " value="<?php echo $details[0]['remarks'] ?>">
				  				</div>
				  			</div>				  									
				  		</div>
				  </div>
		</div>		
	</div>
	
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">		
		  		<div class="form-footer">
						<div class="row">
							<div class="col-md-7"></div>
							<div class="col-md-5">
								<input id="class_update" class="btn btn-success col-xs-5 pull-right btn-sm" type="submit" value="Update">								
							</div>
						</div>
		  	 	</div>	 
		</div>
	</div>
</div>

</div>

<script>

	var app_create ={
		init:function(){

			$('#date').date({
				option : {
					changeYear: true,
					dateFormat:'yy-mm-dd',
				}
			});

			var option = {
				profit_center : $('#create_profit_center'),
				main_office : false,
			}			

			$('#create_project').get_projects(option);


			this.bindEvent();

		},get_classification_setup:function(){
			$('.popup-content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/get_position',function(response){
				$('.popup-content').html(response);
				$('.sub-table').dataTable(datatable_option);
			});
		},bindEvent:function(){

			$('#class_update').on('click',this.class_update);

			$('#add').on('click',function(){
				$('#tbl-contact tbody tr:first').clone().find('td').each(function(){
					$(this).text('-');
				}).end().appendTo('#tbl-contact tbody');
			});

			$('#remove').on('click',function(){

				$('#tbl-contact tbody tr').each(function(){
					if($(this).is(':first-child')){

					}else{
						$('#tbl-contact tbody tr:last-child').remove();
						return false;
					}
				});
								
			});	

			$('input[name="vat"]').on('change',function(){
				if($('input[name="vat"]:checked').attr('id') == "vat"){
					$('#vat-value').val('12');
				}else{
					$('#vat-value').val('0');
				}				
			});

			$('input[name="vat"]').trigger('change');


		},class_update:function(){

			if($('.required').required()){
				return false;
			}

			var con = confirm('Do you want to Proceed?');
			if(!con){
				return false;
			}
						
			$.save({appendTo : 'body'});
			
			$post = {
				id            : $('#id_main').val(),
				tenant_name   : $('#tenant_name').val(),
				address       : $('#address').val(),
				contact_no    : $('#contact_no').val(),
				project_id    : $('#create_profit_center option:selected').val(),
				data          : {
									contract_amount : $('#contract_amount').val(),
									remarks         : $('#remarks').val(),
								}
			};

			$.post('<?php echo base_url().index_page();?>/setup/tenant_setup/update_action',$post,function(response){

					switch(response){
						case"1": 
							$.save({action : 'success',reload : 'true'});
						break;
						default :
							$.save({action : 'hide'});
						break;
					}

				/*$('.required,.clear').val('');*/
				app_sub_classification.get_classification_setup();

			}).error(function(){
				alert('Service Unavailable');
			});

			/*$('#class_save').val('Save');*/

		},get_details:function(){
			var data = new Array();
			$('#tbl-contact tbody tr').each(function(i,value){
				var obj = {
					contact_type : $(value).find('td.contact-type').text(),
					contact_number : $(value).find('td.contact-number').text(),
				};
				data.push(obj);
			});
			return data;
		}
	};

	$(function(){		
		app_create.init();

		$('.editable').editable_td();
	});
</script>

