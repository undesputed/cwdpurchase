

<div class="header">
		<div class="container">	
			<div class="row">
				<div class="col-md-8">
					<h2>Purchase Order <small>Edit</small></h2>			
				</div>
				<div class="col-md-4">				
				</div>
			</div>
	</div>
</div>

<div class="container">	
	<form action="" id="form" method="post">
	<div class="row">

		<input type="hidden" id="pr_id"       value="<?php echo $canvass['pr_id']; ?>">
		<input type="hidden" id="supplier_id" value="<?php echo $supplier['business_number']; ?>">
		<input type="hidden" id="can_id"      value="<?php echo $canvass['can_id']; ?>">
		<input type="hidden" id="po_id"       value="<?php echo $main['po_id']; ?>">

		<div class="col-md-12">
			<div class="content-title">
				<h3>Item Information</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">
			  		<div class="row">
			  			<div class="col-md-6">
				  			<div class="form-group">
				  					<div class="control-label">PR No</div>
						  			<input type="text" name="pr_no" id="pr_no" class="form-control input-sm " value="<?php echo $canvass['pr_no'] ?>">
				  			</div>
			  			</div>
	
			  			<div class="col-md-2">
			  				<div class="form-group">
						  			<div class="control-label">Canvass No</div>
						  			<input type="text" name="canvass_no" id="canvass_no" class="form-control input-sm date" value="<?php echo $canvass['canvass_no'] ?>">
					  			</div>
			  			</div>

			  			<div class="col-md-2">
				  				<div class="form-group">
						  			<div class="control-label">Date</div>
						  			<input type="text" name="po_date" id="po_date" class="form-control input-sm date">
					  			</div>
			  			</div>

			  			<div class="col-md-2">
				  				<div class="form-group">
						  			<div class="control-label">Purchase Order.</div>
						  			<input type="text" name="po_no" id="po_no" class="form-control input-sm">
					  			</div>
			  			</div>	

			  		</div>
			  		<div class="row">
			  				<div class="col-md-4">
			  					<div class="row">
			  						<div class="col-md-8">
			  							<div class="form-group">
								  			<div class="control-label">Supplier Name</div>
								  			<input type="text" name="supplier" id="supplier_name" class="form-control input-sm" value="<?php echo $supplier['business_name']; ?>">
						  				</div>			 
			  						</div>
			  						<div class="col-md-4">
			  							<div class="form-group">
								  			<div class="control-label">Supplier Type</div>
								  			<input type="text" name="supplier_type" id="supplier_type" class="form-control input-sm" value="<?php echo $supplier['type']; ?>">
						  				</div>
			  						</div>
				  					
					  			</div> 

					  			<div class="form-group">
						  			<div class="control-label">Place of Delivery</div>
						  			<input type="text" name="place_delivery" id="place_of_delivery" class="form-control input-sm" value="<?php echo $supplier['business_name']; ?>">
					  			</div>
								
					  			<div class="form-group">
						  			<div class="control-label">Po Remarks</div>
						  			<input type="text" name="po_remarks" id="po_remarks" class="form-control input-sm" value="<?php echo $main['po_remarks'] ?>">
					  			</div>
								
			  				</div>	

			  				<div class="col-md-4">
			  					<div class="form-group">
						  			<div class="control-label">Supplier Address</div>
						  			<input type="text" name="supplier_address" id="supplier_address" class="form-control input-sm" value="<?php echo $supplier['address']; ?>">
					  			</div>
					  			<div class="form-group">
						  			<div class="control-label">Supplier Tel No</div>
						  			<input type="text" name="tel_no" id="supplier_tel_no" class="form-control input-sm" value="<?php echo $supplier['contact_no']; ?>">
					  			</div>
					  			<div class="panel panel-default">		
					  			  <div class="panel-body">

					  			  		<div class="radio-inline">
					  			  			<input type="radio" name="tax" id="vat" value="vat" checked><label for='vat'>Vat</label for>
					  			  		</div>
					  			  		<div class="radio-inline">
					  			  			<input type="radio" name="tax" id="non-vat" value="vat"><label for='non-vat'>Non-Vat</label for>
					  			  		</div>

					  			  </div>	 
					  			</div>
			  				</div>

			  				<div class="col-md-4">
			  					<div class="form-group">
						  			<div class="control-label">Delivery Term</div>
						  			<input type="text" name="delivery_term" id="delivery_term" class="form-control input-sm" value="<?php echo $main['deliverTerm']; ?>">
					  			</div>

			  					<div class="form-group">
						  			<div class="control-label">Date of Delivery</div>
						  			<input type="text" name="date_delivery" id="date_of_delivery" class="form-control input-sm date" value="<?php echo $main['dtDelivery']; ?>">
					  			</div>

			  					<div class="form-group">
						  			<div class="control-label">Terms of Payment</div>
						  			<input type="text" name="terms_payment" id="term_of_payment" class="form-control input-sm" value="<?php echo $supplier['term_type'].'-'.$supplier['term_days']; ?>">
					  			</div>
			  				</div>

			  		</div>	  
			  </div>	 
			</div>

		</div>
	</div>
	



	<div class="row">
		<div class="col-md-12">
				<div class="panel panel-default">
				<div class="table-responsive table-overflow">
					  <table class="table table-striped">
					  	<thead>
					  		<tr>
					  			<th>itemNo</th>
					  			<th>ITEM</th>
					  			<th>UNIT</th>
					  			<th>UNIT PRICE</th>
					  			<th>QTY</th>
					  			<th>TOTAL</th>
					  			<th>REMARKS</th>
					  		</tr>
					  	</thead>
					  	<tbody>
					  		<?php foreach($item as $key => $value): ?>
						  		<tr>
						  			<td><?php echo $value['itemNo']?></td>
						  			<td><?php echo $value['ITEM DESCRIPTION']?></td>
						  			<td><?php echo $value['UNIT']?></td>
						  			<td><?php echo $value['UNIT PRICE']?></td>
						  			<td><?php echo $value['QTY']?></td>
						  			<td><?php echo $value['TOTAL']?></td>
						  			<td><?php echo $value['REMARKS']?></td>	
						  		</tr>
					  		<?php endforeach; ?>
					  	</tbody>
					  	<tfoot>
					  		<tr>
					  			<td></td>
					  			<td></td>
					  		</tr>
					  	</tfoot>
					  </table>
					  		
				  </div>		
				</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">		
			  <div class="panel-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
					  			<div class="control-label">Prepared by</div>
					  			<select name="prepared_by" id="prepared_by" class="form-control input-sm"></select>
					  		</div>
						</div>

						<div class="col-md-4">						
							<div class="form-group">
					  			<div class="control-label">Recommended_by</div>
					  			<select name="approved_by" id="recommended_by" class="form-control input-sm"></select>
					  		</div>
						</div>

						<div class="col-md-4">						
							<div class="form-group">
					  			<div class="control-label">Approved By</div>
					  			<select name="approved_by" id="approved_by" class="form-control input-sm"></select>
					  		</div>
						</div>
						
					</div>			  		
			  </div>
			  
			  <div class="form-footer">			  	
					<div class="row">
						<div class="col-md-8"> </div>
						<div class="col-md-4">
							<input id="save" class="btn btn-success  col-xs-5 pull-right" type="submit" value="Update">
						</div>
					</div>					
			  </div>

			</div>
		</div>
	</div>
	</form>

