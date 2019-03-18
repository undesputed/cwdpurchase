<div class="content-title">
	<h3>Mining </h3>	
</div>
<small class="text-muted">Mining Operations</small>

<h4>Mine to Stockyard</h4>
<section id="mine-stockyard">
	<div class="row">
		<div class="col-md-4">
					<div class="form-group">
			  			<div class="control-label">Total Amount / Tons</div>
			  			<input name="" id="stockyard-amount" class="form-control input-sm stock-clear" placeholder="Enter Amount Gathered">
				  	</div>
		</div>
		<div class="col-md-2">
					<div class="form-group">
			  			<div class="control-label">Date</div>
			  			<input name="" id="stockyard-date" class="date form-control input-sm " placeholder="Save Date">
				  	</div>
		</div>
		<div class="col-md-3">
					<button id="stockyard-save" class="btn btn-primary btn-sm nxt-btn">Save</button>
		</div>
	</div>
	
</section>

<h4>Mine to Port</h4>
<section id="mine-stockyard">
	<div class="row">
		<div class="col-md-4">
					<div class="form-group">
			  			<div class="control-label">Total Amount / Tons</div>
			  			<input name="" id="port-amount" class="form-control input-sm port-clear" placeholder="Enter Amount Gathered">
				  	</div>
		</div>

		<div class="col-md-2">
					<div class="form-group">
			  			<div class="control-label">Date</div>
			  			<input name="" id="port-date" class="date form-control input-sm" placeholder="Save Date">
				  	</div>
		</div>	

		<div class="col-md-3">
					<button id="port-save" class="btn btn-primary btn-sm nxt-btn">Save</button>
		</div>

	</div>
	
</section>

<section id="table">
	<div class="row">
		<div class="col-md-7">
			<div class="panel panel-default">
			<div class="panel-body"></div>			
			<table id="datable" class="table table-condensed table-hover">
			<thead>
				<tr>
					<th>Type</th>
					<th>Amount</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($mining as $row): ?>
				<tr>
					<td><?php echo $row['type'] ?></td>
					<td><?php echo $row['amount'] ?></td>
					<td><?php echo $row['date'] ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
			</div>
		</div>
	</div>		
</section>


<script>
	
	$(function(){
		$('.date').date();
		$('#datable').dataTable(datatable_option);		
		var app_mining = {	
			type : 'mine-stockyard',
			init:function(){
				this.bindEvents();
			},bindEvents:function(){
				$('#stockyard-save').on('click',this.save);
			},save:function(){
				if($('.stock-clear').val()==""){
					alert('Invalid Input');
					return false;
				}

				$post = {
					amount : $('#stockyard-amount').val(),
					date   : $('#stockyard-date').val(),
					type   : app_mining.type,
				};		

				$.post('<?php echo base_url().index_page();?>/manage_report/mining/save',$post,function(response){
					alert('Successfully Save');
					$('.stock-clear').val('');
					location.reload(true);
				});
			}
		}

		var app_port = {	
			type : 'mine-port',
			init:function(){
				this.bindEvents();
			},bindEvents:function(){
				$('#port-save').on('click',this.save);
			},save:function(){
				if($('.port-clear').val()==""){
					alert('Invalid Input');
					return false;
				}
				$post = {
					amount : $('#port-amount').val(),
					date   : $('#port-date').val(),
					type   : app_port.type,
				};		

				$.post('<?php echo base_url().index_page();?>/manage_report/mining/save',$post,function(response){
					alert('Successfully Save');
					$('.port-clear').val('');
					location.reload(true);
				});
				
			}
		}

		app_mining.init();
		app_port.init();

	});


</script>