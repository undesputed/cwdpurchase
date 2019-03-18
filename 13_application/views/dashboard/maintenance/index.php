<div class="header">
	<h2>Maintenance Update</h2>	
</div>

<div class="container">
	<div class="content-title">
		<h3>Menu Option</h3>
	</div>
	<div class="panel panel-default">		
	  <div class="panel-body">
	  		<div class="row">
	  			<div class="col-md-4">
	  				<div class="form-group">
				  		<div class="control-label">Project</div>
				  		<select name="" id="project" class="form-control" style="display:none"></select>
				  		<select id="profit_center" class="form-control" ></select>
		  			</div>
		  			<div class="form-group">
		  				<div class="control-label">Address : <span id="address"></span> </div>
		  			</div>
	  			</div>
	  			<div class="col-md-2">
	  				<div class="form-group">
				  		<div class="control-label">Date</div>
						<small style="color:#ccc">from</small>
				  		<div class="input-group">
				  			<input type="text" name="from_date" id="from_date" class="form-control date">
				  			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				  		</div>
						<small style="color:#ccc">to</small>
				  		<div class="input-group" style="margin-top:3px">
				  			<input type="text" name="to_date" id="to_date" class="form-control date">
				  			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				  		</div>
		  			</div>	
	  			</div>
	  			<div class="col-md-2">
	  				<input type="submit" id="apply_filter" class="btn btn-primary btn-block nxt-btn" Value="Apply Filter">
	  			</div>	  			
	  		</div>		  	
	  </div>

		<!--TAB-->
			<ul class="nav nav-tabs nav-custom">
			  <li class="active"><a href="#equipment" data-toggle="tab">Equipment</a></li>
			  <li><a href="#summary" data-toggle="tab">Summary</a></li>			
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
			  <div class="tab-pane active" id="equipment">

			  			    <div class="table-content content-equipment" >

						    	<table class="table">
						    		<thead>
						    			<tr>
						    				<th>DESCRIPTION</th>
						    				<th>TARGET</th>
						    				<th>ACTUAL</th>
						    				<th>VARIANCE(MT)</th>
						    				<th>ATTAIMENT(%)</th>
						    			</tr>
						    		</thead>
						    		<tbody>
						    			<tr>
						    				<td colspan="5">Empty Result</td>
						    			</tr>
						    		</tbody>
						    	</table>

					    	</div>
							
			  </div>
			  <div class="tab-pane" id="summary">
			  	
							 <div class="table-content content-summary" >

						    	<table class="table">
						    		<thead>
						    			<tr>
						    				<th>DESCRIPTION</th>
						    				<th>TARGET</th>
						    				<th>ACTUAL</th>
						    				<th>VARIANCE(MT)</th>
						    				<th>ATTAIMENT(%)</th>
						    			</tr>
						    		</thead>
						    		<tbody>
						    			<tr>
						    				<td colspan="5">Empty Result</td>
						    			</tr>
						    		</tbody>
						    	</table>

					    	</div>
	
			  </div>			
			</div>

		<!--/TAB-->

	</div>
</div>

<script type="text/javascript">


	var app = {
		init:function(){

			$('#from_date').date_from()
			$('#to_date').date_to();

			var option = {
				profit_center : $('#profit_center')
			}			
			$('#project').get_projects(option);			
						

			this.bindEvents();
						

		},bindEvents:function(){

			$('#apply_filter').on('click',this.apply_filter);
			$('#profit_center').on('change',function(){
				$('#address').html($('#profit_center option:selected').attr('data-to'));
			});
			$('#profit_center').trigger('change');

		},apply_filter:function(){

			$post = {				
				date_from : $('#from_date').val(),
				date_to   : $('#to_date').val(),
				location  : $('#profit_center option:selected').val(),
				project_effectivity : $('#profit_center option:selected').attr('data-effectivity'),
			}
			
				$('.content-equipment').content_loader(true);
				$('.content-summary').content_loader(true);
				$.post('<?php echo base_url().index_page()?>/dashboard/maintenance/apply_filter',$post,function(response){
					//$('.table-content').html(response);
					 $('.content-equipment').html(response.equipment);	
					 $('.content-summary').html(response.summary);	
					$('#apply_filter').removeClass('disabled');
				},'json');
					
		}

	}

$(function(){
	app.init();
});
</script>