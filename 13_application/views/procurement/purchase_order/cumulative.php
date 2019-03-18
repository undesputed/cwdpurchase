<style>
	.myTable tbody tr:hover{
		text-decoration: underline;
		cursor: pointer;
	}	
</style>

<div class="header">
	<div class="container">
	
		<div class="row">
			<div class="col-md-8">
					<h2>Purchase Order </h2>					
			</div>
			<div class="col-md-4">					
			</div>
		</div>

	</div>
</div>


<div class="container">
		<div class="row">
			<div class="col-md-3">
					<div class="content-title">
						<h3>Project Information</h3>
					</div>
					<div class="panel panel-default" style="display:none">		
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
					  			<button id="new_request" class="btn btn-primary btn-block">Create New Purchase Order</button>
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
					<div class="table-content">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Approved</th>
									<th>PR No</th>
									<th>PR Date</th>
									<th>Project</th>
									<th>Date</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>

					</div>		
					
				</div>
			</div>

		</div>
</div>

<script>
	var app = {
		init:function(){

			var option = {
				profit_center : $('#profit_center'),				
			}
			$('#project').get_projects(option);
			app.bindEvents();
			
		},bindEvents:function(){
			$('#profit_center').on('change',this.get_cumulative);
			$('#new_request').on('click',this.new_request);
			$('body').on('click','.myTable tbody tr',this.get_details);
		},get_cumulative:function(){

			$('.table-content').content_loader(true);

			$post = {
				location : $('#profit_center option:selected').val(),
			}
			$.post('<?php echo base_url().index_page();?>/procurement/purchase_order/get_cumulative',$post,function(response){
				$('.table-content').html(response);
				datatable_option.aaSorting =  [[ 4, "desc" ]];
				$('.myTable').dataTable(datatable_option);
			});
		},get_details:function(){			
			$post = {
				po_id : $(this).find('td.po_id').text(),
			}	
			$.fancybox.showLoading();		
			$.post('<?php echo base_url().index_page();?>/procurement/purchase_order/get_cumulative_detail',$post,function(response){
				$.fancybox(response,{
					width     : 1000,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			});
		},new_request:function(){

			$.save({
				loading : 'Processing...'
			});

			$post = {
				location : $('#profit_center option:selected').val(),
			}			
			$.post('<?php echo base_url().index_page();?>/procurement/purchase_order/create_form',$post,function(response){
				$.save({
					action : 'hide',
				});
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				});
			});
			
		}
	}

$(function(){
	app.init();
});
</script>