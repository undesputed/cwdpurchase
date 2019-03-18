<style>
	.cal-date img{
		height:40px;
		width:40px;
		margin-top:-9px;
	}
	#tbl-data-ns tbody td,#tbl-data tbody td,{
		font-size:14px;
	}
	#tbl-data-ns tbody tr:hover,#tbl-data tbody tr:hover{
		font-weight: bold;
	}

</style>

<div class="header">
	<h2>PROJECT RESOURCE MONITORING REPORT</h2>	
</div>

<div class="container">

	<div class="row" style="margin-top:2em">
		
		<div class="col-md-6">			
		</div>
		
		<div class="col-md-6">
			<h2 class="pull-right cal-date"><span class="full-date">- </span> <input type="hidden" class="date"></h2>
		</div>
		
	</div>
	
	<ul class="nav nav-tabs">
	  <li class="active"><a href="#ds" data-toggle="tab">Day Shift</a></li>
	  <li><a href="#ns" data-toggle="tab">Night Shift</a></li>	  
	</ul>


	<div class="tab-content">
		  <div class="tab-pane active" id="ds">
				<div class="row" style="margin-top:2em">
					<div class="col-md-12">
						<div class="panel panel-default">		
								<div class="data-container">
									
								</div>
						</div>			
					</div>			
				</div>
		  </div>

		  <div class="tab-pane " id="ns">
				<div class="row" style="margin-top:2em">
					<div class="col-md-12">
						<div class="panel panel-default">		
								<div class="data-container-night">
									
								</div>
						</div>			
					</div>			
				</div>
		  </div>

	</div> 




	



	<hr>
</div>


