<div class="header">
	<h2>Material Setup</h2>	
</div>

<div class="container">
	<form id="myForm">
		<div style="margin-top:1em;display:none;" id="slide-content">
			<div class="row">
				<div class="col-md-9">
					<fieldset>
						<div class="content-title">
							<h3>Add Item</h3>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<span class="control-label">Inventory Group</span>
									<div class="controls">
										<select id="inventory_group" name="cmbGroupName" class="form-control input-sm">
											<?php echo $cmbGroupName; ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<span class="control-label">Item Code</span>
									<div class="controls">
										<input type="text" id="item_code" name="txtItemCode" required class="form-control input-sm">
									</div>
								</div>

								<div class="form-group">
									<span class="control-label">Item Description</span>
									<div class="controls">
										<input type="text" id="item_description" name="txtDescription" required class="form-control input-sm">
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<span class="control-label">Unit Measure</span>
									<div class="controls">
										<input type="text" id="unit_measure" name="txtUnitMeasure" required class="form-control input-sm">
									</div>
								</div>

								<div class="form-group">
									<span class="control-label">Unit Cost</span>
									<div class="controls">
										<input type="text" id="unit_cost" placeholder="0.00" name="txtUnitCost" class="numbers_only form-control input-sm" required>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<div class="control-label">Base 1</div>
											<input type="text" id="base1" name="base1" class="form-control input-sm">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="control-label">Base 2</div>
											<input type="text" id="base2" name="base2" class="form-control input-sm">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="control-label">Base 3</div>
											<input type="text" id="base3" name="base3" class="form-control input-sm">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div class="control-label">Base 4</div>
											<input type="text" id="base4" name="base4" class="form-control input-sm">
										</div>
									</div>

								</div>

							</div>
						</div>
					</fieldset>
				</div>

				<div class="col-md-3">

					<fieldset>
						<div class="content-title">
							<h3>Item Group</h3>
						</div>

						<span class="radio form-label">
							<label>
								<input type="radio" name="GroupPanel1" value="1" checked>Fuel Supply
							</label>
						</span>
												
						<span class="radio form-label">
							<label>
								<input type="radio" name="GroupPanel1" value="2">Equipment and Machinery
							</label>
						</span>
												
						<span class="radio form-label">
							<label>
								<input type="radio" name="GroupPanel1" value="3">Materials/Supplies/Spareparts
							</label>
						</span>
												
						<span class="radio form-label">
							<label>
								<input type="radio" name="GroupPanel1" value="4">Tank Storage
							</label>
						</span>
						
						<span class="radio form-label">
							<label>
								<input type="radio" name="GroupPanel1" value="5">Tire Inventory
							</label>
						</span>
						
					</fieldset>
				</div>
			</div>

		</div>
		<input type="submit" id="btnSubmit" style="display:none;">
		<span style="display:none;">
			<select name="cmbClassification"></select>	
			<select name="cmbIncomeAccountDescription"></select>
			<input type="text" name="txtIncomeAccountCode">	
			<input type="text" name="_title_id" value="<?php echo $this->session->userdata('Proj_Main');?>">	
			<input type="text" name="Quantity" value="1">
		</span>
	</form>
	
	<hr>
	

	<div class="panel panel-default">
	  <div class="panel-body">
		  	<div class="row">
				<span class="col-md-12">
					<button class="btn btn-primary" id="btnSave">Add Item</button>
					<button class="btn btn-primary" onclick="location.reload('true')">Refresh</button>
				</span>
			</div>
	   </div>
			<div id="InsertTable">	
				<table class="table">
					<thead>
						<tr>
							<th>Item No</th>
							<th>Item Description</th>
							<th>Unit Cost</th>
							<th>Unit Measure</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
	 
	</div>


</div>
	
