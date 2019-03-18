<script>


	$(function(){
		$('#today_date').date();
		$post = {	
				from : $('#from_date').val(),
				to   : $('#to_date').val(),
		};

		var app = {
			init : function(){
				
				this.get_target();
				this.get_mine_operation();
				this.get_shipment();	
				this.get_draft_survey();
				this.sub_con();
				this.inhouse();	
				this.get_shipment_subcon();
				this.get_shipment_inhouse();
				this.utilization();
				this.get_vessel();	
				this.progress_target();
				
			},
			get_target:function(){

				function wmt_format(v,axis){
						return comma(v.toFixed(axis.tickDecimals)) + " wmt";
				};

				$('#gh-target-production').content_loader('true');				
				$.post('<?php echo base_url().index_page();?>/manage_report/get_target',$post,function(response){

						var markings = [
							{ color: "#ffe5e5", yaxis: { to: 20000 }}								
						];

						var ticks = [];

						for(var i in response.ticks)
						{
							ticks.push([i,response.ticks[i]]);
						}

						$.plot("#gh-target-production",response.total_wmt,{
								xaxes: [ {  
											ticks:ticks,
											axisLabel: response.month,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5

										 } ],
								yaxes  : [ { min: 0 ,
											alignTicksWithAxis : 1,
											position:'left',
											tickFormatter: wmt_format,
											 } ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true,markings:markings},
								points :  { show:true },
								lines  :  { show:true },
								
						});



						$('.pop-tooltip2').bind("plothover",function(event,pos,item){
					  		var title = $(this).data('title');
					  		if(item){
					  				
								var x = item.datapoint[0],
									y = item.datapoint[1];
									
									var my_date = response.month +', '+ response.ticks[x];

									$('#tooltip').html(item.series.label + " : "+my_date+" = " + comma(y) +" "+title)
											.css({top: item.pageY+5, left: item.pageX+5})
											.fadeIn(200);
									

						  		}else{
						  			$("#tooltip").hide();		  			
						  		}
						  });


							$("#gh-target-production").bind("plotdblclick", function (event, pos, item){

								if(item){
																		
									$.fancybox.showLoading();									
									$post = {
										 x : response.dates[item.datapoint[0]],
										 y : item.datapoint[1],
										 label : item.series.label,
									}
									
									$.post('<?php echo base_url().index_page() ?>/report_details/daily_production',$post,function(response){

										$.fancybox(response,{
											width     : 1000,
											height    : 550,
											fitToView : false,
											autoSize  : true,
										})	
										$('.myTable').dataTable(datatable_option);

									}); 

								}
							});



				},'json');

			},
			stockpile:function(){

				$('#gh-stockpile').content_loader('true');
				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_stockpile',function(response){
					$('.stockpile-total').html(comma(response.total)+" WMT");
					$.plot('#gh-stockpile',response.data,{
						xaxis: {
							axisLabel: response.date,
				            axisLabelUseCanvas: false,
				            axisLabelFontSizePixels: 12,
				            axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
				            axisLabelPadding: 10,
				            ticks: [
		                        [1, "LG HF"],
		                        [2, "LG MF"],
		                        [3, "MG HF"],
		                        [4, "MG MF"],
		                        [5, "MG LF"],
		                        [6, "HG"],		                       
		                    ],
						},
						yaxes:[{
							 tickFormatter: function (val, axis) {
				                    return comma(val);
				             },

						}]

					});


				$('.valueLabel,.valueLabelLight').each(function(i,val){
					$(this).text(comma($(this).text()));
				});
							
				},'json');
				
			},
			get_mine_operation : function(){

				$('#mine-operation-unit').content_loader('true');
				$('#mine-operation-trip').content_loader('true');

				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_production',$post,function(response){
						
						//console.log(response);
						//var oilprices  = [[1167792400000,61.05], [1167778800000,58.32], [1167865200000,57.35], [1167951600000,56.31], [1168210800000,55.55]];						
						//$.plot("#placeholder", value, plot_option);

						$('.mine-data-date').html(response.date);

						$('#mine-adt-unit-day').html(response.avg['DS ADT UNIT']);
						$('#mine-dt-unit-day').html(response.avg['DS DT UNIT']);
						$('#mine-adt-unit-night').html(response.avg['NS ADT UNIT']);
						$('#mine-dt-unit-night').html(response.avg['NS DT UNIT']);

						$('.mine-progress-day-unit').html(sum(response.avg['DS ADT UNIT'],response.avg['DS DT UNIT']) + " Day Shift");
						$('.mine-progress-night-unit').html(sum(response.avg['NS ADT UNIT'],response.avg['NS DT UNIT']) + " Night Shift");

						$('#mine-adt-avg').html(average(response.avg['DS ADT UNIT'],response.avg['NS ADT UNIT']));
						$('#mine-dt-avg').html(average(response.avg['DS DT UNIT'],response.avg['NS DT UNIT']));

						$('#mine-adt-trips-day').html(response.avg['DS ADT TRIP']);
						$('#mine-adt-wmt-day').html(comma(response.avg['DS ADT WMT']));
						$('#mine-dt-trips-day').html(response.avg['DS DT TRIP']);
						$('#mine-dt-wmt-day').html(comma(response.avg['DS DT WMT']));


						$('#mine-adt-trips-night').html(response.avg['NS ADT TRIP']);
						$('#mine-adt-wmt-night').html(comma(response.avg['NS ADT WMT']));
						$('#mine-dt-trips-night').html(response.avg['NS DT TRIP']);
						$('#mine-dt-wmt-night').html(comma(response.avg['NS DT WMT']));


						$('.mine-progress-trip-day').html(sum(response.avg['DS ADT TRIP'],response.avg['DS DT TRIP']) + " Day Shift");
						$('.mine-progress-trip-night').html(sum(response.avg['NS ADT TRIP'],response.avg['NS DT TRIP'])+ " Night Shift");



						$('#mine-avg-adt-trips').html(average(response.avg['DS ADT TRIP'],response.avg['NS ADT TRIP']));
						$('#mine-avg-adt-wmt').html(comma(average(response.avg['DS ADT WMT'],response.avg['NS ADT WMT'])));
						$('#mine-avg-dt-trips').html(average(response.avg['DS DT TRIP'],response.avg['NS DT TRIP']));
						$('#mine-avg-dt-wmt').html(comma(average(response.avg['DS DT WMT'],response.avg['NS DT WMT'])));


						$('.mine-avg-trip-adt').html(response.average.mine.adt.all);
						$('.mine-avg-trip-dt').html(response.average.mine.dt.all);	
						
												
						$.plot("#mine-operation-unit",response.unit,{
								xaxes: [ {  mode: "time",
											TickSize: [1, "day"],
											timeformat : '%d',	
											axisLabel: response.month,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5

										 } ],
								yaxes  : [ { min: 0 ,
											alignTicksWithAxis : 1,
											position:'left',
											 } ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:true },
								lines  :  { show:true },
								
						});


						$.plot("#mine-operation-trip",response.trip,{
								xaxes: [ { 
											mode: "time",
											minTickSize: [1, "day"],
											timeformat : '%d',	
											axisLabel: response.month,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5																
										 } ],
								yaxes  : [ { 
											min: 0 ,																	
											 } ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:true },
								lines  :  { show:true },

						});
						

				},'json');
			},sub_con:function(){

				$('#subcon-operation-unit').content_loader('true');
				$('#subcon-operation-trip').content_loader('true');

				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_subcon',$post,function(response){
						$('.mine-subcon-data-date').html(response.date);
						//$('#subcon-adt-unit-day').html(response.avg['DS ADT UNIT']);
						$('#subcon-dt-unit-day').html(response.avg['DS DT UNIT']);
						//$('#subcon-adt-unit-night').html(response.avg['NS ADT UNIT']);
						$('#subcon-dt-unit-night').html(response.avg['NS DT UNIT']);

						$('.subcon-progress-day-unit').html(response.avg['DS DT UNIT'] + " Day Shift");
						$('.subcon-progress-night-unit').html(response.avg['NS DT UNIT'] + " Night Shift");

						//$('#subcon-adt-avg').html(average(response.avg['DS ADT UNIT'],response.avg['NS ADT UNIT']));
						$('#subcon-dt-avg').html(average(response.avg['DS DT UNIT'],response.avg['NS DT UNIT']));

						//$('#subcon-adt-trips-day').html(response.avg['DS ADT TRIP']);
						//$('#subcon-adt-wmt-day').html(comma(response.avg['DS ADT WMT']));
						$('#subcon-dt-trips-day').html(response.avg['DS DT TRIP']);
						$('#subcon-dt-wmt-day').html(comma(response.avg['DS DT WMT']));


						//$('#subcon-adt-trips-night').html(response.avg['NS ADT TRIP']);
						//$('#subcon-adt-wmt-night').html(comma(response.avg['NS ADT WMT']));
						$('#subcon-dt-trips-night').html(response.avg['NS DT TRIP']);
						$('#subcon-dt-wmt-night').html(comma(response.avg['NS DT WMT']));


						$('.subcon-progress-trip-day').html(response.avg['DS DT TRIP'] + " Day Shift");
						$('.subcon-progress-trip-night').html(response.avg['NS DT TRIP'] + " Night Shift");


						//$('#subcon-avg-adt-trips').html(average(response.avg['DS ADT TRIP'],response.avg['NS ADT TRIP']));
						//$('#subcon-avg-adt-wmt').html(comma(average(response.avg['DS ADT WMT'],response.avg['NS ADT WMT'])));
						$('#subcon-avg-dt-trips').html(average(response.avg['DS DT TRIP'],response.avg['NS DT TRIP']));
						$('#subcon-avg-dt-wmt').html(comma(average(response.avg['DS DT WMT'],response.avg['NS DT WMT'])));

						$('.mine-avg-trip-dt-subcon').html(response.average.mine.dt.subcon);					
						
						$.plot("#subcon-operation-unit",response.unit,{
								xaxes: [ {  mode: "time",
											minTickSize: [1, "day"],
											timeformat : '%d',	
											axisLabel: response.month,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5																
										 } ],
								yaxes  : [ { min: 0 ,
											alignTicksWithAxis : 1,
											position:'left',
											 } ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:true },
								lines  :  { show:true },
								
						});

						$.plot("#subcon-operation-trip",response.trip,{

								xaxes: [ { 
											mode: "time",
											minTickSize: [1, "day"],
											timeformat : '%d',	
											axisLabel: response.month,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5
										 } ],
								yaxes  : [ { 
											min: 0 ,																	
											 } ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:true },
								lines  :  { show:true },

						});
												
					
				},'json');
			},
			inhouse:function(){

				$('#inhouse-operation-unit').content_loader('true');
				$('#inhouse-operation-trip').content_loader('true');

				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_inhouse',$post,function(response){
						$('.mine-inhouse-data-date').html(response.date);

						$('#inhouse-adt-unit-day').html(response.avg['DS ADT UNIT']);
						$('#inhouse-dt-unit-day').html(response.avg['DS DT UNIT']);
						$('#inhouse-adt-unit-night').html(response.avg['NS ADT UNIT']);
						$('#inhouse-dt-unit-night').html(response.avg['NS DT UNIT']);

						$('.inhouse-progress-day-unit').html(sum(response.avg['DS DT UNIT'],response.avg['DS ADT UNIT']) + " Day Shift");
						$('.inhouse-progress-night-unit').html(sum(response.avg['NS DT UNIT'],response.avg['NS ADT UNIT']) + " Night Shift");

						$('#inhouse-adt-avg').html(average(response.avg['DS ADT UNIT'],response.avg['NS ADT UNIT']));
						$('#inhouse-dt-avg').html(average(response.avg['DS DT UNIT'],response.avg['NS DT UNIT']));

						$('#inhouse-adt-trips-day').html(response.avg['DS ADT TRIP']);
						$('#inhouse-adt-wmt-day').html(comma(response.avg['DS ADT WMT']));
						$('#inhouse-dt-trips-day').html(response.avg['DS DT TRIP']);
						$('#inhouse-dt-wmt-day').html(comma(response.avg['DS DT WMT']));


						$('#inhouse-adt-trips-night').html(response.avg['NS ADT TRIP']);
						$('#inhouse-adt-wmt-night').html(comma(response.avg['NS ADT WMT']));
						$('#inhouse-dt-trips-night').html(response.avg['NS DT TRIP']);
						$('#inhouse-dt-wmt-night').html(comma(response.avg['NS DT WMT']));


						$('.inhouse-progress-trip-day').html(sum(response.avg['DS DT TRIP'],response.avg['DS ADT TRIP'])+ " Day Shift");
						$('.inhouse-progress-trip-night').html(sum(response.avg['NS DT TRIP'],response.avg['DS ADT TRIP']) + " Night Shift");



						$('#inhouse-avg-adt-trips').html(average(response.avg['DS ADT TRIP'],response.avg['NS ADT TRIP']));
						$('#inhouse-avg-adt-wmt').html(comma(average(response.avg['DS ADT WMT'],response.avg['NS ADT WMT'])));
						$('#inhouse-avg-dt-trips').html(average(response.avg['DS DT TRIP'],response.avg['NS DT TRIP']));
						$('#inhouse-avg-dt-wmt').html(comma(average(response.avg['DS DT WMT'],response.avg['NS DT WMT'])));


						$('.mine-avg-trip-adt-inhouse').html(response.average.mine.adt.inhouse);
						$('.mine-avg-trip-dt-inhouse').html(response.average.mine.dt.inhouse);						

												
						$.plot("#inhouse-operation-unit",response.unit,{
								xaxes: [ {  mode: "time",
											minTickSize: [1, "day"],
											timeformat : '%d',	
											 axisLabel: response.month,
							                 axisLabelUseCanvas: false,
							                 axisLabelFontSizePixels: 11,
							                 axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                 axisLabelPadding: 5															
										 } ],
								yaxes  : [ { min: 0 ,
											alignTicksWithAxis : 1,
											position:'left',
											 } ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:true },
								lines  :  { show:true },
								
						});


						$.plot("#inhouse-operation-trip",response.trip,{
								xaxes: [ { 
											mode: "time",
											minTickSize: [1, "day"],
											timeformat : '%d',	
											axisLabel: response.month,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5
										 } ],
								yaxes  : [ { 
											min: 0 ,																	
											 } ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:true },
								lines  :  { show:true },

						});

				},'json');
			},
			get_donut : function(){
				$.get('<?php echo base_url().index_page();?>/manage_report/get_donut',$post,function(response){

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

				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_total',$post,function(response){
					$.each(response,function(i,val){
						$('#'+val.description).html(val.value);
					});
				},'json');

			},get_shipment : function(){
				$('#shipment_unit').content_loader('true');
				$('#shipment_trip').content_loader('true');

				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_shipment',$post,function(response){

						$('.shipment-data-date').html(response.date);

						$('#shipment-unit-day').html(response.avg_unit.day_unit.total);
						$('#shipment-unit-night').html(response.avg_unit.night_unit.total);
						$('#shipment-unit-avg').html(response.avg_unit.total_unit);
						$('.progress-day-unit').html(response.avg_unit.day_unit.total +" Day Shift");
						$('.progress-day-night').html(response.avg_unit.night_unit.total +" Night Shift");
						
						$('.shipment-progress-day-trip').html(response.avg_unit.day_trips.total +" Day Shift");
						$('.shipment-progress-night-trip').html(response.avg_unit.night_trips.total +" Night Shift");
						$('#shipment-day-trip').html(response.avg_unit.day_trips.total);
						$('#shipment-day-wmt').html(response.avg_unit.day_trips.wmt);
						$('#shipment-night-trip').html(response.avg_unit.night_trips.total);
						$('#shipment-night-wmt').html(response.avg_unit.night_trips.wmt);
						$('#shipment-trip-avg').html(response.avg_unit.total_trips);
						
						var total_wmt = (+ response.avg_unit.night_trips.wmt.replace(',','') + +response.avg_unit.day_trips.wmt.replace(',','')); 
						$('#shipment-total-avg').html(comma(total_wmt));
						//$('#shipment-total-avg').html(response.avg_unit.total_wmt);


						$('.shipment-avg-trip-dt').html(response.average.shipment.dt.all);
						
												
						$.plot("#shipment_unit",response.unit,{
								xaxes: [ {  mode: "time",
											minTickSize: [1, "day"],
											timeformat : '%d',	
											axisLabel: response.month,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5																
										 } ],
								yaxes  : [ { min: 0 ,
											alignTicksWithAxis : 1,											
											position:'left',
											 } ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:true },
								lines  :  { show:true },
						});

						$.plot("#shipment_trip",response.trip,{
								xaxes: [ { 
											mode: "time",
											minTickSize: [1, "day"],																
											timeformat : '%d',	
											axisLabel: response.month,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5 ,
										 } ],
								yaxes  : [ { 
											min: 0 ,																	
											 } ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:true },
								lines  :  { show:true },

						});

				},'json');

			},
			get_shipment_subcon:function(){

				$('#shipment-subcon-operation-unit').content_loader('true');
				$('#shipment-subcon-operation-trip').content_loader('true');
				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_shipment_subcon',$post,function(response){


						$('.shipment-subcon-data-date').html(response.date);

						//$('#shipment-subcon-adt-unit-day').html(response.avg['DS ADT UNIT']);
						$('#shipment-subcon-dt-unit-day').html(response.avg['DS DT UNIT']);
						//$('#shipment-subcon-adt-unit-night').html(response.avg['NS ADT UNIT']);
						$('#shipment-subcon-dt-unit-night').html(response.avg['NS DT UNIT']);

						$('.shipment-subcon-progress-day-unit').html(response.avg['DS DT UNIT'] + " Day Shift");
						$('.shipment-subcon-progress-night-unit').html(response.avg['NS DT UNIT'] + " Night Shift");

						//$('#shipment-subcon-adt-avg').html(average(response.avg['DS ADT UNIT'],response.avg['NS ADT UNIT']));
						$('#shipment-subcon-dt-avg').html(average(response.avg['DS DT UNIT'],response.avg['NS DT UNIT']));

						//$('#shipment-subcon-adt-trips-day').html(response.avg['DS ADT TRIP']);
						//$('#shipment-subcon-adt-wmt-day').html(comma(response.avg['DS ADT WMT']));
						$('#shipment-subcon-dt-trips-day').html(response.avg['DS DT TRIP']);
						$('#shipment-subcon-dt-wmt-day').html(comma(response.avg['DS DT WMT']));


						//$('#shipment-subcon-adt-trips-night').html(response.avg['NS ADT TRIP']);
						//$('#shipment-subcon-adt-wmt-night').html(comma(response.avg['NS ADT WMT']));
						$('#shipment-subcon-dt-trips-night').html(response.avg['NS DT TRIP']);
						$('#shipment-subcon-dt-wmt-night').html(comma(response.avg['NS DT WMT']));


						$('.shipment-subcon-progress-trip-day').html(response.avg['DS DT TRIP'] + " Day Shift");
						$('.shipment-subcon-progress-trip-night').html(response.avg['NS DT TRIP'] + " Night Shift");


						//$('#shipment-subcon-avg-adt-trips').html(average(response.avg['DS ADT TRIP'],response.avg['NS ADT TRIP']));
						//$('#shipment-subcon-avg-adt-wmt').html(comma(average(response.avg['DS ADT WMT'],response.avg['NS ADT WMT'])));
						$('#shipment-subcon-avg-dt-trips').html(average(response.avg['DS DT TRIP'],response.avg['NS DT TRIP']));
						$('#shipment-subcon-avg-dt-wmt').html(comma(average(response.avg['DS DT WMT'],response.avg['NS DT WMT'])));



						$('.shipment-avg-trip-dt-subcon').html(response.average.shipment.dt.subcon);
						


						
						$.plot("#shipment-subcon-operation-unit",response.unit,{
								xaxes: [ {  mode: "time",
											minTickSize: [1, "day"],
											timeformat : '%d',	
											axisLabel: response.month,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5	,
							                														
										 } ],
								yaxes  : [ { min: 0 ,
											alignTicksWithAxis : 1,
											position:'left',
											 } ],
								grid   :  { hoverable : true , borderWidth: 1,clickable:true},
								points :  { show:true },
								lines  :  { show:true },
								
						});


						$.plot("#shipment-subcon-operation-trip",response.trip,{
								xaxes: [ { 
											mode: "time",
											minTickSize: [1, "day"],
											timeformat : '%d',	
											axisLabel: response.month,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5
										 } ],
								yaxes  : [ { 
											min: 0 ,																	
											 } ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:true },
								lines  :  { show:true },

						});


				},'json');

			},
			get_shipment_inhouse:function(){

				$('#shipment-inhouse-operation-unit').content_loader('true');
				$('#shipment-inhouse-operation-trip').content_loader('true');

				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_shipment_inhouse',$post,function(response){

						$('.shipment-inhouse-data-date').html(response.date);

						//$('#shipment-subcon-adt-unit-day').html(response.avg['DS ADT UNIT']);
						$('#shipment-inhouse-dt-unit-day').html(response.avg['DS DT UNIT']);
						//$('#shipment-inhouse-adt-unit-night').html(response.avg['NS ADT UNIT']);
						$('#shipment-inhouse-dt-unit-night').html(response.avg['NS DT UNIT']);

						$('.shipment-inhouse-progress-day-unit').html(response.avg['DS DT UNIT'] + " Day Shift");
						$('.shipment-inhouse-progress-night-unit').html(response.avg['NS DT UNIT'] + " Night Shift");

						//$('#shipment-inhouse-adt-avg').html(average(response.avg['DS ADT UNIT'],response.avg['NS ADT UNIT']));
						$('#shipment-inhouse-dt-avg').html(average(response.avg['DS DT UNIT'],response.avg['NS DT UNIT']));

						//$('#shipment-inhouse-adt-trips-day').html(response.avg['DS ADT TRIP']);
						//$('#shipment-inhouse-adt-wmt-day').html(comma(response.avg['DS ADT WMT']));
						$('#shipment-inhouse-dt-trips-day').html(response.avg['DS DT TRIP']);
						$('#shipment-inhouse-dt-wmt-day').html(comma(response.avg['DS DT WMT']));


						//$('#shipment-inhouse-adt-trips-night').html(response.avg['NS ADT TRIP']);
						//$('#shipment-inhouse-adt-wmt-night').html(comma(response.avg['NS ADT WMT']));
						$('#shipment-inhouse-dt-trips-night').html(response.avg['NS DT TRIP']);
						$('#shipment-inhouse-dt-wmt-night').html(comma(response.avg['NS DT WMT']));


						$('.shipment-inhouse-progress-trip-day').html(response.avg['DS DT TRIP'] + " Day Shift");
						$('.shipment-inhouse-progress-trip-night').html(response.avg['NS DT TRIP'] + " Night Shift");


						//$('#shipment-inhouse-avg-adt-trips').html(average(response.avg['DS ADT TRIP'],response.avg['NS ADT TRIP']));
						//$('#shipment-inhouse-avg-adt-wmt').html(comma(average(response.avg['DS ADT WMT'],response.avg['NS ADT WMT'])));
						$('#shipment-inhouse-avg-dt-trips').html(average(response.avg['DS DT TRIP'],response.avg['NS DT TRIP']));
						$('#shipment-inhouse-avg-dt-wmt').html(comma(average(response.avg['DS DT WMT'],response.avg['NS DT WMT'])));

						$('.shipment-avg-trip-dt-inhouse').html(response.average.shipment.dt.inhouse);
												
						$.plot("#shipment-inhouse-operation-unit",response.unit,{
								xaxes: [ {  mode: "time",
											minTickSize: [1, "day"],
											timeformat : '%d',	
											axisLabel: response.month,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5																
										 } ],
								yaxes  : [ { min: 0 ,
											alignTicksWithAxis : 1,
											position:'left',
											 } ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:true },
								lines  :  { show:true },
								
						});


						$.plot("#shipment-inhouse-operation-trip",response.trip,{
								xaxes: [ { 
											mode: "time",
											minTickSize: [1, "day"],
											timeformat : '%d',	
											axisLabel: response.month,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5
										 } ],
								yaxes  : [ { 
											min: 0 ,																	
											 } ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:true },
								lines  :  { show:true },

						});

				},'json');

			},
			get_draft_survey:function(){
				
				$('#foo0').html('');

				$.get('<?php echo base_url().index_page(); ?>/manage_report/get_draft_survey',$post,function(response){
					
					var cnt = 1;
					$.each(response,function(i,val){

						$('<li><span>'+i+'</span><div class="demo-container"><div id="gh-draft'+cnt+'" class="draftsurvey-container pop-tooltip" data-title="Trucks"></div></div></li>').appendTo('#foo0');					
						//console.log($('#gh-draft'+cnt));

						$.plot("#gh-draft"+cnt,[val['truck_factor'],val['truck_load']],{
										xaxes: [ { 
													mode: "time",
													minTickSize: [1, "day"],													
												 } ],
										yaxes  : [ { 
													min: 0 ,																	
													 } ],
										grid   :  { hoverable : true, borderWidth: 1},
										points :  { show:true },
										lines  :  { show:true },
						});
						
						//$carousel.carousel('refresh');

						cnt++;


								  $('.pop-tooltip').bind("plothover",function(event,pos,item){
							  		var title = $(this).data('title');
							  		if(item){
							  			//console.log(item);
							  			/*var x = item.datapoint[0].toFixed(2),
											y = item.datapoint[1].toFixed(2);*/
											var x = item.datapoint[0],
												y = item.datapoint[1];
											var date = new Date(x);								
													var curr_date = date.getDate();
													var curr_month = date.getMonth();
													var curr_year = date.getFullYear();
													var my_date = m_names[curr_month] +' '+curr_date;
													switch(item.series.label){
														case "Truck Factor":
															title = "";
														break;
														case "Truck Load":
															title = "trucks";
														break;
													}

												$('#tooltip').html(item.series.label + " : "+my_date+" = " + comma(y) +" "+title)
														.css({top: item.pageY+5, left: item.pageX+5})
														.fadeIn(200);
										/*$("#tooltip").html(item.series.label + " : " + x + " = " + y)
											.css({top: item.pageY+5, left: item.pageX+5})
											.fadeIn(200);
										*/

							  		}else{
							  			$("#tooltip").hide();		  			
							  		}
							  });



					});
									
					$('#foo0').carouFredSel({
									responsive: true,
									width: '100%',
									scroll:{
										duration : 1000,
										items : 1,
									},
									items: {
										width: 400,
									//	height: '30%',	//	optionally resize item-height
										visible: {
											min: 1,
											max: 3
										}
									},
									prev: '#prev',
									next: '#next',
									auto: {
										   pauseOnHover: 'resume',						
									}						
						});


					},'json');
			},
			avg_trips:function(){
				
			/*	$.post('<?php echo base_url().index_page();?>/manage_report/avg_trip',$post,function(response){

					$('.mine-avg-trip-adt').html(response.mine.adt.all);
					$('.mine-avg-trip-dt').html(response.mine.dt.all);					
					$('.mine-avg-trip-dt-subcon').html(response.mine.dt.subcon);					
					$('.mine-avg-trip-adt-inhouse').html(response.mine.adt.inhouse);
					$('.mine-avg-trip-dt-inhouse').html(response.mine.dt.inhouse);
					$('.shipment-avg-trip-dt').html(response.shipment.dt.all);
					$('.shipment-avg-trip-dt-subcon').html(response.shipment.dt.subcon);
					$('.shipment-avg-trip-dt-inhouse').html(response.shipment.dt.inhouse);			

				},'json');
			*/

			},
			utilization:function(){

				$('.content-utilization').content_loader('true');

				$post = {
					date : $('#today_date').val(),
				}
				$.post('<?php echo base_url().index_page();?>/manage_report/get_utilization',$post,function(response){
					$('.content-utilization').html(response);
					$('#ul-utilization').carouFredSel({
						auto: false,
						prev: '#prev2',
						next: '#next2',
					});
				});
			},
			get_vessel:function(){

				$('#vessel-loading').content_loader('true');

				$post = {
					date : $('#today_date').val(),
				}
				$.post('<?php echo base_url().index_page();?>/manage_report/get_vessel',$post,function(response){

						

						$('#vessel-loading').html(response.table);
						$('#barge-out').html(response.barge_out.barge_out);
						$('#complete-vessel').html(response.barge_out.total_vessel_complete);
						var cnt = 1;
						$.each(response.moris,function(i,val){
							Morris.Donut({
								  element: 'vessel-'+cnt,
								  data: val,
								  formatter: function (x) { return comma(x) + "wmt"}
								}).on('click', function(i, row){
								  console.log(i, row);
							});
							cnt++;
						});
						
				},'json');

			},
			progress_target:function(){

				$.post('<?php echo base_url().index_page();?>/manage_report/progress_target',function(response){					
					$('#target-progressbar').html(comma(response.load) +' WMT');
					$('#target-progressbar').css({width:response.percentage + '%'});
				},'json');

			}

		};
		

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
		  			//console.log(item);
		  			/*var x = item.datapoint[0].toFixed(2),
						y = item.datapoint[1].toFixed(2);*/
						var x = item.datapoint[0],
							y = item.datapoint[1];
						var date = new Date(x);								
								var curr_date = date.getDate();
								var curr_month = date.getMonth();
								var curr_year = date.getFullYear();
								var my_date = m_names[curr_month] +' '+curr_date;
								
							$('#tooltip').html(item.series.label + " : "+my_date+" = " + comma(y) +" "+title)
									.css({top: item.pageY+5, left: item.pageX+5})
									.fadeIn(200);
					/*$("#tooltip").html(item.series.label + " : " + x + " = " + y)
						.css({top: item.pageY+5, left: item.pageX+5})
						.fadeIn(200);
					*/					
		  		}else{
		  			$("#tooltip").hide();		  			
		  		}
		  });




		$(".mine-operation-fancy").bind("plotdblclick", function (event, pos, item){

			if(item){
				console.log(item);
				$.fancybox.showLoading();

				$post = {
					 x : item.datapoint[0],
					 y : item.datapoint[1],
					 label : item.series.label,
				}
				
				$.post('<?php echo base_url().index_page() ?>/report_details/operation/A',$post,function(response){
					$.fancybox(response,{
						width     : 1000,
						height    : 550,
						fitToView : true,
						autoSize  : false,
					})	
					$('.myTable').dataTable(datatable_option);
				});

			}
		});


		$(".mine-operation-subcon").bind("plotdblclick", function (event, pos, item){

			if(item){

				console.log(item);
				$.fancybox.showLoading();

				$post = {
					 x : item.datapoint[0],
					 y : item.datapoint[1],
					 label : item.series.label,
				}
				
				$.post('<?php echo base_url().index_page() ?>/report_details/operation/B',$post,function(response){
					$.fancybox(response,{
						width     : 1000,
						height    : 550,
						fitToView : true,
						autoSize  : false,
					})	
					$('.myTable').dataTable(datatable_option);
				});

			}
		});



		$(".mine-operation-inhouse").bind("plotdblclick", function (event, pos, item){

			if(item){

				console.log(item);
				$.fancybox.showLoading();

				$post = {
					 x : item.datapoint[0],
					 y : item.datapoint[1],
					 label : item.series.label,
				}
				
				$.post('<?php echo base_url().index_page() ?>/report_details/operation/C',$post,function(response){
					$.fancybox(response,{
						width     : 1000,
						height    : 550,
						fitToView : false,
						autoSize  : true,
					})	
					$('.myTable').dataTable(datatable_option);
				});

			}
		});


		$(".shipment-operation").bind("plotdblclick", function (event, pos, item){

			if(item){

				console.log(item);
				$.fancybox.showLoading();

				$post = {
					 x : item.datapoint[0],
					 y : item.datapoint[1],
					 label : item.series.label,
				}
				
				$.post('<?php echo base_url().index_page() ?>/report_details/operation/D',$post,function(response){
					$.fancybox(response,{
						width     : 1000,
						height    : 550,
						fitToView : false,
						autoSize  : true,
					})	
					$('.myTable').dataTable(datatable_option);
				});

			}
		});


		$(".shipment-operation-subcon").bind("plotdblclick", function (event, pos, item){

			if(item){

				console.log(item);
				$.fancybox.showLoading();

				$post = {
					 x : item.datapoint[0],
					 y : item.datapoint[1],
					 label : item.series.label,
				}
				
				$.post('<?php echo base_url().index_page() ?>/report_details/operation/E',$post,function(response){
					$.fancybox(response,{
						width     : 1000,
						height    : 550,
						fitToView : false,
						autoSize  : true,
					})	
					$('.myTable').dataTable(datatable_option);
				});

			}
		});


		$(".shipment-operation-inhouse").bind("plotdblclick", function (event, pos, item){

			if(item){

				console.log(item);
				$.fancybox.showLoading();

				$post = {
					 x : item.datapoint[0],
					 y : item.datapoint[1],
					 label : item.series.label,
				}
				
				$.post('<?php echo base_url().index_page() ?>/report_details/operation/F',$post,function(response){
					$.fancybox(response,{
						width     : 1000,
						height    : 550,
						fitToView : false,
						autoSize  : true,
					})	
					$('.myTable').dataTable(datatable_option);
				});
			}
		});




	var date  = new Date();
	var month = date.getMonth() + 1;
	$('#month').val(month);
	$('#month').on('change',function(){

			var date = new Date();
			var y = date.getFullYear();
			var m = $(this).val();
			
			var from =  new Date(y,m,1);
			var to   =  new Date(y,m,0);
						

			to   = to.getFullYear()+"-"+String(to.getMonth()+1).padLeft('0',2)+"-"+String(to.getDate()).padLeft('0',2);
			from = from.getFullYear()+"-"+String(from.getMonth()).padLeft('0',2)+"-"+String(from.getDate()).padLeft('0',2);

			$('#from_date').val(from);
			$('#to_date').val(to)

			$post = {
				from : $('#from_date').val(),
				to   : $('#to_date').val(),
			};

			app.init();
		});

		$('#month').trigger('change');
				
	});	

