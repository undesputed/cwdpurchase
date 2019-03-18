
<input type="hidden" id="is_date">
<input type="hidden" id="is_no">

<div class="t-content">
	<div class="t-header">
		<a href="<?php echo base_url().index_page(); ?>/transaction_list/item_withdrawal" class="close close-info"><span aria-hidden="true">&times;</span><span></a>
		<h4 id="ws-no"><?php echo $main_data['issuance_no']; ?></h4>
	</div>	
	<div class="row">
		<div class="col-md-12">
			<div class="control-group">
				<span class="control-item-group">
					<a href="javascript:void(0)"  class="action-status" id="update">Update</a>
				</span>
				<span class="control-item-group">
					<a href="<?php echo base_url().index_page();?>/print/issuance/<?php echo $main_data['issuance_no']; ?>" target="_blank" class="action-status"><i class="fa fa-print"></i> Print</a>
				</span>
			</div>
		</div>
	</div>
	<table id="item_list" class="table table-item">
		<thead>
			<tr>							
				<th>Item Description</th>
				<th>Unit Measure</th>				
				<th>Issued Qty</th>
				<th>Returned Qty</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($details as $row): ?>
				<tr>
					<td style="display:none" class="item_no"><?php echo $row['item_no'] ?></td>
					<td><?php echo $row['item_description']; ?></td>
					<td><?php echo $row['unit_measure']; ?></td>					
					<td class="issued_qty"><?php echo $row['issued_qty']; ?></td>
					<td><input type="text" class="form-control return-qty" style="width:50px" value="0"></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<th>
					<div><span id="item-count"><?php echo count($details); ?></span> item(s)</div>					
				</th>
				<th></th>
				<th></th>
				<th></th>				
			</tr>			
		</tfoot>
	</table>
		
	<div class="row">
		<div class="col-xs-12">
			<div class="t-title">
				<div>Issued To:</div>
				<strong><?php echo $main_data['issued_to'] ?></strong>
			</div>
		</div>		
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="t-title">
				<div>Remarks/Purpose :</div>
				<strong><p><?php echo $main_data['remarks'] ?></p></strong>
			</div>
		</div>		
	</div>	
	<div class="row">
		<div class="col-xs-6">
			<div class="t-title">
				<div>Prepared By : </div>
				<strong><?php echo $main_data['preparedBy_name']; ?></strong>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="t-title">
				<div>Approved By : </div>
				<strong><?php echo $main_data['approvedBy_name']; ?></strong>
			</div>
		</div>
	</div>
		
</div>

<?php 
		
		$a = array(
			 'transaction_id'=>$main_data['id'],
			 'transaction_no'=>$main_data['issuance_no'],
			 'type'=>'Issuance',
			);

		echo $this->lib_transaction->comments($a);

?>

<script>	
	$(function(){

		var app = {
			init:function(){
				this.bindEvent();
			},bindEvent:function(){
				$('#update').on('click',this.update);
			},update:function(){

				var bool = confirm('Are you sure?');
				if(!bool){
					return false;	
				}

				var details = new Array();
				$('#item_list tbody tr').each(function(i,value){					
					var data = {
						issued_qty : $(value).find('.issued_qty').text(),
						return_qty : $(value).find('.return-qty').val(),
						item_no    : $(value).find('.item_no').text(),
					}
					details.push(data);
				});

				$post = {
					data  : details,
					is_id : '<?php echo $main_data['id']; ?>',
					is_no : '<?php echo $main_data['issuance_no']; ?>',
				}

				$.post('<?php echo base_url().index_page();?>/transaction_list/update_issuance',$post,function(response){
					switch($.trim(response)){
						case "1":
							alert('Successfully Updated');
							location.reload('true');
						break;
					}

				});


			}	
		}		

		app.init();

	});
</script>
