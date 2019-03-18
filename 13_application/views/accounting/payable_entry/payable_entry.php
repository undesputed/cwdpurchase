<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Payable Entry</h2>
</div>

<script>
	$(function(){
			var date  = new Date();
			var month = date.getMonth() + 1;		
			$('#month').val(month);
	});

</script>
<div class="container">
	<div class="panel panel-default" style="margin-top:1em;">		
		  <div class="panel-body">
		  			<div class="row">
						<div class="col-md-6">
							<table class="table">
								<tr>
									<td >Project Site : </td>
									<td  colspan="3">
										<select name="" id="profit_center" class="form-control">
											<?php foreach($project as $row): ?>
											<option value="<?php echo $row['project_id'] ?>"><?php echo $row['project_full'] ?></option>
											<?php endforeach; ?>
										</select>
									</td>
								</tr>
								<tr>
									<td>PO No. : </td>
									<td><input type="text" class="form-control required numbers_only" id="po_no"></td>
									<td>PO Date : </td>
									<td><input type="date" class="form-control date required" id="po_date"></td>
								</tr>
								<tr>
									<td>Supplier : </td>
									<td  colspan="3">										
										<select name="" id="supplier_list" class="form-control">
											<?php foreach($supplier as $key=>$row): ?>
												 <optgroup label="<?php echo $key; ?>">
												 	<?php foreach($row as $row1): ?>
														<option data-type="<?php echo $key ?>" value="<?php echo $row1['supplier_id']; ?>" data-address="<?php echo $row1['address']; ?>" data-contact="<?php echo $row1['contact_no']; ?>"><?php echo $row1['business_name']; ?></option>
												 	<?php endforeach; ?>
												  </optgroup>
											<?php endforeach; ?>
										</select>
									</td>
								</tr>
								<tr>
									<td>PO Amount : </td>
									<td  colspan="3"><input type="text" class="form-control numbers_only required" id="po_amount"></td>
								</tr>
								<tr>
									<td>Invoice No : </td>
									<td>
										<input type="text" class="form-control  required" id="invoice_no">
									</td>
									<td>Invoice Date : </td>
									<td><input type="text" class="form-control" id="invoice_date"></td>
								</tr>
								<tr>
									<td>Terms : </td>
									<td>
										<select name="" id="terms" class="form-control">									
											<option value="COD">COD</option>
											<option value="In Days">In Days</option>
										</select>
									</td>
									<td>Days : </td>
									<td><input type="number" class="form-control" value="1" id="no_days"></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td><button id="save" class="pull-right btn-primary btn">Save</button></td>
								</tr>
							</table>
						</div>
					</div>
		  </div>	 
	</div>
	
</div>
</div>
</div>


<script>
	var xhr = "";
	var app ={

		init:function(){
			$('.date').date();		
			app.bindEvent();
		},bindEvent:function(){
			$('#save').on('click',this.save);
			
		},save:function(){
			if($('.required').required()){
				return false;
			}

			var bool = confirm('Are you Sure to Proceed?');
			if(!bool){
				return false;
			}

			$post = {
				project_id    : $('#profit_center option:selected').val(),
				po_no         : $('#po_no').val(),
				po_date       : $('#po_date').val(),
				supplier_id   : $('#supplier_list option:selected').val(),
				supplier_name : $('#supplier_list option:selected').text(),
				terms         : $('#terms option:selected').val(),
				no_days       : $('#no_days').val(),	
				invoice_no    : $('#invoice_no').val(),
				invoice_date  : $('#invoice_date').val(),
			};

			/*$.post('<?php echo base_url().index_page();?>/');*/
		}

	};

	$(function(){		
		app.init();
	});
</script>