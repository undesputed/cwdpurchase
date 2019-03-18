<style type="text/css">
 	#InsertHere table tbody tr:hover{
 		text-decoration: underline;
 		cursor: pointer;
 	}
</style>
<div class="header">
	<h2>Signatory Setup</h2>	
</div>

	<form id="myForm">
		<div class="container">	
			<div class="row">
				<div class="col-md-3">
					<!-- PROJECTS -->
					<div class="content-title">
						<h3>PROJECT INFORMATION</h3>
					</div>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group">
								<span class="control-label">Project: </span>
								<select class="form-control input-sm" name="cmbMainProject_cumulative">
								</select>
							</div>	
							<div class="form-group">
								<span class="control-label">Profit Center: </span>
								<select class="span2" name="txtprojno" readonly style="display:none;">		
								</select>
								<select class="form-control input-sm" name="cmbprojectlocation">		
								</select>						
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-9">

					<div class="content-title">
						<h3>Transaction</h3>
					</div>

					<div class="panel panel-default">
						<div class="panel-body">

							<div class="row">
								<div class="form-group col-md-6">
									<span class="form-label">Form:</span>
									<select class="form-control input-sm" name="cmbForm"><?php echo "<option></option>".$cmbForm; ?></select>
								</div>	

								<div class="form-group col-md-6">
									<span class="form-label">Signatory:</span>
									<select class="form-control input-sm" name="cmbSignatory"><?php echo "<option></option>".$cmbSignatory; ?></select>
								</div>	
							</div>

							<div class="row">
								<div class="form-group col-md-6">
									<span class="form-label">Employee Name:</span>
									<select class="form-control input-sm" name="cmbEmployeeName"><?php echo $cmbEmployeeName; ?></select>
								</div>	

								<div class="form-group col-md-4">
									<span class="form-label">Priority Order:</span>
									<select class="form-control input-sm" name="cmbPriority"><?php for ($i=1; $i <= 10 ; $i++) { 
																				echo "<option value='".$i."'>".$i."</option>";
																				} ?>
									</select>
								</div>	

								<div class="form-group col-md-2">
									<span class="form-label">&nbsp;</span>
									<button class="btn btn-primary col-md-12" id="btnSave" onClick='event.preventDefault();'>Save</button>
								</div>
								

							</div>


						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-3">

				</div>

				<div class="col-md-9">
					<div class="panel panel-default">
						<div class="panel-body">
							<div id="InsertHere">

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	


<script type="text/javascript">
(function($){
	var app = {init:function(){
				app.bindEvents();
				app.cmbprojectlocation();
				generate_chosens();
				$('#btnSave').generate_preload();
			},bindEvents:function(){
				$('[name="cmbprojectlocation"]').on('change',this.cmbprojectlocation);
				$('body').on('click','.addForm',this.addForm);
				$('body').on('click','.addSignatory',this.addSignatory);
				$('body').on('click','#InsertHere table tbody tr',this.rowClick);
				$('#btnSave').on('click',this.btnSave);
			},rowClick:function(){
				var row = $(this);
				var obj = $(row).generate_object();
				if(!obj){
					return false;
				}
				
				console.log(obj);
				// $(row).generate_popover('thequickbrownfox');



			},btnSave:function(){
				if($('select option:selected').filter(function() { return ($(this).val().trim() == '') }).length > 0){
					alert('Please fill up all the fields');
					return false;
				}

				$.post('<?php echo base_url().index_page()?>/setup/signatory_setup/btnSave',{data: $('#myForm').serialize()},function(data){
					if(data == 'true'){
						alert('Successfully saved');
						app.cmbprojectlocation();
					}else{
						alert('Error in Saving');
						app.cmbprojectlocation();
					}
				});
			},addForm:function(){
				console.log('AddForm');
			},addSignatory:function(){
				console.log('Signatory');
			},cmbprojectlocation:function(){
				$('#InsertHere').content_loader();
				$.post('<?php echo base_url().index_page()?>/setup/signatory_setup/cmbprojectlocation',{data:$('[name="cmbprojectlocation"]').val()},function(data){
					$('#InsertHere').html(data);
					$('#InsertHere table').dataTable(datatable_option);
				});
			}
	};

	$(document).ready(function(){
		var options={profit_center:$('[name="cmbprojectlocation"]'),
					txtprojno:$('[name="txtprojno"]'),
					call_back:function(){app.init()}};
		$('[name="cmbMainProject_cumulative"]').get_projects(options);
	});




	function generate_chosens(){
		$("[name='cmbForm']").chosen({
								no_results_text	: "<button class='btn btn-small btn-primary addForm' onclick='event.preventDefault()'>Add Form</button>"
								});
		$("[name='cmbSignatory']").chosen({
								no_results_text	: "<button class='btn btn-small btn-primary addSignatory' onclick='event.preventDefault()'>Add Signatory</button>"
								});
		return true;
	}
}(jQuery));
</script>
