<style>
	.selection-table tbody tr:hover{
		cursor: pointer;
		text-decoration: underline;		
	}	
	
	.selection-details-table  tbody tr:hover{
		cursor: pointer;
		text-decoration: underline;		
	}	
	
	.selected{
		background : #19BC9C  !important;
		color:#fff;
	}
	

</style>

<div class="header">
		<div class="container">
	
		<div class="row">
			<div class="col-md-8">
				<h2>Select Approved Canvass</h2>			
			</div>
			<div class="col-md-4">				
			</div>
		</div>

	</div>
</div>

<input type="hidden" id="location" value="<?php echo $location; ?>">
<div class="container">	
	<div class="row">
		<div class="col-md-5">
			<div class="content-title">
				<h3>Canvass List</h3>
			</div>
			<div class="panel panel-default">	
			  <div class="panel-body">
			  		<div class="row">
			  			<div class="col-md-4">
							
			  				<div class="form-group">					  			
					  			<div class="radio-inline">
					  				<input type="radio" name="filter" value="all" id="all"> <label for="all">All</label>
					  			</div>
								<div class="radio-inline">
					  				<input type="radio" name="filter" value="month" id="month" checked> <label for="month">Month</label>
					  			</div>
					  			<input type="text" class="form-control input-sm" id="date">
					  		</div>

			  			</div>
			  			<div class="col-md-4">
							<button id="selection-apply" class="btn btn-primary btn-sm">Apply Filter</button>
			  			</div>
			  		</div>					
			  </div>
			  <div class="selection-loader">
			  		<table class="table table-striped">
			  			<thead>
			  				<tr>
			  					<th>Canvass #</th>
			  					<th>Canvass Date</th>
			  					<th>PR #</th>
			  					<th>PR Date</th>
			  					<th>Project</th>
			  				</tr>
			  			</thead>
			  		</table>
			  </div>
			</div>
		</div>
		<div class="col-md-7">
				<div class="content-title">
					<h3>Item List</h3>
				</div>
				<div class="panel panel-default">

					<div class="panel-body">
						<button id="proceed" class="btn pull-right btn-success disabled">Proceed</button>
					</div>

					<div class="selection-detail-loader">
						<table class="table">
							<thead>
								<tr>
									<th>SUPPLIER</th>
									<th>ITEM DESCRIPTION</th>
									<th>UNIT</th>
									<th>UNIT PRICE</th>
									<th>QTY</th>
									<th>TOTAL</th>
									<th>REMARKS</th>
								</tr>
							</thead>
						</table>



					</div>											
				</div>
		</div>
	</div>
	
	


</div>

<script type="text/javascript">
	
	var app_selection = {
		init:function(){
			$('#date').date();
			this.bindEvents();
		},bindEvents:function(){

			$('#selection-apply').on('click',this.get_canvass);
			$('body').on('click','.selection-table tbody tr',this.get_canvass_details);
			$('body').on('click','.selection-details-table tbody tr',this.selected);
			$('#proceed').on('click',this.proceed);

		},get_canvass:function(){
			$('.selection-loader').content_loader(true);
			$post = {
				type     : $('input[name=filter]:checked').val(),
				date     : $('#date').val(),
				title_id : $('#location').val(),
			};			

			$.post('<?php echo base_url().index_page(); ?>/procurement/purchase_order/canvass_selection',$post,function(response){
				$('.selection-loader').html(response);
			});

		},get_canvass_details:function(){			
			if($(this).hasClass('empty_result')===true){
				return false;
			}
			$('.selection-table tbody tr').each(function(){
				$(this).removeClass('selected');
			});

			$(this).addClass('selected');

			$('.selection-detail-loader').content_loader('true');

			$post = {
				id : $(this).find('td:first').text(),
			};			
			
			$.post('<?php echo base_url().index_page();?>/procurement/purchase_order/canvass_selection_details',$post,function(response){
				$('.selection-detail-loader').html(response);
			});

		},selected:function(){

			$('.selection-details-table tbody tr.selected').removeClass('selected');
			$(this).addClass('selected');
			$('#proceed').removeClass('disabled');			

		},proceed:function(){

			$.save({appendTo : '.fancybox-outer','loading':'Processing...'});

			$selection_main = [];
			$('.selection-table tbody tr.selected >td').each(function(i,val){
				$selection_main.push($(val).text());
			});
			
			$selection_details = [];
			$('.selection-details-table tbody tr.selected >td').each(function(i,val){
				$selection_details.push($(val).text());
			});

			$post = {
				main    : $selection_main,
				details : $selection_details,
			}
				
			$.post('<?php echo base_url().index_page(); ?>/procurement/purchase_order/proceed',$post,function(response){
					$.fancybox(response,{
						width     : 1200,
						height    : 550,
						fitToView : false,
						autoSize  : false,
					})
			});			
		}
	}

$(function(){
	app_selection.init();
});
</script>