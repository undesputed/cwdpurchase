<style>
	
	.myTable tbody tr:hover{
		text-decoration: underline;
		cursor: pointer;
	}


</style>


<div class="header">
	<h2>Tank & Fuel Monitoring <small>Fuel Equipment</small></h2>
</div>

<div class="container">
	<div class="content-title">
		<h3>Filters</h3>
	</div>
	<div class="panel panel-default">		
	  <div class="panel-body">
	  		<div class="row">	  			

	  			<div class="col-md-2">
	  						 					
					<div class="dates">
						<div class="form-group">
					  		<div class="control-label"><small class="text-muted">From</small></div>						
					  		<div class="input-group">
					  			<input type="text" name="from_date" id="from_date" class="form-control date input-sm">
					  			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					  		</div>
			  			</div>
						
			  			<div class="form-group">
					  		<div class="control-label"><small class="text-muted">To</small></div>						
					  		<div class="input-group">
					  			<input type="text" name="to_date" id="to_date" class="form-control date input-sm">
					  			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					  		</div>
			  			</div>
		  			</div>

	  			</div>

	  			<div class="col-md-2">
	  				<input type="submit" id="apply_filter" class="btn btn-primary btn-block nxt-btn btn-sm" Value="Apply Filter">
	  			</div>	  			
	  		</div>		  	
	  </div>
		
		    <div class="table-content">	
			
			</div>		
</div>

<script type="text/javascript">


	var app = {
		init:function(){

			$('#from_date').date_from();	
			$('#to_date').date_to();	
						
			this.bindEvents();
								
			
		},bindEvents:function(){

			$('#apply_filter').on('click',this.apply_filter);								
			$('body').on('click','.myTable tbody tr',this.fuel_equipment_details);


		},apply_filter:function(){

			$post = {
				date_from : $('#from_date').val(),
				date_to   : $('#to_date').val()			
			}
			
			$('.table-content').content_loader(true);				
			$.post('<?php echo base_url().index_page()?>/tank-fuel-monitoring/get_fuel_equipment',$post,function(response){
				$('.table-content').html(response);				
				$(".table-responsive").niceScroll();
				$('.myTable').dataTable(datatable_option);
				$('#apply_filter').removeClass('disabled');
			}).error(function(){
				alert('Error');
				$('.table-content').content_loader(false);
			});
						
		},fuel_equipment_details:function(){

			$.fancybox.showLoading();

			$post = {
				date_from : $('#from_date').val(),
				date_to   : $('#to_date').val()	,
				body_no   : $(this).find('td:first-child').text(),
			}
			
			$.post('<?php echo base_url().index_page();?>/tank-fuel-monitoring/get_fuel_equipment_detail',$post,function(response){
				$.fancybox(response,{
					width     : 1000,
					height    : 550,
					fitToView : true,
					autoSize  : false,
				})				
			}).error(function(){
				alert('Internal Server Error,Try again later..');
				$.fancybox.hideLoading();
			});
						
		}

	}

$(function(){
	app.init();
});
</script>