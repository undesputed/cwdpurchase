<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Journal View</h2>
</div>

<script>
	$(function(){
			var date  = new Date();
			var month = date.getMonth() + 1;		
			$('#month').val(month);
	});

</script>

<div class="container">

	<input type="hidden" id="cv_id" value="">	
	<input type="hidden" id="date" value="">	
	
	<div class="row">				
		<div class="col-md-12">
			
			<div class="content-title">
					<h3>Journal View</h3>	
			</div>
			
			<div class="panel panel-default">		
			  <div class="panel-body">			  		
					<div class="row">

						<div class="col-md-4">							
						  		<div class="form-group">
						  			<div class="control-label">Filter By Project</div>
						  			<select name="" id="profit_center" style="width:100%">	
						  					<option value="%%">All</option>
						  				<?php foreach($project as $row): ?>
											<option value="<?php echo $row['project_id']; ?>"><?php echo $row['project_full'] ?></option>
						  				<?php endforeach; ?>
						  			</select>
						  		</div>
						</div>

						<div class="col-md-2">
							<div class="radio">
								<label for="monthly"><input type="radio" id="monthly" name="display_type" value="monthly" checked>Monthly</label>
							</div>
							<div class="radio">
								<label for="date_range"><input type="radio" id="date_range" name="display_type" value="date_range">Date Range</label>
							</div>
						</div>
		
						<div class="col-md-2">
								<div class="monthly-display" style="display:none">
								<div class="form-group inline">
						  			<div class="control-label">Year</div>
						  			<select name="" id="year" style="margin-top:2px">
										<?php $year = date('Y') ?>
										<?php for($year;$year >='2013';$year--): ?>
										<option value="<?php echo $year;?>"><?php echo $year; ?></option>
										<?php endfor; ?>
									</select>
						  		</div>
						  		<div class="form-group inline">
						  			<div class="control-label">Month</div>
						  			<select name="" id="month" style="margin-top:2px">
										<option value="1">January</option>
										<option value="2">Febuary</option>
										<option value="3">March</option>
										<option value="4">April</option>
										<option value="5">May</option>
										<option value="6">June</option>
										<option value="7">July</option>
										<option value="8">August</option>
										<option value="9">September</option>
										<option value="10">October</option>
										<option value="11">November</option>
										<option value="12">December</option>
									</select>
						  		</div>
							</div>

						  		<div class="date-range-display" style="display:none">
									<div class="form-group inline">
										<div class="control-label">Start</div>
										<input type="text" id="date_from" class="form-control" style="width:120px">
									</div>
									<div class="form-group inline">
										<div class="control-label">End</div>
										<input type="text" id="date_to" class="form-control" style="width:120px">
									</div>
								
								</div>
						</div>

						<div class="col-md-3">
								<button id="filter" class="btn btn-success" style="margin-top:10px">Apply Filter</button>
						</div>						

					</div>

			  </div>

				  <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="#all" aria-controls="all" role="tab" data-toggle="tab">All</a></li>
				    <li role="presentation"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a></li>
				    <li role="presentation"><a href="#purchases" aria-controls="purchases" role="tab" data-toggle="tab">Purchases</a></li>
				    <li role="presentation"><a href="#payments" aria-controls="payments" role="tab" data-toggle="tab">Payments</a></li>				    
				    <li role="presentation"><a href="#receipts" aria-controls="receipts" role="tab" data-toggle="tab">Receipts</a></li>				    		    
				    <li role="presentation"><a href="#sales" aria-controls="sales" role="tab" data-toggle="tab">Collections</a></li>
				  </ul>
				  				
				<div class="tab-content">
				    <div role="tabpanel" class="tab-pane active" id="all">
				    	
							<table class="table table-striped all table-condensed">
								<thead>
									<tr>
										<th>Date</th>
										<th>Account Title and Explanation</th>
										<th>Debit</th>	
										<th>Credit</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="4">Empty Result</td>
									</tr>
								</tbody>		
							</table>
				  		
				    </div>
				    <div role="tabpanel" class="tab-pane" id="general">
				    		<table class="table table-striped general table-condensed">
								<thead>
									<tr>
										<th>Date</th>
										<th>Account Title and Explanation</th>
										<th>Debit</th>	
										<th>Credit</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="4">Empty Result</td>
									</tr>
								</tbody>		
							</table>
				    </div>
				    <div role="tabpanel" class="tab-pane" id="payments">
				    		<table class="table table-striped payments table-condensed">
								<thead>
									<tr>
										<th>Date</th>
										<th>Account Title and Explanation</th>
										<th>Debit</th>	
										<th>Credit</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="4">Empty Result</td>
									</tr>
								</tbody>		
							</table>
				    </div>
				    <div role="tabpanel" class="tab-pane" id="receipts">
				    		<table class="table table-striped receipts table-condensed">
								<thead>
									<tr>
										<th>Date</th>
										<th>Account Title and Explanation</th>
										<th>Debit</th>	
										<th>Credit</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="4">Empty Result</td>
									</tr>
								</tbody>		
							</table>
				    </div>				    
				    <div role="tabpanel" class="tab-pane" id="sales">
				    		<table class="table table-striped sales table-condensed">
								<thead>
									<tr>
										<th>Date</th>
										<th>Account Title and Explanation</th>
										<th>Debit</th>	
										<th>Credit</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="4">Empty Result</td>
									</tr>
								</tbody>		
							</table>
				    </div>
				     <div role="tabpanel" class="tab-pane" id="purchases">
				    		<table class="table table-striped purchases table-condensed">
								<thead>
									<tr>
										<th>Date</th>
										<th>Account Title and Explanation</th>
										<th>Debit</th>	
										<th>Credit</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="4">Empty Result</td>
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
</div>

