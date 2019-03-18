<div class="header">
	<h2>Project Consumption Report <small>Equipment Consumption</small></h2>	
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
		  				<div class="control-label">Address : </div>
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
	  				<div class="form-group">
	  					<div class="control-label">Display Type</div>
	  					<div class="radio">
	  						<input type="radio" name="display_type" id="amount" checked value="amount"><label for="amount">Amount</label>
	  					</div>
	  					<div class="radio">
	  						<input type="radio" name="display_type" id="quantity" value="quantity"><label for="quantity">Quantity</label>
	  					</div>
	  				</div>
	  			</div>
	  			<div class="col-md-2">
	  				<input type="submit" id="apply_filter" class="btn btn-primary btn-block nxt-btn" Value="Apply Filter">
	  			</div>	  			
	  		</div>		  	
	  </div>

	    <div class="table-content" >

	    	<table class="table">
	    		<thead>
	    			<tr>
	    				<th>UNIT</th>
	    				<th></th>
	    				<th></th>
	    				<th></th>
	    				<th></th>
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

		},apply_filter:function(){

			$post = {
				type      : $('[name="display_type"]:checked').val() ,
				date_from : $('#from_date').val(),
				date_to   : $('#to_date').val(),
				location  : $('#profit_center option:selected').val()
			}
			
				$('.table-content').content_loader(true);				
				$.post('<?php echo base_url().index_page()?>/monitoring/consumption/apply_filter',$post,function(response){
					$('.table-content').html(response);	
					$('#apply_filter').removeClass('disabled');
				});
			
			

		}

	}

$(function(){
	app.init();
});
</script>