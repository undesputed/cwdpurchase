<?php 
	$transaction_type = array(
			array('title'=>'Adjustment Entry','short'=>'AE'),
			array('title'=>'Bank Recon Entry','short'=>'BRE'),
			array('title'=>'Beginning Balance','short'=>'BB'),
			array('title'=>'Cash Count Entry','short'=>'CCE'),
			array('title'=>'Closing Entry','short'=>'CE'),
			array('title'=>'Enter Advances','short'=>'EA'),
			array('title'=>'Enter Invoice','short'=>'EI'),
			array('title'=>'Enter Payable','short'=>'EP'),
			array('title'=>'Enter Receipt','short'=>'ER'),
			array('title'=>'Inventory Entry','short'=>'IE'),
			array('title'=>'Journal Entry','short'=>'JE'),
			array('title'=>'Pay Advances','short'=>'PA'),
			array('title'=>'Pay Payable','short'=>'PP'),
			array('title'=>'Petty Cash Entry','short'=>'PCE'),
			array('title'=>'Received Payment','short'=>'RP'),
		);
 ?>
 <div class="header">
	<h2>Journal Entry Cumulative</h2>
</div>

<div class="container">
												  						
			<div class="row">			
				<div class="col-md-12">
					<div class="content-title">
							<h3>Entries</h3>
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
							

							<div class="col-md-3">
								<div class="form-group">
						  			<div class="control-label">Transaction Type</div>						  			
						  			<select name="" id="transaction_type" class="form-control input-sm">									  				
							  				<?php foreach($transaction_type as $row): ?>
							  						<option value="<?php echo $row['short']; ?>"><?php echo $row['title']; ?></option>
							  				<?php endforeach; ?>									  				
									 </select>
								</div>
							</div>
							
							<div class="col-md-1">
								<div class="form-group">
									<div class="control-label">From</div>						  			
						  			<input type="text" id="date_from" class="form-control input-sm " readonly>
						  			
								</div>
							</div>
							<div class="col-md-1">
								<div class="form-group">
									<div class="control-label">to</div>						  			
						  			<input type="text" id="date_to" class="form-control input-sm" readonly>						  			
								</div>
							</div>

							<div class="col-md-2">
								<button id="filter" class="btn btn-success nxt-btn">Apply Filter</button>
							</div>
							
						</div>
						
					</div>	
						
					<div class="table-content">
						  <table class="table table-striped table-hover tbl-journal tbl-event">
						  		<thead>
						  			<tr>
						  				<th>Reference No</th>
										<th>Trans Date</th>
										<th>Type</th>
										<th>Memo</th>
										<th>Status</th>
						  			</tr>
						  		</thead>
						  		<tbody>
						  			<tr>
						  				<td></td>
						  				<td></td>
						  				<td></td>
						  				<td></td>
						  				<td></td>
						  			</tr>
						  		</tbody>
						  </table>
					</div>
					</div>
				</div>

			</div>		
</div>
<script>
	var app ={
		init:function(){

			$('#date_from').date_from();
			$('#date_to').date_to();
			
			var option = {
				profit_center : $('#profit_center'),
				call_back     : function(){
					app.bindEvent();
					app.get_cumulative();
				}				
			}
			$('#project').get_projects(option);	
			
		},bindEvent:function(){

			$('#filter').on('click',this.get_cumulative);
			$('body').on('click','.cancel',this.cancel);
			$('body').on('click','.modify',this.modify);
			$('body').on('click','.details',this.details);


		},get_cumulative:function(){

			$post = {
				location : $('#profit_center option:selected').val(),
				transaction_type : $('#transaction_type option:selected').text(),
				from : $('#date_from').val(),
				to   : $('#date_to').val(),
			};

			$('.table-content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/accounting/journal_entry_cumulative/get_cumulative',$post,function(response){
				$('.table-content').html(response);
				$('.myTable').dataTable(datatable_option);
			});
		},cancel  : function(){

			var bool = confirm("Are you Sure to CANCEL the transaction?");

			if(bool){
				var td_status =  $(this).closest('tr').find('td.status');
				var  journal_id = $(this).closest('tr').find('td.journal_id').text();
				$post = {
					journal_id : journal_id,
				}
				$.save({'loading': 'Processing...'});
				$.post('<?php echo base_url().index_page();?>/accounting/journal_entry_cumulative/change_status',$post,function(response){
						$.save({'action': 'hide'});
						td_status.find('span').html("CANCELLED").removeClass('label-success').addClass('label-danger');
				}).error(function(){
					alert('error');
				});

			}
			
		},modify : function(){

			$.fancybox.showLoading();
			var  journal_id = $(this).closest('tr').find('td.journal_id').text();

			$post = {
				journal_id : journal_id,
			}

			$.post('<?php echo base_url().index_page(); ?>/accounting/journal_entry_cumulative/edit',$post,function(response){
						$.fancybox(response,{
								width     : 1200,
								height    : 550,
								fitToView : false,
								autoSize  : false,
						})
			});


		},details:function(){

			$.fancybox.showLoading();
			var  journal_id = $(this).closest('tr').find('td.journal_id').text();

			$post = {
				journal_id : journal_id,
			}

			$.post('<?php echo base_url().index_page(); ?>/accounting/journal_entry_cumulative/get_details',$post,function(response){
						$.fancybox(response,{
								width     : 1000,
								height    : 550,
								fitToView : false,
								autoSize  : false,
						})
			});

			
		}
					
	};


	$(function(){
		app.init();
	});
</script>