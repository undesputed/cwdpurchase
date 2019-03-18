<div class="header">
	<h2>Maintenance</h2>
</div>
<input type="hidden" id="hdn-date">
<div class="container">
	<div class="row">
		<div class="col-md-5">
			<div class="content-title">
				<h3>Breakdown History</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">
			  		<div id="gh-breakdown-history" style="width:100%;height:210px"></div>
			  </div>
			  <div class="panel-footer">
			  		<div class="row">
			  			<div class="col-xs-4">
			  				<select name="" id="select-breakdown-history" class="form-control input-sm">
			  					<?php foreach($equipment_list as $row): ?>
										<option value="<?php echo $row['ID']; ?>"><?php echo $row['Equipment']; ?></option>
							  	<?php endforeach; ?>
			  				</select>
			  			</div>
			  			<div class="col-xs-3">
			  			</div>
			  			<div class="col-xs-5">
			  				<button  id="btn-breakdown-history" class="btn btn-sm btn-primary pull-right">
			  					Apply <span class="fa"></span>
			  				</button>
			  			</div>
			  		</div>
			  </div>	 
			</div>
			<!--section-->

			<div class="content-title">
				<h3>Job Order Summary</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">
			  		<div id="gh-jo-summary" style="width:100%;height:210px"></div>
			  </div>
			  <div class="panel-footer">
			  		<div class="row">
			  			<div class="col-xs-4">
			  				<select name="" id="select-jo-summary" class="form-control input-sm">
			  					<?php foreach($equipment_list as $row): ?>
										<option value="<?php echo $row['ID']; ?>"><?php echo $row['Equipment']; ?></option>
							  	<?php endforeach; ?>
			  				</select>
			  			</div>

			  			<div class="col-xs-5">
			  				<input type="text" id="jo-date-from" class="form-control input-sm" style="display:inline-block;width:80px">
			  				<input type="text" id="jo-date-to" class="form-control input-sm" style="display:inline-block;width:80px">
			  			</div>

			  			<div class="col-xs-3">
			  				<button id="btn-jo-summary" class="btn btn-sm btn-primary pull-right">
			  					Apply <span class="fa"></span>
			  				</button>
			  			</div>
			  		</div>
			  </div>	 
			</div>
			
		</div>
		
		<div class="col-md-7">
			
			<div class="row">
				<div class="col-md-4">
					<div class="content-title">
						<h3>Job Order</h3>
					</div>	
					<div class="panel panel-default">

					  <div class="jo-block">
					  		<div class="jo-block-left" style="width:40%">		
					  			<small>All</small>			  			
						  		<h1><a href="javascript:void(0)" id="total_pending"><?php echo $total_pending; ?></a></h1>
						  		<small>Pending</small>
					  		</div>
					  		<div class="jo-block-right pull-right">
					  			<div id="graph" style="height:120px;width:120px"></div>
					  		</div>
					  </div>

					</div>
					<div class="panel panel-default">		
					  <div class="panel-body">
					  		<div class="row jo-block-1">
					  			<div class="col-xs-5">
					  				<h3 id="complete-today">-</h3>
					  				<small>Today</small>
					  			</div>
					  			<div class="col-xs-7">
					  				<h3 id="complete-all">-</h3>
					  				<small>Total Complete JO</small>
					  			</div>							  			
					  		</div>						
					  </div>	 
					</div>
					
					<div class="panel panel-default">		
					  <div class="panel-body">
					  				
							<div class="form-group">

					  			<?php foreach($maintenance as $row): 
					  				  $width = ( $row['PA'] / $row['NUMBER OF UNIT'] ) * 100;					  				  
					  				  $width = "width:".$width."%";
					  			?>
									<div>
						  				<small><?php echo $row['EQUIPMENT']; ?></small>
						  				<small class="pull-right text-muted"><?php echo $row['PA']; ?>/<?php echo $row['NUMBER OF UNIT']; ?></small>
						  				<div class="progress progress-sm" style="height:5px">
											<div class="progress-bar" role="progressbar" style="<?php echo $width; ?>"></div>
										</div>
					  				</div>
					  			<?php endforeach; ?>
													  								  		
					  		</div>

					  </div>	 
					</div>

				</div>
				<div class="col-md-8">
					<div class="content-title">
						<h3>Mechanical Availability</h3>
					</div>	
					<div class="panel panel-default">		
					  <div class="panel-body">
					  			<div id="gh-mechanical-availability" style="width:100%;height:210px"></div>
					  </div>
					  <div class="panel-footer">
							<div class="row">
					  			<div class="col-xs-4">
					  				<select name="" id="select-mechanical-availability" class="form-control input-sm">
					  					<?php foreach($equipment_list as $row): ?>
												<option value="<?php echo $row['ID']; ?>"><?php echo $row['Equipment']; ?></option>
									  	<?php endforeach; ?>
					  				</select>
					  			</div>
					  			<div class="col-xs-3">
					  			</div>
					  			<div class="col-xs-5">
					  				<button  id="btn-mechanical-availability" class="btn btn-sm btn-primary pull-right">
					  					Apply <span class="fa"></span>
					  				</button>
					  			</div>
					  		</div>
					  </div>	 
					</div>
				</div>
			</div>

		</div>


	</div>

		
</div>


