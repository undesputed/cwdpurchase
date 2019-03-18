<script>
	
	$(function(){

		
		var app = {
			init : function(){
				this.get_mining();
				this.get_donut();
				this.get_total();
				this.get_trip_count();
				this.get_shipment();
				this.get_shipment_trips();
				this.get_mining_daily();
			},
			get_mining : function(){
				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_production',function(response){

						//console.log(response);
						//var oilprices  = [[1167792400000,61.05], [1167778800000,58.32], [1167865200000,57.35], [1167951600000,56.31], [1168210800000,55.55]];						
						//$.plot("#placeholder", value, plot_option);

						$.plot("#placeholder",response,{
								xaxes: [ { 
											mode: "time",
											minTickSize: [1, "day"],																
										 } ],
								yaxes  : [ { 
											min: 0 ,																										
											 } ],
								grid   :  { hoverable : true},
								points :  { show: true },
								lines  :  { show:true },

						});
						
				},'json');
			},
			get_mining_daily : function(){
				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_production',function(response){

						//console.log(response);
						//var oilprices  = [[1167792400000,61.05], [1167778800000,58.32], [1167865200000,57.35], [1167951600000,56.31], [1168210800000,55.55]];						
						//$.plot("#placeholder", value, plot_option);

						$.plot("#mining-daily-unit",response,{
								xaxes: [ { 
											mode: "time",
											minTickSize: [1, "hours"],																
										 } ],
								yaxes  : [ { 
											min: 0 ,																										
											 } ],
								grid   :  { hoverable : true},
								points :  { show: true },
								lines  :  { show:true },

						});
						
				},'json');
			},
			get_trip_count:function(){
				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_trip_cnt',function(response){																	
						$.plot("#trip_count",response,{
								xaxes: [ { 
											mode: "time",
											minTickSize: [1, "day"],																
										 } ],
								yaxes  : [ { 
											min: 0 ,																	
											 } ],
								grid   :  { hoverable : true},
								points :  { show: true },
								lines  :  { show:true },

						});
						
				},'json');
			},
			get_donut : function(){
				$.get('<?php echo base_url().index_page();?>/manage_report/get_donut',function(response){

							Morris.Donut({
							  element: 'donut',
							  data: response.drivers,
							  formatter: function (x) { return x }
							});

							Morris.Donut({
							  element: 'donut2',
							  data:response.trucks,
							  formatter: function (x) { return x }
							})

							Morris.Donut({
							  element: 'donut3',
							  data: response.drop,
							  formatter: function (x) { return x }
							})

				},'json');
			},
			get_total : function(){

				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_total',function(response){
					$.each(response,function(i,val){
						$('#'+val.description).html(val.value);
					});
				},'json');

			},get_shipment : function(){
				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_shipment',function(response){

						$.plot("#shipment_unit",response,{
								xaxes: [ { 
											mode: "time",
											minTickSize: [1, "day"],																
										 } ],
								yaxes  : [ { 
											min: 0 ,																	
											 } ],
								grid   :  { hoverable : true},
								points :  { show: true },
								lines  :  { show:true },

						});

				},'json');

			},
			get_shipment_trips : function(){
				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_shipment_trips',function(response){

						$.plot("#shipment_trip",response,{
								xaxes: [ { 
											mode: "time",
											minTickSize: [1, "day"],																
										 } ],
								yaxes  : [ { 
											min: 0 ,																	
											 } ],
								grid   :  { hoverable : true},
								points :  { show: true },
								lines  :  { show:true },

						});

				},'json');

			}
		};

		app.init();

		  $("<div id='tooltip'></div>").css({
				position: "absolute",
				display: "none",
				border: "1px solid #000",
				padding: "2px",
				"background-color": "#333",
				opacity: 0.9  
		  }).appendTo("body");
		  		  
		  $('.pop-tooltip').bind("plothover",function(event,pos,item){
		  		var title = $(this).data('title');
		  		if(item){
		  			console.log(item);
		  			/*var x = item.datapoint[0].toFixed(2),
						y = item.datapoint[1].toFixed(2);*/
						var x = item.datapoint[0],
							y = item.datapoint[1];
						$('#tooltip').html(item.series.label + " : "+title+" = " + comma(y))
								.css({top: item.pageY+5, left: item.pageX+5})
								.fadeIn(200);
					/*$("#tooltip").html(item.series.label + " : " + x + " = " + y)
						.css({top: item.pageY+5, left: item.pageX+5})
						.fadeIn(200);*/

		  		}else{
		  			$("#tooltip").hide();		  			
		  		}
		  });

	});	

</script>
<nav id="nav">
	<div class="container">
		<ul>
			<li><a href="#mining">Mine Operation</a></li>
			<li><a href="#shipping">Shipment Operation</a></li>
			<li><a href="#"></a></li>
			<li><a href="#"></a></li>
		</ul>

	</div>
</nav>

<div class="container">

<div class="content-title">
	<h3>DashBoard</h3>
</div>

