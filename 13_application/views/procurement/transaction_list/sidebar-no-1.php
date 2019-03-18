
<div class="center-menu">
	<span class="page-title"></span>	
		<?php 
			echo $view;
		?>
</div>

<div class="right-menu">
	<?php echo $this->lib_transaction->transaction_info(); ?>
</div>

<script>
	$(function(){
		
		var title1  = $('#sidebar-menu').find('.subdrop.active').find('span:first').html();
		var title2  = $('#sidebar-menu').find('.subdrop.active').closest('li').find('ul').find('.active').find('span:first').html();
		var title3  = $('#sidebar-menu').find('.active').find('span:first').html();
		var title4  = $('#sidebar-menu').find('.subdrop.active').closest('li').find('ul').find('.active').html();
		if(typeof(title1) != 'undefined' && typeof(title2) != 'undefined')
		{
			$('.page-title').html(title1 +" > "+title2);	
		}else if(typeof(title3) !='undefined' && typeof(title1) == 'undefined')
		{
			$('.page-title').html(title3);	
		}else{
			$('.page-title').html(title1 +" > "+title4);	
		}
			
	});
</script>