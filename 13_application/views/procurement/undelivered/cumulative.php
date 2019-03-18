

<?php 	
	$total_debit  = 0;
	$total_credit = 0;

	$po_list = array();
	$total_cost = 0;
	$total_balance = 0;
 ?>
<table class="table myTable table-condensed table-striped table-hover">
	<thead>
		<tr>
			<th>PO#</th>
			<th>Date</th>
			<th>Total Amount</th>
			<th>No Items</th>
			<th>Project</th>
			<th>Supplier</th>
			<th>Delay</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($result as $key=>$value): ?>

		<?php  
			$project_requestor = isset($value['project_requestor'])? $value['project_requestor'] : '' ; 
		?>
			<tr class="clickable" data-id="<?php echo $value['po_id']; ?>">
				<?php if(!isset($po_list[$value['reference_no']])): 
					  $po_list[$value['reference_no']] = '';
				?>
					<td class="po_no"><?php echo $value['reference_no']; ?></td>
					<td class="po_date"><?php echo $value['po_date']; ?></td>
					<td class="total_amount"><?php echo $this->extra->number_format($value['total_cost']); ?></td>
					<td><?php echo $value['total_item']; ?></td>
					<td><?php echo $project_requestor; ?></td>
					<td><?php echo $value['Supplier']; ?></td>
					<?php $balance = $value['total_cost']; ?>
				<?php else: ?>
					<td class="po_no"><span style="display:none"><?php echo $value['reference_no']; ?></span></td>
					<td class="po_date"><span style="display:none"><?php echo $value['po_date']; ?></span></td>
					<td class="total_amount"><span style="display:none"><?php echo $this->extra->number_format($value['total_cost']); ?></span></td>
					<td><?php echo $value['total_item']; ?></td>
					<td><?php echo $project_requestor; ?></td>
					<td><?php echo $value['Supplier']; ?></td>					
				<?php endif; ?>				
				<td><?php echo $value['overdue_day']; ?> days</td>
				<?php $total_cost = $total_cost + $value['total_cost']; ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<td></td>
			<td style="font-weight:bold">Total</td>
			<td style="font-weight:bold;"><?php echo $this->extra->number_format($total_cost); ?></td>
			<td></td>
			<td ></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tfoot>
</table>

<script>
	var xhr = "";
	$(function(){

		$('body').on('click','.clickable',function(){
			
			$.fancybox.showLoading();

			$post = {
				po_id : $(this).attr('data-id'),
			};


	        if(xhr && xhr.readystate != 4){
	            xhr.abort();
	        }
	        
			xhr = $.post('<?php echo base_url().index_page();?>/transaction_list/details_undelivered',$post,function(response){
				$.fancybox(response,{
					width     : 1000,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			});

		});

	});
</script>



			