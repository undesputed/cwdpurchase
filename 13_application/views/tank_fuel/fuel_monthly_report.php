<style>
	.myTable tbody tr:last-child{
		font-weight: bold;
	}

</style>
<div class="header">
	<h2>Tank & Fuel Monitoring <small>Fuel Monthly Report</small></h2>	
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
			
			this.assign_unit();
			this.hide_dates();
			
		},bindEvents:function(){

			$('#apply_filter').on('click',this.apply_filter);
			$('#unit').on('change',this.assign_unit);

			$('[name="filter"]').on('change',this.hide_dates);

		},hide_dates:function(){
			
			if($('[name="filter"]:checked').val()=="all"){
				$('.dates').hide();
			}else{
				$('.dates').show();
			}			
			
		},assign_unit:function(){
			var $this = $("#unit option:selected");
			var attr = {
				brand_no  : $this.attr('data-brand'),
				tire_name : $this.attr('data-name'),
				size      : $this.attr('data-fulltank'),
			}

			$.each(attr,function(i,val){				
				$('#'+i).val(val);
			});


		},apply_filter:function(){

			$post = {
				date_from : $('#from_date').val(),
				date_to   : $('#to_date').val()			
			}
			
			$('.table-content').content_loader(true);				
			$.post('<?php echo base_url().index_page()?>/tank-fuel-monitoring/get_fuel_monthly',$post,function(response){
				$('.table-content').html(response);	
				$(".table-responsive").niceScroll();
				$('#apply_filter').removeClass('disabled');
			}).error(function(){
				alert('Error');
				$('.table-content').content_loader(false);
			});

							
		}

	}

$(function(){
	app.init();
});
</script>