</script>
<nav id="nav">
	<div class="container">
		<ul>
			<!-- <li><a href="#stockpile" id="nav-stockpile">StockPile</a></li> -->
			<li><a href="#daily-production" id="nav-daily-production">Daily Production</a></li>
			<li><a href="#mining" id="nav-mining">Mine Operation</a></li>
			<li><a href="#subcon" id="nav-subcon">Mine : Subcon</a></li>
			<li><a href="#inhouse" id="nav-inhouse">Mine : Inhouse</a></li>
			<li><a href="#shipping" id="nav-shipping">Shipment Operation</a></li>
			<li><a href="#shipment-subcon" id="nav-shipment-subcon">Shipment : Subcon</a></li>
			<li><a href="#shipment-inhouse" id="nav-shipment-inhouse">Shipment : Inhouse</a></li>
			<li><a href="#draft_survey" id="nav-draft_survey">Draft Survey</a></li>
			<!-- <li><a href="#vessel_loading" id="nav-vessel_loading">Vessel Loading Report</a></li> -->
		</ul>
		<div class="pull-right">
			<select name="" id="month" style="margin-top:2px">
				<option value="1">January</option>
				<option value="2">Febuary</option>
				<option value="3">March</option>
				<option value="4">April</option>
				<option value="5">May</option>
				<option value="6">June</option>
				<option value="7">July</option>
				<option value="8">August</option>
				<option value="9">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>
			</select>
		</div>
	</div>
	
