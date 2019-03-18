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
				<div class="round padding center-text margin-top bold"><?php echo $project_name.' '.strtoupper($payment); ?> VOUCHERING SUMMARY FROM <?php echo $from.' - '.$to; ?></div>			
			</div>
		
			<div class="col-xs-4">

				<div style="display:block;height:60px;margin-top:2em">
					<span style="display:block" class="center-text"><?php echo $header['address']; ?></span>
					<span style="display:block" class="center-text"><?php echo $header['contact']; ?></span>
				</div>

			</div>

		</div>
		<?php echo $table; ?>
	<!-- <table id="item-table" >
		<thead>
			<tr class="border">
				<th>PO #</th>
				<th>Date</th>
				<th>Total Amount</th>
				<th>Project</th>
				<th>SI #</th>
				<th>SI Date</th>
				<th>Check #</th>
				<th>SI Amount</th>
				<th>Due Date</th>
				<th>Balance</th>
				<th>CV #</th>
				<th>Remarks</th>
			</tr>
		</thead>
		<tbody>
			<tr class="border">
				<th class="center-text">&nbsp;</th>
				<th class="center-text">&nbsp;</th>
				<th class="center-text">&nbsp;</th>
				<th class="center-text">&nbsp;</th>
				<th class="center-text">&nbsp;</th>
				<th class="center-text">&nbsp;</th>
				<th class="center-text">&nbsp;</th>
				<th class="center-text">&nbsp;</th>
				<th class="center-text">&nbsp;</th>
				<th class="center-text">&nbsp;</th>
				<th class="center-text">&nbsp;</th>
				<th class="center-text">&nbsp;</th>
			</tr>
		</tbody>
	</table> -->
	



	</div>
</div>

