
<div>
	<div class="dropdown" style="float:left;position:relative;z-index:9999;display:none" >
		<a href="javascript:void(0)" id="filter"  class="search-item dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-filter"></i> Filter </a>
		    <ul class="dropdown-menu filter-menu" role="menu">
		    	<li><a href="javascript:void(0)" class="filter-item active" data-url = "<?php echo base_url().index_page(); ?>/transaction_list/get_item_issuance_list?p=1" >All  <i class="fa fa-check"></i></a></li>
		      <li><a href="javascript:void(0)" class="filter-item "  data-url  = "<?php echo base_url().index_page(); ?>/transaction_list/get_item_issuance_list?p=1?&filter=pending" >Pending  <i class="fa fa-check"></i></a></li>
		      <li><a href="javascript:void(0)" class="filter-item"  data-url  = "<?php echo base_url().index_page(); ?>/transaction_list/get_item_issuance_list?p=1?&filter=approved">Approved <i class="fa fa-check"></i></i></a></li>
		      <li><a href="javascript:void(0)" class="filter-item"  data-url  = "<?php echo base_url().index_page(); ?>/transaction_list/get_item_issuance_list?p=1?&filter=rejected">Rejected <i class="fa fa-check"></i></a></li>
		    </ul>
	</div>

	
	
	<div class="searchform">
		<div class='search-sieve'>
			<div class='search-group'>
				<input type='text' placeholder='' class="form-control search-textbox" > <span> <i class='fa fa-search'></i></span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<a data-method="issuance" data-type="create" data-value="" href="<?php echo base_url().index_page(); ?>/transaction_list/item_issuance/create" class=" history-link btn pull-right btn-primary btn-sm" style="margin:5px">Create Issuance</a>
		</div>
	</div>

</div>				

	  
	  <table class="table table-sieve">
	  	<thead>
	  		<tr>
	  			<th>Date</th>
	  			<th>Issuance No.</th>	
	  			<th>Issued To</th>			  			
	  			<th>No Items</th>	  			
	  			<th>Remarks</th>
	  			<th>Status</th>
	  		</tr>
	  	</thead>	
	  	<tbody>

	  	</tbody>				  	
	  </table>
	