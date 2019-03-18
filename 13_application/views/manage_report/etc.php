<div class="content-title">
	<h3>Drivers / Trucks/ Drop Survey</h3>	
</div>

<small class="text-muted">Dashboard Reporting Setup</small>
<h4>Drivers</h4><a href="javascript:void(0)" class="add-row" id="add-driver">add</a> <a href="javascript:void(0)" class="remove-row" id="remove-driver">remove</a>
<section id="drivers">
	<table class="table" id="tbl-driver">
		<thead>
			<tr>
				<th>Description</th>
				<th>Value</th>				
			</tr>
		</thead>
		<tbody>
			<?php 
				if(count($this->lib_manage_report->get_type('driver'))>0):
				foreach($this->lib_manage_report->get_type('driver') as $row) : 
			?>
				<tr class="default-tr">
					<td><input type="text" class="form-control input-sm input-description" value="<?php echo $row['description']; ?>"></td>
					<td><input type="text" class="form-control input-sm input-value" value="<?php echo $row['value']; ?>"></td>				
				</tr>
			<?php 
				endforeach; 
				else:
			?>
				<tr class="default-tr">
					<td><input type="text" class="form-control input-sm input-description"></td>
					<td><input type="text" class="form-control input-sm input-value"></td>				
				</tr>
			<?php 
				endif;
			 ?>
		</tbody>
		<tfoot>
			<tr>
				<td></td>
				<td><button id="driver-save" class="btn btn-primary btn-sm nxt-btn">Save</button></td>
			</tr>
		</tfoot>
	</table>	
</section>
<hr>
<h4>Trucks</h4><a href="javascript:void(0)" class="add-row" id="add-trucks">add</a> <a href="javascript:void(0)" class="remove-row" id="remove-trucks">remove</a>
<section id="drivers">
	<table class="table" id="tbl-trucks">
		<thead>
			<tr>
				<th>Description</th>
				<th>Value</th>				
			</tr>
		</thead>
		<tbody>
			<?php 
				if(count($this->lib_manage_report->get_type('trucks'))>0):
				foreach($this->lib_manage_report->get_type('trucks') as $row) : 
			?>
				<tr class="default-tr">
					<td><input type="text" class="form-control input-sm input-description" value="<?php echo $row['description']; ?>"></td>
					<td><input type="text" class="form-control input-sm input-value" value="<?php echo $row['value']; ?>"></td>				
				</tr>
			<?php 
				endforeach; 
				else:
			?>
				<tr class="default-tr">
					<td><input type="text" class="form-control input-sm input-description"></td>
					<td><input type="text" class="form-control input-sm input-value"></td>				
				</tr>
			<?php 
				endif;
			 ?>
		</tbody>
		<tfoot>
			<tr>
				<td></td>
				<td><button id="trucks-save" class="btn btn-primary btn-sm nxt-btn">Save</button></td>
			</tr>
		</tfoot>
	</table>	
</section>
<hr>
<h4>Drop Survey</h4><a href="javascript:void(0)" class="add-row" id="add-drop">add</a> <a href="javascript:void(0)" class="remove-row" id="remove-drop">remove</a>
<section id="drop">
	<table class="table" id="tbl-drop">
		<thead>
			<tr>
				<th>Description</th>
				<th>Value</th>				
			</tr>
		</thead>
		<tbody>
			<?php 
				if(count($this->lib_manage_report->get_type('drop'))>0):
				foreach($this->lib_manage_report->get_type('drop') as $row) : 
			?>
				<tr class="default-tr">
					<td><input type="text" class="form-control input-sm input-description" value="<?php echo $row['description']; ?>"></td>
					<td><input type="text" class="form-control input-sm input-value" value="<?php echo $row['value']; ?>"></td>				
				</tr>
			<?php 
				endforeach; 
				else:
			?>
				<tr class="default-tr">
					<td><input type="text" class="form-control input-sm input-description"></td>
					<td><input type="text" class="form-control input-sm input-value"></td>				
				</tr>
			<?php 
				endif;
			 ?>
		</tbody>
		<tfoot>
			<tr>
				<td></td>
				<td><button id="drop-save" class="btn btn-primary btn-sm nxt-btn">Save</button></td>
			</tr>
		</tfoot>
	</table>	
</section>

<script>
	$(function(){

		var app_driver = {
			init:function(){
				this.bindEvents();
			},
			bindEvents:function(){

				$('#add-driver').on('click',function(){
					var clone = $('#tbl-driver .default-tr:first').clone().removeClass('default-tr');
					clone.find(':input').val('');
					$('#tbl-driver tbody').append(clone);
				});

				$('#remove-driver').on('click',function(){
					$('#tbl-driver tbody').find('tr:last').not('.default-tr').remove();										
				});

				$('#driver-save').on('click',function(){
					var container = new Array();
					$('#tbl-driver tbody tr').each(function(i,value){
						
						var data = {
							'description' : $(value).find('td').find(':input.input-description').val(),
							'value' : $(value).find(':input.input-value').val(),							
						};
						container.push(data);
					});
					
					$post = {
						data  : container,
						label : 'driver',
					};

					$.post('<?php echo base_url().index_page();?>/manage_report/etc/save',$post,function(response){
						console.log(response);
					});

				});

			},

		}



		var app_trucks = {
			init:function(){
				this.bindEvents();
			},
			bindEvents:function(){

				$('#add-trucks').on('click',function(){
					var clone = $('#tbl-trucks .default-tr:first').clone().removeClass('default-tr');
					clone.find(':input').val('');
					$('#tbl-trucks tbody').append(clone);
				});

				$('#remove-trucks').on('click',function(){
					$('#tbl-trucks tbody').find('tr:last').not('.default-tr').remove();										
				});

				$('#trucks-save').on('click',function(){
					var container = new Array();
					$('#tbl-trucks tbody tr').each(function(i,value){
						
						var data = {
							'description' : $(value).find('td').find(':input.input-description').val(),
							'value' : $(value).find(':input.input-value').val(),							
						};
						container.push(data);
					});
					
					$post = {
						data  : container,
						label : 'trucks',
					};

					$.post('<?php echo base_url().index_page();?>/manage_report/etc/save',$post,function(response){
						console.log(response);
						alert('Successfully Save');
					});

				});

			},

		}
		
		var app_drop = {
			init:function(){
				this.bindEvents();
			},
			bindEvents:function(){

				$('#add-drop').on('click',function(){
					var clone = $('#tbl-drop .default-tr:first').clone().removeClass('default-tr');
					clone.find(':input').val('');
					$('#tbl-drop tbody').append(clone);
				});

				$('#remove-drop').on('click',function(){
					$('#tbl-drop tbody').find('tr:last').not('.default-tr').remove();										
				});

				$('#drop-save').on('click',function(){
					var container = new Array();
					$('#tbl-drop tbody tr').each(function(i,value){
						
						var data = {
							'description' : $(value).find('td').find(':input.input-description').val(),
							'value' : $(value).find(':input.input-value').val(),							
						};
						container.push(data);
					});
					
					$post = {
						data  : container,
						label : 'drop',
					};

					$.post('<?php echo base_url().index_page();?>/manage_report/etc/save',$post,function(response){
						console.log(response);
						alert('Successfully Save');
					});

				});

			},

		}		

		app_driver.init();
		app_trucks.init();
		app_drop.init();

	});



</script>