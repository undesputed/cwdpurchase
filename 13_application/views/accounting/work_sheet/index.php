<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Work Sheet</h2>
</div>



<div class="container">

	<input type="hidden" id="cv_id" value="">	
	<input type="hidden" id="date" value="">	

	<div class="row">				
		<div class="col-md-12">
			
			<div class="content-title">
					<h3>Work Sheet</h3>
			</div>

			<div class="panel panel-default">		
			  <div class="panel-body">			  		
					<div class="row">

						<div class="col-md-4">							
						  		<div class="form-group">
						  			<div class="control-label">Projects</div>
						  			<select name="" id="project" class="form-control input-sm" style="display:none"></select>
						  			<select name="" id="profit_center" class="form-control input-sm"></select>
						  		</div>
						</div>

						<div class="col-md-2">
								<div class="form-group">
						  			<div class="control-label">Display By</div>
						  			<select name="" id="display_by" class="form-control input-sm">
						  				<option value="today">Today</option>
						  				<option value="month">This Month</option>
						  				<option value="year">This Year</option>
						  			</select>
						  		</div>
						</div>

						<div class="col-md-2">
								<button id="filter" class="btn btn-success nxt-btn">Apply Filter</button>
						</div>

					</div>
			  </div>
				<div class="table_content">
					<table class="table table-striped table-hover">
						<tbody>
							<tr>
								<td rowspan="2" style="vertical-align:middle;text-align:left">No</td>
								<td rowspan="2" style="vertical-align:middle;text-align:left">Account Title</td>								
								<td colspan="2">Trial Balance</td>
								<td colspan="2">Adjustments	</td>
								<td colspan="2">Adjusted Trial Balance</td>
								<td colspan="2">Income Statement</td>
								<td colspan="2">Balance Sheet</td>
							</tr>
							<tr>
								<td>DEBIT</td>
								<td>CREDIT</td>
								<td>DEBIT</td>
								<td>CREDIT</td>
								<td>DEBIT</td>
								<td>CREDIT</td>
								<td>DEBIT</td>
								<td>CREDIT</td>
								<td>DEBIT</td>
								<td>CREDIT</td>								
							</tr>
						
						
							<tr>
								<td colspan="12">Empty Result</td>
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
				display_by : $('#display_by option:selected').val(),
			}

			$.post('<?php echo base_url().index_page();?>/accounting_entry/work_sheet/get_cumulative',$post,function(response){
				$('.table_content').html(response);
				//$('.myTable').dataTable(datatable_option);
			}).error(function(){
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