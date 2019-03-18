<div class="content-page">
    <div class="content">


<div class="header">
	<h2>Tenant Setup</h2>
</div>


<div class="container">

<div class="content-title">
	<h3>Tenant Setup</h3>	
</div>


<input type="hidden" id="id" value="" class="clear">

<div class="row">	
	<div class="col-md-12">
		<div class="panel panel-default">		
		  <div class="panel-body">	
		  		<div class="btn-header">
					<button id="create" class="btn pull-right btn-primary btn-sm">Add Tenant</button>
				</div>	  		
		  </div>	
		  <div class="classification_setup_content">
		 	 <table class="table table-striped">
				<thead>
					<tr>
						<th>BUSINESS NAME</th>
						<th>ADDRESS</th>
						<th>CONTACT NO</th>
						<th>CONTACT PERSON</th>
						<th>TIN NUMBER</th>
						<th>MODE DELIVERY</th>
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

		},get_classification_setup:function(){
			$('.classification_setup_content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/setup/tenant_setup/get_cumulative',function(response){
				$('.classification_setup_content').html(response);
				$('.classification_table').dataTable(datatable_option);
			});
		},bindEvent:function(){
			$('#create').on('click',this.create);
			$('body').on('click','.update_class',this.update);
			$('body').on('click','.cancel_class',this.cancel);
			$('body').on('click','.activate_class',this.activate);
		},class_reset:function(){
			$('.required,.clear').val('');
			$('#class_save').val('Save');
		},create:function(){

			$.fancybox.showLoading();
			$.post('<?php echo base_url().index_page();?>/setup/tenant_setup/create_supplier',function(response){

				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})

			});

		},update:function(){

			$.fancybox.showLoading();
			var tr = $(this).closest('tr');
			
			$post = {
				id : tr.find('td.id').text(),
			}
			$.post('<?php echo base_url().index_page();?>/setup/tenant_setup/update',$post,function(response){

				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			});

		},cancel:function(){
			var con = confirm('Are you Sure to Cancel?');

			if(!con){
				return false;
			}

			var tr = $(this).closest('tr');
			$post = {
				id : tr.find('td.id').text(),
			}
			$.post('<?php echo base_url().index_page();?>/setup/tenant_setup/cancel',$post,function(response){
				app_sub_classification.get_classification_setup();				
			});
		},activate:function(){
			var con = confirm('Are you Sure to Activate?');

			if(!con){
				return false;
			}

			var tr = $(this).closest('tr');
			$post = {
				id : tr.find('td.id').text(),
			}
			$.post('<?php echo base_url().index_page();?>/setup/tenant_setup/activate',$post,function(response){

				app_sub_classification.get_classification_setup();

			});

		}

	};

	$(function(){		
		app_sub_classification.init();
	});
</script>


