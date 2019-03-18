	  
	  <script>

		var enter_payable = function(){
			$.fancybox.showLoading();
			$.get('<?php echo base_url().index_page() ?>/accounting_entry/enter_payable/form',function(response){
				$.fancybox(response,{
					width     : 1300,
					height    : 650,
					fitToView : false,
					autoSize  : false,
				});
			});
		}

	  </script>

	  <div class="row">
			<div class="col-md-6">
				<div class="dropdown search-item" style="float:left;position:relative;z-index:9999">
					<a href="javascript:void(0)" id="filter"  class="search-item dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-filter"></i> Filter </a>
					    <ul class="dropdown-menu filter-menu" role="menu">
					      <li><a href="javascript:void(0)" class="filter-item active" data-url = "<?php echo base_url().index_page(); ?>/transaction_list/get_voucher_list?p=1" >All  <i class="fa fa-check"></i></a></li>
					      <li><a href="javascript:void(0)" class="filter-item "  data-url  = "<?php echo base_url().index_page(); ?>/transaction_list/get_voucher_list?p=1?&filter=pending" >Pending  <i class="fa fa-check"></i></a></li>		
					    </ul>
				</div>
			</div>
			<div class="col-md-6">
				
				<!-- <a href="<?php echo base_url().index_page(); ?>/accounting/voucher/create" class="btn pull-right btn-primary btn-sm history-link" data-method="cash_voucher" data-type="create" data-value="" style="margin:5px">Create Voucher</a>
				<a href="javascript:void(0)" onclick="cash_voucher()" class="btn pull-right btn-primary btn-sm" style="margin:5px">Create Cash Voucher</a> -->
					
				<div class="search-sieve" style="float:left">
					<div class="search-group" style="width:160px">
						<input class="form-control search-textbox" type="text" placeholder="">
						<span>
							<i class="fa fa-search"></i>
						</span>
					</div>	
				</div>
				


				<div class="btn-group pull-right" style="margin:5px">
				  <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    Create Payable <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">
				    <li><a href="javascript:void(0)" onclick="enter_payable()">Enter Accounts Payable</a></li>				    
				  </ul>
				</div>

			</div>
	  </div>



    	<table class="table table-sieve table-mobile">
		  	<thead>
		  		<tr>
		  			<th>Date</th>
		  			<th>Payto / Address / Amount</th>
		  			<th>Project</th>
		  			<th>Status</th>
		  		</tr>
		  	</thead>
		  	<tbody>
		  	
		  	</tbody>				  	
		</table>			
    