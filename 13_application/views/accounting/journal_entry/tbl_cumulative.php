<style>
	tr.clickable{
		cursor:pointer;
	}
</style>




<table class="table table-hover" id="cumulative">
	<thead>
		<tr>
			<th>Status</th>
			<th>Reference No</th>
			<th>Trans Date</th>
			<th>Project</th>
			<th>Particulars</th>			
			<th>Amount</th>			
			<th>Encoded By</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($result as $row): ?>
			<tr>
				<td><?php echo $this->extra->label_journal($row['status']);?></td>
				<td><a href="javascript:void(0)" class="clickable" data-journal_id="<?php echo $row['journal_id']; ?>"><?php echo $row['Reference No'];?></a></td>
				<td><?php echo $row['Trans Date'];?></td>
				<td><?php echo $row['Project Name']; ?></td>
				<td><?php echo $row['particulars']; ?></td>				
				<td><?php echo $this->extra->number_format($row['_amount']); ?></td>
				<td><?php echo $row['username'];?></td>
				<td>
					<?php if($row['status']=='ACTIVE'): ?>
					<div class="dropdown">
						<a href="javascript:void(0)" data-toggle="dropdown" aria-expanded="true">Action</a>
						  <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu1">
						  	<li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo site_url('accounting_entry/journal_entry?edit='.$row['journal_id']); ?>" data-journal_id="<?php echo $row['journal_id']; ?>" class="edit-event">Edit</a></li>
						  	<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0)" data-journal_id="<?php echo $row['journal_id']; ?>" class="posting-event">Posting</a></li>
						  	<li class="divider"></li>
						    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0)" data-journal_id="<?php echo $row['journal_id']; ?>" class="cancel-event">Cancel</a></li>
						  </ul>
					</div>
					<?php endif; ?>
				</td>				
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>