<div class="header">
	<h2>Tire History <small>Tire Monitoring</small></h2>	
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
				  		<select name="" id="project" class="form-control"></select>
				  		
		  			</div>
		  			<div class="form-group">
				  		<div class="control-label">Profit Center</div>				  		
				  		<select id="profit_center" class="form-control" ></select>
		  			</div>
	  			</div>
	  			<div class="col-md-3">
	  				<div class="form-group">
				  		<div class="control-label">Branding No</div>				  		
				  		<select class="form-control" id="unit">
				  		<?php foreach($unit as $row): ?>
							<option value="<?php echo $row['equipment_id']; ?>" data-name="<?php echo $row['equipment_description'] ?>" data-chassis="<?php echo $row['equipment_chassisno'] ?>" data-brand="<?php echo $row['equipment_brand']; ?>" data-fulltank = "<?php echo $row['equipment_fulltank'] ?>"><?php echo $row['equipment_platepropertyno'] ?></option>
				  		<?php endforeach; ?>
				  		</select>
		  			</div>
		  			<div class="form-group">
				  		<div class="control-label">Brand No.</div>				  		
				  		<input type="text" class="form-control" id="brand_no">
		  			</div>
		  			<div class="form-group">
				  		<div class="control-label">Tire Name</div>				  		
				  		<input type="text" class="form-control" id="tire_name">
		  			</div>
		  			<div class="form-group">
				  		<div class="control-label">Size</div>				  		
				  		<input type="text" class="form-control" id="size">
		  			</div>
	  			</div>

	  			<div class="col-md-2">

	  				<div class="checkbox" style="display:none">
	  					<input type="checkbox" id="retired"><label for="retired">Retired</label>
	  				</div>
	  				
		  			<div class="form-group">
		  				<div class="radio-inline">
		  					<input type="radio" name="filter" id="all" value="all"><label for="all">All</label>
		  				</div>
		  				<div class="radio-inline">
		  					<input type="radio" checked name="filter" id="month" value="month"><label for="month">Month</label>
		  				</div>
		  			</div>
					
					<div class="dates">
					<div class="form-group">
				  		<div class="control-label"><small class="text-muted">From</small></div>						
				  		<div class="input-group">
				  			<input type="text" name="from_date" id="from_date" class="form-control date">
				  			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				  		</div>
		  			</div>

		  			<div class="form-group">
				  		<div class="control-label"><small class="text-muted">To</small></div>						
				  		<div class="input-group">
				  			<input type="text" name="to_date" id="to_date" class="form-control date">
				  			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				  		</div>
		  			</div>
		  			</div>

	  			</div>

	  			<div class="col-md-2">
	  				<input type="submit" id="apply_filter" class="btn btn-primary btn-block nxt-btn" Value="Apply Filter">
	  			</div>	  			
	  		</div>		  	
	  </div>

	    <div class="table-content">	
	    	<table class="table">
	    		<thead>
	    			<tr>
	    				<th>Install Date</th>
	    				<th>Install No.</th>
	    				<th>Instal Ref.</th>
	    				<th>Install SMR</th>
	    				<th>Install Depth</th>
	    				<th>Pullout Date</th>
	    				<th>Pullout No.</th>
	    				<th>Pullout Ref.</th>
	    				<th>Pullout SMR</th>
	    				<th>Pullout Depth</th>
	    				<th>Update Date</th>
	    				<th>Update No.</th>
	    				<th>Update Ref.</th>
	    				<th>Update SMR</th>
	    				<th>Update Depth</th>
	    				<th>Checked By</th>
	    			</tr>
	    		</thead>
	    		<tbody>
	    			<tr>
	    				<td colspan="16">Empty Result</td>	    				
	    			</tr>
	    		</tbody>
	    	</table>

	    </div>	  
	</div>
</div>

<script type="text/javascript">


	var app = {
		init:function(){

			$('#from_date').date_from();	
			$('#to_date').date_to();	

			var option = {
				profit_center : $('#profit_center')
			}			
			$('#project').get_projects(option);			
					
			this.bindEvents();
			
			this.assign_unit();
			this.hide_dates();
			
		},bindEvents:function(){

			$('#apply_filter').on('click',this.apply_filter);
			$('#unit').on('change',this.assign_unit);

			$('[name="filter"]').on('change',this.hide_dates);

		},hide_dates:function(){
			if($('[name="filter"]:checked').val()=="all"){
				$('.dates').hide();
			}else{
				$('.dates').show();
			}

		},assign_unit:function(){
			var $this = $("#unit option:selected");
			var attr = {
				brand_no  : $this.attr('data-brand'),
				tire_name : $this.attr('data-name'),
				size      : $this.attr('data-fulltank'),
			}

			$.each(attr,function(i,val){				
				$('#'+i).val(val);
			});


		},apply_filter:function(){

			$post = {				
				date_from : $('#from_date').val(),
				date_to   : $('#to_date').val(),
				location  : $('#profit_center option:selected').val(),
				unit      : $('#unit option:selected').val(),
				type      : $('[name="filter"]:checked').val(),
				retired   : $('#retired').is(':checked'),
			}
			
				$('.table-content').content_loader(true);				
				$.post('<?php echo base_url().index_page()?>/monitoring/tire/apply_filter',$post,function(response){
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