
<input type="hidden" value="" class="date" id="po_date">
<input type="hidden" value="" class="" id="po_no">
<input type="hidden" value="<?php echo $canvass_main['pr_id']; ?>" id="pr_id">
<input type="hidden" value="<?php echo $canvass_main['can_id'] ?>" id="can_id">

<input type="hidden" value="<?php echo $supplier_info['supplier_id'] ?>" id="supplier_id">
<input type="hidden" value="<?php echo $supplier_info['supplierType'] ?>" id="supplier_type">

<div class="content-title">
	<h3>Create P.O</h3>	
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="t-content">			
			<div class="row">
				<div class="col-md-2">
					<div class="panel panel-default">
						<div class="panel-heading">
							Canvass Supplier
						</div>
						<table class="table">
							<tbody>
								<?php foreach($supplier as $row): ?>
								<?php 
									$supplier_id = $this->uri->segment(4);
									$active = ($row['supplier_id'] == $supplier_id )? 'act': '';
								 ?>
									<tr class="<?php echo $active; ?>">
										<td>
											<?php if(!empty($row['po_id'])): ?>
												<label  class="label label-success">Already PO</label>
											<?php endif; ?>
											<a href="<?php echo base_url().index_page(); ?>/transaction/create_po/<?php echo $canvass_main['c_number']; ?>/<?php echo $row['supplier_id']; ?>">											
											<?php echo $row['Supplier'] ?>
											</a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-10">
					<a href="<?php echo base_url().index_page(); ?>/transaction_list/purchase_order/for_po" class="close"><span aria-hidden="true">&times;</span><span></a>
				</div>


		</div>		
	</div>
</div>

<script>
	
		var po = {
			init:function(){

				$('#date_of_delivery').date();
				$('#po_date').date({
					url : 'get_po_code',
					dom : $('#po_no'),
					div   : $('#po-no-title'),
					event : 'change',
				});

				var cmbproject = '<?php echo $this->session->userdata("Proj_Code"); ?>';

				$.signatory({
					approved_by    : ["6","4","1",cmbproject],
					recommended_by : ["6","5","1",cmbproject],
				});

				$(".editable").editable("<?php echo base_url().index_page(); ?>/ajax/display", { 
					      indicator : "<img src='<?php echo base_url().index_page();?>/asset/img/indicator.gif'>",
					      tooltip   : "Click to edit...",					      
					      width     : $('.editable').width() + 50,
					      callback  : function(value, settings){							
							var unit = $(this).closest('tr').find('.unit-price').text();
							var total = parseFloat(value) * parseFloat(unit);
							$(this).closest('tr').find('.total').html(comma(total));
							var grand_total = 0;
							$('#tbl_pr_details tbody tr').each(function(i,val){
								var total = remove_comma($(val).find('.total').text());								
								grand_total +=parseFloat(total);
							});
							$('#grand_total').html(comma(grand_total));
					      }
				});

				this.bindEvent();

			},
			bindEvent:function(){
				$('#supplier').on('change',this.po_items);
				$('#supplier').trigger('change');
				$('#save').on('click',this.save);
			},
			po_items:function(){
				$post = {
					can_id : $('#supplier option:selected').attr('data-can_id'),
					supplier_id : $('#supplier option:selected').val(),
				}
				$.post('<?php echo base_url().index_page();?>/transaction/get_po_items',$post,function(response){
					$('#po_items').html(response);
				});
			},save:function(){				
				if($('.required').required()){
					return false;					
				}

				var bool = confirm('Are you sure?');

				if(!bool){
					return false;
				}


				var details = new Array();
				var details_main = new Array();
				$('#tbl_pr_details tbody tr').each(function(i,val){

					details = {
						itemNo   : $(val).find('.item-no').text(),
						itemDesc : $(val).find('.item-desc').text(),
						unit     : $(val).find('.unit').text(),
						qty      : remove_comma($(val).find('.qty').text()),
						unit_price : remove_comma($(val).find('.unit-price').text()),
						total    : remove_comma(($(val).find('.total').text())),
						remarks  : $(val).find('.remarks').text(),
					}

					details_main.push(details);
					details = [];

				});
				
				$post = {
						can_id		  : $('#can_id').val(),
						po_number     : $('#po_no').val(),
						po_date       : $('#po_date').val(),
						pr_id         : $('#pr_id').val(),
						supplierID    : $('#supplier_id').val(),
						supplierType  : $('#supplier_type').val(),
						placeDelivery : $('#place_delivery').val(),
						deliverTerm   : $('#delivery_term').val(),
						paymentTerm   : $('#terms_payment option:selected').val(),
						indays        : $('#in_days').val(),
						dtDelivery    : $('#date_of_delivery').val(),
						approvedBy    : $('#approved_by').val(),
						preparedBy    : $('#prepared_by').val(),					
						recommendedBy : $('#recommended_by').val(),
						title_id      : '<?php echo $this->session->userdata("Proj_Main"); ?>',
						project_id    : '<?php echo $this->session->userdata("Proj_Code"); ?>',
						po_remarks    : $('#remarks').val(),
						data          : details_main,
				}
								
				
				$.post('<?php echo base_url().index_page();?>/procurement/purchase_order/save_purchaseOrder',$post,function(response){
					switch($.trim(response)){
						case "1":
							alert('Successfully Save');
							location.reload(true);
							/*window.location = "<?php echo base_url().index_page(); ?>/transaction_list/purchase_order";*/
						break;
						case "2":
							alert('Already Save!');
							location.reload(true);						
						break;
					}
				});

			}
		}

	$(function(){
		po.init();
	});
</script>