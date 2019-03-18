<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Signatory Print Setup</h2>
</div>

<div class="container">

				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-5">
								<div class="form-group">
									<div class="control-label">Form</div>
									<select name="" id="form" class="form-control">
										<optgroup label="ERP">
											<option value="pr">Purchase Request</option>
											<option value="cv">Canvass Sheet</option>
											<option value="po">Purchase Order</option>
											<option value="rr">Receiving Report</option>
											<option value="rx">Return</option>
											<option value="ir">Item Request</option>
											<option value="iw">Item Withdrawal</option>
											<option value="is">Item Issuance</option>
											<option value="jo">Job Order</option>
										</optgroup>
									</select>									
								</div>

								<div class="form-group">
									<div class="control-label">Signatory</div>
									<select name="" id="signatory" class="form-control">
										<option value="requested_by">Requested by</option>
										<option value="received_by">Received by</option>
										<option value="approved_by">Approved by</option>
										<option value="issued_by">Issued by</option>
										<option value="returned_by">Returned by</option>
										<option value="posted_by">Posted by</option>
										<option value="bidding_committee">Bidding Committee</option>
									</select>
								</div>

								<div class="form-group">
									<div class="control-label">Designation</div>
									<select name="" id="designation" class="form-control">
										<option value="chairperson">Chairperson</option>
										<option value="vice chairperson">Vice Chairperson</option>
										<option value="member">Member</option>
										<option value="end user">End User</option>
										<option value="general manager">General Manager</option>
										<option value="utilities">Utilities</option>
										<option value="oic">OIC</option>
										<option value="bac chairperson">BAC Chairperson</option>
									</select>
								</div>			

								<div class="row">
									<div class="col-xs-8">
										<div class="form-group">
											<div class="control-label">Employee List</div>
											<select name="" id="employee_id" class="form-control">
												<?php foreach($employee as $row): ?>
													<option value="<?php echo $row['emp_number']; ?>"><?php echo $row['person_name']; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>

									<div class="col-xs-4" style="padding-top:10px;">										
										<input type="button" id="addtolist" value="add to list" class="btn btn-primary">										
									</div>
								</div>

							</div>
							<div class="col-xs-7">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h5>Signatory List</h5>
									</div>
									<table id="tbl-signatory-list" class="table">										
										<tbody>
											
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>				
</div>

</div>
</div>

<script>
	$(function(){

		var app = {
			init:function(){
				this.bindEvent();
			},bindEvent:function(){

				$('#addtolist').on('click',this.addtolist);
				$('#form').on('change',this.signatory_list);
				$('#signatory').on('change',this.signatory_list);

				$('body').on('click','.remove',this.remove);

				$('#form').trigger('change');

			},addtolist:function(){

				$post = {
					form        : $('#form option:selected').val(),
					signatory   : $('#signatory option:selected').val(),
					designation : $('#designation option:selected').val(),
					employee_id : $('#employee_id option:selected').val(),
				};

				$.post('<?php echo base_url().index_page();?>/setup/signatory/addtolist_print',$post,function(response){
					app.render(response);
				},'json');

			},signatory_list:function(){

				$post = {
					form        : $('#form option:selected').val(),
					signatory   : $('#signatory option:selected').val(),					
				};

				$.post('<?php echo base_url().index_page();?>/setup/signatory/signatory_list_print',$post,function(response){
					app.render(response);
				},'json');

			},render:function(data){

				var div = "";
				$('#tbl-signatory-list tbody').html('');
				if(data.length > 0)
				{	

					$.each(data,function(i,val){
						div += "<tr>";
						div += "<td>"+val['Person Name']+"</td>";
						div += "<td><a href='javascript:void(0)' class='remove' data-id='"+val.id+"'>Remove</a></td>";					
						div += "</tr>";
					});
					$('#tbl-signatory-list tbody').html(div);

				}else{
					$('#tbl-signatory-list tbody').html("<tr><td colspan='2'>Empty</td></tr>");
				}
				
			},remove:function(){
				var bool = confirm('Are you Sure?');
				if(!bool){
					return false;
				}
				
				$post = {
					id        : $(this).attr('data-id')
				}

				$.post('<?php echo base_url().index_page();?>/setup/signatory/remove_print',$post,function(response){
					app.render(response);
				},'json');

			}

		};
		app.init();

	});	
</script>