</nav>

<div class="container">
<input type="hidden" value="" id="from_date">
<input type="hidden" value="" id="to_date">
<input type="hidden" value="" id="today_date">


<section id="daily-production" style="margin-top:1em">
	
	<div class="row">
		<div class="col-md-6">

			<div class="content-title">
				<h3>Production</h3>
			</div>

			<div class="panel panel-default">				  
			  <div class="panel-body">
			  		<div class="row">
			  			<div class="col-md-12">			  					
				  				<div class="stockpile-container">
									<div id="gh-target-production" class="demo-placeholder pop-tooltip2" data-title="WMT"></div>
								</div>
			  			</div>
			  		</div>
			  </div>	 
			</div>
			

			<div class="progress">
				<span  style="position:absolute;right:20px;font-size:11px">3.5M WMT</span>
			  <div id="target-progressbar" class="progress-bar" role="progressbar" >
			    
			  </div>
			</div>
			
		</div>	
		<div class="col-md-3">
			<div class="content-title">
				<h3>Vessel Loading</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">
			  		<div id="vessel-loading" style="height:280px;overflow:auto">
			  			
			  		</div>

					<div class="row">
						<div class="col-xs-6" style="text-align:center">
							<h4 id="complete-vessel"> - </h4>
							<small><a href="<?php echo base_url().index_page(); ?>/vessel_summary">Complete Vessel</a></small>
						</div>
						<div class="col-xs-6" style="text-align:center">
							<h4 id="barge-out"> - </h4>
							<small>Barge Out</small>
						</div>						
					</div>

			  </div>
			</div>
		</div>
		<div class="col-md-3">		
			<div class="content-title">
				<h3>Utilization</h3>
			</div>			
			<div class="panel panel-default content-utilization">
			</div>
		</div>
	</div>
	
