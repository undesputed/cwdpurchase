<div class="content-page">
<div class="content">

<div class="header">
	<h2>Payable List</h2>
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
											<option value="<?php echo $row['project_id']; ?>"><?php echo $row['project_full_name'] ?></option>
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
						<div class="col-md-1">
							<fieldset>
							<div class="radio">
								<label for="all"><input type="radio" id="all" name="paid_status" value="all" checked>All</label>
							</div>
							<div class="radio">
								<label for="paid"><input type="radio" id="paid" name="paid_status" value="paid" checked>Paid</label>
							</div>
							<div class="radio">
								<label for="unpaid"><input type="radio" id="unpaid" name="paid_status" value="unpaid">Unpaid</label>
							</div>
							</fieldset>
						</div>
						<div class="col-md-1">
							<div class="radio">
								<label for="monthly"><input type="radio" id="monthly" name="display_type" value="monthly" checked>Monthly</label>
							</div>
							<div class="radio">
								<label for="date_range"><input type="radio" id="date_range" name="display_type" value="date_range">Date Range</label>
							</div>
						
							
														
							<!-- <div class="form-group">
								<div class="control-label"></div>
								<select name="" id="" style="width:100%">
									
								</select>
							</div> -->

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
								<button id="filter" class="btn btn-success" style="margin-top:10px">Apply Filter</button>
								<span class="control-item-group">
									 <a href="<?php echo base_url().index_page(); ?>/print/payables/" target="_blank" class="print action-status cancel-event">Print</a>
								</span>
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



<div id="dialog1" title="" style="display:none">
	<form action="">

	<table class="table">
		<tr>
			<th>PO No.</th>
			<th>AMOUNT</th>
		</tr>
		<tr>
			<td id="po_no"></td>
			<td id="amount"></td>
		</tr>
	</table>

	<div class="form-group">
  		<label for="">Amount Paid</label>
  		<input type="text" class="form-control" id="amount_paid">
  	</div>
	
  	<div class="form-group">
  		<label for="">Invoice Number</label>
  		<input type="text" class="form-control" id="invoice_number">
  	</div>
	
  	<div class="form-group">
  		<label for="">Invoice Date</label>
  		<input type="text" class="form-control date" id="invoice_date">
  	</div>

	<div class="form-group">
		<label class="radio-inline">
		  <input type="radio" name="inlineRadioOptions" id="radio-check" value="check" checked> Check
		</label>
		<label class="radio-inline">
		  <input type="radio" name="inlineRadioOptions" id="radio-cash" value="cash"> Cash
		</label>		
	</div>
	
	<div id="content-cash"  class="hidden">
		<div class="form-group">
	  		<label for="">Affiliate/Disbursing Officer</label>
	  		<select name="" id="affiliate" class="form-control">
	  			<?php foreach($get_affiliate as $row): ?>
	  				<option value="<?php echo $row['business_number']; ?>"><?php echo $row['business_name']; ?></option>
	  			<?php endforeach; ?>
	  		</select>	  		
	  	</div>
	</div>	

	<div id="content-check" class="hidden">
	  	<div class="form-group">
	  		<label for="">Bank</label>
	  		<select name="" id="bank" class="form-control">
	  			<?php foreach($bank_setup as $row): ?>
					<option value="<?php echo $row['bank_id']; ?>"><?php echo $row['bank_name']; ?></option>
	  			<?php endforeach; ?>
	  		</select>  		
	  	</div>
	  	<div class="form-group">
	  		<label for="">Check No</label>
	  		<input type="text" class="form-control required" id="check_no">
	  	</div>
	  	<div class="form-group">
	  		<label for="">Check Date</label>
	  		<input type="text" class="form-control date required" id="check_date">
	  	</div>
  	</div>

  	</form>

</div>


