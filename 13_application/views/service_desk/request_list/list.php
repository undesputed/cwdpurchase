	<?php 
	$base_url = base_url().index_page();	
	?>
	



	<input type="hidden" id="date">
	

	<section id="sidebar">		
		<div class="row">
			<div class="col-md-12">
					<ul class="sidebar-ul">
						<li><a href="<?php echo base_url().index_page(); ?>/service_desk/create">Create Request</a></li>
						<li><a data-toggle="collapse" href="#collapseOne">Request List <span class="pull-right sidebar-icon fa"></span></a>
							<div id="collapseOne" class="panel-collapse collapse in">

								<ul class="sub-menu">
									<li class="active"><a href="<?php echo $base_url?>/service_desk/in_house">In House <span class="pull-right label label-warning">20</span></a></li>
									<li><a href="<?php echo $base_url?>/service_desk/job_out">Job Out</a></li>
								</ul>

							</div>
						</li>
					</ul>
			</div>
		</div>	
	</section>
		
	<section class="page-content-wrapper">
	<div id="content">		
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="table-content">
					<table class="table">
						<thead>
							<tr>
								<th width="20px">Status</th>
								<th>Subject</th>
								<th>Body No</th>
								<th>Mechanic</th>
								<th>Date</th>
							</tr>
						</thead>
						<tbody>
					
						</tbody>
					</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	</section>

	
<script>
	var app = {
		init:function(){
			$('#date').date();
			$('#time').timepicker();			
			$('#body_no').chosen();
			app.get_list();
			this.bindEvent();
		},bindEvent:function(){
			$('.date').on('change',this.get_ref);
			$('#operator').on('keyup',function(){
				//alert('test');
					$('#body_no option:eq(2)').prop('selected',true);
					$('#body_no').trigger("chosen:updated");
			});

			$('#save').on('click',function(){	
					$.save();
					delay(function(){
						$.save({action : 'hide'});
					},2000)
			});
		},get_list:function(){

			$get = {
				date : $('#date').val(),
			};

			$('.table-content').content_loader(true);
			$.get('<?php echo base_url().index_page()?>/service_desk/get_list',$get,function(response){
				$('.table-content').html(response);
			});
		}
	}

$(function(){
	app.init();
	$('.details').custom_pop();
});

</script>











