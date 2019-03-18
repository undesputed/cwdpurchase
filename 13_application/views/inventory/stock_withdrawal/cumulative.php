<style>
	.myTable tbody tr:hover{
		cursor:pointer;
		text-decoration: underline;
	}

</style>

<div class="header">
	<h2>Stock Inventory</h2>	
</div>

<div class="container">
	<div class="row">
			<div class="col-md-3">
				<div class="content-title">
					<h3>Project Information</h3>
				</div>
				<div class="panel panel-default">		
				  <div class="panel-body">
				  		<div class="form-group">
				  			<div class="control-label">Project</div>
				  			<select name="" id="project" class="form-control input-sm"></select>
				  		</div>

				  		<div class="form-group">
				  			<div class="control-label">Profit Center</div>
				  			<select name="" id="profit_center" class="form-control input-sm"></select>
				  		</div>			  		
				  </div>
				</div>
				
				<div class="panel panel-default">		
					  <div class="panel-body">
					  		<div class="form-group">
					  			<button id="new_request" class="btn btn-primary btn-block">New Withdrawal</button>
					  		</div>
					  </div>	 
				</div>

			</div>

			<div class="col-md-9">	
				<div class="content-title">
					<h3>Cumulative Data</h3>
				</div>				
				<div class="panel panel-default">	
					<div class="panel-body"></div>	
					 <div class="data_content">
					 	<table class="table table-content">
					 		<thead>
					 			<tr>
					 				<th>MIS NO</th>
					 				<th>MIS DATE</th>
					 				<th>MIS STATUS</th>
					 				<th>ISSUED BY</th>
					 				<th>NOTED BY</th>					 				
					 			</tr>
					 		</thead>					 		
					 	</table>
					 </div>
				</div>
			</div>
	</div>

</div>

<script type="text/javascript">
	
	var app = {
		init:function(){
			$('.date').date();		
			var option = {
				profit_center : $('#profit_center')
			}			
			$('#project').get_projects(option);			
			
			this.bindEvents();
					
		},bindEvents:function(){

			$('#apply_filter').on('click',this.apply_filter);
			$('#profit_center').on('change',function(){
				app.get_item();
			});
			$('#new_request').on('click',this.new_request);

			$('body').on('click','.myTable tbody tr',this.get_details);

		},get_item:function(){

			$('.data_content').content_loader('true');
			var $post = {
				location : $('#profit_center option:selected').val(),
			};

			$.post('<?php echo base_url().index_page(); ?>/inventory/stock_withdrawal/get_cumulative',$post,function(response){
				$('.data_content').html(response);				
				$('.myTable').dataTable(datatable_option);
			});

		},get_details:function(){
			if($(this).find('td.dataTables_empty').length>0){
				return false;
			}
			$.fancybox.showLoading();
			$post = {

				location     : $('#profit_center option:selected').val(),
				project      : $('#project option:selected').val(),
				withdraw_id  : $(this).find('td.withdraw_id').text(),

			};

			$.post('<?php echo base_url().index_page();?>/inventory/stock_withdrawal/get_details',$post,function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			});
		},new_request:function(){

			$post = {
				location : $('#profit_center option:selected').val(),
				project  : $('#project option:selected').val(),
			};
			
			$.fancybox.showLoading();
			$.post('<?php echo base_url().index_page();?>/inventory/stock_withdrawal/new_request',$post,function(response){
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