<div id="dialog2" title="Edit" style="display:none">
	<form action="">

	<table class="table">
		<tr>
			<th>PO No.</th>
			<th>AMOUNT</th>
		</tr>
		<tr>
			<td id="edit_po_no"></td>
			<td id="edit_amount"></td>
		</tr>
	</table>

	<div class="form-group">
  		<label for="">Amount Paid</label>
  		<input type="text" class="form-control" id="edit_amount_paid">
  	</div>
	
  	<div class="form-group">
  		<label for="">Invoice Number</label>
  		<input type="text" class="form-control" id="edit_invoice_number">
  	</div>
	
  	<div class="form-group">
  		<label for="">Invoice Date</label>
  		<input type="text" class="form-control date" id="edit_invoice_date">
  	</div>

	<div class="form-group">
		<label class="radio-inline">
		  <input type="radio" name="edit_inlineRadioOptions" id="radio-check" value="check" checked> Check
		</label>
		<label class="radio-inline">
		  <input type="radio" name="edit_inlineRadioOptions" id="radio-cash" value="cash"> Cash
		</label>		
	</div>
	
	<div id="edit_content-cash"  class="hidden">
		<div class="form-group">
	  		<label for="">Affiliate/Disbursing Officer</label>
	  		<select name="" id="affiliate" class="form-control">
	  			<?php foreach($get_affiliate as $row): ?>
	  				<option value="<?php echo $row['business_number']; ?>"><?php echo $row['business_name']; ?></option>
	  			<?php endforeach; ?>
	  		</select>	  		
	  	</div>
	</div>	

	<div id="edit_content-check" class="hidden">
	  	<div class="form-group">
	  		<label for="">Bank</label>
	  		<select name="" id="bank" class="form-control">
	  			<?php foreach($bank_setup as $row): ?>
					<option value="<?php echo $row['bank_id']; ?>"><?php echo $row['bank_name']; ?></option>
	  			<?php endforeach; ?>
	  		</select>  		
	  	</div>
	  	<div class="form-group">
	  		<label for="">Check No</label>
	  		<input type="text" class="form-control edit_required" id="edit_check_no">
	  	</div>
	  	<div class="form-group">
	  		<label for="">Check Date</label>
	  		<input type="text" class="form-control date edit_required" id="edit_check_date">
	  	</div>
  	</div>

  	</form>
  		
</div>

