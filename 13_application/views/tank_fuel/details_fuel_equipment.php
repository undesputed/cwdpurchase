

<div class="container">
	<div class="content-title">
		<h3><?php echo $title; ?></h3>		
	</div>
	<small class="text-muted">Fuel History and Production</small>
	
	<div class="row">
		<div class="col-md-12">
			<div class="flot-container" style="height:300px;width:900px">
					<div id="gh-fuel-equipment" class="demo-placeholder pop-tooltip" data-title="ltr" data-bar='wmt'></div>
			</div>

		</div>
	</div>

</div>




<script>
	var response = eval(<?php echo $json; ?>);

	$('#gh-fuel-equipment').empty();

	function liters(v,axis){
			return comma(v.toFixed(axis.tickDecimals)) + " ltr";
		};

	var graph = function(){

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
						console.log(response);
						$.plot("#gh-fuel-equipment",response.output,{
								xaxes: [ {  											
											ticks:ticks,
											axisLabel: response.month,
							                axisLabelUseCanvas: false,
							                axisLabelFontSizePixels: 11,
							                axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							                axisLabelPadding: 5,
										 } ],
								yaxes  : [ { min: 0 ,
											alignTicksWithAxis : 1,
											position:'left',		
											tickFormatter: liters,								
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
									
									if(typeof date !='undefined')
									{
										$('#tooltip').html(date+" = ["+type+"] " + comma(value) +" "+title)
											.css({top: item.pageY+5, left: item.pageX+5})
											.fadeIn(200);
									}else
									{
										$('#tooltip').html(my_date+" = " + comma(y) +" "+title)
											.css({top: item.pageY+5, left: item.pageX+5})
											.fadeIn(200);
									}
									


						  		}else{
						  			$("#tooltip").hide();		  			
						  		}

						  });

	}

	graph();



</script>