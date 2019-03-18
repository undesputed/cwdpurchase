<div class="content-page">
	<div class="content">
		<div class="header">
				<h2>Billing Statement</h2>	
		</div>


		<div style="margin-top:5px">
				<ul class="nav nav-tabs" role="tablist">
				    <li><a href="<?php echo base_url().index_page(); ?>/accounting_entry/billing_statement">Entry</a></li>
				    <li class="active"><a href="<?php echo base_url().index_page(); ?>/accounting_entry/billing_statement/cumulative">Cumulative Data</a></li>
			  	</ul>
		 </div>
		
		<div class="container">			
			<div class="panel panel-default" style="margin-top:5px">		
			  <div class="panel-body">
			  		<div class="row">
			  			<div class="col-md-4">
				  			<div class="form-group" style="display:none">
						  			<div class="control-label">Company Name</div>
						  			<select name="" id="create_project" class="form-control input-sm"></select>
						  	</div>

					  		<div class="form-group">
					  			<label class=" control-label">Project Site</label>
					  			<div class="">
					  				<select name="" id="create_profit_center" class="form-control input-sm"></select>
					  			</div>
					  		</div>
				  		</div>
				  		<div class="col-md-4">
							<div class="checkbox">
				  				<label for="display_all"><input type="checkbox" id="display_all"> Display All</label>
				  			</div>
				  			<div class="form-groupn inline">
				  				<label class=" control-label">Date From:</label>
				  				<input type="text" id="date_from" class="form-control" style="width:100px">
				  			</div>
				  			<div class="form-group inline">
				  				<label class=" control-label">Date to:</label>
				  				<input type="text" id="date_to" class="form-control" style="width:100px">
				  			</div>					  				
				  		</div>
				  		<div class="col-md-2">
				  			<button id="filter" class="btn btn-success" style="margin-top:10px">Apply Filter</button>
				  		</div>
				  	</div>
			  </div>
			</div>
			
		
		<div class="table-responsive" id="table-content">
			
		</div>

		<div style="margin-top:2em;"></div>
</div>
<script>
	var xhr = "";


	var v_app = {
		init:function(){

			this.bindEvent();
			$('.date').date();


			$('#date_from').date_from({
					now : '<?php echo date("Y-m-01") ?>',
				});
			$('#date_to').date_to();


			$('#invoice_date').date({
				url : 'get_invoice_max_id',
				dom : $('#invoice_no'),
				event : 'change',
			});

			var option = {
				profit_center : $('#create_profit_center'),
				main_office : true,
			}			

			$('#create_project').get_projects(option);

			$.signatory({
				type          : 'pr',
				prepared_by   : 'sesssion',				
			});

		},bindEvent:function(){			

			$('#filter').on('click',this.filter);
			$('#display_all').on('change',function(){
				if($('#display_all').is(':checked')){
					$('#date_from,#date_to').prop('disabled',true);
				}else{
						$('#date_from,#date_to').prop('disabled',false);
				}
			});
			$('#display_all').trigger('change');

		},filter:function(){
	        if(xhr && xhr.readystate != 4){
	            xhr.abort();
	        }
	        $post = {
	        	project_id : $('#create_profit_center option:selected').val(),
	        	all : $('#display_all').is(':checked'),
	        	from : $('#date_from').val(),
	        	to   : $('#date_to').val(),
	        }
	        $('#table-content').html('Loading...');
			xhr = $.post('<?php echo base_url().index_page();?>/accounting_entry/billing_statement/view_cumulative',$post,function(response){
				$('#table-content').html(response);
			});
		}
	}
	
	$(function(){
		v_app.init();
	});
</script>