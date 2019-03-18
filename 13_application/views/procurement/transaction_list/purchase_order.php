<script>		
	var summary_xhr = ""
	$(function(){	
		
		var search_item  = {
			init:function(){							
				this.bindEvent();
			},bindEvent:function(){
				$('#po_summary').on('click',this.search_item);
			},search_item:function(){
				$.fancybox.showLoading();				
		        if(summary_xhr && summary_xhr.readystate != 4){
		            summary_xhr.abort();
		        }


				summary_xhr = $.get('<?php echo base_url().index_page();?>/transaction/po_summary',function(response){					
					$.fancybox(response,{
						width     : 1000,
						height    : 550,
						fitToView : false,
						autoSize  : false,
					})					
				});
			}
		};		
		
		search_item.init();	
				
	});

	$(document).ready(function(){
		$('#advance_search').on('click',function(){
			$.fancybox.showLoading();
			$.post('<?php echo base_url().index_page();?>/transaction_list/advance_search',function(response){
				/*$('.right-menu').html(response);*/
				$.fancybox(response,{
					width     : 700,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				});
			});
			
		});
	});
</script>

<style>
	.search-item{
		font-size  :11px;
		font-weight:bold;
		margin:10px;
	}
</style>
<div>
	<div class="dropdown search-item" style="float:left;position:relative;z-index:9999">
		<a href="javascript:void(0)" id="filter"  class="search-item dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-filter"></i> Filter </a>
		    <ul class="dropdown-menu filter-menu" role="menu">
		      <li><a href="javascript:void(0)" class="filter-item " data-url = "<?php echo base_url().index_page(); ?>/transaction_list/get_po_forapproval?p=1&filter=all" >All  <i class="fa fa-check"></i></a></li>
		      <li><a id="pending" href="javascript:void(0)" class="filter-item active"  data-url  = "<?php echo base_url().index_page(); ?>/transaction_list/get_po_forapproval?p=1&filter=pending" >Pending  <i class="fa fa-check"></i></a></li>
		      <li><a href="javascript:void(0)" class="filter-item"  data-url  = "<?php echo base_url().index_page(); ?>/transaction_list/get_po_forapproval?p=1&filter=approved">Approved <i class="fa fa-check"></i></i></a></li>
		      <li><a href="javascript:void(0)" class="filter-item"  data-url  = "<?php echo base_url().index_page(); ?>/transaction_list/get_po_forapproval?p=1&filter=rejected">Rejected <i class="fa fa-check"></i></a></li>
		      <li><a href="javascript:void(0)" class="filter-item"  data-url  = "<?php echo base_url().index_page(); ?>/transaction_list/get_po_forapproval?p=1&filter=without_print">Approved Without Print <i class="fa fa-check"></i></a></li>
		    </ul>
	</div>

	<?php if($this->lib_auth->restriction('ADMIN')): ?>
	  <a href="javascript:void(0)" id="po_summary" style="float:left;position:relative;z-index:9999" class="search-item">PO Summary List</a>
	<?php  endif;?>
	<a href="javascript:void(0)" id="advance_search" style="float:left;position:relative;z-index:9999" class="search-item">Advance Search</a>
	
	<div class="searchform">
		<div class='search-sieve'>
			<div class='search-group'>
				<input type='text' placeholder='' class="form-control search-textbox" > <span> <i class='fa fa-search'></i></span>
			</div>
		</div>
	</div>

</div>


 <table class="table table-sieve table-mobile">
  	<thead>
  		<tr>
  			<th>Date</th>
  			<th>P.O</th>
  			<th>Supplier</th>
  			<th>Items</th>
  			<th>Delivery Date</th>
  			<th>Status</th>
  		</tr>
  	</thead>	
  	<tbody>
		  	
  	</tbody>				  	
  </table>



	