</section>

<hr>
<section id="mining" style="margin-top:1em">
	<div class="content-title">
		<h3>MINE OPERATION : <small>Subcon and Inhouse</small></h3>
	</div>
<div class="row">	
	<div class="col-md-9">
		<div class="panel panel-default">
		  <div class="panel-body">
		  		<div class="row">
		  			<div class="col-md-12">
		  					<h5>Utilized Hauling Unit</h5>
			  				<div class="demo-container">			  					
								<div id="mine-operation-unit" class="mine-operation-fancy pop-tooltip demo-placeholder" data-title="Unit"></div>
							</div>		
		  			</div>
				</div>
				<div class="row">
		  			<div class="col-md-12">
		  					<h5>Trip Counts</h5>
		  					<div class="demo-container">					
								<div id="mine-operation-trip" class="mine-operation-fancy pop-tooltip demo-placeholder" data-title="Trips"></div>
							</div>
		  			</div>
		  		</div>
		  	</div>		  	
		</div>		
	</div>
	
	<div class="col-md-3">		
							  
				<ul class="list-group">
					<li class="list-group-item">
						<h5>No of Units Utilized :  <small class="mine-data-date"></small></h5>	

						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="mine-progress-day-unit"></span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="mine-progress-night-unit"></span>
							</div>
						</div>

						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th>ADT</th>
									<th>DT</th>									
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>No.Unit : <strong>Day</strong> </td>
									<td><span id="mine-adt-unit-day">-</span></td>
									<td><span id="mine-dt-unit-day">-</span></td>									
								</tr>
								<tr>
									<td>No.Unit : <strong>Night</strong> </td>
									<td><span id="mine-adt-unit-night">-</span></td>
									<td><span id="mine-dt-unit-night">-</span></td>							
								</tr>
								<tr>
									<th><strong>Avg.Unit</strong> </th>
									<th><strong><span id="mine-adt-avg">-</span></strong></th>
									<th><strong><span id="mine-dt-avg">-</span></strong></th>									
								</tr>							
							</tbody>
						</table>
						
					</li>
					<li class="list-group-item">
						<h5> No of Trips</h5>	
						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="mine-progress-trip-day">-</span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="mine-progress-trip-night">-</span>
							  </div>

						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th>ADT</th>
									<th><small>WMT</small></th>
									<th>DT</th>
									<th><small>WMT</small></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>No.Trips : <strong>Day</strong> </td>
									<td><span id="mine-adt-trips-day">-</span></td>
									<td><span id="mine-adt-wmt-day">-</span></td>
									<td><span id="mine-dt-trips-day">-</span></td>
									<td><span id="mine-dt-wmt-day">-</span></td>
								</tr>
								<tr>
									<td>No.Trips : <strong>Night</strong> </td>
									<td><span id="mine-adt-trips-night">-</td>
									<td><span id="mine-adt-wmt-night">-</td>
									<td><span id="mine-dt-trips-night">-</td>
									<td><span id="mine-dt-wmt-night">-</td>
								</tr>
									<tr>
									<td><strong>Avg.Trips</strong> </td>
									<td><strong><span id="mine-avg-adt-trips">-</span></strong></td>
									<td><strong><span id="mine-avg-adt-wmt">-</span></strong></td>
									<td><strong><span id="mine-avg-dt-trips">-</span></strong></td>
									<td><strong><span id="mine-avg-dt-wmt">-</span></strong></td>
								</tr>								
							</tbody>
						</table>

						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th>ADT</th>
									<th>DT</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><strong>Avg. Trips Per Trucks</strong></th>
									<th class="mine-avg-trip-adt"></th>
									<th class="mine-avg-trip-dt"></th>
								</tr>
							</tbody>						
						</table>	
					</li>
				</ul>
		   
		
	</div>