</div>

<script type="text/javascript">
			
	var app_po = {
		init:function(){

			$('#po_date').date({
				url : 'get_po_code',
				dom : $('#po_no'),
				event : 'change',
			});

			$('.date').date();
			
			var option = {
				profit_center : $('#create_profit_center')
				}

			$.signatory({
				approved_by    : ["6","4","1","2"],
				recommended_by : ["6","5","1","2"],
				approved_by_id : '<?php echo $main["approvedBy"] ?>',
				recommended_by_id : '<?php echo $main["recommendedBy"] ?>',
			});

			this.bindEvents();
		},bindEvents:function(){
			//$('#save').on('click',this.save);
			$('#form').bind('submit',this.save);
		},save:function(e){
			e.preventDefault();
			$.save({appendTo : '.fancybox-outer'});
			$post = {
					po_number       :  $('#po_no').val(),    
					po_date         :  $('#po_date').val(),  
					pr_id           :  $('#pr_id').val(),
					supplierID      :  $('#supplier_id').val(),     
					supplierType    :  $('#supplier_type').val(),       
					placeDelivery   :  $('#place_of_delivery').val(),        
					deliverTerm     :  $('#delivery_term').val(),      
					paymentTerm     :  $('#term_of_payment').val(),      
					indays          :  '', 
					dtDelivery      :  $('#date_of_delivery').val(),     
					approvedBy      :  $('#approved_by').val(),     
					preparedBy      :  $('#prepared_by').val(),     
					project_id      :  $('#profit_center option:selected').val(),     
					recommendedBy   :  $('#recommended_by').val(),        
					title_id        :  $('#project option:selected').val(),   
					po_remarks      :  $('#po_remarks').val(),
					notedBy         :  '',
					can_id          :  $('#can_id').val(),
					po_id           :  $('#po_id').val(),
			}
			
			$.post('<?php echo base_url().index_page();?>/procurement/purchase_order/update_purchaseOrder',$post,function(response){
					switch(response){
						case '1' :
							$.save({action : 'success','reload':'true'});
						break;
						default:
							$.save({action : 'hide'});
						break;
					}					
			}).error(function(){
				$.save({action : 'error'});
			});
			
		}
	}

$(function(){
	app_po.init();
});
</script>