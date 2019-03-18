

<div class="header">
	<h2>Tank & Fuel Monitoring <small>Fuel Withdrawal</small></h2>	
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
	  			<div class="col-md-2"></div>
	  			<div class="col-md-6">
	  				<div class="row">
	  					<div class="col-md-3"><h3 id="fws">-</h3><small>FWS</small></div>
	  					<div class="col-md-3"><h3 id="fts">-</h3><small>FTS</small></div>
	  					<div class="col-md-3"><h3 id="ftd">-</h3><small>FTD</small></div>
	  					<div class="col-md-3"><h3 id="total">-</h3><small>TOTAL</small></div>
	  				</div>	  			
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

		},apply_filter:function(){

			$post = {
				date_from : $('#from_date').val(),
				date_to   : $('#to_date').val()			
			}
			var qty = 0;
			var nCells;
			$('.table-content').content_loader(true);				
			$.post('<?php echo base_url().index_page()?>/tank-fuel-monitoring/get_fuel_withdrawal',$post,function(response){

				$('.table-content').html(response.table);				
				$(".table-responsive").niceScroll();


				datatable_option.fnFooterCallback = function ( nRow, aaData, iStart, iEnd, aiDisplay )
				{
					qty = 0;					
					for ( var i=iStart ; i<iEnd ; i++ )
					{	
						console.log(aaData[ aiDisplay[i]][5]);
						qty += +aaData[ aiDisplay[i]][5];
					}		

					console.log(qty);
					nCells = nRow.getElementsByTagName('th');
					nCells[5].innerHTML = parseInt(qty);

				}

							
				$('.myTable').dataTable(datatable_option);
				$('#apply_filter').removeClass('disabled');

				$('#fws').html(comma(response.total.FWS));
				$('#fts').html(comma(response.total.FTS));
				$('#ftd').html(comma(response.total.FTD));
				$('#total').html(comma(response.total.TOTAL));
				
			},'json').error(function(){
				alert('Internal Server Error, Try again later..');
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