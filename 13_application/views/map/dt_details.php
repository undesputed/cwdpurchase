<div class="content-title">
	<h3>Logs</h3>
</div>

<div class="container">
	<h3><?php echo $dt_name; ?></h3>

	<ul class="nav nav-tabs">
	  <li class="active"><a href="#ds" data-toggle="tab">Day Shift</a></li>
	  <li><a href="#ns" data-toggle="tab">Night Shift</a></li>
	  <li><a href="#logs" data-toggle="tab">Logs</a></li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
	  <div class="tab-pane active" id="ds">
	  		<div class="table-responsive">
	  		<table id="tbl-dayshift" class="table table-hover table-nowrap">
				<thead>
					<tr>
						<th>Trip No</th>
						<th>From</th>
						<th>Start</th>
						<th>To</th>										
						<th>End</th>
						<th>Cycle Time</th>
						<th>Operation</th>
						<th>Elapsed Time</th>
					</tr>
					<tbody>
						<?php $cnt = 0; foreach($dt['shift']['DS'] as $row): $cnt++; ?>
						<tr>
							<td><?php echo $cnt; ?></td>
							<td><?php echo $row['from']; ?></td>
							<td><?php echo $row['start_time']; ?></td>
							<td><?php echo $row['to']; ?></td>														
							<td><?php echo $row['end_time']; ?></td>
							<td><?php echo $row['cycle_time'] ?></td>
							<td><?php echo $row['operation'] ?></td>
							<td><?php echo $row['est']; ?></td>
						</tr>
						<?php endforeach; ?>			
					</tbody>
					<tfoot>
						<tr>
							<td>
								<div><strong><?php echo $cnt; ?></strong></div>								
								<div><strong><?php echo $dt['shift']['ds_total_barging']; ?></strong></div>
								<div><strong><?php echo $dt['shift']['ds_total_production']; ?></strong></div>
							</td>
							<td>
								<div><strong>Total Trips</strong></div>
								<div><strong>Barging</strong></div>
								<div><strong>Production</strong></div>
							</td>
							
							
							<td>
								<div><strong>WMT</strong></div>
								<div><?php echo $dt['shift']['ds_total_barging_wmt']; ?></div>
								<div><?php echo $dt['shift']['ds_total_production_wmt']; ?></div>
							</td>
							<td>

							</td>
							<td>
								<div><strong>Total Cycle Time</strong></div>
								<div><strong>Avg Barging Cycle Time</strong></div>
								<div><strong>Avg Production Cycle Time</strong></div>
							</td>
							<td>
								<div><?php echo $dt['total_day_time']; ?></div>
								<div><?php echo $dt['avg']['DS']['BARGING']." Minutes"; ?></div>
								<div><?php echo $dt['avg']['DS']['PRODUCTION']." Minutes"; ?></div>
							</td>
							<td></td>
							<td></td>
						</tr>						
					</tfoot>
				</thead>		
			</table>
			</div>

	  </div>
	  <div class="tab-pane" id="ns">
	  		<div class="table-responsive">
	  		<table id="tbl-nightshift" class="table table-hover table-nowrap">
				<thead>
					<tr>
						<th>Trip No</th>
						<th>From</th>
						<th>To</th>
						<th>Start</th>
						<th>End</th>
						<th>Cycle Time</th>
						<th>Operation</th>
						<th>Elapsed Time</th>
					</tr>
					<tbody>
						<?php $cnt = 0; foreach($dt['shift']['NS'] as $row): $cnt++; ?>
						<tr>
							<td><?php echo $cnt; ?></td>
							<td><?php echo $row['from']; ?></td>
							<td><?php echo $row['start_time']; ?></td>
							<td><?php echo $row['to']; ?></td>
							<td><?php echo $row['end_time']; ?></td>
							<td><?php echo $row['cycle_time'];?></td>
							<td><?php echo $row['operation']; ?></td>
							<td><?php echo $row['est']; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<td>
								<div><strong><?php echo $cnt; ?></strong></div>								
								<div><strong><?php echo $dt['shift']['ns_total_barging']; ?></strong></div>
								<div><strong><?php echo $dt['shift']['ns_total_production']; ?></strong></div>
							</td>
							<td>
								<div><strong>Total Trips</strong></div>
								<div><strong>Barging</strong></div>
								<div><strong>Production</strong></div>
							</td>							
							<td>
								<div><strong>WMT</strong></div>
								<div><?php echo $dt['shift']['ns_total_barging_wmt']; ?></div>
								<div><?php echo $dt['shift']['ns_total_production_wmt']; ?></div>
							</td>
							<td>
														
							</td>
							<td>
								<div><strong>Total Cycle Time</strong></div>
								<div><strong>Avg Barging Cycle Time</strong></div>
								<div><strong>Avg Production Cycle Time</strong></div>
							</td>
							<td>
								<div><?php echo $dt['total_night_time']; ?></div>
								<div><?php echo $dt['avg']['NS']['BARGING']." Minutes"; ?></div>
								<div><?php echo $dt['avg']['NS']['PRODUCTION']." Minutes"; ?></div>
							</td>
							<td></td>
							<td></td>
						</tr>						
					</tfoot>
				</thead>		
			</table>	
			</div>
	  </div>
	<div class="tab-pane" id="logs">
		<div class="table-responsive">
		<table id="tbl_logs" class="table table-condensed table-hover tbl-event table-nowrap">
			<thead>
				<tr>
					<th>Tag id</th>
					<th>Location Log</th>
					<th>Date</th>
					<th>Time</th>
					<!-- <th>Action</th> -->
				</tr>
			</thead>
			<tbody>
				<?php foreach($logs as $row): ?>
				<tr>					
					<td><?php echo $row['tag_id']; ?></td>
					<td><?php echo $row['device_name']; ?></td>
					<td><?php echo date('M d',strtotime($row['time_in'])); ?></td>
					<td><?php echo date('h:i:s A',strtotime($row['time_in'])); ?></td>
					<!-- <td><a href="javascript:void(0)" onclick="update_status(this,'<?php  echo $row['id']; ?>')" class="activate" data-id="<?php echo $row['id']; ?>"><?php echo $row['status'] ?></a></td> -->
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		</div>
	</div>

	</div>
			
</div>

<script type="text/javascript">
	$('#tbl_logs').dataTable(datatable_option);
	$('#tbl-dayshift').dataTable(datatable_option1);
	$('#tbl-nightshift').dataTable(datatable_option1);

	var update_status = function(dom,id){
			$post = {
				id :id,
			};
			
			$.post('<?php echo base_url().index_page();?>/map/update_status',$post,function(response){
				$(dom).html(response);
			});
	}

	
</script>