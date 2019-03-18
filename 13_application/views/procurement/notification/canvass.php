
<input type="hidden" id="pr_id" value="<?php echo $pr_main['pr_id'] ?>">

<div class="container">
	<div class="row" style="margin-top:5px">
		<div class="col-md-3">
			<div class="panel panel-default">		
			  <div class="panel-heading">Supplier List</div>
			  <div class="panel-body">
			  	<ul class="supplier-ul">
			  		<li>
			  			<span>Affiliate</span>
			  			<ul>
			  				<?php foreach($afilliate as $row): ?>
						  	 <li value="<?php echo $row['supplier_id']; ?>"><?php echo $row['business_name']; ?> <a href="javascript:void(0)"  class="add-supplier pull-right sh-hover" data-type="affiliate" data-id="<?php echo $row['supplier_id']; ?>">add</a></li>
						    <?php endforeach; ?>			
			  			</ul>
			  		</li>
			  		<li>
			  			<span>Business</span>
			  				<ul>
				  				<?php foreach($business as $row): ?>
							  	 <li value="<?php echo $row['supplier_id']; ?>"><?php echo $row['business_name']; ?> <a href="javascript:void(0)"  class="add-supplier pull-right sh-hover" data-type="business" data-id="<?php echo $row['supplier_id']; ?>">add</a></li>
							    <?php endforeach; ?>
						    </ul>
			  		</li>
			  	
			    </ul>
			  </div>
			</div>
		</div>
		<div class="col-md-9">
			
			<div class="panel panel-default">
			 <div class="panel-heading">Canvass Form
			 	<span class="pull-right"><input type="text" readonly class="required" id="cv_date" value="" size="11"> :  <input type="text" id="cv_no" value="" disabled size="14"></span>
			 </div>		
			  <div class="panel-body">
			  	<div class="row">
			  		<div class="col-sm-6">
			  			<table>
							<tbody>
								<tr>
									<td>P.R No :</td>
									<td><?php echo $pr_main['purchaseNo']; ?></td>
								</tr>
								<tr>
									<td>Created From :</td>
									<td></td>
								</tr>
							</tbody>
			  			</table>			  			
			  		</div>
			  		<div class="col-sm-6">
			  			
			  		</div>
			  	</div>
			  </div>			 
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Item No</th>
								<th>Item Description</th> 	
								<th>PR Qty</th> 	
								<th>Unit of Measure</th> 	
								<th>Model No</th> 	
								<th>Serial No</th> 	
								<th>Remarks</th>
							</tr>
						</thead>
						<tbody>
							<?php $cnt = 0; 
							foreach($pr_details as $row): 
								$cnt++;
							?>
								<tr>
									<td><?php echo $cnt;?></td>
									<td><?php echo $row['itemNo']; ?></td>
									<td><?php echo $row['itemDesc']; ?></td>
									<td><?php echo $row['qty']; ?></td>
									<td><?php echo $row['unitmeasure']; ?></td>
									<td><?php echo $row['modelNo']; ?></td>
									<td><?php echo $row['serialNo']; ?></td>
									<td><?php echo $row['remarkz']; ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>			 
			</div>	

			<div class="panel panel-default">		
			 	<div class="panel-heading">Canvass Supplier</div>
			 	<table id="tbl-added-supplier" class="table">
			 		<thead>
			 			<tr>
			 				<th>Supplier Name</th>
			 				<th>Item</th>
			 				<th>Qty</th>
			 				<th>Cost</th>
			 				<th>Total</th>
			 				<th>Remarks</th>
			 			</tr>
			 		</thead>
			 		<tbody>
			 			<tr>
			 				<td colspan="6">Empty list</td>
			 			</tr>
			 		</tbody>
			 	</table>
			</div>

			<div>
				<div class="pull-right">
					<span id="loading" class="fa"></span>					
					<div class="btn-group">
						 <button class="btn btn-primary" id="save">Save</button>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label for="">Prepared by:</label>
						<select name="" id="prepared_by">
						
						</select>
					</div>
					<div class="col-md-4">
						<label for="">Approved by:</label>
						<select name="" id="approved_by">
						
						</select>
					</div>
				</div>				
				<div class="clearfix"></div>
			</div>
			<hr>
			
		</div>		
	</div>
