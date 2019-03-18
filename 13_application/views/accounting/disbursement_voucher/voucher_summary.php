

<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Voucher Summary</h2>
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
					<h3>Voucher Summary</h3>
			</div>
			
			<div class="panel panel-default">		
			  <div class="panel-body">			  		
					<div class="row">

						<div class="col-md-4">							
						  		<div class="form-group">
						  			<div class="control-label">Project</div>
						  			<select name="" id="profit_center" style="width:100%">
						  				<option value="all">ALL</option>
						  				<?php foreach($project as $row): ?>
											<option value="<?php echo $row['project_id']; ?>"><?php echo $row['project_full'] ?></option>
						  				<?php endforeach; ?>
						  			</select>
						  		</div>
						  		<div class="form-group">
						  			<div class="control-label">Supplier</div>
						  			<select name="" id="supplier" style="width:100%">
						  				<option value="all">ALL</option>
						  				<?php foreach($supplier as $row): ?>
											<option value="<?php echo $row['pay_to']; ?>"><?php echo $row['pay_to'] ?></option>
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


							<div class="radio">
								<label for="cash"><input type="radio" id="cash" name="payment_type" value="cash" checked>Cash</label>
							</div>
							<div class="radio">
								<label for="check"><input type="radio" id="check" name="payment_type" value="check">Check</label>
							</div>

							<div class="checkbox">
								<label for="cash_advance"><input type="checkbox" id="cash_advance" name="cash_advance" value="c_a">Include C.A.?</label>
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
										<div class="control-label">Date From</div>
										<input type="text" id="date_from" class="form-control" style="width:120px">
									</div>
									<div class="form-group inline">
										<div class="control-label">Date To</div>
										<input type="text" id="date_to" class="form-control" style="width:120px">
									</div>
								
								</div>
						</div>

						<div class="col-md-3">
								<div class="btn-group" style="margin-top:10px;">
										  <button type="button" id="filter" class="btn btn-success">Search</button>
										  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										    <span class="caret"></span>
										    <span class="sr-only">Toggle Dropdown</span>
										  </button>
										  <ul class="dropdown-menu">
										  		<li><a id="export" href="javascript:void(0)">Export to Excel</a></li>
									  			<!-- <li><a id="print" href="javascript:void(0)">Print</a></li> -->
										  </ul>
										  <a href="<?php echo base_url().index_page(); ?>/print/voucher_summary/" target="_blank" class="print action-status cancel-event">Print</a>
								</div>						
						</div>
					</div>
			  </div>
				<div class="table_content">
					<table class="table table-striped ">
						<thead>
							<tr>
								<th>Date</th>
								<th>Voucher No</th>
								<th>Particulars</th>
								<th>Amount</th>							
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


<script>
	var app ={
		init:function(){			
						
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
			
			/*$('.table_content').content_loader('true');*/

			$post = {
				supplier   : $('#supplier option:selected').val(),
				year       : $('#year option:selected').val(),
				month      : $('#month option:selected').val(),
				location   : $('#profit_center option:selected').val(),
				display_by : $('#display_by option:selected').val(),
				view_type  : $('input[name="display_type"]:checked').val(),
				date_from  : $('#date_from').val(),
				date_to    : $('#date_to').val(),
				payment_type : $('input[name="payment_type"]:checked').val(),
				cash_advance : $('input[name="cash_advance"]').is(':checked')
			}
			$('.table_content').html('Loading...');
			$.post('<?php echo base_url().index_page();?>/accounting/get_voucher_summary',$post,function(response){
				$('.table_content').html(response);
				/*$('.myTable').dataTable(datatable_option);*/
			}).error(function(){
				alert('Service Unavailable');
				$('.table_content').content_loader('false');                      
			});

		},bindEvent:function(){
			$('#filter').on('click',this.apply_filter);
			$('#export').on('click',this.do_export);



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
				var payment = $('input[name="payment_type"]:checked').val();
				var project_name = $('#profit_center option:selected').text();
				var display_by = $('#display_by option:selected').val();
				var supplier   = $('#supplier option:selected').val();
				var year       = $('#year option:selected').val();
				var month      = $('#month option:selected').val();
				var cash_advance = $('input[name="cash_advance"]').is(':checked');

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

				window.open(url+'?project='+project+'&from='+from+'&to='+to+'&name='+project_name+'&payment='+payment+'&view_type='+type+'&supplier='+supplier+'&year='+year+'&month='+month+'&ca='+cash_advance,'_blank');

			});

		},apply_filter:function(){
			app.get_classification_setup();
		},do_print:function(){
			
		},do_export:function(){

			$post = {
				supplier   : $('#supplier option:selected').val(),
				year       : $('#year option:selected').val(),
				month      : $('#month option:selected').val(),
				location   : $('#profit_center option:selected').val(),
				display_by : $('#display_by option:selected').val(),
				view_type  : $('input[name="display_type"]:checked').val(),
				date_from  : $('#date_from').val(),
				date_to    : $('#date_to').val(),
				payment_type : $('input[name="payment_type"]:checked').val(),
				cash_advance : $('input[name="cash_advance"]').is(':checked'),
			}

			$url = jQuery.param($post);
			console.log($url);
			document.location.href = "<?php echo base_url().index_page();?>/print/voucher_summary_excel?"+$url;
						
		}

	};

	$(function(){		
		app.init();
	});
</script>