<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Signatory Setup</h2>
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
											<option value="iw">Item Withdrawal</option>
											<option value="is">Item Issuance</option>
											<option value="jo">Job Order</option>
										</optgroup>
										<optgroup label="ACCOUNTING">
											<option value="dv">Disbursement Voucher</option>
										</optgroup>
									</select>									
								</div>
								<div class="form-group">
									<div class="control-label">Signatory</div>
									<select name="" id="signatory" class="form-control">
										<option value="prepared_by">Prepared by</option>
										<option value="recommended_by">Recommended by</option>
										<option value="checked_by">Checked by</option>
										<option value="noted_by">Noted by</option>
										<option value="received_by">Received by</option>
										<option value="approved_by">Approved by</option>
										<option value="finalapproved_by">Final Approved by</option>
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
					employee_id : $('#employee_id option:selected').val(),
				};

				$.post('<?php echo base_url().index_page();?>/setup/signatory/addtolist',$post,function(response){
					app.render(response);
				},'json');

			},signatory_list:function(){

				$post = {
					form        : $('#form option:selected').val(),
					signatory   : $('#signatory option:selected').val(),					
				};

				$.post('<?php echo base_url().index_page();?>/setup/signatory/signatory_list',$post,function(response){
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
						div += "<td><a href='javascript:void(0)' class='remove' data-empid='"+val.employee_id+"'>Remove</a></td>";					
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
					form        : $('#form option:selected').val(),
					signatory   : $('#signatory option:selected').val(),
					employee_id : $(this).attr('data-empid'),
				}

				$.post('<?php echo base_url().index_page();?>/setup/signatory/remove',$post,function(response){
					app.render(response);
				},'json');

			}

		};
		app.init();

	});	
</script>