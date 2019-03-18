
	
	<section id="sidebar">
		
		<div class="row">
			<div class="col-md-12">
				<div class="content-title" style="margin-left:15px">
					<h3>Options</h3>
				</div>
					
				<ul class="sidebar-ul">	
					<li><a href="javascript:void(0)" id="mining">Mining Operations</a></li>
					<li><a href="javascript:void(0)" id="etc">Drivers / Trucks/ Drop Survey</a></li>
					<li><a href="javascript:void(0)" id="total">Total Barging/Mining/Equipment</a></li>
					<li class="selected-date"><a href="<?php echo base_url().index_page(); ?>/manage_report/upload/production" id="production">Production Report</a></li>
				</ul>
			</div>
		</div>
		
	</section>
		
	<section class="page-content-wrapper">
	
		<div id="content">	
		<?php echo $this->extra->alert(); ?>	
			<div class="row">
				<div class="col-md-4">
					<div class="container main-content">
						<div class="content-title">
							<h3>Mine Production</h3>
						</div>
						
								<form id="form1" method="post" action="" enctype="multipart/form-data">
									<div class="panel panel-default">		
									  <div class="panel-body">
									  		<div class="form-group">
									  			<div class="control-label">Import Mining Report</div>
									  			<input type="hidden" name="type" value="mine">
									  			<input type="hidden" name="chker" value="1">
									  			<input type="file" name="file" >
									  		</div>
									  		<input type="submit" class="btn btn-primary" value="Submit">
									  </div>	 
									</div>
								</form>
							
						
					</div>
				</div>
				<div class="col-md-4">
					<div class="container main-content">
						<div class="content-title">
							<h3>Shipment Operations</h3>
						</div>
						
								<form id="form1" method="post" action="" enctype="multipart/form-data">
									<div class="panel panel-default">		
									  <div class="panel-body">
									  		<div class="form-group">
									  			<div class="control-label">Import Shipment Operations</div>
									  			<input type="hidden" name="type" value="shipment">
									  			<input type="hidden" name="chker" value="1">
									  			<input type="file" name="file">
									  		</div>
									  		<input type="submit" class="btn btn-primary" value="Submit">
									  </div>	 
									</div>
								</form>
							
						
					</div>
				</div>

			</div>


		</div>
				
	</section>
	
<script>
$(function(){
	var app = {
		init:function(){
			this.bindEvent();
			this.has_selected();		
		},
		bindEvent:function(){						
			$('.sidebar-ul li').on('click',this.selected);			
		},selected:function(){
			$('.sidebar-ul li').removeClass('selected-date');
			$(this).addClass('selected-date');
			var page = $(this).find('a').attr('id');
			app.get_data(page);			
		},get_data:function(page){
			$post = {
				page : page,
			};
			$('.main-content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/manage_report/page',$post,function(response){
				$('.main-content').html(response);
			});			
		},has_selected:function(){
			if(!$('.sidebar-ul li').hasClass('selected-date')){
				$('.sidebar-ul li:first').addClass('selected-date');
				var page = $('.sidebar-ul li:first').find('a').attr('id');
				app.get_data(page);
			}
		}
		
	};		
		app.init();
	});
	
</script>











