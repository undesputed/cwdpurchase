<div class="header">
	<h2>Tank & Fuel Monitoring</h2>	
</div>

<div class="container">	
	<div class="row">
		<div class="col-md-2 ">
			<div class="panel panel-default sidebar" style="margin-top:10px" data-spy="affix" data-offset-top="100" data-offset-bottom="10">
			 	<div class="panel-body">
			 		<div style="padding:5px 0px">
			 			<input type="text" class="date form-control input-sm">
					</div>												
					<div>
			    		<button id="search" class="btn btn-primary btn-sm pull-right">Search <span class="fa"></span></button>
			    	</div>
			    	<div class="clearfix"></div>	
			    								   
			    </div>
			   					
			</div>
		</div>		
		<div class="col-md-10 driver-content" style="margin-bottom:5em">
	
			<div class="row">
				<div class="col-md-8">
					<div class="content-title">
						<h3>Tank & Fuel Balance</h3>
					</div>
					<div id="gh-tank-fuel-monitoring" class="pop-tooltip" data-title="ltrs" style="width:100%;height:240px"></div>				
				</div>
				<div class="col-md-4">
					<div class="content-title">
						<h3>Fuel Balance</h3>
					</div>	
						
					<div id="gh-sidebar" class="pop-tooltip" data-title="ltrs" style="width:100%;height:240px">
			    		
			    	</div>
					
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="content-title">
						<h3>Withdraw & Transfer Fuel</h3>
					</div>

					<div class="withdraw-content">
							

					</div>



				</div>
				<div class="col-md-6">
					<div class="content-title">
						<h3>received Fuel</h3>
					</div>
					<div class="received-content">
						

					</div>
				</div>
			</div>
			
		</div>
	</div>
		
</div>




<script>

	$(function(){
		var app = {
			init : function(){
				$('.date').date();
				this.get_tank_fuel_monitoring();
				this.bindEvent();
			},
			bindEvent:function(){
				$('#search').on('click',this.get_tank_fuel_monitoring);
			},
			get_tank_fuel_monitoring : function(){
				$('#search').addClass('disabled');
				$('#search').find('span').addClass('fa-spin fa-spinner');

				app.withdraw();
				app.received();

				



				$post = {
					date : $('.date').val(),
				};



				$.post('<?php echo base_url().index_page();?>/tank-fuel-monitoring/get_tank_fuel_monitoring',$post,function(result){

						var response = result.main;

						app.sidebar_graph(result.sidebar);


						$("<div id='tooltip'></div>").css({
								position: "absolute",
								display: "none",
								border: "1px solid #000",
								padding: "2px",
								"background-color": "#333",
								"z-index": '9999',
								opacity: 0.9  
						}).appendTo("body");

						var ticks = [];
						for(var i in response.ticks)
						{
							ticks.push([i,response.ticks[i]]);
						}
						
						$.plot("#gh-tank-fuel-monitoring",response.output,{
								xaxes: [ {  											
											ticks:ticks,
											axisLabel: response.label,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5,
										 } ],
								yaxes  : [ 
											{
												position : 'left',
													tickFormatter: function(v,axis){
															return comma(v.toFixed(axis.tickDecimals) +" ltrs");
													}
											}
										  ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:false },
								lines  :  { show:false },								
						});

						$('.valueLabel,.valueLabelLight').each(function(i,val){
							$(this).text(comma($(this).text()));
						});
						
						$('.pop-tooltip').bind("plothover",function(event,pos,item){
						  		var title = $(this).data('title');
						  		var bar = $(this).data('bar');
					  			if(item){

					  			var value = item.series.data[item.dataIndex][1];
								var date = item.series.data[item.dataIndex][2];
								var type = item.series.data[item.dataIndex][3];
													  			
								var x = item.datapoint[0],
									y = item.datapoint[1];
									
								var my_date = response.ticks[x];
																			
								$('#tooltip').html(date+" = " + comma(value) +" "+title)
									.css({top: item.pageY+5, left: item.pageX+5})
									.fadeIn(200);
									
						  		}else{
						  			$("#tooltip").hide();		  			
						  		}
						  });

						$('#search').removeClass('disabled');
						$('#search').find('span').removeClass('fa-spin fa-spinner');


				},'json');

			},sidebar_graph:function(response){


				$("<div id='tooltip'></div>").css({
						position: "absolute",
						display: "none",
						border: "1px solid #000",
						padding: "2px",
						"background-color": "#333",
						"z-index": '9999',
						opacity: 0.9  
				}).appendTo("body");

				var ticks = [];
				for(var i in response.ticks)
				{
					ticks.push([i,response.ticks[i]]);
				}
				
				$.plot("#gh-sidebar",response.output,{
						xaxes: [ {  											
									ticks:ticks,
									axisLabel: response.label,
					                axisLabelUseCanvas: false,
					                axisLabelFontSizePixels: 11,
					                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
					                axisLabelPadding: 5,
								 } ],
						yaxes  : [ 
									{
										position : 'left',
											tickFormatter: function(v,axis){
													return comma(v.toFixed(axis.tickDecimals) +" ltrs");
											}
									}
								  ],
						grid   :  { hoverable : true, borderWidth: 1,clickable:true},
						points :  { show:false },
						lines  :  { show:false },								
				});

				$('.valueLabel,.valueLabelLight').each(function(i,val){
					$(this).text(comma($(this).text()));
				});
				
				$('.pop-tooltip').bind("plothover",function(event,pos,item){
				  		var title = $(this).data('title');
				  		var bar = $(this).data('bar');
			  			if(item){

			  			var value = item.series.data[item.dataIndex][1];
						var date = item.series.data[item.dataIndex][2];
						var type = item.series.data[item.dataIndex][3];
											  			
						var x = item.datapoint[0],
							y = item.datapoint[1];
							
						var my_date = response.ticks[x];
																	
						$('#tooltip').html(date+" = " + comma(value) +" "+title)
							.css({top: item.pageY+5, left: item.pageX+5})
							.fadeIn(200);
							
				  		}else{
				  			$("#tooltip").hide();		  			
				  		}
				  });

			},
			withdraw:function(){

				$('.withdraw-content').content_loader('true');
				$post = {
					date : $('.date').val(),
				};

				$.post('<?php echo base_url().index_page();?>/tank-fuel-monitoring/get_withdrawal',$post,function(response){
					$('.withdraw-content').html(response);
					$('#tbl-withdraw').dataTable(datatable_option);
				});

			},
			received:function(){

				$('.received-content').content_loader('true');
				$post = {
					date : $('.date').val(),
				};

				$.post('<?php echo base_url().index_page();?>/tank-fuel-monitoring/get_received',$post,function(response){
					$('.received-content').html(response);
					$('#tbl-received').dataTable(datatable_option);
					
				});

			}

		}

		app.init();

	});
	
</script>





