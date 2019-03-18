<div class="content-title">
	<h3>Total Barging / Mining / Equipment / Breakdown Monitoring</h3>	
</div>

<small class="text-muted">Dashboard Reporting Setup</small>
<h4>Drivers</h4>
<section id="drivers">
	<table class="table" id="tbl-driver">
		<thead>
			<tr>
				<th>Title</th>
				<th>Value</th>				
			</tr>
		</thead>
		<tbody>
				<tr>
					<td>As Date of</td>
					<td><input type="text" class="form-control input-sm input-value" id="date" value=""></td>
				</tr>
				<tr class="">
					<td>Total Barging</td>
					<td><input type="text" class="form-control input-sm input-value" id="barging" value=""></td>				
				</tr>
				<tr class="">
					<td>Mining Operation</td>
					<td><input type="text" class="form-control input-sm input-value" id="operation" value=""></td>				
				</tr>
				<tr class="">
					<td>Breakdown Monitoring</td>
					<td><input type="text" class="form-control input-sm input-value" id="monitoring" value=""></td>				
				</tr>
				<tr class="">
					<td>Equipment Availability</td>
					<td><input type="text" class="form-control input-sm input-value" id="equipment_availability" value=""></td>
				</tr>
				
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


<script>
	$(function(){

		var app_driver = {
			init:function(){
				this.bindEvents();
				$('#date').date();
			},
			bindEvents:function(){

				$('#driver-save').on('click',function(){

					$post = {
						barging    : $('#barging').val(),
						mining     : $('#operation').val(),
						monitoring : $('#monitoring').val(),
						equipment_availability : $('#equipment_availability').val(),
						date       : $('#date').val(),
					}
					
					$.post('<?php echo base_url().index_page();?>/manage_report/total/save',$post,function(response){
						alert('Successfully Save');
					});

				});

			},

		}

		app_driver.init();

	});



</script>