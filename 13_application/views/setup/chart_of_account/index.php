

<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Chart of Account</h2>
</div>

<div class="container">
	<div class="row">			
			<div class="col-md-12">	
				<div class="content-title">
					<h3>List of Accounts</h3>
				</div>	
				<div class="panel panel-default">	
					<div class="panel-body">
						<div class="pull-right">
							<span id="add_account" class="btn btn-sm  btn-primary" title="">Add Account</span>
							<span id="save_changes" class="btn btn-sm  btn-primary" title="">Save Changes</span>
						</div>						
					</div>	
					 <div class="data_content table-responsive">
					 	<table class="table table-content">
					 		<thead>
					 			<tr>
					 				<th>ACCOUNT</th>
					 				<th>CLASSIFICATION</th>
					 				<th>SUBCLASS</th>
					 				<th>ACCOUNT TITLE</th>
					 				<th>CODE</th>
					 			</tr>
					 		</thead>					 		
					 	</table>
					 </div>
				</div>
			</div>
	</div>
</div>

</div>	
</div>

<script>
	
	var app = {
		init:function(){
			this.bindEvents();
			this.get_cumulative();
		},bindEvents:function(){

			$('#create').on('click',this.create);
			$('#bank_setup').on('click',this.bank_setup);
			$('body').on('click','.update',this.update);			
			$('body').on('click','.chk-main',this.toggleCheck);
			$('#save_changes').on('click',this.save_changes);
			$('#add_account').on('click',this.add_account);		

			$('body').on('click','.delete',this.delete);


		},get_cumulative:function(){
			
			$('.data_content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/setup/chart_of_account/get_cumulative',function(response){
				$('.data_content').html(response);
				datatable_option['bSort'] = false;
				$('.myTable').dataTable(datatable_option);
			});

		},toggleCheck:function(){

			var checked = $(this).is(':checked');

			var rows = $(".myTable").dataTable().fnGetNodes();
			for(var i=0;i<rows.length;i++){				 
		           $(rows[i]).find('.chk-list').prop('checked',checked);
		    }

		},create :function(){
			$.fancybox.showLoading();
			$.post('<?php echo base_url().index_page();?>/setup/asset_setup/new_request',function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			}).error(function(){
				alert('Service Unavailable');
			});

		},bank_setup:function(){
			$.fancybox.showLoading();		
			$.post('<?php echo base_url().index_page();?>/setup/asset_setup/bank_setup',function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			}).error(function(){
				alert('Service Unavailable');
			});
		},update :function(){
			$.fancybox.showLoading();
			var tr = $(this).closest('tr');
			$post = {
				id : tr.find('td.account_id').text(),
			};
			$.post('<?php echo base_url().index_page();?>/setup/asset_setup/update_request',$post,function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			}).error(function(){
				alert('Service Unavailable');
			});

		},delete:function(){
			var bool = confirm('Are you Sure?');
			if(!bool){
				return false;
			}
			$post = {
				id : $(this).closest('tr').find('.account_id').text()
			}

			$.post('<?php echo base_url().index_page();?>/setup/chart_of_account/delete',$post,function(response){
				switch($.trim(response)){
					case "1":
						alert('Successfully Deleted');		
						location.reload('true');
					break;
					default:
						alert('Internal Server Error');
					break;
				}
				
				
			});

		},get_details:function(){			
			if($(this).find('td.dataTables_empty').length > 0){
				return false;
			}

			$.fancybox.showLoading();

			$post = {
				location     : $('#profit_center option:selected').val(),
				project      : $('#project option:selected').val(),
				id           : $(this).find('td.asset_id').text(),				
			};

			$.post('<?php echo base_url().index_page();?>/setup/asset_setup/get_details',$post,function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			});
		},save_changes:function(){
			$.save();
			var data = new Array();
			var rows = $(".myTable").dataTable().fnGetNodes();
			var length = rows.length;
			for(var i=0;i<length;i++){
				
				if($(rows[i]).find('.chk-list').is(':checked')){

						var obj = {					
							account_id : $(rows[i]).find('.account_id').text(),	
							status     : $(rows[i]).find('.status').text(),
							checked    : $(rows[i]).find('.chk-list').is(':checked'),
						}

						data.push(obj);
				}
				
			}
			$post = {
				data : JSON.stringify(data),				
			}


			$.post('<?php echo base_url().index_page(); ?>/setup/chart_of_account/save_changes',$post,function(response){				
					switch(response){
						case "1":
							$.save({action : 'success',reload : 'false'});
						break;
						default :
							$.save({action : 'hide'});							
						break;

					}
			});

		},add_account:function(){
			$.fancybox.showLoading();
			$.post('<?php echo base_url().index_page();?>/setup/account_setup/new_request',function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			}).error(function(){
				alert('Service Unavailable');
			});

		}

	}


	$(function(){
		app.init();
	});


</script>