<script>
	$(function(){
		var app = {
			init:function(){
				$('#hdn-date').date();
				$('#jo-date-from').date_from(
				{
					now : '<?php echo date("Y-m-01") ?>',
				});
				$('#jo-date-to').date_to();
				this.morris();
				this.breakdown_history();
				this.get_complete_jo();
				this.bindEvents();
				this.jo_summary();
				this.get_ma();
			},
			bindEvents:function(){				

				$('#btn-jo-summary').on('click',this.jo_summary);
				$('#btn-breakdown-history').on('click',this.breakdown_history);
				$('#total_pending').on('click',this.view_pending);

				$('#btn-mechanical-availability').on('click',this.get_ma);
			},
			jo_summary:function(){

				$('#btn-jo-summary').addClass('disabled');
				$('#btn-jo-summary').find('span').addClass('fa-spin fa-spinner');

				$get = {
					from : $('#jo-date-from').val(),
					to   : $('#jo-date-to').val(),
					equipment : $('#select-jo-summary option:selected').text(),
				};

				$.post('<?php echo base_url().index_page();?>/maintenance/get_jo_summary',$get,function(response){
					var ticks = [];
					for(var i in response.ticks)
					{
						ticks.push([i,response.ticks[i]]);
					}
					$.plot('#gh-jo-summary',response.data,{
							xaxis: {
								axisLabel: response.title,
					            axisLabelUseCanvas: true,
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

					$('#btn-jo-summary').removeClass('disabled');
					$('#btn-jo-summary').find('span').removeClass('fa-spin fa-spinner');

				},'json').error(function(){
					$('#btn-jo-summary').removeClass('disabled');
					$('#btn-jo-summary').find('span').removeClass('fa-spin fa-spinner');					
					alert('Internal Server Error');
				});

			},
			morris:function(){
				Morris.Donut({
				  element: 'graph',
				  data: <?php echo $pending_monthly; ?>,
				  formatter: function (x) { return x }
				});
			},
			breakdown_history:function(){

				$('#btn-breakdown-history').addClass('disabled');
				$('#btn-breakdown-history').find('span').addClass('fa-spin fa-spinner');

				$post = {
					equipment : $('#select-breakdown-history option:selected').text(),
				}

				$.post('<?php echo base_url().index_page();?>/maintenance/get_breakdown_history',$post,function(response){
					
					var ticks = [];
					for(var i in response.ticks)
					{
						ticks.push([i,response.ticks[i]]);
					}

					$.plot('#gh-breakdown-history',response.data,{
							xaxis: {
								axisLabel: response.title,
					            axisLabelUseCanvas: true,
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

					$('#btn-breakdown-history').removeClass('disabled');
					$('#btn-breakdown-history').find('span').removeClass('fa-spin fa-spinner');

				},'json');

				/*
				Morris.Bar({
				  element: 'gh-breakdown-history',
				  data: [
				    {x: '2011 Q1', y: 3, z: 2, a: 3, v : 'test' },
				    {x: '2011 Q2', y: 2, z: null, a: 1, v : 'test'},
				    {x: '2011 Q3', y: 0, z: 2, a: 4, v : 'test'},
				    {x: '2011 Q4', y: 2, z: 4, a: 3, v :'test'},
					{x: '2011 Q4', y: 2, z: 4, a: 3, v :'test'},
					{x: '2011 Q4', y: 2, z: 4, a: 3, v :'test'},
					{x: '2011 Q4', y: 2, z: 4, a: 3, v :'test'},
					{x: '2011 Q4', y: 2, z: 4, a: 3, v :'test'},
					{x: '2011 Q4', y: 2, z: 4, a: 3, v :'test'},
					{x: '2011 Q4', y: 2, z: 4, a: 3, v :'test'},
					{x: '2011 Q4', y: 2, z: 4, a: 3, v :'test'},
				  ],
				  xkey: 'x',
				  ykeys: ['y', 'z', 'a'],
				  labels: ['Y', 'Z', 'A']
				});
				*/


			},
			get_complete_jo:function(){

				$get = {
					date : $('#hdn-date').val()
				};

				$.get('<?php echo base_url().index_page();?>/maintenance/get_complete_jo',$get,function(response){
					$('#complete-all').html(comma(response.all));
					$('#complete-today').html(response.today);
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
		get_ma:function(){
			$post = {
				equipment : $('#select-mechanical-availability option:selected').text(),
			};
			$('#btn-mechanical-availability').addClass('disabled');
			$('#btn-mechanical-availability').find('span').addClass('fa-spin fa-spinner');
			$.post('<?php echo base_url().index_page();?>/maintenance/get_ma',$post,function(response){
				var ticks = [];
					for(var i in response.ticks)
					{
						ticks.push([i,response.ticks[i]]);
					}

					$.plot('#gh-mechanical-availability',response.data,{
							xaxis: {
								axisLabel: response.title,
					            axisLabelUseCanvas: true,
					            axisLabelFontSizePixels: 12,
					            axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
					            axisLabelPadding: 10,
					            ticks: ticks,
							},
							yaxes:[{
										tickFormatter: function (val, axis) {
					                    return comma(val) +'%';
					            	 },
							}],
							legend:{
								show:false,
							}
					});

					$('#btn-mechanical-availability').removeClass('disabled');
					$('#btn-mechanical-availability').find('span').removeClass('fa-spin fa-spinner');

			},'json');

		}


		}

		app.init();

	});

</script>