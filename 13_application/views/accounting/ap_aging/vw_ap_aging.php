<div class="content-page">
 <div class="content">

<div class="header">
	<h2>A/P Aging</h2>
</div>



<div class="container">

	<input type="hidden" id="cv_id" value="">	
	<input type="hidden" id="date" value="">	

	<div class="row">				
		<div class="col-md-12">
			
			<div class="content-title">
					<h3>A/P Aging Report</h3>	
			</div>

			<div class="panel panel-default">		
			  <div class="panel-body">			  		
					<div class="row">
												
						<div class="col-md-6">
								<table class="table">
									<tbody>										
										<tr>
											<td>Total: </td>
											<td id="total" style="font-weight:bold">0</td>
											<td>1 - 30: </td>
											<td id="one" style="font-weight:bold">0</td>
											<td>61 - 90:</td>
											<td id="sixtyOne" style="font-weight:bold">0</td>
										</tr>
										<tr>
											<td>Current: </td>
											<td id="current" style="font-weight:bold">0</td>
											<td>31 - 60: </td>
											<td id="thirtyOne" style="font-weight:bold">0</td>
											<td>90+:</td>
											<td id="ninetyPlus" style="font-weight:bold">0</td>
										</tr>
									</tbody>
								</table>
						</div>
					</div>
			  </div>
				<div class="table_content">
					<table class="table table-striped ">
						<thead>
							<tr>
								<th>SUPPLIER</th>,
								<th>CURRENT</th>
								<th>1 - 30</th>	
								<th>31 - 60</th>
								<th>61 - 90</th>
								<th>90+</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="7">Empty Result</td>
							</tr>
						</tbody>		
					</table>
			  	</div>			
			</div>
			
		</div>
	
	</div>
		
</div>



</div>
</div>

<script>

	var app ={
		init:function(){
			$('#date').date();
			
			var option = {
				profit_center : $('#profit_center'),
				call_back     : function(){
					app.bindEvent();
					app.get_classification_setup();
				}				
			}

			$('#project').get_projects(option);	

		},get_classification_setup:function(){

			$('.table_content').content_loader('true');

			$post = {
				date       : $('#date').val(),
				location   : $('#profit_center option:selected').val(),				
			}

			$.post('<?php echo base_url().index_page();?>/accounting_entry/ap_aging/get_cumulative',$post,function(response){
				if(response !=0)
				{	

					$('#total').html(response[0]);
					$('#current').html(response[1]);
					$('#one').html(response[2]);
					$('#thirtyOne').html(response[3]);
					$('#sixtyOne').html(response[4]);
					$('#ninetyPlus').html(response[5]);
					$('.table_content').html(response[6]);

				}else{
					$('.table_content').html('Empty');
				}	
				//$('.myTable').dataTable(datatable_option);
			},'json').error(function(){
				alert('Service Unavailable');
				$('.table_content').content_loader('false');			
			});

		},bindEvent:function(){
			$('#filter').on('click',this.apply_filter);
		},apply_filter:function(){
			app.get_classification_setup();
		}

	};

	$(function(){		
		app.init();
	});
</script>