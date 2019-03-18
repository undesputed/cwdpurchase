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
	<h2>Mine Operation  <small>Production</small></h2>	
</div>

<div class="container">	
	<div class="row">
		<div class="col-md-2 ">
			<div class="panel panel-default sidebar" style="margin-top:10px" data-spy="affix" data-offset-top="100" data-offset-bottom="10">
			 	<div class="panel-body">

			 		<div style="padding:5px 0px">
			 			<small>From</small>
			 			<input type="text" id="date_from" class="date form-control input-sm">
					</div>

					<div style="padding:5px 0px">
						<small>To</small>
			 			<input type="text" id="date_to" class="date form-control input-sm">
					</div>

					<div style="padding:5px 0px">
						<select name="" id="owner" class="form-control input-sm">
							<option value="%">All</option>
							<option value="INHOUSE">Inhouse</option>
							<option value="SUBCON">Subcon</option>
						</select>
					</div>

					<div style="padding:5px 0px">
						<select name="" id="type" class="form-control input-sm">
							<option value="mine">Mine</option>
							<option value="barging">Barging</option>
						</select>
					</div>				

					

					<div>
			    		<button id="search" class="btn btn-primary btn-sm pull-right">Search <span class="fa"></span></button>
			    	</div>
			    	<div class="clearfix"></div>
								    
			    </div>
			   					
			</div>
		</div>		
		<div class="col-md-10 mine-content" style="margin-bottom:5em">
			
		</div>
	</div>
		
</div>

<script>
		
		var xhr = new Array();
		var app = {
			init:function(){
				$('#date_from').date_from({
					now : '<?php echo date("Y-m-01") ?>',
				});
				$('#date_to').date_to();
				app.mine_operation();
				this.bindEvents();
			},
			bindEvents:function(){
				$('#search').on('click',this.mine_operation);
			},
			mine_operation:function(){
								
				$('.sidebar-table').onePageNav();

				$('#dv-sidebar').content_loader('true');
				$('.mine-content').content_loader('true');

				$('#search').addClass('disabled');
				$('#search').find('span').addClass('fa-spin fa-spinner');


				$get = {
					date_from      : $('#date_from').val(),
					date_to        : $('#date_to').val(),
					owner          : $('#owner option:selected').val(),
					type           : $('#type option:selected').val(),							
				};

				$.get('<?php echo base_url().index_page(); ?>/production_report/get_operation',$get,function(response){

					$('.mine-content').html(response);
					/*$('#dv-sidebar').html(response.sidebar);*/
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