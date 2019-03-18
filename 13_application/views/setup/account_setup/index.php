<div class="header">
	<h2>Account Setup</h2>
</div>


<div class="container">
	<div class="row">
			
			<div class="col-md-12">	
				<div class="content-title">
					<h3>Account List</h3>
				</div>				
				<div class="panel panel-default">	
					<div class="panel-body">
						<span id="create" class="btn btn-sm pull-right btn-primary" title=""><i class="fa fa-plus"></i> Create New Account</span>
					</div>	
					 <div class="data_content">
					 	<table class="table table-content">
					 		<thead>
					 			<tr>
					 				<th>Account Code</th>
					 				<th>Account Description</th>
					 				<th>Sub Classification</th>
					 				<th>Classification</th>
					 				<th>Account Type</th>					 				
					 			</tr>
					 		</thead>					 		
					 	</table>
					 </div>
				</div>
			</div>
	</div>

</div>

<script>
	
	var app = {
		init:function(){
			this.bindEvents();
			this.get_accounts();
		},bindEvents:function(){
			$('#create').on('click',this.create);
			$('body').on('click','.update',this.update);
		},get_accounts:function(){

			$('.data_content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/setup/account_setup/get_account',function(response){
				$('.data_content').html(response);
				$('.myTable').dataTable(datatable_option);
			});

		},create :function(){
			$.fancybox.showLoading();
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

		},update :function(){
			$.fancybox.showLoading();
			var tr = $(this).closest('tr');
			$post = {
				id : tr.find('td.account_id').text(),
			};
			$.post('<?php echo base_url().index_page();?>/setup/account_setup/update_request',$post,function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			}).error(function(){
				alert('Service Unavailable');
			});

		}

	}


	$(function(){
		app.init();
	});


</script>