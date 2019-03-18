<div class="content-title">
	<h3>Dispatching</h3>
</div>
<input type="hidden" id="equipment_category" value="<?php echo trim($this->input->post('equipment')); ?>">
<input type="hidden" id="shift" value="<?php echo $this->input->post('shift'); ?>">
<?php 

	$available_equip = $available_equipment;

	foreach($available_equipment as $key=>$row){
		foreach($dispatch_person as $row1){
			if($row1['equipment_id'] == $row['equipment_id']){
				unset($available_equipment[$key]);				
			}

		}
	}

 ?>

<div class="container">
				<div class="row" style="font-size:12px;">
					<div class="col-md-9">
						<div class="panel panel-default">		
						  <div class="panel-body">
						  		<div class="radio-inline">
						  			<input type="radio" name='equipment' class="chk-equipment" id="chk-adt" value="ADT"><label for="chk-adt">ADT</label> 
						  		</div>
						  		<div class="radio-inline">
						  			<input type="radio" name='equipment' class="chk-equipment" id="chk-dt" value="HOWO DT"><label for="chk-dt">DT</label>
						  		</div>
						  		<div class="radio-inline">
						  			<input type="radio" name='equipment' class="chk-equipment" id="chk-backhoe" value="BACK HOE"><label for="chk-backhoe">Backhoe</label> 
						  		</div>
						  		<div class="radio-inline">
						  			<input type="radio" name='equipment' class="chk-equipment" id="chk-bd" value="BULLDOZER"><label for="chk-bd">BullDozer</label> 
						  		</div>
						  		<div class="radio-inline">
						  			<input type="radio" name='equipment' class="chk-equipment" id="chk-payLoader" value="PAY LOADER"><label for="chk-payLoader">Pay Loader</label> 
						  		</div>
						  		<div class="radio-inline">
						  			<input type="radio" name='equipment' class="chk-equipment" id="chk-rg" value="ROAD GRADER"><label for="chk-rg">Road Grader</label> 
						  		</div>
						  		<div class="radio-inline">
						  			<input type="radio" name='equipment' class="chk-equipment" id="chk-rr" value="ROAD ROLLER"><label for="chk-rr">Road Roller</label> 
						  		</div>
						  		<input type="button" id="load_units" onClick="load_unit()" class="btn btn-sm pull-right" value="Load Units">
						  </div>	 
						</div>
					</div>
				</div>
				
				<table id="tbl-assigning" class="table table-hover table-condensed">
					<thead>
						<tr>
							<th style="width:200px">Available Drivers</th>
							<th>Position</th>
							<th>Equipment Name</th>
							<th>Remarks</th>
							<th>Action</th>						
						</tr>
					</thead>
					<tbody>
					<?php foreach($drivers as $row): 
						$tr_class="";
						$default_values = array('equipment_id'=>'','remarks'=>'','equipment_name'=>'');
						$disabled = "";
						$label = "Confirm";
							foreach($dispatch_person as $row_value){
								
								if($row_value['employee_id'] == $row['id'] and $row_value['assign_status'] =='lock' ){
									$tr_class = "assigned";
									$default_values = array('equipment_id'=>$row_value['equipment_id'],'remarks'=>$row_value['remarks'],'equipment_name'=>$row_value['equipment_name']);
									$label = "Release";
									$disabled = "disabled=disabled";
									continue;
								}

							}
					?>
						
						<tr class="<?php echo $tr_class; ?>">
							<td><?php echo $row['name']; ?></td>
							<td><?php echo $row['position']; ?></td>
							<td>

								<div class="selection">
									
									<?php if(!empty($default_values['equipment_id'])): ?>
											<strong data-equipment-id="<?php echo $default_values['equipment_id'] ?>"><?php echo $default_values['equipment_name']; ?></strong>
									<?php else: ?>
											<select name="" id="" data-person="<?php echo $row['name']; ?>" data-equipment-category ="<?php echo trim($this->input->post('equipment')); ?>" class="select-equipment" <?php echo $disabled; ?>>
											<option value="0">To be assign</option>
											<?php foreach($available_equipment as $equip_row): ?>										
												<option  value="<?php echo $equip_row['equipment_id'] ?>"><?php echo $equip_row['equipment_brand']; ?></option>												
											<?php endforeach; ?>
											</select>
									<?php endif; ?>
										
																														
										
								</div>
							
							</td>
							<td><input type="text" class="remarks" value="<?php echo $default_values['remarks'] ?>" <?php echo $disabled; ?>></td>
							<td><button  onclick="assign_person(this)" class="confirmation " data-id="<?php echo $row['id']; ?>" data-person="<?php echo $row['name']; ?>"><span><?php echo $label; ?></span> <i class="fa"></i></button></td>
						</tr>
						
					<?php endforeach; ?>
					</tbody>
				</table>

