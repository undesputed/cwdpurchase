<?php $id =  $this->uri->segment(3); ?>

<div class="container">
	<div class="row" style="margin-top:5px">

		<div class="col-xs-3">
			<div class="panel panel-default">		
			  <div class="panel-heading">
			  	Select Canvass Supplier
			  </div>
			  <div class="panel-body">
					<ul class="sidebar">
						
						<?php 
							foreach($canvass_supplier as $row){
								foreach($row as $key=>$row1)
								{
									?>
									<li><a href="<?php echo base_url().index_page(); ?>/transaction/purchase_order/<?php echo $id; ?>/cv/<?php echo $row1[0]['supplier_id']; ?>" class="supplier-li"><?php echo $key; ?></a></li>
									<?php
								}
							}
			  			?>
					</ul>
			  </div>	 
			</div>
		</div>
		<div class="col-xs-9">
			<div></div>				
		</div>
		
	</div>	
</div>


<script>

	$(function(){

		var app_po = {
			init:function(){

				$('.date').date();

				$('#po_date').date({
					url : 'get_po_code',
					dom : $('#po_no'),
					event : 'change',
				});

				var cmbproject = '<?php echo $this->session->userdata("Proj_Code"); ?>';
				$.signatory({
					approved_by    : ["6","4","1",cmbproject],
					recommended_by : ["6","5","1",cmbproject],
				});				
				this.bindEvents();

			},bindEvents:function(){
				$('#save').on('click',this.save);
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
						qty      : $(val).find('.qty').text(),
						unit_price : $(val).find('.unit-price').text(),
						total    : $(val).find('.total').text(),
						remarks  : $(val).find('.remarks').text(),
					}

					details_main.push(details);
					details = [];

				});

				$post = {					
					po_number     : $('#po_no').val(),
					po_date       : $('#po_date').val(),
					pr_id         : $('#pr_id').val(),
					supplierID    : $('#supplier_id').val(),
					supplierType  : $('#supplier_type').val(),
					placeDelivery : $('#pod').val(),
					deliverTerm   : $('#delivery_term').val(),
					paymentTerm   : $('terms_payment').val(),
					indays        : '',
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
						break;
						case "2":
							alert('Already Save!');
							location.reload(true);						
						break;
					}
				});



			}
		}
		app_po.init();
	});

</script>