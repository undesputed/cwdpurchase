<div class="header">
	<h2>Maintenance Department</h2>	
</div>
<div class="container">
 
 	<section id="breakdown-history">
		<div class="content-title">
			<h3>Breakdown History</h3>					
		</div>

		<div class="panel panel-default">	
				<div class="panel-body">	
					<div class="row">
						<div class="col-md-8">																
								<div class="stockpile-container" alt="Bargraph" title="Bargraph">
									<div id="get_jo_breakdown" class="demo-placeholder"></div>
								</div>								
						</div>
						<div class="col-md-4">
							<div class="panel panel-default">		
										  <div class="panel-body">
										  		<table class="table table-bordered table-condensed table-sidebar">
										  			<tbody>
										  				<tr>
										  					<td>Pending Job Order</td>
										  					<td class="middle"><a href="javascript:void(0)" id="total_pending"><?php echo $total_pending; ?></a></td>										  					
										  				</tr>
										  			</tbody>
										  		</table>
										  </div>
							</div>			
								
							<!--FILter-->
							<div class="panel panel-default">		
							  <div class="panel-body">
							  	<h4>Filter</h4>
							  	<hr>
							  		<div class="form-group">
							  			<div class="control-label">Equipment Type</div>
							  			<select name="" id="equipment_type_history" class="form-control input-sm">
							  				<?php foreach($equipment_list as $row): ?>
													<option value="<?php echo $row['ID']; ?>"><?php echo $row['Equipment']; ?></option>
							  				<?php endforeach; ?>
							  			</select>
							  		</div>
							  		
									<div class="form-group">
										<div class="control-label">Body Number</div>
										<select name="" id="body_no_history" class="form-control input-sm">											

										</select>
									</div>

							  		<div class="row">
							  			<div class="col-md-6">
							  					<div class="form-group">
										  			<div class="control-label">From</div>
										  			<input name="" id="date_from_history" class="form-control input-sm">
										  		</div>
							  			</div>
							  			<div class="col-md-6">
								  				<div class="form-group">
											  			<div class="control-label">To</div>
											  			<input name="" id="date_to_history" class="form-control input-sm">
											  	</div>
											  	<div class="form-group">
											  		<button  id="job_breakdown_apply" class="btn btn-primary btn-sm">Apply</button>
											  	</div>
							  			</div>
							  		</div>
							  </div>	 
							</div>
						</div>
					</div>

				</div>					 	
		</div>



		
		<!-- 		<div class="row">
			<div class="col-md-12">			
					<div class="panel panel-default">	
						<div class="panel-body"></div>	
					 	<div class="table-responsive grid-breakdown">
					 		
					 	</div> 
					</div>
			</div>
		</div> -->
	</section>

	 <section id="jo-summary">
		<div class="content-title">
			<h3>Job Order Summary</h3>					
		</div>
			<div class="panel panel-default">	
				<div class="panel-body">	
					<div class="row">
						<div class="col-md-8">																
								<div class="stockpile-container" alt="Bargraph" title="Bargraph">
									<div id="get_jo_summary" class="demo-placeholder"></div>
								</div>								
						</div>
						<div class="col-md-4">
							<div class="panel panel-default">		
							  <div class="panel-body">
							  	<h4>Filter</h4>
							  	<hr>
							  		<div class="form-group">
							  			<div class="control-label">Equipment Type</div>
							  			<select name="" id="equipment_type" class="form-control input-sm">
							  				<?php foreach($equipment_list as $row): ?>
													<option value="<?php echo $row['ID']; ?>"><?php echo $row['Equipment']; ?></option>
							  				<?php endforeach; ?>
							  			</select>
							  		</div>
							  		<div class="row">
							  			<div class="col-md-6">
							  					<div class="form-group">
										  			<div class="control-label">From</div>
										  			<input name="" id="date_from" class="form-control input-sm">
										  		</div>
							  			</div>
							  			<div class="col-md-6">
								  				<div class="form-group">
											  			<div class="control-label">To</div>
											  			<input name="" id="date_to" class="form-control input-sm">
											  	</div>
											  	<div class="form-group">
											  		<button  id="job_order_apply" class="btn btn-primary btn-sm">Apply</button>
											  	</div>
							  			</div>
							  		</div>
							  </div>	 
							</div>
						</div>
					</div>

				</div>					 	
			</div>
	</section>
	<hr>

	<section>
		<div class="content-title">
			<h3>Mechanical Availability</h3>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">		
				  <div class="panel-body">
				  		<div class="row">
				  			<div class="col-md-8">
				  				<div class="table-responsive mechnanical_availability">
									<div class="legendary">										
									</div>

				  					<div class="stockpile-container">
										<div id="gh-mechanical" class="demo-placeholder pop-tooltip" data-title="%"></div>
									</div>
			
				  				</div>
				  			</div>
				  			<div class="col-md-4">
				  				<div class="panel panel-default">		
				  				  <div class="panel-body">
				  				  		<div class="row">
				  				  			<div class="col-md-6">

											<div class="form-group">
					  				  			<div class="control-label">From</div>
					  				  			<input name="" id="date_from_mech" class="form-control input-sm">
					  				  		</div> 

					  				  		</div>			  			
											<div class="col-md-6">
												<div class="form-group">
						  				  			<div class="control-label">Daily</div>
						  				  			<input name="" id="date_to_mech" class="form-control input-sm">
						  				  		</div>
						  				  		 <div class="form-group">
											  		<button  id="mech_avail_apply" class="btn btn-primary btn-sm">Apply</button>
											  	</div>
											</div>
				  				  		</div>										
				  				  </div>	 
				  				</div>
				  			</div>
				  		</div>
				  </div>	 
				</div>
			</div>
		</div>
		
	</section>
	
