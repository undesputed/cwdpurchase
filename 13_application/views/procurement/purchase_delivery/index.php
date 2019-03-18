<style>
	.table-main tbody tr:hover{
		cursor:pointer;
		text-decoration: underline;
	}

</style>

<div class="header">
	<h2>Purchase Delivery</h2>	
</div>

<div class="container">
	<div class="row">

			<div class="col-md-3" style="display:none">
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
			</div>

			<div class="col-md-12">	
				<div class="content-title">
					<h3>Transaction List</h3>
				</div>				
				<div class="panel panel-default">

					 <div class="data_content" style="margin-top:5px">
					 	
					 </div>
				</div>
			</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="content-title">
					<h3>RR Details</h3>
			</div>
			<div class="panel panel-default">		
	 			<div class="rr_content">
	 				<table class="table table-striped">
	 					<thead>
	 						<tr>
	 							<th>RR No.</th>
	 							<th>RR Date</th>
	 							<th>INV/DR NO.</th>
	 							<th>business_number</th>
	 							<th>Supplier Name</th>
	 						</tr>	 						
	 					</thead>
	 					<tbody>
	 						<tr>
	 							<td colspan="5"><span class="text-muted">Select Transaction</span></td>
	 						</tr>
	 					</tbody>
	 				</table>
	 			</div>
			</div>
		</div>	
		<div class="col-md-6">
			<div class="content-title">
					<h3>PO Details</h3>
			</div>
			<div class="panel panel-default">		
	 			<div class="po_content">
	 				<table class="table table-striped">
	 					<thead>	 						
	 						<tr>
	 							<th>ITEM No.</th>
	 							<th>ITEM NAME</th>
	 							<th>PO QTY</th>
	 							<th>RECEIVED QTY</th>
	 							<th>DISCREPANCY	REMARKS</th>
	 						</tr>	 						
	 					</thead>
	 					<tbody>
	 						<tr>
	 							<td colspan="5"><span class="text-muted">Select Transaction</span></td>
	 						</tr>
	 					</tbody>
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
				app.get_data();
			});
			$('#profit_center').trigger('change');
			$('body').on('click','.table-main tbody tr',this.get_data_details);

		},apply_filter:function(){

			$post = {				
				date_from : $('#from_date').val(),
				date_to   : $('#to_date').val(),				
				location  : $('#profit_center option:selected').val(),
				project_effectivity : $('#profit_center option:selected').attr('data-effectivity'),
			}			
				$('.content-equipment').content_loader(true);
				$('.content-summary').content_loader(true);
				$.post('<?php echo base_url().index_page()?>/dashboard/revenue/apply_filter',$post,function(response){
						//$('.table-content').html(response);
						$('.content-equipment').html(response.equipment);	
						$('.content-summary').html(response.summary);	
						$('#apply_filter').removeClass('disabled');
				},'json');					
		},get_data:function(){

			$('.data_content').content_loader('true');
			var $post = {
				location : $('#profit_center option:selected').val(),
			};

			$.post('<?php echo base_url().index_page(); ?>/procurement/purchase_delivery/get_data',$post,function(response){
				$('.data_content').html(response);
				datatable_option.iDisplayLength = 6;
				$('.table-main').dataTable(datatable_option);
			});

		},get_data_details:function(){

			if($(this).find('td.dataTables_empty').length > 0){
				return false;
			}

			$('.table-main  tbody tr.selected').removeClass('selected');
			$(this).addClass('selected');

			$('.rr_content').content_loader('true');
			$('.po_content').content_loader('true');


			$post = {
				po_id : $(this).find('td.po_id').text(),
			};

			$.post('<?php echo base_url().index_page(); ?>/procurement/purchase_delivery/get_data_details',$post,function(response){
				$('.rr_content').html(response.rr);
				$('.po_content').html(response.po);	
			},'json');

		}
	}

$(function(){
	app.init();
});
</script>