<script>
	var app ={
		init:function(){
			$('#date').date();

			$('#date_from').date_from({
				now : '<?php echo date('Y-m-01'); ?>',
			});

			$('#date_to').date_to();


			
			var option = {
				profit_center : $('#profit_center'),
				call_back     : function(){
					app.bindEvent();
					app.get_classification_setup();
				}				
			}

			$('#project').get_projects(option);	


		},get_classification_setup:function(){

			$('.tab-content').content_loader('true');

			$post = {
				month      : $('#month option:selected').val(),
				year       : $('#year option:selected').val(),
				location   : $('#profit_center option:selected').val(),
				display_by : $('#display_by option:selected').val(),
				view_type  : $('input[name="display_type"]:checked').val(),
				date_from  : $('#date_from').val(),
				date_to    : $('#date_to').val(),				
			}

			$.post('<?php echo base_url().index_page();?>/accounting_entry/journal_view/get_cumulative',$post,function(response){

				$('#all').html(response.all);
				$('#purchases').html(response.purchases);
				$('#general').html(response.general);
				$('#payments').html(response.payments);
				$('#sales').html(response.sales);
				$('#receipts').html(response.receipt);
				$('.table-preload').remove();
				//console.log(response);
				//$('.table_content').html(response);
				//$('.myTable').dataTable(datatable_option);

			},'json').error(function(){
				alert('Service Unavailable');
				$('.tab-content').content_loader('false');			
			});

		},bindEvent:function(){

			$('input[name="display_type"]').on('change',function(){
			
				if($('input[name="display_type"]:checked').val() == "monthly"){
					$('.monthly-display').removeAttr('style');
					$('.date-range-display').css({'display':'none'});
				}else{
					$('.date-range-display').removeAttr('style');
					$('.monthly-display').css({'display':'none'});
				}
			});
			$('input[name="display_type"]').trigger('change');

			$('#filter').on('click',this.apply_filter);
		},apply_filter:function(){
			app.get_classification_setup();
		}

	};

	$(function(){		
		app.init();
	});
</script>