<style>
	#approved-tbl tbody tr:hover{
		cursor:pointer;
		text-decoration: underline;
	}
</style>

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<div class="content-title">
				<h3>Approved Canvass</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">
			  		<div class="row">
			  			<div class="col-md-6">
			  				<div class="form-group">
				  				<div class="control-label">Canvass No</div>
				  				<input type="text" class="form-control input-sm" value="<?php echo $main['c_number']; ?>">
			  				</div>
			  			</div>
			  			<div class="col-md-6">
			  				<div class="form-group">
				  				<div class="control-label">Date</div>
				  				<input type="text" class="form-control input-sm" value="<?php echo $main['c_date']; ?>">
			  				</div>
			  			</div>
			  		</div>		
			  		<div class="row">
			  			<div class="col-md-12">
			  				<div class="form-group">
				  				<div class="control-label">
				  					Approved by
				  				</div>
				  				<select name="" id="approved_by" class="form-control input-sm">
				  					
				  				</select>
			  				</div>
			  			</div>
			  		</div>	  		
			  </div>	 
			</div>
		</div>
		<div class="col-md-8">
			<div class="content-title">
				<h3>Item List</h3>
			</div>
			<div class="panel panel-default">					 
			  <table class="table table-striped" id="approved-tbl">
			  		<thead>
			  			<tr>
			  				<th>Description</th>
			  				<th>Unit</th>
			  				<th>Requested Qty</th>
			  				<th>Approved Qty</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  		<?php foreach($items as $row): ?>	
						<tr>
							<td style="display:none"><?php echo $row['can_id']; ?></td>
							<td style="display:none"><?php echo $row['itemNo']; ?></td>
							<td><?php echo $row['DESCRIPTION']; ?></td>
							<td><?php echo $row['UNIT']; ?></td>
							<td><?php echo $row['REQUESTED QTY']; ?></td>
							<td><?php echo $row['APPROVED QTY']; ?></td>
						</tr>
			  		<?php endforeach; ?>
			  		</tbody>
			  </table>
			</div>
		</div>
	</div>
	<div class="content-title">
		<h3>Supplier List</h3>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">		
			    <div class="approve-table-content">
					<table class="table">
						<thead>
							<tr>
								<th>Approve</th>
								<th>Supplier</th>
								<th>Unit</th>
								<th>Stock</th>
								<th>Unit Price</th>
								<th>Qty</th>
								<th>Rem</th>
								<th>Total</th>
							</tr>
						</thead>
					</table>
				</div>
				<div class="form-footer">
					<div class="row">
					<div class="col-md-8"> </div>
						<div class="col-md-4">
							<input id="save" class="btn btn-success col-xs-5 pull-right input-sm" type="submit" value="Save">							
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>

</div>

<script>

var data = new Array();



	var app_approved = {
		init : function(){

			this.bindEvents();
			$.signatory({
				approved_by  : ["5", "4", "1","2"],
				approved_by_id : "<?php echo $main['approvedBy']; ?>",
			});

		},bindEvents : function(){
			$('#approved-tbl tbody tr').on('click',this.get_data_supplier);
			$('#save').on('click',this.approved);
		},get_data_supplier:function(){
			data = {
				can_id  : $(this).find('td:first').text(),
				item_no : $(this).find('td:nth-child(2)').text(),
			};
			app_approved.get_supplier();
		},get_supplier : function(){
			$post = {
				can_id  : data.can_id,
				item_no : data.item_no,
			};
			$('.approve-table-content').content_loader('true');
			$.post('<?php echo base_url().index_page(); ?>/procurement/canvass_sheet/get_supplier',$post,function(response){
				$('.approve-table-content').html(response);
			});
		},approved : function(){
			$.save({appendTo : '.fancybox-outer'});
			var item = new Array();
			$('.approved-chk').each(function(i,val){

				var approved = {
					can_id      : $(val).attr('data-can-id'),
					item_no     : $(val).attr('data-item-no'),
					supplier_id : $(val).attr('data-supplier-id'),
					sup_qty     : $(val).attr('data-sup-qty'),
					qty         : $(val).attr('data-qty'),
					stocks      : $(val).attr('data-stocks'),
					status      : $(val).is(':checked'),
					approval_by : $('#approved_by').val(),
				};
				item.push(approved);
			});
			$post = {
				data : item,
			}
			$.post('<?php echo base_url().index_page();?>/procurement/canvass_sheet/change_status_supplier',$post,function(response){
				if(response==1){
					$.save({action : 'success','reload':'true'});
					app_approved.get_supplier();
					app.get_cumulative();

				}
			});

		}
	}
$(function(){
	app_approved.init();
});



</script>