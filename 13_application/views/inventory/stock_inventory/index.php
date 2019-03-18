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
			</div>

			<div class="col-md-9">	
				<div class="content-title">
					<h3>Item List</h3>
				</div>				
				<div class="panel panel-default">	
					<div class="panel-body"></div>	
					 <div class="data_content">
					 	<table class="table table-content">
					 		<thead>
					 			<tr>
					 				<th>Item ID</th>
					 				<th>Item Name</th>
					 				<th>Item Cost</th>
					 				<th>Item Code</th>
					 				<th>Unit Measure</th>
					 				<th>Current Qty</th>
					 				<th>Max Qty</th>
					 				<th>Min Qty</th>
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

			$('body').on('click','.myTable tbody tr',this.get_details);

		},get_item:function(){

			$('.data_content').content_loader('true');
			var $post = {
				location : $('#profit_center option:selected').val(),
			};

			$.post('<?php echo base_url().index_page(); ?>/inventory/stock_inventory/get_item',$post,function(response){
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
				item_id      : $(this).find('td.Item_No').text(),
				item_name    : $(this).find('td.Item_Name').text(),
				current_qty  : $(this).find('td.Current_Qty').text(),
				max_qty      : $(this).find('td.Max_Qty').text(),
				min_qty      : $(this).find('td.Min_Qty').text(),
				division     : $(this).find('td.division_code').text(),
				account_code : $(this).find('td.account_code').text(),
				serial_no    : $(this).find('td.serial_no').text(),
			};

			$.post('<?php echo base_url().index_page();?>/inventory/stock_inventory/get_details',$post,function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			});
		},get_tbl_row:function( obj ){

		}

	}

$(function(){
	app.init();
});
</script>