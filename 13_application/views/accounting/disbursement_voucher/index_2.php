
<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Disbursement Voucher</h2>
</div>

<div class="container">

	<div style="margin-top:5px">
		<ul class="nav nav-tabs" role="tablist">
		    <li class="active"><a href="<?php echo base_url().index_page(); ?>/accounting/voucher">Main</a></li>
		    <li><a href="<?php echo base_url().index_page(); ?>/accounting/voucher/cumulative">Cumulative Data</a></li>
	  	</ul>
  	</div>

		<div class="content-title">
			<h3>Menus</h3>	
		</div>
			
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-3">
						<table class="table">							
							<tr>
								<td style="width:80px">Bank :</td>
								<td>
									<select name="" id="bank_list" class="form-control">
										<?php foreach($bank as $row): ?>	
											<option value="<?php echo $row['bank_id']; ?>"><?php echo $row['bank_name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>DV No.:</td>
								<td>
									<select name="" id="" class="form-control">
									<?php foreach($dv_no as $row): ?>
										<option value="<?php echo $row['cvdtl_id']; ?>"><?php echo $row['cv_no']; ?></option>									
									<?php endforeach; ?>
									</select>
							</td>
							</tr>
							<tr>
								<td>DV Date:</td>
								<td><input type="text" class="form-control" id="date"></td>
							</tr>
						</table>
					</div>
					<div class="col-md-4">
						<table class="table">							
							<tr>
								<td style="width:80px">PO No. :</td>
								<td>
									<div class="inline">
										<input type="text" class="form-control" value="">
									</div>
									<div class="inline">
										<button id="btn_po_list" class="btn btn-sm pull-left">...</button>
									</div>
								</td>
							</tr>
							<tr>
								<td>Sales Invoice No:</td>
								<td><input type="text" class="form-control" value=""></td>
							</tr>
							
						</table>
					</div>
					<div class="col-md-4">
						<table class="table">							
							<tr>
								<td style="width:80px">Supplier. :</td>
								<td><input type="text" class="form-control" value=""></td>
							</tr>
							<tr>
								<td>Type:</td>
								<td><input type="text" class="form-control" value=""></td>
							</tr>
							<tr>
								<td>Remarks:</td>
								<td><input type="text" class="form-control" value=""></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>


		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<table class="table table-condensed">
							<thead>
								<tr>
									<th>PO No.</th>
									<th>PO Items</th>
									<th>Account Code</th>
									<th>Account Description</th>
									<th>Amount</th>
								</tr>
							</thead>
							<tbody>								
									<tr>
										<td colspan="5">Empty List</td>
									</tr>								
							</tbody>
							<tfoot>
								<tr>
									<td>0 item(s)</td>
									<td></td>
									<td></td>
									<td style="text-align:right">Total : </td>
									<td>-</td>									
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
			 <div class="form-footer">			  	
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
							  			<div class="control-label">Prepared by : </div>
							  			<select name="checked_by" id="checked_by" class="form-control input-sm"></select>
							 </div>
						</div>
						<div class="col-md-5"></div>
						<div class="col-md-4">
							<input id="save" class="btn btn-success  col-xs-5 pull-right" type="submit" value="Save">
							<!-- <input id="reset" class="btn btn-link  pull-right" type="reset" value="Reset"> -->
						</div>
					</div>					
			  </div>
		</div>

	
			
</div>


</div>
</div>
<script>

	var application ={
		init:function(){
			this.bindEvent();
			$('#date').date();
		},bindEvent:function(){
			$('#btn_po_list').on('click',this.open_po_list);
		},open_po_list:function(){

			$( "#dialog" ).dialog({
				modal :  true, 
				title : 'Received PO List',
				width :  '700px'
			});

			$('#dialog').html('Loading...');

			$.post('<?php echo base_url().index_page();?>/accounting/get_po_list',function(response){
				$('#dialog').html(response);
			}).error(function(){
				$('#dialog').html('ERROR!, FAILED TO LOAD DATA');
			});

		}
	};

	$(function(){		
		application.init();
	});
</script>