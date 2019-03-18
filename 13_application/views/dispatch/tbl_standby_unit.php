<div class="content-title">
	<h3>Standby Available</h3>
</div>



<input type="hidden" value="<?php echo $this->input->post('date') ?>" id="date">
<input type="hidden" value="<?php echo $this->input->post('shift') ?>" id="shift">

<div class="container">

				<table id="tbl-standby-delay" class="table table-hover table-condensed">
					<thead>
						<tr>
							<th>Standby Unit</th>
							<th>Cause of Delay</th>
							<th>Action</th>
							
						</tr>
					</thead>
					<tbody>
							<?php foreach($available_equipment as $row): ?>							
							<?php 

								if(empty($row['delay_id'])){
									$dom  = "";
									$set = "";
								}else{
									if(!empty($row['shift']) && $row['shift'] == $this->input->post('shift')){
										$dom  = "fa-check";
										$set = "green";
									}
									

								}
							?>
							<tr class="<?php echo $set; ?>">
								<td style="width:200px"> <span class="fa <?php echo $dom; ?>"></span> <?php echo $row['equipment_brand']; ?></td>
								<td>
									<select name="" id="" class="delay_type">
										<?php foreach($delay_list as $delay_row): ?>
										<?php $selected = ($delay_row['delay_id'] == $row['delay_id'] && $row['shift'] == $this->input->post('shift'))? "selected='selected'" : "";  ?>
											<option <?php echo $selected; ?> value="<?php echo $delay_row['delay_id']; ?>"><?php echo $delay_row['delay_type']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
								<td><button onclick="submit_delay(this)" data-name="<?php echo $row['equipment_brand']; ?>" data-id="<?php echo $row['equipment_id'] ?>" >Accept <i class="fa"></i></button></td>
							</tr>
							<?php endforeach; ?>
					</tbody>
				</table>

</div>
<script>

	var xhr;
	$('#tbl-standby-delay').dataTable(datatable_option);

	var submit_delay = function(dom){
			var bool = confirm('Are you sure?');

				if(!bool){
					return false;
				}

		 	if(xhr && xhr.readystate != 4){
			            xhr.abort();
			}
			
			$post = {
				equipment_name : $(dom).attr('data-name'),
				equipment_id : $(dom).attr('data-id'),
				delay_id : $(dom).closest('tr').find('.delay_type option:selected').val(),
				delay_title : $(dom).closest('tr').find('.delay_type option:selected').text(),
				shift    : $('#shift').val(),
				date     : $('#date').val(),
			}

			$(dom).find('.fa').addClass('fa-spin fa-spinner');
			$(dom).attr('disabled','disabled');

			xhr = $.post('<?php echo base_url().index_page();?>/dispatch/set_delay',$post,function(response){

				$(dom).removeAttr('disabled');
				$(dom).find('.fa').removeClass('fa-spin fa-spinner');

			}).error(function(){
				alert('Internal Server Error, try again later');
				$(dom).find('.fa').removeClass('fa-spin fa-spinner');
			});

	};



		var assign_person = function(dom){

				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}

				$(dom).find('.fa').addClass('fa-spin fa-spinner');
				 if(xhr && xhr.readystate != 4){
				            xhr.abort();
				 }

				 var select = $(dom).closest('tr').find('.select-equipment option:selected');
				 var remarks = $(dom).closest('tr').find('.remarks');
				 $(dom).attr('disabled','disabled');
				 $post = {
				 	id : $(dom).attr('data-id'),
				 	person : $(dom).attr('data-person'),
				 	equipment_id : select.val(),
				 	equipment_name : select.text(),
				 	equipment_category : $('#equipment_category').val(),
				 	date  : $('.date').val(),
				 	shift : $('#shift').val(),
				 	remarks : remarks.val(),
				 	action  : $(dom).find('span').text(),
				 };
				xhr = $.post('<?php echo base_url().index_page();?>/dispatch/set_driver',$post,function(response){
					alert(response.msg);
					 if(response.status == 0){
					 	 $(dom).find('span').html('Release');
					 	 $(dom).closest('tr').find('.select-equipment').attr('disabled','disabled');	
					 	 $(dom).closest('tr').addClass('assigned');	
						 $(dom).closest('tr').find('.remarks').attr('disabled','disabled');							 
						 app.get_assigned();
						 $(dom).removeAttr('disabled');
					 }else if(response.status==3){
					 	 $(dom).closest('tr').find('.select-equipment').removeAttr('disabled');	
					 	 $(dom).closest('tr').removeClass('assigned');	
						 $(dom).closest('tr').find('.remarks').removeAttr('disabled');
						 $(dom).closest('tr').find('.remarks').val('');
						  app.get_assigned();
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