<script>
	var xhr = "";
	var oTable = "";

	$.fn.dataTableExt.oApi.fnStandingRedraw = function(oSettings) {
	    //redraw to account for filtering and sorting
	    // concept here is that (for client side) there is a row got inserted at the end (for an add)
	    // or when a record was modified it could be in the middle of the table
	    // that is probably not supposed to be there - due to filtering / sorting
	    // so we need to re process filtering and sorting
	    // BUT - if it is server side - then this should be handled by the server - so skip this step
	    if(oSettings.oFeatures.bServerSide === false){
	        var before = oSettings._iDisplayStart;
	        oSettings.oApi._fnReDraw(oSettings);
	        //iDisplayStart has been reset to zero - so lets change it back
	        oSettings._iDisplayStart = before;
	        oSettings.oApi._fnCalculateEnd(oSettings);
	    }
	    //draw the 'current' page
	    oSettings.oApi._fnDraw(oSettings);
	};
	
	var app ={
		init:function(){			
			
			$('.date').date(now());

			$('#date_from').date_from({
				now : '2016-01-01',
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
				paid_status: $('input[name="paid_status"]:checked').val(),
			}
			
			$.post('<?php echo base_url().index_page();?>/accounting_entry/payable_list/get_cumulative',$post,function(response){
				$('.table_content').html(response);
				datatable_option.bSort = false;
				datatable_option.iDisplayLength = 50;
				oTable = $('.myTable').dataTable(datatable_option);
			}).error(function(){				
				$('.table_content').content_loader('false');                      
			});

		},bindEvent:function(){

			$('input[name="inlineRadioOptions"]').on('change',function(){

				if($('input[name="inlineRadioOptions"]:checked').val() == "check"){
					$('#content-check').removeClass('hidden');
					$('#content-cash').addClass('hidden');
				}else{
					$('#content-check').addClass('hidden');
					$('#content-cash').removeClass('hidden');
				}
			});
			$('input[name="edit_inlineRadioOptions"]').on('change',function(){

				if($('input[name="edit_inlineRadioOptions"]:checked').val() == "check"){
					$('#edit_content-check').removeClass('hidden');
					$('#edit_content-cash').addClass('hidden');
				}else{
					$('#edit_content-check').addClass('hidden');
					$('#edit_content-cash').removeClass('hidden');
				}

			});

			
			$('input[name="inlineRadioOptions"]').trigger('change');
			$('input[name="edit_inlineRadioOptions"]').trigger('change');

			$('body').on('click','.update',this.update);
			$('body').on('click','.edit',this.edit);
			$('body').on('click','.delete',this.deleted);


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
		},update:function(){

			var me = $(this);
			var po_no = me.closest('tr').find('.po_no').text();
			var total_amount = me.closest('tr').find('.total_amount').text();
			var check_date   = me.closest('tr').find('.po_date').text();

			$( '#po_no' ).html(po_no);
			$( '#amount' ).html(total_amount);
			$('#amount_paid').val(remove_comma(total_amount));

			$('#radio-check').attr('checked','checked');
			$('input[name="inlineRadioOptions"]').trigger('change');

			$('#invoice_number').val('');
			$('#check_no').val('');
			$('#check_date').val(check_date);
			
			$( "#dialog1" ).dialog({
			  title : 'Pay Payable',
		      modal: true,
		      buttons: {
		        Proceed: {
		        	"class": "btn btn-primary btn-sm",
		        	"text" : "Proceed",
		        	click :function(){

		        	if($('input[name="inlineRadioOptions"]:checked').val() == 'cash'){
		        		if($('.required1').required()){
		        			alert('Fill up Required Fields');
			        		return false;
			        	}
		        	}else{		        				        		
			        	if($('.required').required()){
		        			alert('Fill up Required Fields');
			        		return false;
			        	}
		        	}

		        	var bool = confirm('Are you sure?');
		        	if(!bool){
		        		return false;
		        	}

		        	$post = {
						bank_id        : $('#bank option:selected').val(),
						bank_name      : $('#bank option:selected').text(),
		        		invoice_number : $('#invoice_number').val(),
		        		invoice_date   : $('#invoice_date').val(),
						check_no       : $('#check_no').val(),
						check_date     : $('#check_date').val(),
						type           : $('input[name="inlineRadioOptions"]:checked').val(),
						affiliate      : $('#affiliate option:selected').text(),
						po_id          : me.attr('data-id'),
						status         : me.attr('data-value'),
						amount_paid    : $('#amount_paid').val(),
						proceed        : 'false',
						paid_status    : $('input[name="paid_status"]:checked').val(),
		        	};

        	        if(xhr && xhr.readystate != 4){
        	            xhr.abort();
        	        }

					$.save({appendTo : 'body'});
					xhr = $.post('<?php echo base_url().index_page();?>/accounting_entry/payable_list/update_status',$post,function(response){
						switch($.trim(response)){
							case "1":
								alert('Successfully Updated');
								app.get_classification_setup();
								if($post.status == 'unpaid'){
									me.closest('td').html('<label class="label label-success" data-id="'+$post.po_id+'" data-value="paid">paid</label>');
								}else{
									me.closest('td').html('<label class="label label-warning update" data-id="'+$post.po_id+'" data-value="unpaid">unpaid</label>');
								}								
								$.save({action : 'success',reload : 'false'});
								$('#dialog1').dialog( "close" );

							break;
							case "exist":

								var bool = confirm('This will save as Additional Invoice.');
								if(!bool){
									$.save({action : 'hide',reload : 'false'});
									$('#dialog1').dialog( "close" );	
									return false;
								}
								
								$post.proceed = 'true';
								$.post('<?php echo base_url().index_page();?>/accounting_entry/payable_list/update_status',$post,function(response){
									switch($.trim(response)){
										case "1":
											alert('Successfully Updated');
											app.get_classification_setup();
											if($post.status == 'unpaid'){
												me.closest('td').html('<label class="label label-success" data-id="'+$post.po_id+'" data-value="paid">paid</label>');
											}else{
												me.closest('td').html('<label class="label label-warning update" data-id="'+$post.po_id+'" data-value="unpaid">unpaid</label>');
											}

											$.save({action : 'success',reload : 'false'});
											$('#dialog1').dialog( "close" );	
										break;
										default:
											alert('Failed to updated');
										break;

									}


								});

							break;
							default:
								alert('Failed to updated');
							break;
						}
											

					});
				
		        		    	
		        },
		    	},		        
		      }
		    });

		/*

			var bool = confirm('Update Status?');
			if(!bool){
				return false;
			}
			var me = $(this);
			$post = {
				po_id  : $(this).attr('data-id'),
				status : $(this).attr('data-value'),
			};

			$.post('<?php echo base_url().index_page();?>/accounting_entry/payable_list/update_status',$post,function(response){
				switch($.trim(response)){
					case "1":
						alert('Successfully Updated');
						if($post.status == 'unpaid'){
							me.closest('td').html('<label class="label label-success update" data-id="'+$post.po_id+'" data-value="paid">paid</label>');
						}else{
							me.closest('td').html('<label class="label label-warning update" data-id="'+$post.po_id+'" data-value="unpaid">unpaid</label>');
						}
					break;
					default:
						alert('Failed to updated');
					break;
				}
			});
		*/
			
		},edit:function(){
			
			var me = $(this);
			$post = {
				receipt_id : me.attr('data-receipt_id'),
				journal_id : me.attr('data-journal_id'),
				po_id      : me.attr('data-id'),
			};

			$.post('<?php echo base_url().index_page();?>/accounting_entry/payable_list/edit',$post,function(response){
				console.log(response);

			var po_no        = response.po_main.reference_no;
			var total_amount = response.po_main.total_cost;
			var check_date   = response.po_main.po_date;

			$( '#edit_po_no' ).html(po_no);
			$( '#edit_amount' ).html(remove_comma(total_amount));
			$('#edit_amount_paid').val(response.journal_main._amount);

			$('#radio-check').attr('checked','checked');
			$('input[name="inlineRadioOptions"]').trigger('change');

			$('#edit_invoice_number').val(response.receiving_main.supplier_invoice);
			$('#edit_check_no').val(response.journal_details[1].check_no);
			$('#edit_check_date').val(check_date);
			
			$( "#dialog2" ).dialog({
			  title : 'Pay Payable',
		      modal: true,
		      buttons: {
		        Proceed: {
		        	"class": "btn btn-primary btn-sm",
		        	"text" : "Update",
		        	click :function(){

		        	if($('input[name="edit_inlineRadioOptions"]:checked').val() == 'cash'){
		        		if($('.required1').required()){
		        			alert('Fill up Required Fields');
			        		return false;
			        	}
		        	}else{		        				        		
			        	if($('.edit_required').required()){
		        			alert('Fill up Required Fields');
			        		return false;
			        	}
		        	}

		        	var bool = confirm('Are you sure?');
		        	if(!bool){
		        		return false;
		        	}

		        	$post = {
						bank_id        : $('#bank option:selected').val(),
						bank_name      : $('#bank option:selected').text(),
		        		invoice_number : $('#invoice_number').val(),
		        		invoice_date   : $('#invoice_date').val(),
						check_no       : $('#check_no').val(),
						check_date     : $('#check_date').val(),
						type           : $('input[name="inlineRadioOptions"]:checked').val(),
						affiliate      : $('#affiliate option:selected').text(),
						po_id          : me.attr('data-id'),
						receipt_id     : me.attr('data-receipt_id'),
						journal_id     : me.attr('data-journal_id'),
						amount_paid    : $('#edit_amount_paid').val(),
						proceed        : 'false',
						journal_id     : me.attr('data-journal_id'),
		        	};
		        	
					$.save({appendTo : 'body'});
					$.post('<?php echo base_url().index_page();?>/accounting_entry/payable_list/do_edit',$post,function(response){
						switch($.trim(response)){
							case "1":
								alert('Successfully Updated');
								app.get_classification_setup();
								if($post.status == 'unpaid'){
									me.closest('td').html('<label class="label label-success" data-id="'+$post.po_id+'" data-value="paid">paid</label>');
								}else{
									me.closest('td').html('<label class="label label-warning update" data-id="'+$post.po_id+'" data-value="unpaid">unpaid</label>');
								}								
								$.save({action : 'success',reload : 'false'});
								$('#dialog2').dialog( "close" );	
							break;
							case "exist":

								var bool = confirm('This will save as Additional Invoice.');
								if(!bool){
									$.save({action : 'hide',reload : 'false'});
									$('#dialog2').dialog( "close" );	
									return false;
								}
								
								$post.proceed = 'true';
								$.post('<?php echo base_url().index_page();?>/accounting_entry/payable_list/update_status',$post,function(response){
									switch($.trim(response)){
										case "1":
											alert('Successfully Updated');
											app.get_classification_setup();
											if($post.status == 'unpaid'){
												me.closest('td').html('<label class="label label-success" data-id="'+$post.po_id+'" data-value="paid">paid</label>');
											}else{
												me.closest('td').html('<label class="label label-warning update" data-id="'+$post.po_id+'" data-value="unpaid">unpaid</label>');
											}
											$.save({action : 'success',reload : 'false'});
											$('#dialog2').dialog( "close" );	
										break;
										default:
											alert('Failed to updated');
										break;

									}


								});

							break;
							default:
								alert('Failed to updated');
							break;
						}					
					});
						        		    
		        },
		    	},		        
		      }
		    });
			
			},'json');

		},deleted:function(){
			var bool = confirm('Are you sure to Delete?');
			if(!bool){
				return false;
			}			
			var me = $(this);
			var index = me.closest('tr').get(0);

			$post = {
				receipt_id : me.attr('data-receipt_id'),
				journal_id : me.attr('data-journal_id'),
				po_id      : me.attr('data-id'),
			};

			$.post('<?php echo base_url().index_page();?>/accounting_entry/payable_list/delete',$post,function(response){											
				oTable.fnDeleteRow(oTable.fnGetPosition(index));			
			});

		}
	};



	$(function(){		
		app.init();
	});

</script>