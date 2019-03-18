<style>
	.myTable tbody tr:hover{
		cursor: pointer;
	}
</style>

<div class="header">
	<div class="container">
	
	<div class="row">
		<div class="col-md-8">
			<h2>Daily Equipment Checklist <small>Daily Checklist</small></h2>			
		</div>
		<div class="col-md-4">
				<div class="btn-group pull-right " style="margin-top:5px;">
					  <a href="<?php echo base_url().index_page(); ?>/maintenance/daily_checklist/" class="btn btn-primary  ">Transaction Form</a>
					  <a href="<?php echo base_url().index_page(); ?>/maintenance/daily_checklist/cumulative" class="btn btn-primary active">Cumulative Data</a>	  
				</div>
		</div>
	</div>
	

	</div>
</div>

<div class="container">	

<form action="" method="post" id="form">

	<div class="content-title">
		<h3>Cumulative Data</h3>
	</div>
	
<?php echo $this->extra->alert(); ?>

	<div class="row">
		<div class="col-md-4">
				  <div class="panel panel-default">		
				  <div class="panel-body">
				  			  														
			  			<div class="form-group">			  				
					  		<div class="control-label">Project</div>				  		
					  		<select name="project" id="project" class="form-control"></select>
			  			</div>

			  			<div class="form-group">			  				
					  		<div class="control-label">Profit Center</div>				  		
					  		<select name="profit_center" id="profit_center" class="form-control"></select>
			  			</div>
			  								  	
				  </div>
				  </div>
		</div>

		<div class="col-md-8">
					<div class="panel panel-default">		
					  <div class="panel-body">
					  			  			
					  		<div class="form-group">
					  			<div class="control-label">Display</div>
					  			<div class="radio">
					  				<input type="radio" name="filter"  id="all" value='all'><label for="all">All</label></input>
					  			</div>
					  			<div class="radio">
					  				<input type="radio" name="filter"  id="month" checked value='month'><label for="month">Month</label></input>
					  				<input type="text" class="date">
					  			</div>
					  			<input type="button" class="btn btn-primary" id="apply_filter" value="Apply Filter">
				  			</div>				  		
					  </div>
					</div>

		</div>

	</div>
	
	<div class="content-title" style="margin-top:0px">
		<h3>Transaction List</h3>
	</div>

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">	
					<div class="table-content table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Transaction Date</th>
									<th>Operator</th>
									<th>Shift</th>
									<th>Remarks</th>
									<th>Equipment Name</th>
									<th>Item Name</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
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


</form>

</div>

<script type="text/javascript">

	var app = {
		init:function(){					
			this.bindEvents();	

			var option = {
				profit_center : $('#profit_center')
			}

			$('#project').get_projects(option);			
								
			$('.date').date();

		},bindEvents:function(){
			$('#apply_filter').on('click',this.cumulative);
							
		},cumulative:function(){
			$post = {
				type : $('input[name=filter]:checked').val(),
				date : $('.date').val(),
			};
			$('.table-content').content_loader(true);
			$.post('<?php echo base_url().index_page(); ?>/maintenance/daily_checklist/cumulative_data',$post,function(response){
				$('.table-content').html(response);
				$('.myTable').dataTable(datatable_option);
				$('#apply_filter').removeClass('disabled');
			});					
		},cumulative_details:function(){


		}

	}

$(function(){
	app.init();

	$('.myTable tbody tr').ben_popover({
		url : '<?php echo base_url().index_page(); ?>/maintenance/daily_checklist/cumulative_details'
	});

});
</script>