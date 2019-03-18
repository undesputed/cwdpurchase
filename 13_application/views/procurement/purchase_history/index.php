<style>
	.myTable tbody tr:hover{
		cursor:pointer;
		text-decoration: underline;
	}
</style>

<div class="header">
	<h2>Purchase History</h2>	
</div>

<div class="container">
	
	<div class="row">
		<div class="col-md-4">
			<div class="content-title">
				<h3>Type</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">
			  	   <div class="radio-inline">
			  	   		<input type="radio" name="type" value="item" id="item" checked> <label for="item">Items</label>
			  	   </div>
			  	   <div class="radio-inline">
			  	   		<input type="radio" name="type" value="suppliers" id="suppliers"><label for="suppliers">Suppliers</label>
			  	   </div>
			  </div>
			  <div class="item_content">
							
				</div>			
			</div>
						
		</div>
		<div class="col-md-8">
			<div class="content-title">
				<h3>Transaction List</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="table_content">
			  	

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

			this.get_data();

		},bindEvents:function(){

			$('#apply_filter').on('click',this.apply_filter);
			$('#profit_center').on('change',function(){
				$('#address').html($('#profit_center option:selected').attr('data-to'));
			});
			$('#profit_center').trigger('change');

			$('input[name="type"]').on('change',this.get_data);

			$('body').on('click','.myTable tbody tr',this.get_details);

		},get_data:function(){
			
			$post = {
				type : $('input[name="type"]:checked').val(),				
			}
			$('.item_content').content_loader();
			$.post('<?php echo base_url().index_page();?>/procurement/purchase_history/get_data',$post,function(response){
				$('.item_content').html(response);
				$('.myTable').dataTable(datatable_option);
			}).error(function(){
				alert('Service Unavailable');
			});
		},get_details:function(){
			if($(this).find('td.dataTables_empty').length>0){				
				return false;				
			}
					
			$('.myTable  tbody tr.selected').removeClass('selected');
			$(this).addClass('selected');

			$('.table_content').content_loader('true');
			switch($('input[name="type"]:checked').val()){
				case "item":
					$post = {
						type : 'item',				
						item_no : $(this).find('td.ITEM_NO').text(),
					};
				break;
				case "suppliers":
					$post = {
						type : 'suppliers',				
						b_type : $(this).find('td.TYPE').text(),
						supplier_id : $(this).find('td.supplierID').text(),
					};
				break;
			}
			
			$.post('<?php echo base_url().index_page();?>/procurement/purchase_history/get_details',$post,function(response){
				$('.table_content').html(response);
			}).error(function(){
				alert('Service Unavailable');
			});


		}

	}

$(function(){
	app.init();
});
</script>