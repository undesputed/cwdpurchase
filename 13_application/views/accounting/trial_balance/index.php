

<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Trial Balance</h2>
</div>



<div class="container">

	<input type="hidden" id="cv_id" value="">	
	<input type="hidden" id="date" value="">	

	<div class="row">				
		<div class="col-md-12">
			
			<div class="content-title">
					<h3>Trial Balance</h3>	
			</div>

			<div class="panel panel-default">		
			  <div class="panel-body">			  		
					<div class="row">	

						<div class="col-md-4">							
						  		<div class="form-group">
						  			<div class="control-label">Filter By Project</div>
						  			<select name="" id="profit_center" style="width:100%">
						  					<option value="0">All</option>
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
									  			<li><a id="print" href="javascript:void(0)">Print</a></li>
										  </ul>
									</div>
						  		</div>
						</div>

						<div class="col-md-2">
							<!-- <span class="control-item-group">
									<a href="<?php echo base_url().index_page(); ?>/print/payables/" target="_blank" class="action-status cancel-event">Print</a>
								</span> -->	
						</div>

					</div>


			  </div>
				<div class="table_content">
					<table class="table table-striped ">
						<thead>
							<tr>
								<th>Account Code</th>
								<th>Description</th>
								<th>Debit </th>
								<th>Credit</th>
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
			
			$('.date_from').date_from({
				'now': '<?php echo date('Y-m-01') ?>'
			});
			$('.date_to').date_to();
			
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
				date_from  : $('.date_from').val(),
				date_to    : $('.date_to').val(),
				location   : $('#profit_center option:selected').val(),
				display_by : $('#display_by option:selected').val(),
			}

			$.post('<?php echo base_url().index_page();?>/accounting_entry/trial_balance/get_cumulative',$post,function(response){
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

				window.open('<?php echo site_url('print/trial_balance/');?>?from='+from+'&to='+to+'&location='+location+'',"Print");

			});

		},apply_filter:function(){
			app.get_classification_setup();
		}

	};

	$(function(){		
		app.init();
	});
</script>