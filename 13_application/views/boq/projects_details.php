
<?php foreach($get_projects as $row): ?>
	<tr>
		<td>			
			<a href="javascript:void(0)" class="boq-project" data-project-id="<?php echo $row['project_id'];?>">
				<div class="t1"><strong><?php echo $row['project_name']; ?></strong> - <?php echo $row['project_location']; ?></div>
	
			</a>
		</td>
		<td class="number"><?php echo $this->extra->number_format($row['boq']); ?></td>
		<td class="number"><?php echo $this->extra->number_format($row['actual']); ?></td>
		<?php 
				$discrepancy = 0;
				$remaining   = 0;

				$result = $row['boq'] - $row['actual'];

				if($result > 0){
					$remaining = $result;
					
				}else{
					$discrepancy = $result;
				}
		 ?>		 
		<td class="number"><?php echo $this->extra->number_format($remaining); ?></td>
		<td class="number"><?php echo $this->extra->number_format($discrepancy); ?></td>
	</tr>
<?php endforeach; ?>
		