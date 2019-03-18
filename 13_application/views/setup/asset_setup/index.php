<style>
	.myTable tbody tr:hover{
		cursor: pointer;
		text-decoration: underline;
	}
</style>


<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Asset Setup</h2>
</div>

<input type="hidden" id="date" value="">

<div class="container">
	<div class="row">			
			<div class="col-md-12">	
				<div class="content-title">
					<h3>List of Asset</h3>
				</div>	
				<div class="panel panel-default">	
					<div class="panel-body">
						<div class="pull-right">
							<span id="create" class="btn btn-sm  btn-primary" title=""><i class="fa fa-plus"></i> Asset Setup</span>
							<!-- <span id="bank_setup" class="btn btn-sm  btn-success" title="">Bank Setup</span>	 -->
						</div>						
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

</div>
</div>

<script>
	
	var app = {
		init:function(){
			this.bindEvents();
			this.get_cumulative();
			$('#date').date();
		},bindEvents:function(){
			$('#create').on('click',this.create);			
			$('#bank_setup').on('click',this.bank_setup);			
			$('body').on('click','.update',this.update);
			$('body').on('click','.myTable tbody tr',this.get_details);
		},get_cumulative:function(){

			$post = {
				date : $('#date').val(),
			} 

			$('.data_content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/setup/asset_setup/get_cumulative',$post,function(response){
				$('.data_content').html(response);
				$('.myTable').dataTable(datatable_option);
			});

		},create :function(){
			$.fancybox.showLoading();
			$.post('<?php echo base_url().index_page();?>/setup/asset_setup/new_request',function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : true,
					autoSize  : false,
				})
			}).error(function(){
				alert('Service Unavailable');
			});

		},bank_setup:function(){
			$.fancybox.showLoading();		
			$.post('<?php echo base_url().index_page();?>/setup/asset_setup/bank_setup',function(response){
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
			$.post('<?php echo base_url().index_page();?>/setup/asset_setup/update_request',$post,function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			}).error(function(){
				alert('Service Unavailable');
			});

		},get_details:function(){			
			if($(this).find('td.dataTables_empty').length > 0){
				return false;
			}

			$.fancybox.showLoading();

			$post = {
				location     : $('#profit_center option:selected').val(),
				project      : $('#project option:selected').val(),
				id           : $(this).find('td.asset_id').text(),				
			};

			$.post('<?php echo base_url().index_page();?>/setup/asset_setup/get_details',$post,function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			});
		},

	}


	$(function(){
		app.init();
	});


</script>