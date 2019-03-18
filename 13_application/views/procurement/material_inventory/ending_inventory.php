<div class="header">
	<h2>Ending Inventory <small> Inventory</small></h2>	
</div>

<div class="container">
	<div class="content-title">
		<h3>Menu Option</h3>
	</div>
	<div class="panel panel-default">		
	  <div class="panel-body">
	  		<div class="row">
	  			<div class="col-md-1">
	  				<div class="form-group">
				  		<div class="control-label">Category</div>
				  		<div class="radio">
				  			<input type="radio" name="inventory" value="materials" id="materials" checked="checked"><label for="materials" >Materials</label>
				  		</div>
				  		<div class="radio">
				  			<input type="radio" name="inventory" value="lubes" id="lubes"><label for="lubes">Lubes</label>
				  		</div>				  		
				  		<div class="radio">
				  			<input type="radio" name="inventory" value="tires" id="tires"><label for="tires">Tires</label>
				  		</div>				  		
		  			</div>		  				  	
	  			</div>
	  			<div class="col-md-2">
	  					<div class="form-group">
					  		<div class="control-label">Date</div>					  		
					  		<input type="text" name="" id="date">					  		
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
	    				<th>SKU</th>
	    				<th>Name/Description</th>
	    				<th>Unit</th>
	    				<th>Beginning</th>
	    				<th>Received Qty</th>
	    				<th>Withdrawn Qty</th>
	    				<th>Current Qty</th>
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
			$('#date').date();
			
			this.bindEvents();			
			
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
				date     : $('#date').val(),
				category : $('input[name="inventory"]:checked').val(),
			}
				
				$('.table-content').content_loader(true);				
				$.post('<?php echo base_url().index_page()?>/procurement/material_inventory/get_inventory',$post,function(response){
					$('.table-content').html(response);						
					$('.myTable').dataTable(datatable_option);
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