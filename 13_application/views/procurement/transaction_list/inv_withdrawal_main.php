<?php $itemNo = $this->uri->segment(4);?>
<?php $location = $this->uri->segment(3); ?>

<div style="margin-top:1em;">


<div role="tabpanel">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation"><a href="<?php echo base_url().index_page(); ?>/transaction_list/inventory/">Inventory</a></li>
    <li role="presentation" class="active"><a href="<?php echo base_url().index_page(); ?>/transaction_list/withdrawal">Withdrawal</a></li>
    <li role="presentation"><a href="<?php echo base_url().index_page(); ?>/transaction_list/issuance">Issuance</a></li>    
  </ul>
  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="inventory">
			
			<div class="row">
				<div class="col-xs-10">
					<div style="margin:5px">
						<select name="" id="category-select"></select>
					</div>
				</div>
				<div class="col-xs-2">
					<div class="control-group">
						<a href="<?php echo base_url().index_page();?>/print/inv_withdrawal_print" target="_blank" class="action-status"><i class="fa fa-print"></i> Print</a>
					</div>
				</div>
			</div>
			
			<table id="tbl-inventory" class="table table-item">
				<thead>
					<tr>
						<th style="width:40px">ItemNo</th>
						<th>Item Name</th>
						<th style="width:90px">Withdraw Qty</th>
						<th style="width:80px">Unit</th>							
					</tr>
				</thead>
				<tbody>
				
				</tbody>	
			</table>
    </div>

    <div role="tabpanel" class="tab-pane" id="withdrawal">

    </div>

    <div role="tabpanel" class="tab-pane" id="issuance">
		
    </div>

  </div>
  <!--/endtab-->

</div>

</div>

<script>
	$(function(){
		var option = eval(<?php echo $item_category; ?>);
		var a_app = {
			init:function(){
				var op = '';				
					op +="<option value='%'>All</option>";
				$.each(option,function(i,val){
					op += "<option value='"+val.group_id+"'>"+val.group_description+"</option>";
				});				
				$('#category-select').html(op);				
				if(localStorage.getItem('group_id'))
				{
					var group_id = localStorage.getItem('group_id');					
					$('#category-select option[value="'+group_id+'"]').attr({'selected':'selected'});
				}
				this.bindEvent();
			},bindEvent:function(){
				$('#category-select').on('change',this.item_change);
				$('#category-select').trigger('change');
			},item_change:function(){				
				$post = {
					 group_id : $('#category-select option:selected').val(),
					 location : '<?php echo $location; ?>',
					 item_no  : '<?php echo $itemNo; ?>',
				};
				localStorage.setItem('group_id',$post.group_id);
				$.post('<?php echo base_url().index_page();?>/transaction_list/get_withdrawal_item',$post,function(response){
					$('#tbl-inventory').dataTable().fnDestroy();
					$('#tbl-inventory tbody').html(response);
					$('#tbl-inventory').dataTable(datatable_option);
				});
			}
		}		
		a_app.init();		
	});
</script>