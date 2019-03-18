<div class="content-page">
    <div class="content">

<div class="header">
	<h2>Person Setup</h2>
</div>


<div class="container">

<div class="content-title">
	<h3>Person Setup</h3>	
</div>


<input type="hidden" id="id" value="" class="clear">

<div class="row">	
	<div class="col-md-12">
		<div class="panel panel-default">		
		  <div class="panel-body">	
		  		<div class="btn-header">
					<button id="create" class="btn pull-right btn-primary btn-sm">Add Person</button>
				</div>	  		
		  </div>	
		  <div class="classification_setup_content">
		 	 <table class="table table-striped">
				<thead>
					<tr>
						<th>Type</th>
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

	var app_sub_classification ={
		init:function(){
			
			this.get_classification_setup();
			this.bindEvent();			

		},get_classification_setup:function(){
			$('.classification_setup_content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/setup/person_setup/get_person',function(response){
				$('.classification_setup_content').html(response);
				$('.classification_table').dataTable(datatable_option);
			});
		},bindEvent:function(){
			$('#create').on('click',this.create);
			$('body').on('click','.update_class',this.update);
			$('body').on('click','.delete_class',this.delete);
		},class_save:function(){

			if($('.required').required()){
				return false;
			}
			
			$.save();

			$post = {
				department_name  : $('#department_name').val(),
				department_Code  : $('#department_Code').val(),
				id               : $('#id').val(),
			};

			$.post('<?php echo base_url().index_page();?>/setup/division_setup/save_division',$post,function(response){
					switch(response){
						case"1": 
							$.save({action : 'success'});
						break;
						default :
							$.save({action : 'hide'});
						break;
					}

				$('.required,.clear').val('');
				app_sub_classification.get_classification_setup();

			}).error(function(){
				alert('Service Unavailable');
			});

			$('#class_save').val('Save');

		},class_reset:function(){
			$('.required,.clear').val('');
			$('#class_save').val('Save');
		},create:function(){

			$.fancybox.showLoading();
			$.post('<?php echo base_url().index_page();?>/setup/person_setup/create_person',function(response){

				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})

			});

		},update:function(){
			$.fancybox.showLoading();
			var tr = $(this).closest('tr');
			$post = {
				id : tr.find('td.id').text(),
			}
			$.post('<?php echo base_url().index_page();?>/setup/person_setup/update',$post,function(response){

				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})

			});
		},delete:function(){

			var bool = confirm('Are you Sure?');
			if(!bool){
				return false;
		}

		var me = $(this);
		var pp_person_code = me.closest('tr').find('td.id').text();
		var index = me.closest('tr').get(0);

		$post = {
			pp_person_code : pp_person_code
		};

		$.post('<?php echo base_url().index_page();?>/setup/person_setup/delete',$post,function(response){
				switch($.trim(response)){
					case "1":

						alert('Successfully Delete!');
						  // oTable.fnDeleteRow(oTable.fnGetPosition(index));
						 app_sub_classification.get_classification_setup();
					break;
					case "default":
						alert('Something went Wrong');
					break;
				}
			});


		}


	};


	$(function(){		
		app_sub_classification.init();
	});
</script>

