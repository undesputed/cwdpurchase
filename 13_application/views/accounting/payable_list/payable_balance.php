<div class="content-page">
<div class="content">

<div class="header">
	<h2>Payable Balance</h2>
</div>
<style>
	.update{
		cursor:pointer;
	}
</style>
<script>
	$(function(){
			var date  = new Date();
			var month = date.getMonth() + 1;		
			$('#month').val(month);
	});
</script>
<div class="">

	<input type="hidden" id="cv_id" value="">	
	<input type="hidden" id="date" value="">	

	<div class="row">				
		<div class="col-md-12">
							
			<div class="panel panel-default">		
			  <div class="panel-body">			  		
					<div class="row">

						<div class="col-md-4">							
						  		<div class="form-group">
						  			<div class="control-label">Filter By Project</div>
						  			<select name="" id="profit_center" style="width:100%">
						  				<option value="all">ALL</option>
						  				<?php foreach($project as $row): ?>
											<option value="<?php echo $row['project_id']; ?>"><?php echo $row['project_full'] ?></option>
						  				<?php endforeach; ?>
						  			</select>
						  		</div>
						  		<div class="form-group">
						  			<div class="control-label">Supplier</div>
						  			<select name="" id="supplier_list" style="width:100%">
						  				<option value="all">ALL</option>
						  				<?php foreach($supplier as $row): ?>
											<option value="<?php echo $row['business_number']; ?>"><?php echo $row['business_name'] ?></option>
						  				<?php endforeach; ?>
						  			</select>
						  		</div>
						</div>
					
						<div class="col-md-3">
								<button id="filter" class="btn btn-success" style="margin-top:10px">Apply Filter</button>
								<span class="control-item-group">
									 <a href="<?php echo base_url().index_page(); ?>/print/payables/" target="_blank" class="print action-status cancel-event">Print</a>
								</span>
						</div>
					</div>
			  </div>
			  <div class="row">
			  	<div class="col-md-12">
					<div class="table_content table-responsive">
						<table class="table table-striped ">
							<thead>
								<tr>
									<th>Supplier</th>
									<th>Payable Balance</th>								
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="2">Empty Result</td>
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
</div>


<script>
	var app ={
		init:function(){			
			
			$('.date').date(now());

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
			/*$('#project').get_projects(option);*/

		},get_classification_setup:function(){
			
			$('.table_content').content_loader('true');

			$post = {
				year       : $('#year option:selected').val(),
				month      : $('#month option:selected').val(),
				location   : $('#profit_center option:selected').val(),
				display_by : $('#display_by option:selected').val(),
				view_type  : $('input[name="display_type"]:checked').val(),
				date_from  : $('#date_from').val(),
				date_to    : $('#date_to').val(),
				supplier_id: $('#supplier_list option:selected').val(),
			}
			
			$.post('<?php echo base_url().index_page();?>/accounting_entry/payable_balance/get_cumulative',$post,function(response){
				$('.table_content').html(response);
				/*$('.myTable').dataTable(datatable_option);*/
			}).error(function(){
				alert('Service Unavailable');
				$('.table_content').content_loader('false');                      
			});

		},bindEvent:function(){

			
			$('body').on('click','.view_info',this.view_info);
			
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
				var supplier = $('#supplier_list option:selected').val();

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

				window.open(url+'?project='+project+'&from='+from+'&to='+to+'&supplier='+supplier,'_blank');				
			});

		},apply_filter:function(){
			app.get_classification_setup();
		},view_info:function(){

			$.fancybox.showLoading();
			$post = {
				supplier_id : $(this).attr('data-supid'),
				year       : $('#year option:selected').val(),
				month      : $('#month option:selected').val(),
				location   : $('#profit_center option:selected').val(),
				display_by : $('#display_by option:selected').val(),
				view_type  : $('input[name="display_type"]:checked').val(),
				date_from  : $('#date_from').val(),
				date_to    : $('#date_to').val(),
			}
			$.post('<?php echo base_url().index_page();?>/accounting_entry/payable_balance/view_info',$post,function(response){

				$.fancybox(response,{
					width     : 1000,
					height    : 550,
					fitToView : true,
					autoSize  : false,
				});

			});

		}
	};

	$(function(){		
		app.init();
	});
</script>