</div>
</section>

<hr>


<section id="subcon" style="margin-top:1em">
	<div class="content-title">
		<h3>MINE OPERATION : <small>SUBCON</small></h3>
	</div>

<div class="row" >	
	<div class="col-md-9">
		<div class="panel panel-default">				  
		  <div class="panel-body">
		  		<div class="row">
		  			<div class="col-md-12">
		  					<h5>Utilized Hauling Unit</h5>
			  				<div class="demo-container">	
			  					<div id="legendholder"></div>				
								<div id="subcon-operation-unit" class="mine-operation-subcon pop-tooltip demo-placeholder" data-title="Unit"></div>
							</div>		
		  			</div>
		  		</div>
		  		<div class="row">	
		  			<div class="col-md-12">
		  					<h5>Trip Counts</h5>
		  					<div class="demo-container">					
								<div id="subcon-operation-trip" class="mine-operation-subcon pop-tooltip demo-placeholder" data-title="Trips"></div>
							</div>
		  			</div>
		  		</div>
		  	</div>				  	
		</div>		
	</div>
	
	<div class="col-md-3">		
							  
				<ul class="list-group">
					<li class="list-group-item">
						<h5> No of Units Utilized :  <small class="mine-subcon-data-date"></small></h5>	

						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="subcon-progress-day-unit">-</span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="subcon-progress-night-unit">-</span>
							  </div>							

						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>
									
									<th>DT</th>									
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>No.Unit : <strong>Day</strong> </td>									
									<td><span id="subcon-dt-unit-day">-</span></td>
									
								</tr>
								<tr>
									<td>No.Unit : <strong>Night</strong> </td>									
									<td><span id="subcon-dt-unit-night">-</span></td>							
								</tr>
									<tr>
									<th><strong>Avg.Unit</strong> </th>									
									<th><strong><span id="subcon-dt-avg">-</span></strong></th>									
								</tr>
							</tbody>
						</table>						

					</li>
					<li class="list-group-item">
						<h5>No of Trips</h5>	
						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="subcon-progress-trip-day">-</span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="subcon-progress-trip-night">-</span>
							  </div>

						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>									
									<th>DT</th>
									<th><small>WMT</small></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>No.Trips : <strong>Day</strong> </td>									
									<td><span id="subcon-dt-trips-day">-</span></td>
									<td><span id="subcon-dt-wmt-day">-</span></td>
								</tr>
								<tr>
									<td>No.Trips : <strong>Night</strong> </td>									
									<td><span id="subcon-dt-trips-night">-</td>
									<td><span id="subcon-dt-wmt-night">-</td>
								</tr>
									<tr>
									<td><strong>Avg.Trips</strong> </td>									
									<td><strong><span id="subcon-avg-dt-trips">-</span></strong></td>
									<td><strong><span id="subcon-avg-dt-wmt">-</span></strong></td>
								</tr>
							</tbody>
						</table>		

						<table class="table">
							<thead>
								<tr>
									<th></th>									
									<th>DT</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><strong>Avg. Trips Per Trucks</strong></th>									
									<th class="mine-avg-trip-dt-subcon"></th>
								</tr>
							</tbody>						
						</table>	


					</li>
				</ul>
		   
		
	</div>
