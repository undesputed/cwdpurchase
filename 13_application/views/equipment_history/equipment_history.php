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
	<h2>Equipment History</h2>	
</div>

<div class="container">	
	<div class="row">
		<div class="col-md-2 ">
			<div class="panel panel-default sidebar" style="margin-top:10px" data-spy="affix" data-offset-top="100" data-offset-bottom="10">
			 	<div class="panel-body">
			 		<div style="padding:5px 0px">
			 			<select name="" id="equipment_list" class="form-control input-sm">

			 				<?php foreach($group as $key=>$row): ?>
								<optgroup label="<?php echo $key; ?>">
								<?php foreach($row as $data): ?>
									<option value="<?php echo $data; ?>"><?php echo $data; ?></option>		
								<?php endforeach; ?>
								</optgroup>
			 				<?php endforeach; ?>							
			 			</select>
					</div>
			 		<div style="padding:5px 0px">
			 			<input type="text" id="date_from" class="date form-control input-sm">
					</div>

					<div style="padding:5px 0px">
			 			<input type="text" id="date_to" class="date form-control input-sm">
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
				$('#date_from').date_from();
				$('#date_to').date_to();
				//app.driver_content();
				this.bindEvents();
			},
			bindEvents:function(){
				$('#search').on('click',this.driver_content);
			},
			driver_content:function(){
								
				$('.sidebar-table').onePageNav();

				/*$('#dv-sidebar').content_loader('true');*/
				$('.driver-content').content_loader('true');

				$('#search').addClass('disabled');
				$('#search').find('span').addClass('fa-spin fa-spinner');

				$get = {
					date_from  : $('#date_from').val(),
					date_to    : $('#date_to').val(),
					unit  : $('#equipment_list option:selected').val(),
				};

				$.post('<?php echo base_url().index_page(); ?>/equipment_history/get_details',$get,function(response){

					$('.driver-content').html(response);
					//$('#dv-sidebar').html(response.sidebar);
					/*$('#tbl-employee').dataTable(datatable_option);*/
					$('#search').removeClass('disabled');
					$('#search').find('span').removeClass('fa-spin fa-spinner');

				}).error(function(){
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