

<div class="container">
	<h2><?php echo $truck; ?></h2>
	<input type="hidden" id="truck" value="<?php echo $truck; ?>">
	<div class="row">
		<div class="col-md-3">
				<div class="content-title">
					<h3>Trip Details</h3>
				</div>
			<div class="panel panel-default">
			  		<table class="table">
						<thead>
							<tr>
								<th></th>
								<th>Trips</th>
							</tr>
						</thead>
						<tbody>					
							<tr>
								<td>Max</td>
								<td><strong><?php echo $details['max']; ?></strong></td>
							</tr>
							<tr>
								<td>Min</td>
								<td><strong><?php echo $details['min']; ?></strong></td>
							</tr>
							<tr>
								<td>Avg</td>
								<td><strong><?php echo $details['avg']; ?></strong></td>
							</tr>
						</tbody>
					</table>
			   
			</div>

			
		</div>
		<div class="col-md-9">
			<div class="row">
				<div class="col-md-12">
					<div class="content-title">
						<h3>Trip History</h3>
					</div>
					<div class="panel panel-default">		
					  <div class="panel-body">	
					  	<div class="demo-container">
					  		<div id="graph" style="height:240px" class="pop-tooltip" data-title="Trips"></div>		  			
					  	</div>
					  </div>	 
					</div>
				</div>
			</div>

		</div>
	</div>

	<div class="row">
				<div class="col-md-6">
					<div class="content-title">
						<h3>Drivers</h3>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-body"></div>		
					  	<?php echo $drivers; ?>
					</div>
					
				</div>

				<!-- <div class="col-md-6">
					<div class="content-title">
						<h3>Breakdown History</h3>
					</div>
					<div class="panel panel-default">		
					  <div class="panel-body">	
					  	<div class="demo-container">
					  		
					  	</div>
					  	
					  </div>	 
					</div>
				</div> -->
	</div>


</div>

<script>
	$(function(){

		var app = {
			init:function(){
				this.bindEvents();
				this.graph_history();
				this.table();
			},bindEvents:function(){

			},graph_history:function(){
				$post = {
					truck : $('#truck').val(),
				};

				$.post('<?php echo base_url().index_page(); ?>/truck/truck_history',$post,function(response){

					console.log(response);

					$.plot('#graph',response.data,{
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

				},'json');

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

			},
			table:function(){
				$('.tbl-driver').dataTable(datatable_option);
			}


		}


		app.init();

	});


</script>