</div>
</section>
<hr>

<section id="inhouse" style="margin-top:1em">

	<div class="content-title">
		<h3>MINE OPERATION : <small>INHOUSE</small></h3>
	</div>
	
<div class="row" >	
	<div class="col-md-9">
		<div class="panel panel-default">				  
		  <div class="panel-body">
		  		<div class="row">
		  			<div class="col-md-12">
		  					<h5>Utilized Hauling Unit</h5>
			  				<div class="demo-container">	
			  					<div id="legendholder"></div>				
								<div id="inhouse-operation-unit" class="mine-operation-inhouse pop-tooltip demo-placeholder" data-title="Unit"></div>
							</div>		
		  			</div>
		  		</div>
		  		<div class="row">
		  			<div class="col-md-12">
		  					<h5>Trip Counts</h5>
		  					<div class="demo-container">					
								<div id="inhouse-operation-trip" class="mine-operation-inhouse pop-tooltip demo-placeholder" data-title="Trips"></div>
							</div>
		  			</div>
		  		</div>
		  	</div>

		  		
		   
		</div>		
	</div>	
<div class="col-md-3">		
							  
				<ul class="list-group">
					<li class="list-group-item">
						<h5>No of Units Utilized :  <small class="mine-inhouse-data-date"></small></h5>	

						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="inhouse-progress-day-unit"></span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="inhouse-progress-night-unit"></span>
							  </div>							

						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th>ADT</th>
									<th>DT</th>									
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>No.Unit : <strong>Day</strong> </td>
									<td><span id="inhouse-adt-unit-day">-</span></td>
									<td><span id="inhouse-dt-unit-day">-</span></td>
									
								</tr>
								<tr>
									<td>No.Unit : <strong>Night</strong> </td>
									<td><span id="inhouse-adt-unit-night">-</span></td>
									<td><span id="inhouse-dt-unit-night">-</span></td>							
								</tr>
									<tr>
									<th><strong>Avg.Unit</strong> </th>
									<th><strong><span id="inhouse-adt-avg">-</span></strong></th>
									<th><strong><span id="inhouse-dt-avg">-</span></strong></th>									
								</tr>
							</tbody>
						</table>						

					</li>
					<li class="list-group-item">
						<h5>No of Trips</h5>	
						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="inhouse-progress-trip-day">-</span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="inhouse-progress-trip-night">-</span>
							  </div>
	
						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th>ADT</th>
									<th><small>WMT</small></th>
									<th>DT</th>
									<th><small>WMT</small></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>No.Trips : <strong>Day</strong> </td>
									<td><span id="inhouse-adt-trips-day">-</span></td>
									<td><span id="inhouse-adt-wmt-day">-</span></td>
									<td><span id="inhouse-dt-trips-day">-</span></td>
									<td><span id="inhouse-dt-wmt-day">-</span></td>
								</tr>
								<tr>
									<td>No.Trips : <strong>Night</strong> </td>
									<td><span id="inhouse-adt-trips-night">-</td>
									<td><span id="inhouse-adt-wmt-night">-</td>
									<td><span id="inhouse-dt-trips-night">-</td>
									<td><span id="inhouse-dt-wmt-night">-</td>
								</tr>
									<tr>
									<td><strong>Avg.Trips</strong> </td>
									<td><strong><span id="inhouse-avg-adt-trips">-</span></strong></td>
									<td><strong><span id="inhouse-avg-adt-wmt">-</span></strong></td>
									<td><strong><span id="inhouse-avg-dt-trips">-</span></strong></td>
									<td><strong><span id="inhouse-avg-dt-wmt">-</span></strong></td>
								</tr>
							</tbody>
						</table>
						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th>ADT</th>
									<th>DT</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><strong>Avg. Trips Per Trucks</strong></th>
									<th class="mine-avg-trip-adt-inhouse"></th>
									<th class="mine-avg-trip-dt-inhouse"></th>
								</tr>
							</tbody>						
						</table>
					</li>
				</ul>
		   
		
	</div>
