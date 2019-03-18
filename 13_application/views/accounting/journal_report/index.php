
<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Journal Posting</h2>
</div>

<div class="container">

	<input type="hidden" id="cv_id" value="">	
	<input type="hidden" id="date" value="">	

	<div class="row">				
		<div class="col-md-12">

			<div class="content-title">
					<h3>Journal Report</h3>
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
						
						<!-- <div class="col-md-2">
								<div class="form-group">
						  			<div class="control-label">Display By</div>
						  			<select name="" id="display_by" class="form-control input-sm">
						  				<option value="today">Today</option>
						  				<option value="month">This Month</option>
						  				<option value="year">This Year</option>
						  			</select>
						  		</div>
						</div> -->

						<div class="col-md-1">
								<div class="radio">
									<input type="radio" id="posted" value="POSTED" name="status" ><label for="posted">Posted</label>
								</div>
								<div class="radio">
									<input type="radio" id="active" value="ACTIVE" name="status" checked="checked"><label for="active">UnPosted</label>
								</div>
						</div>
						<div class="col-md-2">
								<button id="filter" class="btn btn-success nxt-btn">Apply Filter</button>
						</div>
						<div class="col-md-3">
								<button id="save" class="btn pull-right btn-primary nxt-btn">SAVE POSTED</button>
						</div>

					</div>
			  </div>
				<div class="table_content table-responsive">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Post?</th>
								<th>Status</th>
								<th>Reference No.</th>
								<th>Transaction Date</th>
								<th>Type</th>
								<th>Memo</th>
								<th>Location</th>
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
				status     : $('input[name="status"]:checked').val(),
			}

			if($post.status!="ACTIVE"){
				$('#save').addClass('disabled');
			}else{
				$('#save').removeClass('disabled');
			}
			

			$.post('<?php echo base_url().index_page();?>/accounting_entry/journal_report/get_cumulative',$post,function(response){
				$('.table_content').html(response);
				datatable_option['bSort'] = false;
				$('.myTable').dataTable(datatable_option);

			}).error(function(){
				alert('Service Unavailable');
				$('.table_content').content_loader('false');			
			});

		},bindEvent:function(){

			$('#filter').on('click',this.apply_filter);
			$('body').on('click','.chk-header',this.chk_all);
			$('#save').on('click',this.save);

		},apply_filter:function(){
			app.get_classification_setup();
		},chk_all:function(){

			var checked = $(this).is(':checked');
			var rows = $(".myTable").dataTable().fnGetNodes();
			var length = rows.length;

			for(var i=0; i<length; i++){					
				$(rows[i]).find('.chk-post').prop('checked',checked);
		    }
			
		},save:function(){


			var rows = $(".myTable").dataTable().fnGetNodes();
			var content = new Array();			
			var length  = rows.length;
			var bool    = false;
			for(var i=0; i<length; i++){

				if($(rows[i]).find('.chk-post').is(':checked')){
					var data = {
						journal_id : $(rows[i]).find('.chk-post:checked').attr('data-journal'),
					}
					content.push(data);
						bool = true;
				}

			}

			if(!bool){
				alert("There is no checked journal ");
				return false;
			}

			var confirmation = confirm('Are you sure to Proceed?');
			if(!confirmation){
				return false;
			}
			
			$.save();

			$post = {
				details : content,				
			}					
			
			$.post('<?php echo base_url().index_page();?>/accounting_entry/journal_report/save_journal',$post,function(response){
				switch(response){
					case"1":						
						app.get_classification_setup();
						$.save({action : 'success'});
					break;
					default:
						app.get_classification_setup();
						$.save({action : 'hide'});
					break;
				}
				

			});

		}

	};

	$(function(){		
		app.init();
	});
</script>