</div>

<script>

	var xhr1;
	$(function(){
		
		var dispatch_app = {
			init:function(){				
				this.bindEvents();
			},
			bindEvents:function(){
				//$('.chk-equipment').val($('#equipment_category').val());
				//var value = $('#equipment_category').val();
				//$("input[name='equipment'][value='" + value + "']").attr('checked', 'checked'));
			}

		};

		dispatch_app.init();
		$('#tbl-assigning').dataTable(datatable_option);

		var value = $('#equipment_category').val();
		console.log(value);
		$("input[name='equipment'][value='"+ value +"']").attr('checked', 'checked');

	});


		var load_unit = function(){

			$post = {
				equipment          : $('.chk-equipment:checked').val(),
				shift              : $('#shift').val(),
				date               : $('.date').val()

			}

			$.post('<?php echo base_url().index_page();?>/dispatch/get_equipment',$post,function(response){
				if($('.select-equipment').length > 0){
					$('.select-equipment').html(response.div).attr('data-equipment-category',response.category);
				}else{
					$('.selection').html('<select class="select-equipment" data-person="" data-equipment-category="'+response.category+'"></select>').html(response.div);
				}				
			},'json');

		}


		var assign_person = function(dom){

				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}

				$(dom).find('.fa').addClass('fa-spin fa-spinner');
				 if(xhr1 && xhr1.readystate != 4){
				            xhr1.abort();
				 }

				 var select = $(dom).closest('tr').find('.select-equipment option:selected');
				 var remarks = $(dom).closest('tr').find('.remarks');
				 $(dom).attr('disabled','disabled');

				 var equipment_id = '';
				 
				 if($(dom).closest('tr').find('.select-equipment').length > 0){
				 	equipment_id = select.val();
				 }else{
				 	equipment_id = $(dom).closest('tr').find('strong').attr('data-equipment-id');
				 }

				 $post = {
				 	id : $(dom).attr('data-id'),
				 	person : $(dom).attr('data-person'),
				 	equipment_id : equipment_id,
				 	equipment_name : select.text(),
				 	equipment_category : $(dom).closest('tr').find('.select-equipment').attr('data-equipment-category'),
				 	date  : $('.date').val(),
				 	shift : $('#shift').val(),
				 	remarks : remarks.val(),
				 	action  : $(dom).find('span').text(),
				 };

				xhr = $.post('<?php echo base_url().index_page();?>/dispatch/set_driver',$post,function(response){
					 alert(response.msg);
					 if(response.status == 0){
					 	 $(dom).find('span').html('Release');
					 	 /*$(dom).closest('tr').find('.select-equipment').attr('disabled','disabled');	*/
					 	 $(dom).closest('tr').find('.selection').html('<strong data-equipment-id="'+ equipment_id +'">'+ select.text() +'</strong>')
					 	 $(dom).closest('tr').addClass('assigned');	
						 $(dom).closest('tr').find('.remarks').attr('disabled','disabled');							 
						 app.get_assigned();
						 $(dom).removeAttr('disabled');
					 }else if(response.status==3){
					 	 app.get_assigned();
					 	$post1 = {
					 		equipment          : $('.chk-equipment:checked').val(),
							shift              : $('#shift').val(),
							date               : $('.date').val()
					 	};

					 	$.post('<?php echo base_url().index_page();?>/dispatch/get_equipment',$post1,function(response){							
									$(dom).closest('tr').find('.selection').html('<select class="select-equipment" data-person="" data-equipment-category="'+response.category+ '">'+ response.div +'</select>');
							},'json');
					 	  
					 	 $(dom).closest('tr').removeClass('assigned');	
						 $(dom).closest('tr').find('.remarks').removeAttr('disabled');
						 $(dom).closest('tr').find('.remarks').val('');						 
						 $(dom).removeAttr('disabled');
						 $(dom).find('span').html('Confirm');
						 
					 }else{
					 	$(dom).removeAttr('disabled');
					 }
							 
					 $(dom).find('.fa').removeClass('fa-spin fa-spinner');

				},'json').error(function(){
					alert('Action Failed! Try Again Later');
					$(dom).removeAttr('disabled');
					$(dom).find('.fa').removeClass('fa-spin fa-spinner');
				});

		}




</script>