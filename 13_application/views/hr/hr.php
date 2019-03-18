<div class="header">
	<h2>Human Resource Management <small>HR</small></h2>	
</div>

<div class="container">
	<div class="row">
		<div class="col-md-1">
			
		</div>
		<div class="col-md-11">
			<div class="content-title">
				<h3>Filters</h3>
			</div>

			<div class="panel panel-default">		
			  <div class="panel-body">
			  		<div class="row">
			  			<div class="col-md-4">
			  				<div class="form-group">
					  			<div class="control-label">Departments</div>
					  			<select name="" id="departments" class="form-control input-sm">
					  				<option value="%">-ALL-</option>
					  				<?php foreach($department as $row): ?>
									<option value="<?php echo $row['DeptCode_Dept']; ?>"><?php echo $row['Name_Dept']; ?></option>
					  				<?php endforeach; ?>
					  			</select>
					  		</div>
			  			</div>
			  		</div>
			  		<div class="row">
			  			<div class="col-md-1">
			  				<div class="form-group">
			  					<div class="control-label"> <small>Total Active Emp</small></div>
			  					<h3 id="no_emp"></h3>
			  				</div>
			  			</div>
			  			<div class="col-md-4">
			  				<div class="form-group">
					  			<div class="control-label">Employee</div>
					  			<select name="" id="employee" class="form-control input-sm">					  				
					  			</select>
					  			<input type="text" id="position" class="form-control input-sm" style="margin-top:4px;" placeholder="POSITION">		
					  		</div>
			  			</div>
			  			<div class="col-md-4">
			  				<div class="form-group">
					  			<div class="control-label">DTR Period : </div>
					  			<div class="row">
					  				<div class="col-md-6">
					  					<input name="" id="date_from" class="form-control input-sm">
					  				</div>
					  				<div class="col-md-6">
					  					<input name="" id="date_to" class="form-control input-sm">
					  					<button id="search" class="btn btn-primary" style="margin-top:4px">Search</button>
					  				</div>
					  			</div>
					  			
					  		</div>
			  			</div>
			  		</div>	
			  		
			  </div>	 
			</div>

			<div class="content-title">
				<h3>DTR</h3>
			</div>

			<div class="row">
				<div class="col-md-4 dtr-header" >
					<div>
						Name  : <span id="name"></span>
					</div>
					<div>
						Dates : <span id="dates"></span>
					</div>
				</div>
			</div>
			<div class="panel panel-default">		
			  	<div class="table-responsive" id="dtr_content">
			  		
			  	</div>
			</div>


		</div>
	</div>	
	<hr>
</div>


<script>
	$(function(){
		var xhr,xhr1;
		var app = {
			init:function(){

				$('#date_from').date_from();
				$('#date_to').date_to();

				this.bindEvents();

			},bindEvents:function(){	
				$('#departments').on('change',this.get_employee);
				$('#departments').trigger('change');

				$('#employee').chosen();

				$('#employee').on('change',function(){
					$('#position').val($('#employee option:selected').data('position'));
				});

				$('#search').on('click',function(){
					$(this).append(' <i class="fa fa-spinner fa-spin"></i>');
					$(this).addClass('disabled');
					app.search();
				});
			},get_employee:function(){

				$post = {
					department : $('#departments option:selected').val(),
				}
				  if(xhr && xhr.readystate != 4){
				            xhr.abort();
				       }
				$('#employee').html('');
				xhr = $.post('<?php echo base_url().index_page();?>/hr/get_employee',$post,function(response){
					$('#employee').html(response.div);
					$('#employee').trigger("chosen:updated");
					$('#no_emp').html(response.total);	
					$('#position').val($('#employee option:selected').data('position'));				
				},'json');
			},search:function(){
				$post = {
					date_from : $('#date_from').val(),
					date_to   : $('#date_to').val(),
					emp_id    : $('#employee option:selected').attr('data-id'),
					emp_name  : $('#employee option:selected').attr('data-name')
				};

				$.post('<?php echo base_url().index_page();?>/hr/get_dtr',$post,function(response){
						
						$('#dtr_content').html(response.table);
						$('#name').html(response.name);
						$('#dates').html(response.dates);
						$('#search').find('i').remove();
						$('#search').removeClass('disabled');

				},'json').error(function(){
						alert('Search Failed');
						$('#search').find('i').remove();
						$('#search').removeClass('disabled');
				});
			}

		}

		app.init();

	});

</script>
