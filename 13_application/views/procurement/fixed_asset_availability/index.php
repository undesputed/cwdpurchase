<div class="header">
	<h2>Fixed Asset Availability</h2>
</div>

<div class="container">	
	<div class="row">
		<div class="col-md-2 ">
			<div class="panel panel-default sidebar" style="margin-top:10px" data-spy="affix" data-offset-top="100" data-offset-bottom="10">
			 	<div class="panel-body" style="overflow:auto">
					<ul class="ul-sidebar">
						<?php foreach($category as $row): ?>
							<li><a href="javascript:void(0)" class="equipment" data-id="<?php echo $row['ITEM NO']; ?>"><?php echo $row['ITEM DESCRIPTION']; ?> <span class="pull-right"><?php echo $row['cnt']; ?></span></a></li>
						<?php endforeach; ?>
					</ul>	    
			    </div>			   					
			</div>
		</div>		
		<div class="col-md-10 data-content" style="margin-bottom:5em">
			
		</div>
	</div>		
</div>

<script>
	$(function(){

		var app = {
			init:function(){
				this.bindEvent();
			},bindEvent:function(){
				$('.equipment').on('click',this.details);
			},
			details:function(){
				
				$post = {
					id   : $(this).attr('data-id'),
					name : $(this).text(),
				}

				$('.data-content').content_loader('true');

				$.post('<?php echo base_url().index_page();?>/procurement/fixed_asset_availability/get_details',$post,function(response){
					$('.data-content').html(response);
				});

			}



		}
		app.init();


	});	
	
</script>