</div>


<script>

	var canvass_supplier = new Array();	
	var render_supplier = function(){
		var $div = "";

		if(canvass_supplier.length > 0){
			$.each(canvass_supplier,function(i,val){				
				$div+="<tr>";
				$div+="<td colspan='6'>"+val.supplier_name+" <span class='pull-right btn-link remove-supplier' data-cnt='"+i+"'>remove</span></td>";
				$div+="</tr>";
				var total = 0;
				$.each(val.items,function(z,value){
						$div+="<tr>";
							$div+="<td></td>";
							$div+="<td>"+value.itemDesc+"</td>";
							$div+="<td>"+value.supp_qty+"</td>";
							$div+="<td>"+value.supp_cost+"</td>";
							$div+="<td>"+value.supp_total+"</td>";
							$div+="<td>"+value.remarks+"</td>";
						$div+="</tr>";
						total = total + +value.supp_total;
				});

				$div+="<tr>";
				$div+="<td></td>";
				$div+="<td><i>Total</i></td>";
				$div+="<td></td>";
				$div+="<td></td>";
				$div+="<td>"+total.toFixed(2)+"</td>";
				$div+="<td></td>";
				$div+="</tr>";

			});
			$('#tbl-added-supplier >tbody').html($div);

		}else{
			$div = "<tr><td colspan='6'>Empty list</td></tr>";
			$('#tbl-added-supplier >tbody').html($div);
		}
	};

	$(function(){

		var app = {
			init:function(){
				this.bindEvent();

				$('#cv_date').date({
					url   : 'get_ccv_code',
					dom   : $('#cv_no'),
					event : 'change',
				});

				var cmbproject = "<?php echo $this->session->userdata('Proj_Code') ?>";
				$.signatory({
					prepared_by   : '',
					recommended_by: ["4", "5", "1",cmbproject],
					approved_by   : ["5", "4", "1",cmbproject],
				});
			},bindEvent:function(){
				$('.add-supplier').on('click',this.add_supplier);
				$('body').on('click','.remove-supplier',this.remove_supplier);
				$('#save').on('click',this.save);
			},add_supplier:function(){
				$.fancybox.showLoading();
				$post = {
					supp_id    : $(this).attr('data-id'),
					type       : $(this).attr('data-type'),
					pr_id      : $('#pr_id').val(),
				}
				$.post('<?php echo base_url().index_page();?>/procurement/canvass_sheet/add_supplier',$post,function(response){					
						$.fancybox(response,{
							width     : 1000,
							height    : 550,
							fitToView : true,
							autoSize  : false,
						})						
				}).error(function(){
					$.fancybox.hideLoading();
					alert('Internal Error');
				});
			},
			remove_supplier:function(){
				var bool = confirm('Do you want to remove?');
				if(!bool){
					return false;
				}
				var cnt = $(this).attr('data-cnt');
				canvass_supplier.splice(cnt, 1);
				render_supplier();
			},save:function(){

				$('#loading').addClass('fa-spin fa-spinner');
				$('#save').addClass('disabled');

				if(canvass_supplier.length<=0){
					$('#loading').removeClass('fa-spin fa-spinner');
					$('#save').removeClass('disabled');

					alert('Please add Canvass supplier !');
					return false;
				}
				$post = {
					c_number   : $('#cv_no').val(),
					c_date     : $('#cv_date').val(),
					pr_id      : $('#pr_id').val(),
					approvedBy : $('#approved_by option:selected').val(),
					preparedBy : $('#prepared_by option:selected').val(),
					data       : canvass_supplier,
				};
								
				$.post('<?php echo base_url().index_page();?>/procurement/canvass_sheet/save_canvass2',$post,function(response){
					$('#loading').removeClass('fa-spin fa-spinner');
					$('#save').removeClass('disabled');

					if($.trim(response)=='1'){
						alert('Successfully Save!');
						window.location = "<?php echo base_url().index_page(); ?>/transaction_list/canvass_sheet";
					}

				});				
			}
		}

		app.init();


	});


</script>