<style>
	.myTable tbody tr:hover{
		cursor:pointer;
		text-decoration: underline;
	}
</style>

<div class="header">
	<h2>Stock Availability</h2>	
</div>

<div class="container">
	
	<div class="row">
		<div class="col-md-6">
			<div class="content-title">
				<h3>Item List</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">
			  				  
			  </div>	
			  <div class="item_content">
			  	
			  </div> 
			</div>
		</div>
		<div class="col-md-6">
			<div class="content-title">
				<h3>Available Location</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">
			  				  
			  </div>
			  <div class="details_content">
			  	<table class="table table-striped">
			  		<thead>
			  			<tr>
			  				<th>PROJECT</th>
			  				<th>PROJECT NAME</th>
			  				<th>QTY ON HAND</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			<tr>
			  				<td colspan="3"><span class="text-muted">Select item</span></td>
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
			this.get_availability();
			

		},bindEvents:function(){

			$('#apply_filter').on('click',this.apply_filter);
			$('#profit_center').on('change',function(){
				$('#address').html($('#profit_center option:selected').attr('data-to'));
			});
			$('#profit_center').trigger('change');

			$('body').on('click','.myTable tbody tr',this.get_details);
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
							
		},get_availability:function(){
			$('.item_content').content_loader('true');

			$.post('<?php echo base_url().index_page();?>/procurement/stock_availability/get_availability',function(response){
				$('.item_content').html(response);
				$('.myTable').dataTable(datatable_option);
			}).error(function(){
				alert('service unavailable');
			});

		},get_details:function(){
			if($(this).find('td.dataTables_empty').length>0){				
				return false;				
			}
			$('.myTable tbody tr.selected').removeClass('selected');
			$(this).addClass('selected');
			$('.details_content').content_loader('true');

			$post = {
				item_no : $(this).find('td.ITEM_NO').text(),
			};

			$.post('<?php echo base_url().index_page();?>/procurement/stock_availability/get_details',$post,function(response){
				$('.details_content').html(response);
			});

		}


	}

$(function(){
	app.init();
});
</script>