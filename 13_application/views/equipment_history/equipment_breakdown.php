<div class="row">
	<div class="col-md-12">
		<div class="content-title">
			<h3>Equipment Breakdown</h3>
		</div>
		<div class="panel panel-default">		
		  <div class="panel-body">
				<div id="gh-equipment-breakdown" class="pop-tooltip" style="width:100%;height:200px">
			
				</div>  		
		  </div>	 
		</div>
		

</div>

</div>


<script>
	$(function(){

		var response2 = eval(<?php echo $json ?>);		
		 var app2 = {
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
						for(var i in response2.ticks)
						{
							ticks.push([i,response2.ticks[i]]);
						}
						
						$.plot("#gh-equipment-breakdown",response2.output,{
								xaxes: [ {  											
											ticks:ticks,
											axisLabel: response2.label,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5,
										 } ],
								yaxes  : [ 
											{
												position : 'left',
													tickFormatter: function(v,axis){
															return comma(v.toFixed(axis.tickDecimals));
													}
											}
										 ],
								grid   :  { hoverable : true, borderWidth: 1,clickable:true},
								points :  { show:false },
								lines  :  { show:false },								
						});
						
						$('.pop-tooltip').bind("plothover",function(event,pos,item){					  		
					  		if(item){

					  			var value = item.series.data[item.dataIndex][1];
								var date = item.series.data[item.dataIndex][2];
								var type = item.series.data[item.dataIndex][3];
												
								var x = item.datapoint[0],
									y = item.datapoint[1];
																
								$('#tooltip').html(comma(value) +" "+date)
									.css({top: item.pageY+5, left: item.pageX+5})
									.fadeIn(200);
									
						  		}else{
						  			$("#tooltip").hide();		  			
						  		}
						  });
				
		 	}


		 }
		 app2.init();
	});


</script>


