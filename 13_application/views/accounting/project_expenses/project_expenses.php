<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Project Expenses</h2>
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
					<h3>Payables</h3>	
			</div>
			
			<div class="panel panel-default">		
			  <div class="panel-body">

					<div class="row">
						<div class="col-md-4">							
						  		<div class="form-group">
						  			<div class="control-label">Filter By Project</div>
						  			<select name="" id="profit_center" style="width:100%">
						  				<option value="all">ALL</option>
						  				<?php foreach($project as $row): ?>
											<option value="<?php echo $row['project_id']; ?>"><?php echo $row['project_full_name'] ?></option>
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
										<?php for($year;$year >='2011';$year--): ?>
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
									<div class="control-label">Date From</div>
									<input type="text" id="date_from" class="form-control" style="width:120px">
								</div>
								<div class="form-group inline">
									<div class="control-label">Date To</div>
									<input type="text" id="date_to" class="form-control" style="width:120px">
								</div>
								
							</div>
						</div>

						<div class="col-md-2">
								<button id="filter" class="btn btn-success" style="margin-top:10px">Apply Filter</button>
								<a href="<?php echo base_url().index_page(); ?>/print/project_expenses/" target="_blank" class="print action-status cancel-event">Print</a>
						</div>
						
						<div class="col-md-2">
							
						</div>

					</div>
			  </div>
				<div class="table_content table-responsive">
					<table class="table table-striped ">
						<thead>
							<tr>
								<th>PO #</th>
								<th>Date</th>
								<th>Total Amount</th>
								<th>Project</th>
								<th>SI #</th>
								<th>SI Date</th>
								<th>Check #</th>
								<th>SI Amount</th>
								<th>Due Date</th>
								<th>Balance</th>
								<th>CV #</th>
								<th>Remarks</th>
							</tr>
						</thead>
						<tbody>
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
			$('#date_from').date_from({
				now : '2012-01-01',
			});
			$('#date_to').date_to();

			var option = {
				profit_center : $('#profit_center'),
				call_back     : function(){
					
				}				
			}

		
			app.get_classification_setup();
				app.bindEvent();
			/*$('#project').get_projects(option);	*/


		},get_classification_setup:function(){

			$('.table_content').content_loader('true');

			$post = {
				year       : $('#year option:selected').val(),
				month      : $('#month option:selected').val(),
				location   : $('#profit_center option:selected').val(),
				display_by : $('#display_by option:selected').val(),
				date_from  : $('#date_from').val(),
				date_to    : $('#date_to').val(),
				view_type  : $('input[name="display_type"]:checked').val(),
			}
			
			$.post('<?php echo base_url().index_page();?>/accounting_entry/project_expenses/get_cumulative',$post,function(response){
				$('.table_content').html(response);
				//$('.myTable').dataTable(datatable_option);
			}).error(function(){
				alert('Service Unavailable');
				$('.table_content').content_loader('false');
			});

		},bindEvent:function(){
			$('#filter').on('click',this.apply_filter);
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

			$('.print').on('click',function(e){

				e.preventDefault();
				var url = $('.print').attr('href');
				var project = $('#profit_center option:selected').val();
				var type = $('input[name="display_type"]:checked').val();

				var year  = $('#year option:selected').val();
				var month = $('#month option:selected').val();

				if(type == 'date_range'){
					var from = $('#date_from').val();
					var to   = $('#date_to').val();
				}else{
					var from = year+'-'+pad(month,2)+'-01';
					/*var to   = new Date(year+'-'+pad(month,2)+'-01');*/					
					var to = new Date(year,month,0);
					to   = to.getFullYear()+"-"+String(to.getMonth()+1).padLeft('0',2)+"-"+String(to.getDate()).padLeft('0',2);					
				}

				window.open(url+'?project='+project+'&from='+from+'&to='+to,'_blank');

			});




		},apply_filter:function(){
			app.get_classification_setup();
		}

	};

	$(function(){		
		app.init();
	});
</script>