</div>
</section>
<hr>

<section id="shipping">
	<div class="content-title">
		<h3>Shipment Operation : <small>Subcon and Inhouse</small></h3>
	</div>
<div class="row">
	<div class="col-md-9">
		<div class="panel panel-default">				  
				  <div class="panel-body">
				  		<div class="row">
				  			<div class="col-md-12">
				  					<h5>Utilized DT Units</h5>
					  				<div class="demo-container">					
										<div id="shipment_unit" class="shipment-operation pop-tooltip demo-placeholder" data-title="Unit"></div>
									</div>		
				  			</div>
				  		</div>
				  		<div class="row">	
				  			<div class="col-md-12">
				  					<h5>Trip Counts</h5>
				  					<div class="demo-container">					
										<div id="shipment_trip" class="shipment-operation pop-tooltip demo-placeholder" data-title="Trips"></div>
									</div>
				  			</div>
				  		</div>
				  </div>	 
		</div>		
	</div>
	<div class="col-md-3">
						  
				<ul class="list-group">
					<li class="list-group-item">
						<h5>No of Units Utilized :  <small class="shipment-data-date"></small></h5>	

						<div class="progress">						
							  <div class="progress-bar progress-bar-info" style="width: 49%">
								    <span class="progress-day-unit">-</span>
								 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="progress-day-night">-</span>
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
									<td>No.Unit : <strong>Day</strong></td>
									<td><span id="shipment-unit-day">-</span></td>
									
								</tr>
								<tr>
									<td>No.Unit : <strong>Night</strong> </td>
									<td><span id="shipment-unit-night">-</span></td>									
								</tr>
									<tr>
									<th><strong>Total.Unit</strong> </th>
									<th><strong><span id="shipment-unit-avg">-</span></strong></th>
									
								</tr>
							</tbody>
						</table>						

					</li>
					<li class="list-group-item">
						<h5>No of Trips</h5>	
						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="shipment-progress-day-trip">-</span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="shipment-progress-night-trip">-</span>
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
									<td>No.Trips : <strong>Day</strong> </td>
									<td><span id="shipment-day-trip">-</span></td>
									<td><span id="shipment-day-wmt">-</span></td>
								</tr>
								<tr>
									<td>No.Trips : <strong>Night</strong> </td>
									<td><span id="shipment-night-trip">-</span></td>
									<td><span id="shipment-night-wmt">-</span></td>
								</tr>
									<tr>
									<td><strong>Total.Trips</strong> </td>
									<td><strong><span id="shipment-trip-avg">-</span></strong></td>
									<td><strong><span id="shipment-total-avg">-</span></strong></td>
								</tr>
							</tbody>
						</table>

						<table class="table">
							<thead>
								<tr>
									<th></th>									
									<th>DT</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><strong>Avg. Trips Per Trucks</strong></th>									
									<th class="shipment-avg-trip-dt"></th>
								</tr>
							</tbody>						
						</table>

					</li>
				</ul>
		  
		
	</div>
</div>
</section>
<hr>



<section id="shipment-subcon" style="margin-top:1em">

	<div class="content-title">
		<h3>Shipment Operation: <small>Subcon</small></h3>
	</div>
	
<div class="row">	
	<div class="col-md-9">
		<div class="panel panel-default">				  
		  <div class="panel-body">
		  		<div class="row">
		  			<div class="col-md-12">
		  					<h5>Utilized Hauling Unit</h5>
			  				<div class="demo-container">	
			  					<div id="legendholder"></div>				
								<div id="shipment-subcon-operation-unit" class="shipment-operation-subcon pop-tooltip demo-placeholder" data-title="Unit"></div>
							</div>		
		  			</div>
		  		</div>
		  		<div class="row">	
		  			<div class="col-md-12">
		  					<h5>Trip Counts</h5>
		  					<div class="demo-container">					
								<div id="shipment-subcon-operation-trip" class="shipment-operation-subcon pop-tooltip demo-placeholder" data-title="Trips"></div>
							</div>
		  			</div>
		  		</div>
		  	</div>
		</div>		
	</div>	
