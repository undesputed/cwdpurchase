<style>	
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border-top:none;
	}
</style>

<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Income Statement</h2>
</div>
	
<div class="container">

	<input type="hidden" id="cv_id" value="">	
	<input type="hidden" id="date" value="">	

	<div class="row">				
		<div class="col-md-12">			
			<div class="content-title">
					<h3>Income Statement</h3>	
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">	
					
					<div class="row">
						<div class="col-md-4">							
						  		<div class="form-group">
						  			<div class="control-label">Filter By Project</div>
						  			<select name="" id="profit_center" style="width:100%">
						  					<option value="">All</option>
						  				<?php foreach($project as $row): ?>
											<option value="<?php echo $row['project_id']; ?>"><?php echo $row['project_full'] ?></option>
						  				<?php endforeach; ?>
						  			</select>
						  		</div>
						</div>
						<div class="col-md-6">
								<div class="form-group inline">
						  			<div class="control-label">Date From</div>
									<input type="text" class="date_from">
						  		</div>
						  		<div class="form-group inline">
						  			<div class="control-label">Date to</div>
									<input type="text" class="date_to">
						  		</div>
						  		<div class="form-group inline">
									<div class="btn-group" style="margin-top:-10px;">
										  <button type="button" id="filter" class="btn btn-success">Search</button>
										  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										    <span class="caret"></span>
										    <span class="sr-only">Toggle Dropdown</span>
										  </button>
										  <ul class="dropdown-menu">
									  			<li><a id="print" href="javascript:void(0)" target="_blank">Print</a></li>
										  </ul>
									</div>
						  		</div>
						</div>					
					</div>

			  </div>
				<div class="table_content">
					<table class="table ">
						<thead>
							<tr>
								<th>Income/Expense</th>,
								<th>Previous</th>
								<th>Current</th>	
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
				year       : $('#year option:selected').val(),
				month      : $('#month option:selected').val(),
				location   : $('#profit_center option:selected').val(),
				pay_item   : $('#project_category option:selected').val(),
				date_from  : $('.date_from').val(),
				date_to    : $('.date_to').val(),
			}

			$.post('<?php echo base_url().index_page();?>/accounting_entry/income_statement/get_cumulative',$post,function(response){
				$('.table_content').html(response);
				//$('.myTable').dataTable(datatable_option);
			}).error(function(){
				alert('Service Unavailable');
				$('.table_content').content_loader('false');			
			});

		},bindEvent:function(){
			$('#filter').on('click',this.apply_filter);

			$('#print').on('click',function(){

				var from =  $('.date_from').val();
				var to   =  $('.date_to').val();
				var location  = $('#profit_center option:selected').val();

				window.open('<?php echo site_url('print/income_statement/');?>?from='+from+'&to='+to+'&location='+location+'',"Print");

			});

		},apply_filter:function(){
			app.get_classification_setup();
		}

	};

	$(function(){		
		app.init();
	});
</script>