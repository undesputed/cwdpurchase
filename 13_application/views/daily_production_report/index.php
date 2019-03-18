<style>
.fair-color{ background-color:#dbe5f1;}
.cloudy-color{ background-color:#bfbfbf;}
.rain-color{ background-color:#538ed5;}
.light-color{ background-color:#e46d0a;}
.heavy-color{ background-color:#ff0000;}

.operational-color { background-color:#ffff00;}
.standby-color { background-color:#9bbb59;}
</style>



<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
					<!--start-->
					
					<section id="section-date" class="pull-right" style="margin-top:2em;">
						<input type="text" id="date" readonly>
					</section>

					

					<section id="production">
						<div class="content-title">
							<h3>A. MINE OPERATION</h3>
						</div>	

						<div class="panel panel-default"  style="overflow:auto">								
								<div class="table-responsive mine-operation">
									
								</div>
						</div>
					</section>
					<div class="row">
						<div class="col-md-3">
							<section id="transferring">
								<div class="content-title">
									<h3>B.TRANSFERRING</h3>
								</div>
								<div class="panel panel-default">		
								  
								  		<div class="table-responsive transferring-operation" >
												
										</div>									  
								</div>						
							</section>		
						</div>

						<div class="col-md-3">
							<section id="barging">
								<div class="content-title">
									<h3>C.BARGING</h3>
								</div>
								<div class="panel panel-default">								  
								  		<div class="table-responsive barging-operation">												
										</div>
								</div>						
							</section>		
						</div>

						<div class="col-md-6">
							<section id="barging">
								<div class="content-title">
									<h3>D.SHIPMENT</h3>
								</div>
								<div class="panel panel-default">		
								  		<div class="table-responsive shipment-operation">												
										</div>	
								</div>						
							</section>	
							
								<section id="hourly-weather">
									<div class="content-title">
										<h3>HOURLY WEATHER CONDITION AND OPERATION ACTIVITY</h3>
									</div>
									<div class="panel panel-default">		
											  <div class="panel-body">
													<div class="row">
														<div class="col-md-6">
															<canvas id="canvas" width=300 height=300></canvas>
														</div>
														<div class="col-md-6">
															<h3>CAGA2</h3>
															<table class="table table-bordered table-condensed">
																	<tbody>
																		<tr>
																			<td colspan="2">Outer</td>
																			<td>Inner</td>																			
																		</tr>
																		<tr>
																			<td colspan="2">Weather</td>
																			<td>Activity</td>
																			
																		</tr>
																		<tr>
																			<td class="fair-color">F</td>
																			<td class="fair-color">Fair</td>
																			<td class="operational-color">Operation</td>
																		</tr>																	
																		<tr>
																			<td class="cloudy-color">C</td>
																			<td class="cloudy-color">Cloudy</td>
																			<td class="standby-color">Standby</td>
																		</tr>
																		<tr>
																			<td class="rain-color">R</td>
																			<td class="rain-color">Rain</td>
																			<td></td>
																		</tr>
																		<tr>
																			<td class="light-color">L</td>
																			<td class="light-color">Light Rain</td>
																			<td></td>
																		</tr>
																		<tr>
																			<td class="heavy-color">H</td>
																			<td class="heavy-color">Heavy Rain</td>
																			<td></td>
																		</tr>
																	</tbody>
															</table>
														</div>
													</div>

											  </div>	 
									</div>												
								</section>


						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="content-title">
								<h3>E.EQUIPMENT INVENTORY</h3>
							</div>
							<div class="panel panel-default" style="overflow:auto">									  
							  			<div class="table-responsive equipment-inventory">							  				
							  			</div>
							</div>

						</div>
					</div>											
					<!--/end-->
				</div>							
			</div>
		</div>	
			
	</div>

	<hr>
		
</div>

<script>
	$(function(){
		var xhr1,xhr2,xhr3;
		var app = {
			init:function(){
				$(".mine-operation").niceScroll();
				$(".equipment-inventory").niceScroll();
				$('#date').date();
				this.bindEvent();
				this.get_production();
				this.get_transferring();
				this.get_barging();
				this.get_shipment();
				this.get_equipment_inventory();
			},
			bindEvent:function(){
				$('#date').on('change',function(){
					if(xhr1 && xhr1.readystate != 4){
				            xhr1.abort();
				    }

				    if(xhr2 && xhr2.readystate != 4){
				            xhr2.abort();
				    }

				    if(xhr3 && xhr3.readystate != 4){
				            xhr3.abort();
				    }

				    if(xhr4 && xhr4.readystate != 4){
				            xhr4.abort();
				    }
				    if(xhr5 && xhr5.readystate != 4){
				            xhr5.abort();
				    }


					app.get_production();
					app.get_transferring();
					app.get_barging();
					app.get_shipment();
					app.get_equipment_inventory();
					app_canvass.get_weather();
				});
			},
			get_production:function(){
				$post = {
					date : $('#date').val(),
				};

				$('.mine-operation').content_loader('true');
				xhr1 = $.post('<?php echo base_url().index_page();?>/daily-project-report/get_production',$post,function(response){
					$('.mine-operation').html(response);
				});
			},
			get_transferring:function(){
				$post = {
					date : $('#date').val(),
				};
				$('.transferring-operation').content_loader('true');
				xhr2 = $.post('<?php echo base_url().index_page();?>/daily-project-report/get_transffering',$post,function(response){
					$('.transferring-operation').html(response);
				});				
			},
			get_barging:function(){
				$post = {
					date : $('#date').val(),
				};
				xhr3 = $('.barging-operation').content_loader('true');
				$.post('<?php echo base_url().index_page();?>/daily-project-report/get_barging_operation',$post,function(response){
					$('.barging-operation').html(response);
				});				
			},
			get_shipment:function(){
				$post = {
					date : $('#date').val(),
				};
				$('.shipment-operation').content_loader('true');
				xhr4 = $.post('<?php echo base_url().index_page();?>/daily-project-report/get_shipment',$post,function(response){
					$('.shipment-operation').html(response);
				});
			},
			get_equipment_inventory:function(){
				$post = {
					date : $('#date').val(),
				};
				$('.equipment-inventory').content_loader('true');
				xhr5 = $.post('<?php echo base_url().index_page();?>/daily-project-report/get_equipment_inventory',$post,function(response){
					$('.equipment-inventory').html(response);
				});
			},

		}

		app.init();


		var app_canvass = {
				get_weather:function(){
					$post = {
					date : $('#date').val(),
					};
					$.post('<?php echo base_url().index_page();?>/daily-project-report/get_weather',$post,function(response){

						var canvas = document.getElementById("canvas");
						var ctx = canvas.getContext("2d");

						ctx.lineWidth = 35;

						var PI = Math.PI;
						var rotation = 0;

						var arcs = [];

						var interval = 15;
						var start = -90;
						var end = 0;
						//var colors = ['#19BC9C','#2C3E50','#DBE4E4','#7FC242'];
						//var colors2 = ['#6A8BBE','#5AB6DF','#FB8575','#2E3E4D'];

						var status = {
							OPERATING : '#ffff00',
							STANDBY     : '#9bbb59',
						};
						var weather = {
							FAIR : '#dbe5f1',
							CLOUDY : '#bfbfbf',
							RAIN_SHOWER  : '#538ed5',
							LIGHT_RAIN : '#e46d0a',
							HEAVY_RAIN : '#ff0000',
						};

						var color_index = 0;

						for (var i = 1 ; i <= 24; i++){
							console.log(response[i]);
							color_index++;
							end = (start + interval);
							arcs.push({
							    cx: 150,
							    cy: 150,
							    radius: 100,
							    startAngle: start,
							    endAngle: end,
							    color: weather[response[i].weather],    
							});
							
							arcs.push({
							    cx: 150,
							    cy: 150,
							    radius: 60,
							    startAngle: start,
							    endAngle: end,
							    color: status[response[i].operation],    
							});

							/*if(color_index == colors.length){
								color_index = 0;
							}*/

							start += interval;						

						};


						drawAll();
						drawLine({ x:  150, y:  0, r: 25 },{ x: 150, y:  300, r: 25 });
						drawLine({ x: 0  , y:  150, r: 25 },{ x: 300, y:  150, r: 25 });


						ctx.font = "bold 12px sans-serif";
						ctx.fillText("7 am", 5, 145);
						ctx.fillText("7 pm", 265, 164);

						function drawAll() {
						    ctx.clearRect(0,0,canvas.width,canvas.height);
						    for (var i = 0; i < arcs.length; i++) {
						        draw(arcs[i]);
						    }
						}

						function draw(a) {
						    ctx.save();
						    ctx.translate(a.cx, a.cy);
						    ctx.rotate(rotation * PI / 180);
						    ctx.beginPath();
						    ctx.arc(0, 0, a.radius, a.startAngle * PI / 180, a.endAngle * PI / 180);
						       
						    ctx.strokeStyle = a.color;
						    ctx.stroke();

						    ctx.restore();
						}

						function drawLine(from, to){
							ctx.lineWidth = 1;
						    ctx.beginPath();
						    ctx.moveTo(from.x, from.y);
						    ctx.lineTo(to.x, to.y);
						    ctx.stroke();
						}


					},'json');
				},
				init:function(){

				/*
				1	1pm
				2	2pm
				3	3pm
				4	4pm
				5	5pm
				6	6pm
				7	7pm
				8	8pm
				9	9pm
				10	10pm
				11	11pm
				12	12am
				13	1am
				14	2am
				15	3am
				16	4am
				17	5am
				18	6am
				19	7am
				20	8am
				21	9am
				22	10am
				23	11am
				24	12nn
				*/

					

					$("#rotate").click(function () {
					    rotation += 90;
					    drawAll();

					});

				}				
		}

		app_canvass.get_weather();

	});
</script>