<div class="row" style="margin-top:1em">
	<div class="col-md-3">
			<div class="row" style="margin-bottom:2em">
					<div class="col-md-6">
						<div class="dashboard-stat blue">
							<div class="visual">
								<i class="fa fa-anchor"></i>					
							</div>
							<div class="details">
								<div class="number" id="barging">0</div>
								<div class="desc">Barging</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="dashboard-stat blue">
							<div class="visual">
								<i class="fa fa-calendar"></i>					
							</div>
							<div class="details">
								<div class="number" id="monitoring">0</div>
								<div class="desc">Breakdown Monitoring</div>
							</div>
						</div>
					</div>
			</div>

			<div class="row">
					<div class="col-md-6">
						<div class="dashboard-stat blue">
							<div class="visual">
								<i class="fa fa-exchange"></i>					
							</div>
							<div class="details">
								<div class="number" id="mining">0</div>
								<div class="desc">Mining Operation</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
							<div class="dashboard-stat blue">
										<div class="visual">
											<i class="fa fa-truck"></i>					
										</div>
										<div class="details">
											<div class="number" id="equipment_availability">0</div>
											<div class="desc">Equipment Availability</div>
										</div>
							</div>
					</div>
			</div>	
	</div>
	<div class="col-md-6">
		<div class="panel panel-default">		
		  <div class="panel-body">
					<div class="row">
						<div class="col-md-4">
								<div id="donut" class="donut"></div>
								<div class="donut-title">Drivers</div>
						</div>
						<div class="col-md-4">
								<div id="donut2" class="donut"></div>
								<div class="donut-title">Trucks</div>
						</div>
						<div class="col-md-4">
								<div id="donut3" class="donut"></div>
								<div class="donut-title">Draft Survey</div>
								<!--
								<ul class="bar-legend">
									<li><span class="light-blue"></span>Flat Tire</li>
									<li><span class="red"></span>Damange Trucks</li>
									<li><span class="light-green"></span>Running</li>
									<li><span class="red"></span>Others</li>
								</ul>
								-->
						</div>
					</div>		  		
		  </div>	 
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">Pdf Reports</div>			
			<div class="list-group dashboard-list-group">
				<?php foreach($this->lib_manage_report->get_reports() as $row): ?>
					  <a href="javascript:void(0)" id="<?php echo $row['id'] ?>" class="fancy-report list-group-item">
					  		<span class="list-date"><?php echo date('F, d',strtotime($row['submission_date'])); ?></span>
					  		<h5 class="list-group-item-heading" title="<?php echo $row['subject'] ?>"><?php echo substr($row['subject'],0,50); ?></h5>
		    				<p class="list-group-item-text"><?php echo substr($row['caption'],0,100).'...'; ?></p>			  	
					  </a>
				<?php endforeach; ?>			
			</div>	
		</div>
	</div>		
</div>

<section class="row" style="margin-top:1em" id="mining">	
	<div class="col-md-9">
		<div class="panel panel-default">		
		  <div class="panel-heading">Mining Operation : Monthly</div>
		  <div class="panel-body">
		  		<div class="row">
		  			<div class="col-md-6">
		  					<h5>Utilized Hauling Unit</h5>
			  				<div class="demo-container">					
								<div id="placeholder" class="pop-tooltip demo-placeholder" data-title="Unit"></div>
							</div>		
		  			</div>
		  			<div class="col-md-6">
		  					<h5>Trip Counts</h5>
		  					<div class="demo-container">					
								<div id="trip_count" class="pop-tooltip demo-placeholder" data-title="Trips"></div>
							</div>
		  			</div>
		  		</div>
		  </div>	 
		</div>		
	</div>
	
	<div class="col-md-3">		
		<div class="panel panel-default">
			<div class="panel-heading">Average</div>
		  <div class="panel-body">
				<ul class="list-group">
					<li class="list-group-item">
						<h5>Average No of Units Utilized</h5>	

						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="">191 Day Shift</span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="">175 Night Shift</span>
							  </div>							

						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th></th>									
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Avg.Unit : <strong>Day</strong> </td>
									<td>34.00</td>
									
								</tr>
								<tr>
									<td>Avg.Unit : <strong>Night</strong> </td>
									<td>30.00</td>									
								</tr>
									<tr>
									<th><strong>Avg.Trips</strong> </th>
									<th><strong>62.00</strong></th>
									
								</tr>
							</tbody>
						</table>						

					</li>
					<li class="list-group-item">
						<h5>Average No of Trips</h5>	
						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="">191 Day Shift</span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="">175 Night Shift</span>
							  </div>							

						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th></th>
									<th><small>Equi.(WMT)</small></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Avg.Trips : <strong>Day</strong> </td>
									<td>191</td>
									<td>3,4338.00</td>
								</tr>
								<tr>
									<td>Avg.Trips : <strong>Night</strong> </td>
									<td>175</td>
									<td>3,4338.00</td>
								</tr>
									<tr>
									<td><strong>Avg.Trips</strong> </td>
									<td><strong>354</strong></td>
									<td><strong>6,372.00</strong></td>
								</tr>
							</tbody>
						</table>		
					</li>
				</ul>
		  </div>	 
		</div>
	</div>
</section>
<hr>

