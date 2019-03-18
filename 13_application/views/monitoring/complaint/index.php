<div class="header">
	<h2>Complaint History <small>Checklist Complaint</small></h2>	
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
		  			<hr>

					<div class="form-group">
				  		<div class="control-label">Checklist </div>
				  		<div class="checkbox col-md-2">
				  			<input type="checkbox" id="all_checklist"><label for="all_checklist">All</label>
				  		</div>

				  		<div class="col-md-10">
				  			<select name="" id="checklist" class="form-control"></select>				  			
				  		</div>
				  		
		  			</div>
		  			
	  			</div>
	  			<div class="col-md-3">	  				

					<div class="unit-content">
		  				<div class="form-group">
					  		<div class="control-label">Equipment</div>				  					  	
					  		<select class="form-control" id="unit">
					  		<?php foreach($unit as $row): ?>
								<option value="<?php echo $row['equipment_id']; ?>"  data-model="<?php echo $row['equipment_model']; ?>" data-chassis="<?php echo $row['equipment_chassisno'] ?>" data-smr="<?php echo $row['equipment_smr']; ?>"><?php echo $row['equipment_brand'] ?></option>
					  		<?php endforeach; ?>
					  		</select>
			  			</div>
			  			<div class="form-group">
					  		<div class="control-label">Model</div>				  		
					  		<input type="text" class="form-control" id="model_no">
			  			</div>
			  			<div class="form-group">
					  		<div class="control-label">Size</div>				  		
					  		<input type="text" class="form-control" id="serial_name">
			  			</div>
			  			<div class="form-group">
					  		<div class="control-label">SMR</div>				  		
					  		<input type="text" class="form-control" id="smr">
			  			</div>		  			
					</div>

	  			</div>

	  			<div class="col-md-2">
	  				<!-- <div class="checkbox"><input type="checkbox"><label for="">NO. Uncheck</label></div> -->
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
						<th>Equipment Scope</th>
						<th>Equipment Name</th>
						<th>Body No.</th>
						<th>Operator</th>
						<th>PM Model</th>
						<th>PM Code</th>
						<th>PM Hours</th>
						<th>SMR	PM Date</th>
	    			</tr>
	    		</thead>
	    		<tbody>
	    			<tr>
	    				<td colspan="8">Empty Result</td>	    				
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

			$('[name="filter"],#all_unit').on('change',this.hide_dates);

			$('#all_checklist').on('change',this.hide_dates);


		},hide_dates:function(){

			if($('[name="filter"]:checked').val()=="all"){
				$('.dates').hide();
			}else{
				$('.dates').show();
			}

			if($('#all_unit').is(':checked')){
				$('.unit-content').hide();
			}else{
				$('.unit-content').show();
			}

			
			$('#checklist').prop({'disabled':$('#all_checklist').is(':checked')});
			


		},assign_unit:function(){
			var $this = $("#unit option:selected");
			var json;
			$.post('<?php echo base_url().index_page() ?>/monitoring/unplanned/get_model_no',{model_id : $this.attr('data-model')},function(response){
					json = response;

					var attr = {
						serial_name  : $this.attr('data-chassis'),
						model_no     : json.CODE,
						smr          : $this.attr('data-smr'),
					}

					$.each(attr,function(i,val){				
						$('#'+i).val(val);
					});


			},'json');

			app.get_checklist();


		},get_checklist:function(){
			$post = {
				unit : $('#unit option:selected').val(),
			}
			$.post('<?php echo base_url().index_page(); ?>/monitoring/complaint/get_checklist',$post,function(response){
					var option = {
						json : response,
						attr : {
								value : 'id',
								text : 'type',
						}				
					}
					$('#checklist').select(option);
			},'json');

		},apply_filter:function(){

			$post = {				
				date_from : $('#from_date').val(),
				date_to   : $('#to_date').val(),
				location  : $('#profit_center option:selected').val(),
				unit      : ($('#all_unit').is(':checked'))? '%' : $('#unit option:selected').val(),
				type      : $('[name="filter"]:checked').val(),
				all       : $('#all_checklist').is(':checked'),
			}
			

				$('.table-content').content_loader(true);				
				$.post('<?php echo base_url().index_page();?>/monitoring/complaint/apply_filter',$post,function(response){
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