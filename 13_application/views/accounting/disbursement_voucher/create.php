
<div class="t-content">
	<div class="t-header">
		<a href="<?php echo base_url().index_page(); ?>/accounting/voucher/" class="close close-info"><span aria-hidden="true">&times;</span><span></a>
		<h4>For Vouchering List</h4>
	</div>



<div class="table-responsive" style="overflow:auto">

	  	<table class="table table-hover" id="item-list">
  			<thead>
  				<tr>
  					<th>PO NUMBER</th>
  					<th>PO DATE</th>
  					<th>SUPPLIER</th>
  					<th>PO AMOUNT</th>
  					<th>TERM</th>
  					<th>STATUS</th>  				
  				</tr>
  			</thead>
  			<tbody>
  				<?php if(count($result) > 0): ?>
		          <?php foreach($result as $row): ?>
							 <tr>
					              <td><a class="po_voucher" href="javascript:void(0);" data-po_id="<?php echo $row['po_id']; ?>" ><?php echo $row['PO NUMBER']; ?></a></td>
					              <td><?php echo $row['PO DATE']; ?></td>
					              <td><?php echo $row['SUPPLIER']; ?></td>
					              <td><?php echo $row['PO AMOUNT']; ?></td>
					              <td><?php echo $row['TERM'] ?></td>
					              <td><?php echo $row['STATUS'] ?></td>
		         			 </tr>
		          <?php endforeach; ?>
  				<?php endif; ?>

  			</tbody>		
  		</table>

</div>



</div><!--/t-content-->


<script>
	$(function(){		

		

		var app_info = {
			init:function(){
				this.bindEvents();
				$('#item-list').dataTable(datatable_option);
			},bindEvents:function(){
				$('.po_voucher').on('click',this.po_voucher);
			},po_voucher:function(){

				$.fancybox.showLoading();
				$post = {
					po_id : $(this).attr('data-po_id'),
				};				
				$.post('<?php echo base_url().index_page();?>/accounting/create_voucher',$post,function(response){
					$.fancybox(response,{
						width     : 1000,
						height    : 550,
						fitToView : false,
						autoSize  : false,
					})
				});

			}
		}

		app_info.init();

	});	
</script>