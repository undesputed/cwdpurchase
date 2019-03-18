<div class="header">
	<h2>Manage Users</h2>
</div>
<div class="container">
	<div class="row">
		
		<div class="col-md-2">
				<div class="content-title">
					<h3>Create Users</h3>		
				</div>
				<div>
					<a id="create" href="javascript:void(0)" class="btn btn-primary ">Create User</a>
				</div>
		</div>
		<div class="col-md-9">
				<div class="content-title">
					<h3>Users List</h3>		
				</div>
				<div class="panel panel-default">	
					<div class="panel-body"></div>	
					<?php echo $table; ?> 
				</div>
				
		</div>

	</div>	
</div>
<script>		
	 var app = {
	 	init:function(){
	 		this.bindEvents();
	 		this.dataTable();
	 	},
	 	bindEvents:function(){
			$('#create').on('click',this.create);
			$('.container').on('click','.update',this.update_position);
			$('.container').on('click','.block',this.block);
	 	},	 	
	 	create:function(){
	 		$.fancybox.showLoading();	 		
			$.post('<?php echo base_url().index_page();?>/manage/create',function(response){
				$.fancybox(response,{
					width     : 400,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				});
			});

	 	},dataTable:function(){
			$('.myTable').dataTable(datatable_option);	 		
	 	},
	 	update:function(){

	 		var id = $(this).closest('tr').find('td.id').text();
	 		$.fancybox.showLoading();
	 		$post = {
	 			id : id,
	 		}
	 		$.post('<?php echo base_url().index_page();?>/manage/update',$post,function(response){
				$.fancybox(response,{
					width     : 400,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				});
			});

	 	},
	 	block:function(){
	 		var bool =  confirm('Are you Sure?');
	 		if(!bool){
				return false;
	 		}
	 		
	 		var id = $(this).closest('tr').find('td.id').text();
	 		$post = {
	 			id : id,
	 		}

	 		$.post('<?php echo base_url().index_page();?>/manage/block',$post,function(response){
	 			alert('Successfully Update Account!');
	 			location.reload(true);
	 		});

	 	},
	 	update_position:function()
	 	{
			var id = $(this).closest('tr').find('td.id').text();	
			$.fancybox.showLoading();
	 		$post = {
	 			id : id,
	 		}
	 		$.post('<?php echo base_url().index_page();?>/manage/update_position',$post,function(response){
				$.fancybox(response,{
					width     : 400,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				});
			}); 	
	 	}
	 }
	$(function(){
		app.init();
	});


</script>