<script>
(function($){
	var cmbIncomeAccountDescription=new Object();
	var cmbClassification=new Object();
	var app = {init:function(){
				app.bindEvents();
				app.display_group_detail_setup();
				cmbClassification = eval(<?php echo $cmbClassification; ?>);
				cmbIncomeAccountDescription = eval(<?php echo $cmbIncomeAccountDescription; ?>);
				app.load_select();
				app.GroupPanel1();

			},load_select:function(){
				$('[name="cmbIncomeAccountDescription"]').generate_option({data: cmbIncomeAccountDescription,val:"account_id",text:"account_description"});
				$('[name="cmbClassification"]').generate_option({data: cmbClassification});
			},bindEvents:function(){
				$('#btnSave').on('click',this.btnSave);
				$('[name="GroupPanel1"]').on('click',this.GroupPanel1);
				$('#myForm').bind('submit',function(e){e.preventDefault();app.myForm()});
				$('body').on('click','.update',this.openUpdate);
			},myForm:function(){
				/*validated*/
				if(confirm("Are you sure you want to Save data?")){

					if($('.Item_Description').filter(function(){ return ($(this).text().trim() == $('[name="txtDescription"]').val().trim()) }).length > 0){
						alert('Item already exists');
						return false;
					}

					var post_data={form:$('#myForm').serialize()}
					$.post('<?php echo base_url().index_page()?>/setup/material_setup/insert_group_detail_setup',post_data,function(data){
						/*refresh form data*/
						clearFields();
					});
				}
			},GroupPanel1:function(){
				var radio_val = $('[name="GroupPanel1"]:checked').val();
				if(radio_val==2 || radio_val==4){
					$("[name='cmbClassification'] option").filter(function(){ return $(this).text() == "NON-CURRENT ASSETS"; }).prop('selected',true);
					$("[name='cmbIncomeAccountDescription'] option").filter(function(){ return $(this).text() == "CONSTRUCTION EQUIPMENT"; }).prop('selected',true);
				}else{
					$("[name='cmbClassification'] option").filter(function(){ return $(this).text() == "CURRENT ASSETS"; }).prop('selected',true);
					$("[name='cmbIncomeAccountDescription'] option").filter(function(){ return $(this).text() == "CONSTRUCTION MATERIALS INVENTORY"; }).prop('selected',true);
				}
				app.txtIncomeAccountCode();
			},txtIncomeAccountCode:function(){
				var index = $("[name='cmbIncomeAccountDescription'] option:selected").index();
				$('[name="txtIncomeAccountCode"]').val(cmbIncomeAccountDescription[index]['account_code']);
			},btnSave:function(){
				if($(this).text()=="Add Item"){
					$(this).text("Save");
					$('#slide-content').stop(true).slideToggle('slow');
					$('[name="cmbGroupName"]').chosen();
				}else{
					$('#btnSubmit').trigger('click');/*submits form*/
				}
			},display_group_detail_setup:function(){
				$('#InsertTable').content_loader('true');
				$.post('<?php echo base_url().index_page();?>/setup/material_setup/display_group_detail_setup',{txtSItemDescr:''},function(data){
					$('#InsertTable').html(data);
					$('#InsertTable table').dataTable(datatable_option);
				});
			},openUpdate:function(){
				
				$('#slide-content').stop().slideDown('slow');
				$('#btnSave').text('Update');
				var tr = $(this).closest('tr');
				var $obj = {
						inventory_group      : tr.find('td.group_id').text(),
						item_code            : tr.find('td.item_description').text(),
						item_description     : tr.find('td.item_description').text(),
						unit_measure         : tr.find('td.unit_measure').text(),
						unit_cost            : tr.find('td.unit_cost').text(),
						base1                : tr.find('td.base1').text(),
						base2                : tr.find('td.base2').text(),
						base3                : tr.find('td.base3').text(),
						base4                : tr.find('td.base4').text(),
				};

				$.each($obj,function(i,value){
					$('#'+i).val(value);
				});

				$('[name="GroupPanel1"]').val(tr.find('td.group_id1').text());

			}
	};
	$(function(){
		app.init();
	});

	function clearFields(){
		$('#btnSave').text("Add Item");
		$('#slide-content').stop(true).slideToggle('slow');
		$('#myForm input[type="text"]').val('');
		app.load_select();
		app.GroupPanel1();
		app.display_group_detail_setup();
		$('[name="_title_id"]').val('<?php echo $this->session->userdata('Proj_Main');?>');
		$('[name="Quantity"]').val("1");
	}

}(jQuery));
</script>

