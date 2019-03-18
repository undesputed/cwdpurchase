<style>
	.cal-date img{
		height:40px;
		width:40px;
		margin-top:-9px;
	}
	#tbl-data-ns tbody td,#tbl-data tbody td,{
		font-size:14px;
	}
	#tbl-data-ns tbody tr:hover,#tbl-data tbody tr:hover{
		font-weight: bold;
	}

</style>

<div class="header">
	<h2>Drivers Dispatch Monitoring</h2>	
</div>

<div class="container">	
	<div class="row">
		<div class="col-md-2 ">
			<div class="panel panel-default sidebar" style="margin-top:10px" data-spy="affix" data-offset-top="100" data-offset-bottom="10">
			 	<div class="panel-body">
			 		<div style="padding:5px 0px">
			 			<input type="text" class="date form-control input-sm">
					</div>
					<div style="padding:5px 0px">
						<select name="" id="dispatch_type" class="form-control input-sm">
							<option value="all">All</option>
							<option value="not_dispatch">Not Dispatch</option>
							<option value="dispatch">Dispatch</option>
						</select>
					</div>
					<div style="padding:5px 0px">
						<select name="" id="shift" class="form-control input-sm">
							<option value="ds">Dayshift</option>
							<option value="ns">Night Shift</option>
						</select>
					</div>				

					<div>						
						<div class="radio-inline">
							<input type="radio" class="" name="radio" id="mine" value="mine"><label for="mine">Mine</label>
						</div>
						
						<div class="radio-inline">
							<input type="radio" class="" name="radio" id="port" checked value="port"><label for="port">Port</label>
						</div>						
					</div>
					<div>
			    		<button id="search" class="btn btn-primary btn-sm pull-right">Search <span class="fa"></span></button>
			    	</div>
			    	<div class="clearfix"></div>
					<hr>
			    	<div id="dv-sidebar">
			    		
			    	</div>

			    	
			    </div>
			   					
			</div>
		</div>		
		<div class="col-md-10 driver-content" style="margin-bottom:5em">
			
		</div>
	</div>
		
</div>

<script>
		
		var xhr = new Array();
		var app = {
			init:function(){
				$('.date').date();
				app.driver_content();
				this.bindEvents();


			},
			bindEvents:function(){
				$('#search').on('click',this.driver_content);
			},
			driver_content:function(){
								
				$('.sidebar-table').onePageNav();

				$('#dv-sidebar').content_loader('true');
				$('.driver-content').content_loader('true');

				$('#search').addClass('disabled');
				$('#search').find('span').addClass('fa-spin fa-spinner');

				$get = {
					date           : $('.date').val(),
					dispatch_type  : $('#dispatch_type option:selected').val(),
					equipment_type : $('#equipment_type option:selected').val(),
					shift          : $('#shift option:selected').val(),
					department     : $('input[name="radio"]:checked').val(),					
				};

				$.get('<?php echo base_url().index_page(); ?>/dispatch/get_drivers_details',$get,function(response){

					$('.driver-content').html(response.content);
					$('#dv-sidebar').html(response.sidebar);
					/*$('#tbl-employee').dataTable(datatable_option);*/
					$('#search').removeClass('disabled');
					$('#search').find('span').removeClass('fa-spin fa-spinner');

				},'json').error(function(){
					alert('Internal Server Error');
					$('#search').removeClass('disabled');
					$('#search').find('span').removeClass('fa-spin fa-spinner');
				});

			}
			
		}

	$(function(){
		app.init();
	});

</script>