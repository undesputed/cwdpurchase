
<div class="content-page">
 <div class="content">
<style>
	.tbl-journal tbody tr:hover{
		cursor: pointer;	
	}
	.tbl-journal tbody tr:first-child >td.remove >.remove{
		display:none;	
	}
</style>

<div class="header">
	<h2>Journal Entry</h2>
</div>


<div style="margin-top:5px">
		<ul class="nav nav-tabs" role="tablist">
		    <li><a href="<?php echo base_url().index_page(); ?>/accounting_entry/journal_entry">Main</a></li>
		    <li class="active"><a href="<?php echo base_url().index_page(); ?>/accounting_entry/journal_entry/cumulative">Cumulative Data</a></li>
	  	</ul>
 </div>

<div class="container">
<div class="row">
	<div class="col-md-12">	
		<div class="panel panel-default" style="margin-top:5px">		
		  <div class="panel-body" >
		  	
				<div class="row">
					<div class="col-md-4">
							<div class="form-group inline">
								<div class="control-label">Date From</div>
									<input type="text" id="date_from" class="form-control" style="width:120px">
							</div>
							
							<div class="form-group inline">
								<div class="control-label">Date To</div>
								<input type="text" id="date_to" class="form-control" style="width:120px">
							</div>	
					</div>
					<div class="col-md-3">
						<button id="filter" class="btn btn-success" style="margin-top:10px">Apply Filter</button>
					</div>
				</div>				
				<div id="data">					
				</div>									
		  </div>	 
		</div>
	</div>
</div>


	
		
</div>

</div>
</div>

<script>

	$(function(){

		$('#date_from').date_from({
			now : '<?php echo date("Y-m-01"); ?>',
		});
		$('#date_to').date_to();


		var xhr = "";
		var app = {
			init:function(){
				this.bindEvent();
				this.get_cumulative();
			},bindEvent:function(){
				$('body').on('click','.clickable',this.journal_details);
				$('body').on('click','.cancel-event',this.cancel);
				$('body').on('click','.posting-event',this.posting);
				$('#filter').on('click',this.get_cumulative);
			},get_cumulative:function(){

				$post = {
					from : $('#date_from').val(),
					to   : $('#date_to').val(),
				};
				
				$.post('<?php echo base_url().index_page();?>/accounting_entry/journal_entry/get_cumulative',$post,function(response){
					$('#data').html(response);
					$('#cumulative').dataTable(datatable_option);
				});

			},journal_details:function(){
		        if(xhr && xhr.readystate != 4){
		            xhr.abort();
		        }

		        var me = $(this);
		        $post = {
		        	journal_id :me.attr('data-journal_id'),
		        }
				xhr = $.post('<?php echo base_url().index_page();?>/accounting_entry/journal_entry/get_journal_details',$post,function(response){
					$.fancybox(response,{
						width     : 1000,
						height    : 550,
						fitToView : false,
						autoSize  : false,
					})
				});
			},cancel:function(){

				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}

				$post = {
					journal_id : $(this).attr('data-journal_id'),
				}
		        if(xhr && xhr.readystate != 4){
		            xhr.abort();
		        }
				xhr = $.post('<?php echo base_url().index_page();?>/accounting_entry/journal_entry/cancel',$post,function(response){
						app.get_cumulative();
				});

			},posting:function(){

				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}

				$post = {
					journal_id : $(this).attr('data-journal_id'),
				}
		        if(xhr && xhr.readystate != 4){
		            xhr.abort();
		        }
				xhr = $.post('<?php echo base_url().index_page();?>/accounting_entry/journal_entry/posting',$post,function(response){
					if($.trim(response) == 1){
						alert('Successfully Posted');
					}
					app.get_cumulative();
				});

			}
		}

		app.init();

	});	
</script>