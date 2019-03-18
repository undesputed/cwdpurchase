<div class="header">
	<h2>Vessel Summary</h2>
</div>
<div class="container">
<div class="row">
	<div class="col-md-2">
		<div class="panel panel-default sidebar" style="margin-top:10px" data-spy="affix" data-offset-top="100" data-offset-bottom="10">		
		  <div class="panel-body">
		  	<table class="table table-condensed">
		  		<thead>
		  			<th>Month</th>
		  			<th>Complete</th>
		  		</thead>
		  		<tbody>
		  	<?php 
		  		$count = array();
		  		$cnt = 0;
		  		foreach($sidebar as $key=>$row): 
		  		$cnt++;
		  		$count[$cnt] = count($row);
		  	?>
		  			
		  		<tr>
		  			<td><?php echo $key; ?></td>
		  			<td><?php echo $count[$cnt]; ?></td>
		  		</tr>	
		  	<?php endforeach; ?>
				<tr>
					<td>Total</td>
					<td><?php echo array_sum($count); ?></td>
				</tr>
		  	 </tbody>
		  	 </table>
		  </div>	 
		</div>
	</div>
	
	<div class="col-md-10">
				<?php
								
				$dates = array();
				$month = array();
				$date = '';
				$bool = false;
				for ($i=0; $i < count($result); $i++):

				if(in_array($result[$i]['draft_date'], $dates))
				{
					$date = '';
				}else{
					$dates[] = $result[$i]['draft_date'];
					$date = date('M d Y',strtotime($result[$i]['draft_date']));
					
				}

				if(in_array(date('F',strtotime($result[$i]['draft_date'])),$month))
				{
					$bool = false;
				}else
				{
					$bool = true;
					if($i>0 && $bool == true)
					{
			?>				
					</tbody>
				</table>
			<?php 
					}
					
					$month[] = date('F',strtotime($result[$i]['draft_date']));
			?>
					<div class="content-title">
						<h3><?php echo date('F',strtotime($result[$i]['draft_date'])) ?></h3>
					</div>
					<table class="table table-condensed">
					<tbody>					
			<?php
				}

				$width = ($result[$i]['running_total'] / $result[$i]['vessel_capacity'] ) * 100;
				$width = "width:".$width."%";

			?>						
				<tr>
					<td style="width:120px"><?php echo $date; ?></td>
					<td style="width:700px">
						<p>
							<?php echo $result[$i]['vessel_name']; ?> - <small> <?php echo $result[$i]['Day'] ?> day(s) </small>
							<span style="color:#666;" class="pull-right"><?php echo $result[$i]['running_total']; ?>/<?php echo $result[$i]['vessel_capacity']; ?></span>							
						</p>						
						<div class="progress progress-sm">
							<div class="progress-bar" role="progressbar" style="<?php echo $width;?>"></div>
						</div>
					</td>
					<td></td>					
				</tr>
				

		<?php 

		endfor; 

		?>


	</div>

</div>			
	
</div>