
<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Add Customer</h2>
</div>


<div class="container">

<div class="content-title">
	<h3>Customer Form</h3>	
</div>


<input type="hidden" id="id" value="" class="clear">
<div class="row">
	<div class="col-md-4">

		<div class="panel panel-default">		
		  <div class="panel-body">
		  				  		

		  		<div class="form-group">
		  			<div class="control-label">Full Name</div>
		  			<input type="text" id="customer_name" class="form-control input-sm required uppercase">
		  		</div>

		  		<div class="form-group">
		  			<div class="control-label">Address</div>
		  			<input type="text" id="address" class="form-control input-sm required uppercase">
		  		</div>

		  		<div class="form-group">
		  			<div class="control-label">Tin</div>
		  			<input type="text" id="tin" class="form-control input-sm required uppercase" placeholder="TIN NO">
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

		},get_classification_setup:function(){
			$('.classification_setup_content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/accounting_entry/customer_setup/get_data',function(response){
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
						
			$.save({appendTo : '.fancybox-outer'});

			$post = {
				customer_name   : $('#customer_name').val(),
				address     : $('#address').val(),
				tin         : $('#tin').val(),
				id          : $('#id').val(),
			};

			$.post('<?php echo base_url().index_page();?>/accounting_entry/customer_setup/save',$post,function(response){
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
				app_sub_classification.get_classification_setup();

				$('#class_save').val('Save');

			}).error(function(){
				alert('Service Unavailable');
			});

			

		},update:function(){

			var tr = $(this).closest('tr');

			var edit = {
				customer_name  : tr.find('td.full_name').text(),							
				id         : tr.find('td.id').text(),
				address    : tr.find('td.address').text(),
				tin        : tr.find('td.tin_number').text(),
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

