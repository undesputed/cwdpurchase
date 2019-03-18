<div class="row">
	<div class="col-md-12">
		<div class="content-title">
			<h3>Equipment Productivity</h3>
		</div>
		<div class="panel panel-default">		
		  <div class="panel-body">
				<div id="gh-equipment-productivity" class="pop-tooltip" style="width:100%;height:200px">
			
				</div>  		
		  </div>	 
		</div>
		

</div>

</div>


<script>
	$(function(){

		var response = eval(<?php echo $json ?>);		
		 var app1 = {
		 	init:function(){
		 		this.bindEvents();
		 		this.generate_graph();
		 	},
		 	bindEvents:function(){

		 	},
		 	generate_graph:function(){

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
						
						$.plot("#gh-equipment-productivity",response.output,{
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
															return comma(v.toFixed(axis.tickDecimals)) + " trips";
													}
											}
											,
											{    min: 0 ,
												alignTicksWithAxis : 0,
												position:'left',
												tickFormatter: function(v,axis){
															return comma(v.toFixed(axis.tickDecimals)) + " wmt";
													}
											 } ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:false },
								lines  :  { show:false },								
						});
						
						$('.pop-tooltip').bind("plothover",function(event,pos,item){
					  		var title = $(this).data('title');
					  		var bar = $(this).data('bar');
					  		if(item){

					  			var value = item.series.data[item.dataIndex][1];
								var date = item.series.data[item.dataIndex][2];
								var type = item.series.data[item.dataIndex][3];

					  			if(item.series.bars.show){
					  				title = bar;
					  			}

								var x = item.datapoint[0],
									y = item.datapoint[1];
									
								var my_date = response.ticks[x];
																			
								$('#tooltip').html(my_date+" = " + comma(value) +" "+date)
									.css({top: item.pageY+5, left: item.pageX+5})
									.fadeIn(200);
									
						  		}else{
						  			$("#tooltip").hide();		  			
						  		}
						  });
				
		 	}


		 }
		 app1.init();
	});


</script>


