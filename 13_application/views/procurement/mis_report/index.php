<div class="header">
	<h2>MIS Report</h2>	
</div>

<input type="hidden" id="hdn_date">
<div class="container">

<div class="row">
	<div class="col-md-2">
		
	</div>
	<div class="col-md-10">
		<div class="panel panel-default" style="margin-top:2em;">		
		  <div class="panel-body">
		  	<div class="row">				
				<div class="col-md-6">
					<div>
						<div class="radio-inline">
							<input type="text" id="from" class="date">
							<input type="text" id="to" class="date">
						</div>						
						<div class="radio-inline">
							<button class="btn btn-primary btn-sm" id="apply">Apply Filter <span class="fa"></span></button>
						</div>
					</div>
				</div>
			</div>
		  </div>	 
		</div>
		

		<section id="request-content">
			
		</section>

	</div>
	
</div>

</div>

<script>
	

	$(function(){
		

		var app = {
			init:function(){
				$('#from').date_from();
				$('#to').date_to();

				this.bindEvents();
				this.get_request_list();
			},
			bindEvents:function(){
				$('#apply').on('click',this.get_request_list);
			},
			get_request_list:function(){			
				var button  = $(this);
				button.addClass('disabled');
				button.find('span').addClass('fa-spin fa-spinner');
				$post = {
					from : $('#from').val(),
					to   : $('#to').val(),
				}
				$('#request-content').content_loader('true');
				$.post('<?php echo base_url().index_page();?>/procurement/mis_report/get_mis_report',$post,function(response){					
					$('#request-content').html(response);
					$('.myTable').dataTable(datatable_option);

					button.removeClass('disabled');
					button.find('span').removeClass('fa-spin fa-spinner');

				});

			}

		}

		app.init();
	});


</script>