<div class="header">
	<h2>Project Operating Hours <small>Equipment Operation</small></h2>
</div>

<div class="container">
	<div class="content-title">
		<h3>Menu Option</h3>
	</div>
	<div class="panel panel-default">		
	  <div class="panel-body">
	  		<div class="row">
	  			<div class="col-xs-4">
	  				<div class="form-group" style="display:none">
				  		<div class="control-label">Project</div>
				  		<select name="" id="project" class="form-control"></select>
				  		
		  			</div>
		  			<div class="form-group">
				  		<div class="control-label">Profit Center</div>				  		
				  		<select id="profit_center" class="form-control" ></select>
		  			</div>
	  			</div>
	  			<div class="col-xs-2">
	  				<div class="form-group">
				  		<div class="control-label">Date</div>
				  		<div class="input-group">
				  			<input type="text" class="form-control date">
				  			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				  		</div>
		  			</div>	
	  			</div>

	  			<div class="col-xs-2">
	  				<input type="submit" id="apply_filter" class="btn btn-primary btn-block nxt-btn" Value="Apply Filter">
	  			</div>

	  		</div>		  	
	  </div>

	    <div class="table-content">
		    <table class="table">
		    	<thead>
		    		<tr>
		    			<th>Unit No.</th>
		    			<th>ProgHrs</th>
		    			<th>SMR Beg</th>
		    			<th>SMR End</th>
		    			<th>Total</th>
		    			<th>Remarks</th>
		    		</tr>
		    	</thead>
		    	<tbody>
		    		<tr>
		    			<td colspan="6">Empty Result</td>
		    		</tr>		 
		    	</tbody>
		    </table>
	  </div>

	</div>


</div>
<script type="text/javascript">
	var app = {
		init:function(){
			$('.date').date();
			var option = {
				profit_center : $('#profit_center')
			}

			$('#project').get_projects(option);	
			this.bindEvents();

		},bindEvents:function(){
			$('#apply_filter').on('click',this.apply_filter);

		},apply_filter:function(){

				$post = {				
					location  : $('#profit_center option:selected').val(),
					date      : $('.date').val(),
				}
				
				$('.table-content').content_loader(true);
				$.post('<?php echo base_url().index_page()?>/monitoring/operation/apply_filter',$post,function(response){
					$('.table-content').html(response);	
					$('#apply_filter').removeClass('disabled');
				}).error(function(){
					alert('Error');
					$('.table-content').content_loader(false);
				});

		}
	}

	$(function(){
		app.init();		
	});

</script>
