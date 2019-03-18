<style>
	tr.clickable{
		cursor:pointer;
	}
</style>




<table class="table table-hover" id="cumulative">
	<thead>
		<tr>
			<th>Reference No</th>
			<th>Trans Date</th>
			<th>Project</th>
			<th>Project Type</th>
			<th>Type</th>
			<th>Reference No</th>
			<th>Amount</th>			
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($result as $row): ?>
			<tr>
				<td><a href="javascript:void(0)" class="clickable" data-journal_id="<?php echo $row['journal_id']; ?>"><?php echo $row['Reference No'];?></a></td>
				<td><?php echo $row['Trans Date'];?></td>
				<td><?php echo $row['Project Name']; ?></td>
				<td><?php echo $row['pay_item']; ?></td>
				<td><?php echo $row['Type'];?></td>
				<td><?php echo $row['Memo'];?></td>
				<td><?php echo $this->extra->number_format($row['_amount']); ?></td>
				<td>
					<div class="dropdown">
						<a href="javascript:void(0)" data-toggle="dropdown" aria-expanded="true">Action</a>
						  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
						    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0)" data-journal_id="<?php echo $row['journal_id']; ?>" class="cancel-event">Cancel</a></li>						    
						  </ul>
					</div>
				</td>				
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>