<style>
	.search-item{
		font-size  :11px;
		font-weight:bold;
		margin:10px;
	}
</style>
<span style="float:left;position:relative;z-index:9999" class="search-item">ADVANCE SEARCH</span>
<div>
	<div class="searchform">
		<div class='search-sieve'>
			<div class='search-group'>
				<input type='text' id="sr" placeholder='' class="form-control search-textbox" > <span> <i class='fa fa-search'></i></span>
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

<div id="contentt">
</div>

<script>
$(document).ready(function(){
	$('#sr').keypress(function(e){
		if(e.which == 13){
			var search = $('#sr').val();

			$('#contentt').append('<div class="appended-loading"><img src="asset/img/loading.gif" alt="Loading" /> Loading...</div>');
			$.post('<?php echo base_url().index_page();?>/transaction_list/get_po_forapproval_search',{search:search},function(response){
				$('#contentt').html(response);
			});
		}
	});
});
</script>