

<div class="container">
	<div class="row">		
		<div class="col-md-9">		
			<iframe src="<?php echo base_url();?>asset/pdf/viewer.html?file=<?php echo $row['file'] ?>" frameborder="0" style="width:100%;height:550px"></iframe>
		</div>
		
		<div class="col-md-3">
			<h4><?php echo $row['subject'] ?></h4>			
				<hr>
			<h5><?php echo $row['caption'] ?></h5>			
		</div>
	</div>
</div>