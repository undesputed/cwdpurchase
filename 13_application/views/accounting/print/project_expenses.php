<style>
	table{
		font-size: 11px !important;
	}
</style>


<div id="wrapper" style="width:100%;">
	<div class="container">
		<div class="row">			
			<div class="col-xs-8">
				<h2 class="center-text title"><?php echo $header['title']; ?></h2>
				<div class="round padding  dark"><span class="center-text" style="border-bottom:1px solid #fff;display:block"><?php echo $header['sub_title'] ?></span></div>
				<div class="round padding center-text margin-top bold">Project Expenses From <?php echo $from." - ".$to; ?></div>			
			</div>		
			<div class="col-xs-4">

				<div style="display:block;height:60px;margin-top:2em">
					<span style="display:block" class="center-text"><?php echo $header['address']; ?></span>
					<span style="display:block" class="center-text"><?php echo $header['contact']; ?></span>
				</div>

			</div>
		</div>

		<?php echo $table; ?>

	</div>
</div>