</div>

<script>
$(function(){
	var xhr;

  $("<div id='tooltip'></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #000",
			padding: "2px",
			"background-color": "#333",
			opacity: 0.9  
	  }).appendTo("body");



	var app = {
		init:function(){
			this.bindEvents();

			$('#date_from').date_from({
				now : '<?php echo date("Y-m-01") ?>',
			});
			$('#date_to').date_to();

			$('#date_from_history').date_from({
				now : '<?php echo date("Y-m-01") ?>',
			});
			$('#date_to_history').date_to();

	
			$('#date_from_mech').date_from({
				now : '<?php echo date("Y-m-01") ?>',
			});

			$('#date_to_mech').date_to();

			this.get_jo();
			this.get_jo_summary();
			this.get_jo_breakdown();
			this.get_maintenance();
			
		},
		bindEvents:function(){

			$('#job_order_apply').on('click',function(){
				$(this).append(' <i class="fa fa-spinner fa-spin"></i>');
				$(this).addClass('disabled');
				app.get_jo_summary();
			});
			$('#job_breakdown_apply').on('click',function(){
				$(this).append(' <i class="fa fa-spinner fa-spin"></i>');
				$(this).addClass('disabled');
				app.get_jo_breakdown();
			});

			$('#mech_avail_apply').on('click',function(){
				$(this).append(' <i class="fa fa-spinner fa-spin"></i>');
				$(this).addClass('disabled');
				app.get_maintenance();
			});


			

			$('#equipment_type_history').on('change',this.get_body_no);
			$('#equipment_type_history').trigger('change');
			$('#total_pending').on('click',this.view_pending);

		},
		get_body_no:function(){
			$post = {
				id : $('#equipment_type_history option:selected').val(),
			}

			$.post('<?php echo base_url().index_page();?>/maintenance/get_body_no',$post,function(response){
				$('#body_no_history').html(response);
			});			
		},
		get_jo:function(){

			/*$.post('<?php echo base_url().index_page();?>/maintenance/get_jo',function(response){
				$('.grid-breakdown').html(response);
				$('.myTable').dataTable(datatable_option);
			});*/
			
		},
		get_jo_summary:function(){			

	        if(xhr && xhr.readystate != 4){
	            xhr.abort();
	        }
	        	        
			$post = {
				from      : $('#date_from').val(),
				to        : $('#date_to').val(),
				equipment : $('#equipment_type option:selected').text(),
			};

			xhr = $.post('<?php echo base_url().index_page();?>/maintenance/get_jo_summary',$post,function(response){
				var ticks = [];
				for(var i in response.ticks)
				{
					ticks.push([i,response.ticks[i]]);
				}
				$.plot('#get_jo_summary',response.data,{
						xaxis: {
							axisLabel: response.title,
				            axisLabelUseCanvas: false,
				            axisLabelFontSizePixels: 12,
				            axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
				            axisLabelPadding: 10,
				            ticks: ticks,
						},
						yaxes:[{
									tickFormatter: function (val, axis) {
				                    return comma(val);
				             },
						}],
						legend:{
							show:false,
						}

					});

				$('#job_order_apply').find('i').remove();
				$('#job_order_apply').removeClass('disabled');

			},'json');

		},
		get_jo_breakdown:function(){

			$post = {
				from      : $('#date_from_history').val(),
				to        : $('#date_to_history').val(),
				equipment : $('#equipment_type_history option:selected').val(),
				equipment_name : $('#equipment_type_history option:selected').text(),
				body_no   : $('#body_no_history option:selected').text(),
			};

			$.post('<?php echo base_url().index_page();?>/maintenance/get_jo_breakdown',$post,function(response){

				var ticks = [];
				for(var i in response.ticks)
				{
					ticks.push([i,response.ticks[i]]);
				}



				$.plot('#get_jo_breakdown',response.data,{
						xaxis: {
									axisLabel: response.title,
						            axisLabelUseCanvas: false,
						            axisLabelFontSizePixels: 12,
						            axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
						            axisLabelPadding: 10,
						            ticks: ticks,
						},
						yaxes:[{
									tickFormatter: function (val, axis) {
				                    return comma(val);
				             },
						}],
						legend:{
							show:false,
						}

					});

				$('#job_breakdown_apply').find('i').remove();
				$('#job_breakdown_apply').removeClass('disabled');

			},'json');

		},
		view_pending:function(){			
			$.fancybox.showLoading();
			$.post('<?php echo base_url().index_page();?>/maintenance/view_pending',function(response){
					$.fancybox(response,{
						width     : 1100,
						height    : 550,
						fitToView : true,
						autoSize  : false,
					})
			});
		},
		get_maintenance:function(){
			
			$post = {
				from : $('#date_from_mech').val(),
				to   : $('#date_to_mech').val(),
			};
			
			function percentage(v,axis){
						return v.toFixed(axis.tickDecimals) + " %";
			};
			
			$.post('<?php echo base_url().index_page();?>/maintenance/get_mech_availability',$post,function(response){

			$('.legendary').html('');

			function plotAccordingToChoices(){


					var data = [];

					$('.legendary').find("input:checked").each(function () {
						var key = $(this).attr("name");
						if (key && response.output[key]) {
							data.push(response.output[key]);
						}
					});

					if (data.length > 0){
							$.plot("#gh-mechanical",data,{
										xaxes: [ {  
												ticks: ticks,
												 } ],
									 	yaxes  : [ { 
												alignTicksWithAxis : 1,
												position:'left',
												tickFormatter:  percentage,							
											 } ],
										grid   :  { hoverable : true, borderWidth: 1,clickable:true},
										points :  { show:true },
										lines  :  { show:true },
										legend: {
												noColumns: 7
										},
							});
					}



				}

				var ticks = [];
				for(var i in response.ticks)
				{
					ticks.push([i,response.ticks[i]]);
				}

				var i = 0;
				$.each(response.output, function(key, val) {
					val.color = i;
					++i;
				});

				var choiceContainer = $(".legendary");
				$.each(response.output, function(key, val) {
					choiceContainer.append("<div class='checkbox-inline'><input type='checkbox' name='" + key +
						"' checked='checked' id='id" + key + "'></input>" +
						"<label for='id" + key + "'>"
						+ val.label + "</label></div>");
				});
				
				$('body').on('click','.legendary >.checkbox-inline input',function(){
						plotAccordingToChoices();						
				});

			
				plotAccordingToChoices();


				$('.pop-tooltip').bind("plothover",function(event,pos,item){
						
				  		var title = $(this).data('title');
				  		if(item){
				  			
								var x = item.datapoint[0],
									y = item.datapoint[1];
																
								var my_date = response.ticks[x];

								$('#tooltip').html(item.series.label + " : "+my_date+" = " + comma(y) +" "+title)
										.css({top: item.pageY+5, left: item.pageX+5})
										.fadeIn(200);					

				  		}else{
				  			$("#tooltip").hide();		  			
				  		}
				});

				$('#mech_avail_apply').find('i').remove();
				$('#mech_avail_apply').removeClass('disabled');
				
			},'json');

		}
	}

	app.init();

});

</script>