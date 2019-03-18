<style>
	.tooltip {
   font-size: 1em;
   opacity: 1;
   padding: 0;
   position: absolute;
   z-index: 12;
   color: #888;
   background: #fff;
   border: 1px solid #aaa;
   -moz-box-shadow: 0px 1px 1px #ddd;
   -webkit-box-shadow: 0px 1px 1px #ddd;
   box-shadow: 0px 1px 2px #ddd;
}

.tooltip div {
   position: relative;
   text-align: center;
   margin: 3px;
   max-width:100px;
}

.tooltip span { font-size: 0.7em; display: block; }
.tooltip span.series { color: #333; }

.tooltip:after {
   content: ' ';
   position: absolute;
   width: 0;
   height: 0;
   margin-left: -5px;
   bottom: -10px;
   left: 50%;
   border-width: 5px;
   border-style: solid;
   border-color: #aaa transparent transparent transparent ;
}
</style>


<script>
		var xcoor = 0;
		var ycoor = 0;
	$(function(){
		var plot;
		var app = {
			init:function(){
				this.bindEvents();
				
			},bindEvents:function(){
				$('#map').on('dblclick',this.addPoint);

			},flot:function(){

				$.get('<?php echo base_url().index_page(); ?>/map/get_scan',function(response){

						/*$.plot("#placeholder",response,{
								xaxes: [ {  mode: "time",
											TickSize: [10, "minute"],											
											axisLabel: 'test',
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5,
										 } ],
								yaxes  : [ { min: 0 ,
											alignTicksWithAxis : 1,
											position:'left',
											 } ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:false },
								lines  :  { show:true },
								
						});*/

					/*	plot = $.plot("#placeholder", response, {
						series: {
							shadowSize: 0	// Drawing is faster without shadows
						},
						yaxis: {
							min: 0,
							max: 100
						},
						xaxis: {
							show: false
						}
					});*/

				},'json');
				

			},update : function(){

			}

		}; //EOF

		app.init();

	});
</script>

<div class="container">

	<div class="content-title">
		<h3>Monitoring</h3>
	</div>	
	
	 <div class="row">
			<div class="col-md-12">
				<div class="row" style="margin-bottom:5px">
								<div class="col-md-4">
									<div class="row">
									<div class="col-md-4 col-xs-6">
										<button class="refresh btn btn-primary btn-block btn-sm ">Refresh</button>
									</div>
									<div class="col-md-4 col-xs-6">
										<input type="text" class="date form-control input-sm" readonly>
									</div>
								</div>
								</div>
																
								<div class="col-md-3 col-xs-12">
									<span class=" title-time" style="font-weight:bold;font-size:15px" ><?php echo $date; ?></span>
								</div>
								<div class="col-md-5">
									<div class="row">
										<div class="col-xs-6">
												<div class="radio-inline">
													<input type="radio" name="filter_trucks" class="" id="adt" checked><label for="adt">ADT</label>
												</div>
												<div class="radio-inline">
													<input type="radio" name="filter_trucks" class="" id="dt"><label for="dt">DT</label>
												</div>
												<div class="radio-inline">
													<input type="radio" name="filter_trucks" class="" id="trucks_all"><label for="trucks_all">ALL</label>
												</div>
										</div>	
										<div class="col-xs-6">
												<div class="radio-inline">
													<input type="radio" name="haul_owner" class="haul_owner" id="inhouse"><label for="inhouse">Inhouse</label>
												</div>
												<div class="radio-inline">
													<input type="radio" name="haul_owner" class="haul_owner" id="subcon"><label for="subcon">SubCon</label>
												</div>
												<div class="radio-inline">
													<input type="radio" name="haul_owner" class="haul_owner" id="haul_all" checked><label for="haul_all">ALL</label>
												</div>
										</div>			
									</div>
									
								</div>

								

				</div>
							
						
			</div> 			
		</div>
		
		<!-- <div class="progress-bar progress-bar-info" style="width: 1%">
			  <span class="mine-progress-day-unit"></span>
		</div>
		<div class="progress-bar progress-bar-danger" style="width: 51%">
		    <span class="mine-progress-night-unit"></span>
		</div> -->		
	<hr>

<!--
	<div class="row">
	<div class="col-md-12">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th style="width:80px">truck</th>
					<th>Logs</th>
				</tr>
			</thead>
			<?php foreach($tags as $row):?>
				<tr class="cls-<?php echo $row; ?>">
					<td><?php echo $row?></td>
					<td class="time"></td>
				</tr>
			<?php endforeach; ?>
		</table>		
	</div>
</div> -->
	<div class="table-responsive">
		<div class="display_rf"></div>
	</div>
	<hr>


	<div class="row">
		<div class="col-md-12">
			<div class="content-title">
				<h3>Production</h3>
			</div>
			<div class="panel panel-default">		
				<div class="panel-body">

					<div class="row">
						<div class="col-md-7">
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
						<div class="col-md-5">
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
					</div>


				</div>	
					<div class="display_report_production"></div>		
			</div>		
		</div>
<!-- 		<div class="col-md-6">
	<div class="content-title">
		<h3>Barging</h3>
	</div>
	<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-7">
						<div class="radio">
							<div class="radio-inline">
								<input type="radio" name="barging-radio" value="ds" id="barging-ds"><label for="barging-ds">Day Shift</label>
							</div>
							<div class="radio-inline">
								<input type="radio" name="barging-radio" value="ns" id="barging-ns"><label for="barging-ns">Night Shift</label>
							</div>						
							<div class="radio-inline">
								<input type="radio" name="barging-radio" value="all" id="barging-all" checked><label for="barging-all">All</label>
							</div>
						</div>
					</div>
					<div class="col-md-5">
						<div class="radio">
							<div class="radio-inline">
								<input type="radio" name="barging-radio-haul" value="inhouse" id="barging-inhouse"><label for="barging-inhouse">Inhouse</label>
							</div>
							<div class="radio-inline">
								<input type="radio" name="barging-radio-haul" value="subcon" id="barging-subcon"><label for="barging-subcon">SubCon</label>
							</div>					
							<div class="radio-inline">
								<input type="radio" name="barging-radio-haul" value="all" id="barging-all-haul" checked><label for="barging-all-haul">All</label>
							</div>
						</div>
					</div>
				</div>
				</div>					
		
			<div class="display_report_barging"></div>		
	</div>			
</div> -->
	</div>
	
	<hr>
	<div style="margin-top:5px;overflow:auto">			
			<!-- <input type="button" id="save" class="btn pull-right btn-primary" value="Save" > -->
	</div>
	<hr>

	<div class="row">
		<div class="col-md-12">
			<div class="table-content">				
			</div>
		</div>
	</div>

</div>
<script>

	$(function(){
		var xhr,xhr2;
		var app_rf = {
			init:function(){
			  $('#date_from').date_from();
			  $('#date_to').date_to();
			  $('.date').date();
			  //this.get_readings();
			  //this.assign_data();
			  this.refresh();
			  this.generate_table();
			  this.generate_report_production();
			  //this.generate_report_barging();
			  this.bindEvents();
			},bindEvents:function(){
				$('.date').on('change','',function(){
					app_rf.generate_table();
					app_rf.generate_report_production();
					//app_rf.generate_report_barging();
				});
				$('#filter').on('click',this.get_readings);
				$('input[name="per_display"]').on('change',this.per_display);
				//$('input[name="per_display"]').trigger('change');
				$('.container').on('click','.view',this.details);
				$('.refresh').on('click',function(){
					location.reload(true);
				});

				$('.progress').on('mouseOn','.custom-hover',function(test){

				});

				$('input[name="filter_trucks"]').on('change',this.generate_table);
				$('input[name="haul_owner"]').on('change',this.generate_table);


								

				$('body').on('click','.dt-details',this.dt_details);

				$('input[name="production-radio"]').on('change',this.generate_report_production);
				$('#production-select').on('change',this.generate_report_production);
				$('input[name="production-radio-haul"]').on('change',this.generate_report_production);
				$('input[name="barging-radio"]').on('change',this.generate_report_barging);
				$('input[name="barging-radio-haul"]').on('change',this.generate_report_barging);

				$('#save').on('click',this.save);


			},get_readings:function(){

				/*$post = {
					from : $('#date_from').val(),
					to   : $('#date_to').val(),
				};*/

				$post = {
					from : '2014-05-24',
					to   : '2014-05-24',
				};
				$('.table-content').content_loader('true');
				$.post('<?php echo base_url().index_page();?>/map/calculate_trips',$post,function(response){
					$('.table-content').html(response);
				});

			},per_dt:function(){
				$post = {
					from : $('#date_from').val(),
					to   : $('#date_to').val(),
				};
				$('.table-content').content_loader('true');
				$.post('<?php echo base_url().index_page();?>/map/per_dt',$post,function(response){
					$('.table-content').html(response);
				});
			},per_display:function(){
				switch($('input[name="per_display"]:checked').attr('id')){
						case"per_dt":
							app_rf.per_dt();
						break;
						case"per_shift":
						break;
				}	
			},details:function(){
				
				$.fancybox.showLoading();
				$post = {
					body_no : $(this).closest('tr').find('td.body_no').text(),
					from : $('#date_from').val(),
					to   : $('#date_to').val(),
				}
				$.get('<?php echo base_url().index_page();?>/map/dt_details',$post,function(response){
					$.fancybox(response,{
						width     : 1200,
						height    : 550,
						fitToView : true,
						autoSize  : true,
					})
				});

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
			assign_data:function(){

				var get = $.get('<?php  echo base_url().index_page(); ?>/map/get_scan',function(response){
					var test = {
						'PY 5 and 6' : 'progress-bar-info',
						'SAMPLING STAND-A' : 'progress-bar-warning',
						'SAMPLING STAND-B' : 'progress-bar-success',
						'Sampling Stand-SubCon': 'progress-bar-danger',
						'Site2' : '',
					};

					//$('#tbl-rf tbody').append();
/*					$.each(response,function(i,val){
						
						var $this = $('.cls-'+i);
						$.each(val,function(key1,val1){
							$.each(val1,function(key2,val2){								
								$.each(val2,function(key3,val3){
																		
									$.each(val3,function(key4,val4){
										
										$this.find('.progress-bar>.last-value').remove();
										$this.append('<div class="progress-bar custom-hover" style="width: .8%"></div>');
										$this.find('.progress-bar:last').addClass(test[key3]);
										var dom = "<div class='last-value'>"+key3 +" : "+val4+"</div>";
										$this.find('.progress-bar:last').html(dom);
										//.addClass(test[key3])
									});


								});
							});
						});
					});*/


				},'json').complete(function(){
					//app_rf.assign_data();
				});

			},
			dt_selection:function(){
				$post = {
					dt : $('#dt-selection').val(),
				};

				$.post('<?php echo base_url().index_page();?>/map/get_trip',$post,function(response){

				},'json');
			},
			refresh:function(){				
				$post = {
					date : $('.date').val(),
				}

				$.post('<?php  echo base_url().index_page(); ?>/map/get_scan',$post,function(response){					
					$.each(response,function(i,val){					
						var $this = $('.'+i);
						$.each(val,function(key1,val1){
							$.each(val1,function(key2,val2){								
								$.each(val2,function(key3,val3){																					
									$.each(val3,function(key4,val4){
										$this.find('td.blue').removeClass('blue');
										if(val3.length-1 == key4)
										{
											$this.find('td.'+key3).html(val4).addClass('blue');
										}
										
									/*	$this.find('.progress-bar>.last-value').remove();
										$this.append('<div class="progress-bar custom-hover" style="width: .8%"></div>');
										$this.find('.progress-bar:last').addClass(test[key3]);
										var dom = "<div class='last-value'>"+key3 +" : "+val4+"</div>";
										$this.find('.progress-bar:last').html(dom);*/
										//.addClass(test[key3])
									});


								});
							});
						});
					});

				},'json').complete(function(){
					/*delay(function(){
						app_rf.refresh();
					},7000);*/
					
				});
			},generate_table:function(){
				
				if($('input[name="filter_trucks"]:checked').attr('id') == 'adt'){
					$('.haul_owner').attr('disabled','disabled');
					$('.haul_owner').closest('.radio-inline').find('label').addClass('text-muted');
				$('#inhouse').prop('checked',true);
				}else{
					$('.haul_owner').removeClass('disabled');
					$('.haul_owner').removeAttr('disabled');
					$('.haul_owner').closest('.radio-inline').find('label').removeClass('text-muted');
				}

				$post = {
					date : $('.date').val(),
					filter_truck : $('input[name="filter_trucks"]:checked').attr('id'),
					haul_owner   : $('input[name="haul_owner"]:checked').attr('id'),
				}
				$('.display_rf').content_loader('true');
				$.post('<?php echo base_url().index_page();?>/map/gen_table',$post,function(response){
					$('.display_rf').html(response.output);
					$('.title-time').html(response.date);
				},'json').complete(function(){
					app_rf.refresh();
				});

			},generate_report_production:function(){

				  if(xhr && xhr.readystate != 4){
			            xhr.abort();
			       }


				var filter_truck = '';
				
				if($('input[name="production-radio-haul"]:checked').val() == 'inhouse'){
					filter_truck = $('#production-select option:selected').val();
					$('#production-select').removeAttr('disabled');
				}else{
					$('#production-select').attr('disabled','disabled');
				}

				$post = {
					date         : $('.date').val(),
					filter       : $('input[name="production-radio"]:checked').val(),
					haul_owner   : $('input[name="production-radio-haul"]:checked').val(),
					filter_truck : filter_truck,
				}

				$('.display_report_production').content_loader('true');
				xhr = $.post('<?php echo base_url().index_page();?>/map/gen_report_production',$post,function(response){
					$('.display_report_production').html(response);
					$('#tbl-production').dataTable(datatable_option);
				}).error(function(){
					//alert('Server Error, Try Again');
					$('.display_report_production .table-preload').remove();
				});


			},generate_report_barging:function(){

				if(xhr2 && xhr2.readystate != 4){
		            xhr2.abort();
		        }
		        
				$post = {
					date         : $('.date').val(),
					filter       : $('input[name="barging-radio"]:checked').val(),
					haul_owner   : $('input[name="barging-radio-haul"]:checked').val(),
				}	
				
				$('.display_report_barging').content_loader('true');
				xhr2 = $.post('<?php echo base_url().index_page();?>/map/gen_report_barging',$post,function(response){
					$('.display_report_barging').html(response);
					$('#tbl-barging').dataTable(datatable_option);
				});

			},save:function(){

				$post = {
					date : $('.date').val(),
				};

				$.fancybox.showLoading();
				$.post('<?php echo base_url().index_page();?>/map/save_reports',$post,function(response){
					alert('Successfully Save');
					$.fancybox.hideLoading();
				});
			}

		}
		app_rf.init();

	});

</script>
