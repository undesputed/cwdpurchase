<style>
#tbl-contact tr td{
	cursor: pointer
}
</style>

<div class="header">
	<h2>Add Supplier</h2>
</div>

<div class="container">
<input type="hidden" id="id" value="" class="clear">

<div class="row">
	<div class="col-md-3">

	<div class="content-title">
		<h3>Create New Supplier</h3>	
	</div>

		<div class="panel panel-default">		
		  <div class="panel-body">

		  		<div class="form-group">

		  			<div class="radio-inline">
		  				<input type="radio" id="vat" name="vat" checked="checked" value="VAT"><label for="vat">VAT</label>
		  			</div>
					
		  			<div class="radio-inline">
		  				<input type="radio" id="non-vat" name="vat" value="NON-VAT"><label for="non-vat">NON-VAT</label>
		  			</div>
					
		  			<input type="text" id="vat-value" class="form-control">
		  		</div>

		  		<div class="form-group">
		  			<div class="control-label">Tin Number</div>		  			
		  			<input type="text" id="tin_number" class="form-control input-sm">
		  		</div>

		  		<div class="form-group">
		  			<div class="control-label">Business Name</div>		  			
		  			<input type="text" id="business_name" class="form-control input-sm required uppercase">
		  		</div>

		  		<div class="form-group">
		  			<div class="control-label">Trade Name</div>		  			
		  			<input type="text" id="trade_name" class="form-control input-sm uppercase">
		  		</div>

		  		<div class="form-group">
		  			<div class="control-label">Address</div>		  			
		  			<input type="text" id="address" class="form-control input-sm uppercase">
		  		</div>
		  </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="content-title">
			<h3>Delivery Info</h3>
		</div>
		<div class="panel panel-default">		
		  <div class="panel-body">

		  		<div class="form-group">
		  			<div class="control-label">Mode of Delivery</div>		  			
		  			<input type="text" id="mode_of_delivery" class="form-control input-sm">
		  		</div>

		  		<div class="form-group">
		  			<div class="control-label">Delivery Lead Time</div>		  			
		  			<input type="text" id="delivery_lead_time" class="form-control input-sm">
		  		</div>

		  		<div class="form-group">
		  			<div class="control-label">Credit Line Limit</div>		  			
		  			<input type="text" id="credit_line_limit" class="form-control input-sm">
		  		</div>
				
				

		  		<div class="form-group">
		  			<div class="control-label">Payment Terms</div>
		  			<div class="row">
					<div class="col-md-7">
						<select type="text" id="payment_terms" class="form-control input-sm">
							<option value="COD">COD</option>
							<option value="In Days">In Days</option>
							<option value="PDC">PDC</option>
						</select>
					</div>
					<div class="col-md-4">
						<input type="text" id="term_days" placeholder="-days-" class="form-control input-sm">
					</div>
				</div>		  			
		  			
		  		</div>

		  </div>
		</div>

	</div>
	<div class="col-md-6">
		<div class="content-title">
			<h3>Contact Info</h3>
		</div>
		<div class="panel panel-default">		
				  <div class="panel-body">
				  		<div class="row">
				  			<div class="col-md-6">
				  				<div class="form-group">
				  					<div class="control-label">Contact Person</div>
									<input type="text" id="contact_person" class="form-control input-sm required uppercase">
				  				</div>
				  			</div>
				  			<div class="col-md-6">
				  				<div class="form-group">
				  					<div class="control-label">Contact No</div>
									<input type="text" id="contact_no" class="form-control input-sm uppercase">
				  				</div>
				  			</div>				  									
				  		</div>
						
				  </div>
				  <table id="tbl-contact" class="table table-striped table-hover">
				  	<thead>
				  		<tr>
							<th>Contact Type</th>
							<th>Contact Number <span class="pull-right"><span id="add" class="btn-link">Add</span> | <span id="remove" class="btn-link">Remove</span></span></th>
				  		</tr>
				  	</thead>
					<tbody>
						<tr>
							<td class="editable contact-type">-</td>
							<td class="editable contact-number">-</td>
						</tr>
					</tbody>
				  </table>				
		</div>		
	</div>
	
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">		
		  		<div class="form-footer">
						<div class="row">
							<div class="col-md-7"> </div>
							<div class="col-md-5">
								<input id="class_save" class="btn btn-success col-xs-5 pull-right btn-sm" type="submit" value="Save">								
							</div>
						</div>
		  	 	</div>	 
		</div>
	</div>
</div>

</div>

<script>

	var app_create ={
		init:function(){

			$('#date').date({
				option : {
					changeYear: true,
					dateFormat:'yy-mm-dd',
				}
			});
			this.bindEvent();

		},get_classification_setup:function(){
			$('.popup-content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/setup/employee_setup/get_position',function(response){
				$('.popup-content').html(response);
				$('.sub-table').dataTable(datatable_option);
			});
		},bindEvent:function(){

			$('#class_save').on('click',this.class_save);
			$('#add').on('click',function(){
				$('#tbl-contact tbody tr:first').clone().find('td').each(function(){
					$(this).text('-');
				}).end().appendTo('#tbl-contact tbody');
			});

			$('#remove').on('click',function(){

				$('#tbl-contact tbody tr').each(function(){
					if($(this).is(':first-child')){

					}else{
						$('#tbl-contact tbody tr:last-child').remove();
						return false;
					}
				});
				
				
			});	

			$('input[name="vat"]').on('change',function(){
				if($('input[name="vat"]:checked').attr('id') == "vat"){
					$('#vat-value').val('12');
				}else{
					$('#vat-value').val('0');
				}				
			});

			$('input[name="vat"]').trigger('change');


		},class_save:function(){

			if($('.required').required()){
				return false;
			}

			var con = confirm('Do you want to Proceed?');
			if(!con){
				return false;
			}
						
			$.save({appendTo : '.fancybox-outer'});
			
			$post = {
				business_name   :  $('#business_name').val(),
				trade_name      :  $('#trade_name').val(),
				address         :  $('#address').val(),
				contact_no      :  $('#contact_no').val(),
				contact_person  :  $('#contact_person').val(),
				tin_number      :  $('#tin_number').val(),
				mode_of_delivery:  $('#mode_of_delivery').val(),
				delivery_lead_time :  $('#delivery_lead_time').val(),
				payment_terms    :  $('#payment_terms').val(),
				credit_line_limit:  $('#credit_line_limit').val(),
				term_days        : $('#term_days').val(),
				vat              : $('input[name="vat"]:checked').val(),
				vat_percentage   : $('#vat-value').val(),
				details          :  app_create.get_details(),
			}

			$.post('<?php echo base_url().index_page();?>/setup/supplier_setup/save_supplier',$post,function(response){

					switch(response){
						case"1": 
							$.save({action : 'success',reload : 'true'});
						break;
						default :
							$.save({action : 'hide'});
						break;
					}

				$('.required,.clear').val('');
				app_sub_classification.get_classification_setup();

			}).error(function(){
				alert('Service Unavailable');
			});
			$('#class_save').val('Save');

		},get_details:function(){
			var data = new Array();
			$('#tbl-contact tbody tr').each(function(i,value){
				var obj = {
					contact_type : $(value).find('td.contact-type').text(),
					contact_number : $(value).find('td.contact-number').text(),
				};
				data.push(obj);
			});
			return data;
		}
	};

	$(function(){		
		app_create.init();

		$('.editable').editable_td();
	});
</script>

