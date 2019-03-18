
<div class="container">
	
	<div class="row">
		<div class="col-md-6">
			<h4>Canvass Items</h4>
			<table id="item-table" class="table">
				<thead>
					<tr>
						<th>Item Description</th>
						<th>Unit</th>						
					</tr>
				</thead>
				<tbody>
					<?php foreach($item as $row): ?>
					<tr>						
						<td><a href="javascript:void(0)" class="item-link" data-itemno="<?php echo $row['item_no'] ?>"><?php echo $row['item']; ?></a></td>
						<td><?php echo $row['unit_measure']; ?></td>
					</tr>						
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div class="col-md-6">
			<div class="supplier-item">
				
			</div>
		</div>		
	</div>
	
</div>

<script>
	$(function(){

		var item_search = {
			init:function(){			
				this.bindEvent();
				this.datatable();
			},bindEvent:function(){
				$('.item-link').on('click',this.item_link);
			},datatable:function(){
				$('#item-table').dataTable(datatable_option);
			},item_link:function(){
				

				$post = {
					item_no : $(this).attr('data-itemno'),
					item_name : $(this).text(),
				}					
				$.post('<?php echo base_url().index_page();?>/transaction/supplier_item',$post,function(response){
					$('.supplier-item').html(response);
				});

			}
		}

		item_search.init();
	});
</script>