<div class="col-md-3">		
							  
				<ul class="list-group">
					<li class="list-group-item">
						<h5>No of Units Utilized :  <small class="shipment-subcon-data-date"></small></h5>	

						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="shipment-subcon-progress-day-unit"></span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="shipment-subcon-progress-night-unit"></span>
							  </div>							

						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>									
									<th>DT</th>									
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>No.Unit : <strong>Day</strong> </td>									
									<td><span id="shipment-subcon-dt-unit-day">-</span></td>
									
								</tr>
								<tr>
									<td>No.Unit : <strong>Night</strong> </td>									
									<td><span id="shipment-subcon-dt-unit-night">-</span></td>							
								</tr>
									<tr>
									<th><strong>Avg.Unit</strong> </th>									
									<th><strong><span id="shipment-subcon-dt-avg">-</span></strong></th>									
								</tr>
							</tbody>
						</table>						

					</li>
					<li class="list-group-item">
						<h5>No of Trips</h5>	
						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="shipment-subcon-progress-trip-day">-</span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="shipment-subcon-progress-trip-night">-</span>
							  </div>

						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th>DT</th>
									<th><small>WMT</small></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>No.Trips : <strong>Day</strong> </td>									
									<td><span id="shipment-subcon-dt-trips-day">-</span></td>
									<td><span id="shipment-subcon-dt-wmt-day">-</span></td>
								</tr>
								<tr>
									<td>No.Trips : <strong>Night</strong> </td>									
									<td><span id="shipment-subcon-dt-trips-night">-</td>
									<td><span id="shipment-subcon-dt-wmt-night">-</td>
								</tr>
								<tr>
									<td><strong>Avg.Trips</strong> </td>									
									<td><strong><span id="shipment-subcon-avg-dt-trips">-</span></strong></td>
									<td><strong><span id="shipment-subcon-avg-dt-wmt">-</span></strong></td>
								</tr>
							</tbody>
						</table>
							<table class="table">
								<thead>
									<tr>
										<th></th>									
										<th>DT</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th><strong>Avg. Trips Per Trucks</strong></th>									
										<th class="shipment-avg-trip-dt-subcon"></th>
									</tr>
								</tbody>						
							</table>		
					</li>
				</ul>
		   
		
	</div>
</div>
</section>
<hr>

<section id="shipment-inhouse" style="margin-top:1em">

	<div class="content-title">
		<h3>Shipment Operation: <small>Inhouse</small></h3>
	</div>
	
<div class="row">	
	<div class="col-md-9">
		<div class="panel panel-default">				  
		  <div class="panel-body">
		  		<div class="row">
		  			<div class="col-md-12">
		  					<h5>Utilized Hauling Unit</h5>
			  				<div class="demo-container">	
			  					<div id="legendholder"></div>				
								<div id="shipment-inhouse-operation-unit" class="shipment-operation-inhouse pop-tooltip demo-placeholder" data-title="Unit"></div>
							</div>
		  			</div>
		  		</div>
		  		<div class="row">	
		  			<div class="col-md-12">
		  					<h5>Trip Counts</h5>
		  					<div class="demo-container">					
								<div id="shipment-inhouse-operation-trip" class="shipment-operation-inhouse pop-tooltip demo-placeholder" data-title="Trips"></div>
							</div>
		  			</div>
		  		</div>
		  	</div>
		</div>		
	</div>	
<div class="col-md-3">		
							  
				<ul class="list-group">
					<li class="list-group-item">
						<h5>No of Units Utilized :  <small class="shipment-inhouse-data-date"></small></h5>	

						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="shipment-inhouse-progress-day-unit">-</span>
							 </div>
							  <div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="shipment-inhouse-progress-night-unit">-</span>
							  </div>							

						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>									
									<th>DT</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>No.Unit : <strong>Day</strong> </td>
									
									<td><span id="shipment-inhouse-dt-unit-day">-</span></td>
									
								</tr>
								<tr>
									<td>No.Unit : <strong>Night</strong> </td>
									
									<td><span id="shipment-inhouse-dt-unit-night">-</span></td>							
								</tr>
									<tr>
									<th><strong>Avg.Unit</strong> </th>									
									<th><strong><span id="shipment-inhouse-dt-avg">-</span></strong></th>									
								</tr>
							</tbody>
						</table>						

					</li>
					<li class="list-group-item">
						<h5>No of Trips</h5>	
						<div class="progress">						
							<div class="progress-bar progress-bar-info" style="width: 49%">
							    <span class="shipment-inhouse-progress-trip-day">-</span>
						</div>
							<div class="progress-bar progress-bar-danger" style="width: 51%">
							    <span class="shipment-inhouse-progress-trip-night">-</span>
							</div>
						</div>
						<table class="table">
							<thead>
								<tr>
									<th></th>									
									<th>DT</th>
									<th><small>WMT</small></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>No.Trips : <strong>Day</strong> </td>
									<td><span id="shipment-inhouse-dt-trips-day">-</span></td>
									<td><span id="shipment-inhouse-dt-wmt-day">-</span></td>
								</tr>
								<tr>
									<td>No.Trips : <strong>Night</strong> </td>									
									<td><span id="shipment-inhouse-dt-trips-night">-</td>
									<td><span id="shipment-inhouse-dt-wmt-night">-</td>
								</tr>
								<tr>
									<td><strong>Avg.Trips</strong> </td>
									
									<td><strong><span id="shipment-inhouse-avg-dt-trips">-</span></strong></td>
									<td><strong><span id="shipment-inhouse-avg-dt-wmt">-</span></strong></td>
								</tr>
							</tbody>
						</table>
						</table>
							<table class="table">
								<thead>
									<tr>
										<th></th>									
										<th>DT</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th><strong>Avg. Trips Per Trucks</strong></th>									
										<th class="shipment-avg-trip-dt-inhouse"></th>
									</tr>
								</tbody>						
							</table>		
					</li>
				</ul>
		   
		
	</div>
</div>
</section>
<hr>

<section id="draft_survey">
	<div class="content-title">
		<h3>Draft Survey</h3>
	</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">				  
				  <div class="panel-body">					
					<div class="list_carousel responsive">
						<ul id="foo0">
												
						</ul>
						<div class="clearfix"></div>
						<div class="foo-nav">
							<a id="prev" class="foo-prev" href="#"></a>
							<a id="next" class="foo-next" href="#"></a>
						</div>
					</div>				  	
				  </div>	 
		</div>		
	</div>
	
</div>
</section>

<hr>

</div>

<script>
	$(function(){
		FixScroll('nav');
		$('#vessel-loading').niceScroll();
		$('#nav').onePageNav();
		var app_fancy = {			
			init:function(){
				this.bindEvents();
				this.autoScroll();
			},
			autoScroll:function(){

				var link = ['#nav-stockpile',
							'#nav-mining',
							'#nav-subcon',
							'#nav-inhouse',
							'#nav-shipping',
							'#nav-draft_survey'];

				var i = 0;

				/*setInterval(function(){

					$(link[i]).trigger('click');
					i++;
					if(i==link.length){
						i = 0;
					}
				}, 80000);*/			

			},
			bindEvents:function(){
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