<script>
		var xhr = new Array();
		var app = {
			init:function(){
				$('.date').val(now());

				this.bindEvents();				
								
				$(".date").datepicker({
			        buttonImage: 'asset/img/events-calendar-icon.png',
			        buttonImageOnly: true,				        
			        showOn: 'both',
			        dateFormat:'yy-mm-dd',
			     });

				this.get_date();

				
			},bindEvents:function(){

				$('body').on('click','.get_drivers',this.get_driver);				
				$('body').on('click','.get_standby',this.get_standby_unit);
				$('.date').on('change',this.get_date);

			},get_date:function(){

				for (var i = xhr.length - 1; i >= 0; i--) {
					
					if(xhr[i] && xhr[i].readystate != 4){
				    	xhr[i].abort();
					}
				};
								
				$post = {
					date : $('.date').val()
				};
				$.post('<?php echo base_url().index_page();?>/dispatch/get_date',$post,function(response){
					$('.full-date').html(response);
					app.get_data();
					app.get_data_ns();

				});	
			},get_data:function(){
				$post = {
					date : $('.date').val(),
					shift : 'ds',
				}

				$('.data-container').content_loader('true');
				xhr[1] = $.post('<?php echo base_url().index_page();?>/dispatch/get_data',$post,function(response){
					$('.data-container').html(response);
					app.get_online();
					app.get_assigned();
				}).error(function(){
					alert('Internal Server Error, Please reload the Page.');
				});
				
			},get_data_ns:function(){

				$post = {
					date : $('.date').val(),
					shift : 'ns',
				}
				$('.data-container-night').content_loader('true');
				xhr[2] = $.post('<?php echo base_url().index_page();?>/dispatch/get_data_ns',$post,function(response){
					$('.data-container-night').html(response);
					app.get_online_ns();
					app.get_assigned();
				});


			},get_driver:function(){

				$.fancybox.showLoading();

				$post = {
					equipment  : $(this).attr('data-equipment'),
					shift      : $(this).attr('data-shift'),
					date       : $('.date').val(),
					department : $(this).attr('data-department'),
				};

				xhr[3] = $.post('<?php echo base_url().index_page();?>/dispatch/get_driver',$post,function(response){
					$.fancybox(response,{
						width     : 1000,
						height    : 550,
						fitToView : true,
						autoSize  : false,
					})			
				}).error(function(){
					alert('Internal Server Error, Try again later');
					$.fancybox.hideLoading();
				});

			},get_online:function(){
				$post = {
					date : $('.date').val(),
					shift : 'ds'
				}
				if($('#mine-loading').length > 0){
					$('#mine-loading').addClass('fa-spin fa-spinner');
				}
				if($('#port-loading').length > 0){
					$('#port-loading').addClass('fa-spin fa-spinner');
				}
				xhr[4] = $.post('<?php echo base_url().index_page();?>/dispatch/get_online',$post,function(response){
					if(typeof(response.port) == 'undefined')
					{
						$.each(response,function(i,val){					
							$('#tbl-data tr td a').each(function(i,a){	

								if($(a).hasClass(val.equipment)){								
									$(a).html(val.ds);
								}
							});						
						});
					}else
					{


						$.each(response.mine,function(i,val){												
							$('#tbl-data tr td a.get_drivers_mine').each(function(i,a){	
								if($(a).hasClass(val.equipment)){								
									$(a).html(val.ds);
								}
							});						
						});

						$.each(response.port,function(i,val){												
							$('#tbl-data tr td a.get_drivers_port').each(function(i,a){	
								if($(a).hasClass(val.equipment)){								
									$(a).html(val.ds);
								}
							});						
						});

						$('#mine-loading').removeClass('fa-spin fa-spinner');
						$('#port-loading').removeClass('fa-spin fa-spinner');

					}
														
				},'json').error(function(){
					alert('Internal Server Error, Try again later');
					$.fancybox.hideLoading();
				});

			},get_online_ns:function(){

				$post = {
					date : $('.date').val(),
					shift : 'ns'
				}
				xhr[5] = $.post('<?php echo base_url().index_page();?>/dispatch/get_online',$post,function(response){

						if(typeof(response.port) == 'undefined')
							{
								$.each(response,function(i,val){					
									$('#tbl-data-ns tr td a').each(function(i,a){	

										if($(a).hasClass(val.equipment)){								
											$(a).html(val.ns);
										}
									});						
								});
							}else
							{
								$.each(response.mine,function(i,val){												
									$('#tbl-data-ns tr td a.get_drivers_mine').each(function(i,a){	
										if($(a).hasClass(val.equipment)){								
											$(a).html(val.ns);
										}
									});						
								});

								$.each(response.port,function(i,val){												
									$('#tbl-data-ns tr td a.get_drivers_port').each(function(i,a){	
										if($(a).hasClass(val.equipment)){								
											$(a).html(val.ns);
										}
									});						
								});


						}
										
				},'json');

			},get_assigned:function(){
				$post = {
					date : $('.date').val(),
				}
				xhr[6] = $.post('<?php echo base_url().index_page();?>/dispatch/get_assigned',$post,function(response){
					$('#tbl-data tr td.td-assigned').html('');
					if(typeof(response.department) !='undefined')
					{

						$.each(response.department,function(i,a){
							if(i == 'mod')
							{

								$.each(a,function(j,b){

									if(j=='ds')
									{
										$.each(b,function(l,d){
											$('#tbl-data tr td.td-assigned-mine').each(function(k,c){										
													if($(c).hasClass(d.equipment_category)){
														var standby = $(c).closest('tr').find('td.td-pa').text();								
														var standby2 = standby - d.assigned;								
														$(c).closest('tr').find('td.td-standby > a').html(standby2);
														/*$(c).closest('tr').find('td.td-utilized').html(d.assigned);*/
														$(c).html(d.assigned);
													}
											});

										});
									}else
									{
										$.each(b,function(l,d){
											$('#tbl-data-ns tr td.td-assigned-mine').each(function(k,c){										
													if($(c).hasClass(d.equipment_category)){
														var standby = $(c).closest('tr').find('td.td-pa').text();								
														var standby2 = standby - d.assigned;								
														$(c).closest('tr').find('td.td-standby > a').html(standby2);
														/*$(c).closest('tr').find('td.td-utilized').html(d.assigned);*/
														$(c).html(d.assigned);
													}
											});
											
										});
									}

								});

							}else
							{
								$.each(a,function(j,b){

									if(j=='ds')
									{
										$.each(b,function(l,d){
											$('#tbl-data tr td.td-assigned-port').each(function(k,c){										
													if($(c).hasClass(d.equipment_category)){
														var standby = $(c).closest('tr').find('td.td-pa').text();								
														var standby2 = standby - d.assigned;								
														$(c).closest('tr').find('td.td-standby > a').html(standby2);
														/*$(c).closest('tr').find('td.td-utilized').html(d.assigned);*/
														$(c).html(d.assigned);
													}
											});

										});
									}else
									{
										$.each(b,function(l,d){
											$('#tbl-data-ns tr td.td-assigned-port').each(function(k,c){										
													if($(c).hasClass(d.equipment_category)){
														var standby = $(c).closest('tr').find('td.td-pa').text();								
														var standby2 = standby - d.assigned;								
														$(c).closest('tr').find('td.td-standby > a').html(standby2);
														/*$(c).closest('tr').find('td.td-utilized').html(d.assigned);*/
														$(c).html(d.assigned);
													}
											});
											
										});
									}						
								});

							}
							
						});
						app.get_utilization();
						app.get_standby();
					}else
					{

						$.each(response.ds,function(i,val){
						
							$('#tbl-data tr td.td-assigned').each(function(i,a){
								if($(a).hasClass(val.equipment_category)){
									var standby = $(a).closest('tr').find('td.td-pa').text();								
									var standby2 = standby - val.assigned;								
									$(a).closest('tr').find('td.td-standby > a').html(standby2);
									$(a).closest('tr').find('td.td-utilized').html(val.assigned);
									$(a).html(val.assigned);
								}
							});
						});

						$.each(response.ns,function(i,val){
							$('#tbl-data-ns tr td.td-assigned').each(function(i,a){
								if($(a).hasClass(val.equipment_category)){
									var standby = $(a).closest('tr').find('td.td-pa').text();								
									var standby2 = standby - val.assigned;								
									$(a).closest('tr').find('td.td-standby > a').html(standby2);
									$(a).closest('tr').find('td.td-utilized').html(val.assigned);
									$(a).html(val.assigned);
								}else{
									
								}
							});
						});

					}

					

														
				},'json');			
			},get_standby_unit:function(){

				$post = {
					equipment : $(this).attr('data-equipment'),
					shift : $(this).attr('data-shift'),
					date : $('.date').val(),
				};	

				$.fancybox.showLoading();
				xhr[7] = $.post('<?php echo base_url().index_page();?>/dispatch/get_standby_unit',$post,function(response){
					$.fancybox(response,{
						width     : 1000,
						height    : 550,
						fitToView : true,
						autoSize  : false,
					})
				}).error(function(){
					alert('Internal Server Error, try again later');
					$.fancybox.hideLoading();

				});

			},get_utilization:function(){
				
				$('.td-utilized').each(function(i,a){
					var utilized = 0;
					var ia = $(a).closest('tr').find('.td-assigned-port').text();
					var ib = $(a).closest('tr').find('.td-assigned-mine').text();					
					utilized = +ia + +ib;		
					$(a).html(utilized);					
				});
				
			},get_standby:function(){
				
				$('.td-standby').each(function(i,a){					
					var standby = 0;
					var ia = $(a).closest('tr').find('.td-utilized').text();					
					var ic = $(a).closest('tr').find('.td-pa').text();					
					standby =(ic - ia);		
					$(a).html(standby);
				});
			}

		}

	$(function(){
		app.init();
	});

</script>