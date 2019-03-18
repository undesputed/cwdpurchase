<div class="header">
	<h2>Delivery Tickets and Rf Readings <small>DT -  RT</small></h2>	
</div>
<div class="container">
	<div class="content-title">
		<h3>Filters</h3>
	</div>

	<div class="row">
		<div class="col-md-2">
			<input type="text" class="date">
		</div>

		<div class="col-md-3">
							<div class="radio">
								<div class="radio-inline">
									<input type="radio" name="production-radio" value="ds" id="production-ds"><label for="production-ds">Day Shift</label>
								</div>
								<div class="radio-inline">
									<input type="radio" name="production-radio" value="ns" id="production-ns"><label for="production-ns">Night Shift</label>
								</div>						
								<div class="radio-inline">
									<input type="radio" name="production-radio" value="all" id="production-all" checked><label for="production-all">All</label>
								</div>
							</div>
		</div>

		<div class="col-md-3">
							<div class="radio">
									<div class="radio-inline">
										<input type="radio" name="production-radio-haul" value="inhouse" id="production-inhouse"><label for="production-inhouse">Inhouse</label>
									</div>
									<div class="radio-inline">
										<input type="radio" name="production-radio-haul" value="subcon" id="production-subcon"><label for="production-subcon">SubCon</label>
									</div>					
									<div class="radio-inline">
										<input type="radio" name="production-radio-haul" value="all" id="production-all-haul" checked><label for="production-all-haul">All</label>
									</div>
							</div>
							<div class="div">
								<select name="" id="production-select" class="form-control input-sm">
									<option value="all">ALL</option>
									<option value="adt">ADT</option>
									<option value="dt">DT</option>
								</select>
							</div>
		</div>

		<div class="col-md-3">
			<input type="button" id="filter" class="btn btn-primary btn-sm" value="Apply Filter">
		</div>
		
	</div>
		
	<div class="row">
		<div class="col-md-6">
			<div class="content-title">
				<h3>Delivery Tickets</h3>
			</div>
			<div class="panel panel-default">					
			  <div class="delivery-result">
			  	
			  </div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="content-title">
				<h3>Rf Readings</h3>
			</div>
			<div class="panel panel-default">
			  <div class="rf-result">
			  	
			  </div>
			</div>
		</div>	
	</div>
</div>
<script>
	$(function(){
		var filter_truck = '';
		var app = {
			init:function(){
				$('.date').date();
				app.get_dt();	
				app.get_rf();		
				this.bindEvents();	
			},
			bindEvents:function(){
				
				$('input[name="production-radio-haul"]').on('change',function(){
					if($('input[name="production-radio-haul"]:checked').val() == 'inhouse'){
						filter_truck = $('#production-select option:selected').val();
						$('#production-select').removeAttr('disabled');
					}else{
						$('#production-select').attr('disabled','disabled');
					}
				});				
				$('#filter').on('click',this.render);

				$('body').on('click','.dt-details',this.dt_details);
				$('body').on('click','.details-delivery-ticket',this.delivery_details);
						
			},
			render:function(){
				
				if($('input[name="production-radio-haul"]:checked').val() == 'inhouse'){
					filter_truck = $('#production-select option:selected').val();
					$('#production-select').removeAttr('disabled');
				}else{
					$('#production-select').attr('disabled','disabled');
					filter_truck = '';
				}
								

				app.get_rf();
				app.get_dt();		

			},
			dt_details:function(){
				$.fancybox.showLoading();

				$post = {
					body_no : $(this).text(),
					date    : $('.date').val(),					
				}

				$.get('<?php echo base_url().index_page();?>/map/dt_details',$post,function(response){

					$.fancybox(response,{
						width     : 1200,
						height    : 550,
						fitToView : true,
						autoSize  : false,
					})
					//$('#tbl_logs').dataTable(datatable_option);

				}).error(function(){
					alert('Unable to connect to Server..');
					$.fancybox.hideLoading();
				});	

			},
			delivery_details:function(){
				$post = {
					body_no : $(this).text(),
					date : $('.date').val(),
					shift : $('input[name="production-radio"]:checked').val()
				}
				$.fancybox.showLoading();
				$.post('<?php echo base_url().index_page();?>/map/delivery_details',$post,function(response){
					$.fancybox(response,{
						width     : 1200,
						height    : 550,
						fitToView : true,
						autoSize  : false,
					})

					$('.myTable').dataTable(datatable_option);
				}).error(function(){
					alert('Internal Server Error, Try again later...');
				});

			},	
			get_dt:function(){

				$('.delivery-result').content_loader('true');

				$post = {
					date         : $('.date').val(),
					filter       : $('input[name="production-radio"]:checked').val(),
					haul_owner   : $('input[name="production-radio-haul"]:checked').val(),
					filter_truck : filter_truck,
				};

				$.post('<?php echo base_url().index_page();?>/map/get_delivery_tickets',$post,function(response){
					$('.delivery-result').html(response);
					$('.tbl-delivery-ticket').dataTable(datatable_option);
				});
				
			},
			get_rf:function(){

				$('.rf-result').content_loader('true');
				$post = {
					date         : $('.date').val(),
					filter       : $('input[name="production-radio"]:checked').val(),
					haul_owner   : $('input[name="production-radio-haul"]:checked').val(),
					filter_truck : filter_truck,
				};

				$.post('<?php echo base_url().index_page();?>/map/gen_report_production',$post,function(response){
					$('.rf-result').html(response);
					$('#tbl-production').dataTable(datatable_option);
				}).error(function(){
					//alert('Server Error, Try Again');
					$('.display_report_production .table-preload').remove();
				});

			}

		};

		app.init();

	});
</script>