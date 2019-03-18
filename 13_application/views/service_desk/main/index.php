	<?php 

	$base_url = base_url().index_page();
	$collapse = "out";
	    $link = array(		
				array('href'=>$base_url."/service_desk/in_house",'title'=>'In House','slug'=>'in_house'),
				array('href'=>$base_url."/service_desk/job_out",'title'=>'Job Out','slug'=>'job_out'),
		);		

	    for ($i=0; $i < count($link) ; $i++){ 
    		if($link[$i]['slug'] == $this->uri->segment(2)){
    			$link[$i]['active']='class="active"';
    			$collapse = 'in';
    		}else{
    			$link[$i]['active']='';
    		}
	    }
	    
	?>
	


	<section id="sidebar">
		
		<div class="row">
			<div class="col-md-12">
					<ul class="sidebar-ul">
						<li><a href="<?php echo base_url().index_page(); ?>/service_desk/create">Create Request</a></li>
						<li><a data-toggle="collapse" href="#collapseOne">Request List</a>
							<div id="collapseOne" class="panel-collapse collapse <?php echo $collapse; ?>">
								<ul class="sub-menu">
									<?php foreach($link as $row): ?>									
										<li <?php echo $row['active']; ?>><a href="<?php echo $row['href'] ?>"><?php echo $row['title'];?></a></li>
									<?php endforeach; ?>																	
								</ul>
							</div>
						</li>
						
					</ul>
			</div>
		</div>
	
	</section>
		
	<section class="page-content-wrapper">

	<div id="content">		
		<div class="container">
			<div class="content-title">
				<h3>Service Request</h3>
			</div>
			<div class="row">
				<div class="col-md-4">
					
					<div class="form-group">
						<div class="control-label">Ref No</div>
						<input type="text" id="ref_no" class="form-control input-sm">
					</div>
		
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="control-label">Date acquired</div>
								<div class="input-group">
									<input type="text" class="date form-control input-sm">
									<span class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</span>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<div class="control-label">Time</div>
								<div class="input-group">
									<input type="text" id="time" class="form-control input-sm">
									<span class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</span>
								</div>
							</div>
						</div>
					</div>
				

					<div class="form-group">
						<div class="control-label">Operator</div>
						<input type="text" id="operator" class="form-control input-sm">
					</div>

					<div class="form-group">
						<div class="control-label">Body Number</div>
						<select name="" id="body_no" class="form-control input-sm">						
							<option value="">1</option>
							<option value="">2</option>
							<option value="">3</option>
							<option value="">4</option>
							<option value="">5</option>
							<option value="">6</option>
						</select>
					</div>

					
					
				</div>
				<div class="col-md-8">

					<div class="form-group">
						<div class="control-label">Location</div>
						<input type="text" class="form-control input-sm">
					</div>

					<div class="form-group">
						<div class="control-label">Work Scope</div>
						<input type="text" class="form-control input-sm">
					</div>

					<div class="form-group">
						<div class="control-label">Assign Mechanic</div>
						<input type="text" class="form-control input-sm">
					</div>

				</div>
			</div>

			<div class="content-title">
				<h3>Check List</h3>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">						
						<input type="text" class="form-control input-sm">
					</div>
					<div class="panel panel-default">							
						<table class="table">
							<thead>
								<tr>
									<th>Equipment parts</th>
									<th></th>
									<th></th>
									
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>									
								</tr>
							</tbody>
						</table>							
					</div>
				</div>
				<div class="col-md-6">

				</div>
			</div>
			<div class="row">
				<div class="col-md-12">					
						<div class="form-footer">
							<div class="row">
								<div class="col-md-8"> </div>
								<div class="col-md-4">
								<input id="save" class="btn btn-primary btn-lg col-xs-5 pull-right" type="submit" value="Save">
								<input id="reset" class="btn btn-link btn-lg pull-right" type="reset" value="Reset">
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>

	</section>

	
<script>
	var app = {
		init:function(){
			$('.date').date();
			$('#time').timepicker();

			app.get_ref();
			$('#body_no').chosen();

			this.bindEvent();

		},bindEvent:function(){
			$('.date').on('change',this.get_ref);
			$('#operator').on('keyup',function(){
				//alert('test');
					$('#body_no option:eq(2)').prop('selected',true);
					$('#body_no').trigger("chosen:updated");
			});

			$('#save').on('click',function(){
					$.save();
					delay(function(){
						$.save({action : 'hide'});
					},2000)
			});
			
		},get_ref:function(){
			$post = {
				date : $('.date').val()
			}
			$.post('<?php echo base_url().index_page();?>/service_desk/get_ref_no',$post,function(response){
				$('#ref_no').val(response);
			});	
		}

	}

$(function(){
	app.init();
});

</script>