<section class="row" style="margin-top:1em" id="mining">	
	<div class="col-md-9">
		<div class="panel panel-default">		
		  <div class="panel-heading">Mining Operation : Daily</div>
		  <div class="panel-body">
		  		<div class="row">
		  			<div class="col-md-6">
		  					<h5>Utilized Hauling Unit</h5>
			  				<div class="demo-container">					
								<div id="mining-daily-unit" class="pop-tooltip demo-placeholder" data-title="Unit"></div>
							</div>		
		  			</div>
		  			<div class="col-md-6">
		  					<h5>Trip Counts</h5>
		  					<div class="demo-container">					
								<div id="mining-daily-trip" class="pop-tooltip demo-placeholder" data-title="Trips"></div>
							</div>
		  			</div>
		  		</div>
		  </div>	 
		</div>		
	</div>
	
	<div class="col-md-3">		
		<div class="panel panel-default">
			<div class="panel-heading">Average</div>
		  <div class="panel-body">
				<ul class="list-group">
					<li class="list-group-item">
						<h5>Average No of Units Utilized</h5>	

						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="">191 Day Shift</span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="">175 Night Shift</span>
							  </div>							

						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th></th>									
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Avg.Unit : <strong>Day</strong> </td>
									<td>34.00</td>
									
								</tr>
								<tr>
									<td>Avg.Unit : <strong>Night</strong> </td>
									<td>30.00</td>									
								</tr>
									<tr>
									<th><strong>Avg.Trips</strong> </th>
									<th><strong>62.00</strong></th>
									
								</tr>
							</tbody>
						</table>						

					</li>
					<li class="list-group-item">
						<h5>Average No of Trips</h5>	
						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="">191 Day Shift</span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="">175 Night Shift</span>
							  </div>							

						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th></th>
									<th><small>Equi.(WMT)</small></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Avg.Trips : <strong>Day</strong> </td>
									<td>191</td>
									<td>3,4338.00</td>
								</tr>
								<tr>
									<td>Avg.Trips : <strong>Night</strong> </td>
									<td>175</td>
									<td>3,4338.00</td>
								</tr>
									<tr>
									<td><strong>Avg.Trips</strong> </td>
									<td><strong>354</strong></td>
									<td><strong>6,372.00</strong></td>
								</tr>
							</tbody>
						</table>		
					</li>
				</ul>
		  </div>	 
		</div>
	</div>
</section>
<hr>



<section class="row" id="shipping">
	<div class="col-md-9">
		<div class="panel panel-default">
				  <div class="panel-heading">Shipment Operation</div>	
				  <div class="panel-body">
				  		<div class="row">
				  			<div class="col-md-6">
				  					<h5>Utilized DT Units</h5>
					  				<div class="demo-container">					
										<div id="shipment_unit" class="pop-tooltip demo-placeholder" data-title="Unit"></div>
									</div>		
				  			</div>
				  			<div class="col-md-6">
				  					<h5>Trip Counts</h5>
				  					<div class="demo-container">					
										<div id="shipment_trip" class="pop-tooltip demo-placeholder" data-title="Trips"></div>
									</div>
				  			</div>
				  		</div>
				  </div>	 
		</div>		
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">Average</div>
		  <div class="panel-body">
				<ul class="list-group">
					<li class="list-group-item">
						<h5>Average No of Units Utilized</h5>	

						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="">191 Day Shift</span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="">175 Night Shift</span>
							  </div>							

						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th></th>									
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Avg.Unit : <strong>Day</strong> </td>
									<td>34.00</td>
									
								</tr>
								<tr>
									<td>Avg.Unit : <strong>Night</strong> </td>
									<td>30.00</td>									
								</tr>
									<tr>
									<th><strong>Avg.Trips</strong> </th>
									<th><strong>62.00</strong></th>
									
								</tr>
							</tbody>
						</table>						

					</li>
					<li class="list-group-item">
						<h5>Average No of Trips</h5>	
						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="">191 Day Shift</span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="">175 Night Shift</span>
							  </div>							

						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th></th>
									<th><small>Equi.(WMT)</small></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Avg.Trips : <strong>Day</strong> </td>
									<td>191</td>
									<td>3,4338.00</td>
								</tr>
								<tr>
									<td>Avg.Trips : <strong>Night</strong> </td>
									<td>175</td>
									<td>3,4338.00</td>
								</tr>
									<tr>
									<td><strong>Avg.Trips</strong> </td>
									<td><strong>354</strong></td>
									<td><strong>6,372.00</strong></td>
								</tr>
							</tbody>
						</table>		
					</li>
				</ul>
		  </div>	 
		</div>
	</div>
</section>

<hr>	
</div>

<script>
	$(function(){
		FixScroll('nav');
		var app_fancy = {
			init:function(){
				$('.fancy-report').on('click',this.fancy);
			},
			fancy:function(){
					var id = $(this).attr('id');
					$post = {
						id : id,
					};
					
					$.post('<?php echo base_url().index_page(); ?>/reports/view_pdf',$post,function(response){
						$.fancybox(response,{
							width     : 1200,
							height    : 550,
							fitToView : false,
							autoSize  : false,
						});
					});

			}
		}
		app_fancy.init();
	});


</script>