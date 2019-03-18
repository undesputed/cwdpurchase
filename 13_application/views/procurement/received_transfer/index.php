<style>
	.myTable tbody tr:hover{
		text-decoration: underline;
		cursor: pointer;
	}
	.po_table tbody tr:hover{
		text-decoration: underline;
		cursor: pointer;
	}
</style>

<div class="header">	
	<h2>Received Transfer </h2>		
</div>


<div class="container">

		<div class="row">
			<div class="col-md-12">
				<div class="content-title">
						<h3>RECEIVED TRANSFER LIST</h3>
				</div>
				<div class="panel panel-default">		
					<div class="po_content">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Ref.no</th>
									<th>Ref.date</th>
									<th>Status</th>
									<th>Location from</th>
								</tr>
							</thead>
						</table>
					</div>				 
				</div>
			</div>
		</div>
		<hr>
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

					<!-- <div class="panel panel-default">		
					  <div class="panel-body">
					  		<div class="form-group">
					  			<button id="new_request" class="btn btn-primary btn-block">Create New Purchase Order</button>
					  		</div>
					  </div>	 
					</div> -->
			</div>

			<div class="col-md-9">
				<div class="content-title">
						<h3>CUMULATIVE DATA</h3>
				</div>
				<div class="panel panel-default">
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
			$('#profit_center').on('change',this.get_po);
			$('#new_request').on('click',this.new_request);
			$('body').on('click','.myTable tbody tr',this.get_details);
			$('body').on('click','.po_table tbody tr',this.get_po_details);
		},get_po:function(){

			$('.po_content').content_loader(true);
			$post = {
				location : $('#profit_center option:selected').val(),
			}

			$.post('<?php echo base_url().index_page();?>/procurement/received_transfer/get_receivedTransfer',$post,function(response){
				$('.po_content').html(response);
				$('.po_table').dataTable(datatable_option);
			});

		},get_cumulative:function(){
			
			$('.table-content').content_loader(true);
			$post = {
				location : $('#profit_center option:selected').val(),
			}
			$.post('<?php echo base_url().index_page();?>/procurement/received_transfer/get_cumulative',$post,function(response){
				$('.table-content').html(response);
				$('.myTable').dataTable(datatable_option);
			});


		},get_details:function(){

			$post = {
				rr_id    : $(this).find('td:first').text(),
				location : $('#profit_center option:selected').val(),
				project  : $('#project option:selected').val(),
			}	
			$.fancybox.showLoading();		
			$.post('<?php echo base_url().index_page();?>/procurement/received_purchase/get_cumulative_details',$post,function(response){
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
			
		},get_po_details:function(){

			if($(this).find('td.dataTables_empty').length > 0){
				return false;
			}

			$post = {
				id : $(this).find('td.id').text(),
				location : $('#profit_center option:selected').val(),
				project : $('#project option:selected').val(),
			}

			$.fancybox.showLoading();		
			$.post('<?php echo base_url().index_page();?>/procurement/received_transfer/received',$post,function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			});
		}
	}

$(function(){
	app.init();
});
</script>