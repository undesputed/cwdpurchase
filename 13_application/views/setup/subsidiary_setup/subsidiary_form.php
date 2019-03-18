
<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Subsidiary Setup</h2>
</div>


<div class="container">

<div class="content-title">
	<h3>Setup Form</h3>	
</div>

<?php $subsidiary = array(
				'ASSET',
				'EMPLOYEE',
				'EQUIPMENT',
				'ITEM',
				'LOCATION',
				'LOT',
				'OFFICE',
				'PAYEE',
				'VEHICLE',
); ?>



<input type="hidden" id="id" value="" class="clear">
<div class="row">
	<div class="col-md-4">

		<div class="panel panel-default">		
		  <div class="panel-body">
		  		
		  		<div class="form-group">
		  			<div class="control-label">Subsidiary Type</div>
		  			<select name="" id="subsidiary_type" class="form-control">
		  			<?php foreach($subsidiary as $row): ?>		
						<option value="<?php echo $row; ?>"><?php echo $row; ?></option>
		  			<?php endforeach; ?>
		  			</select>
		  		</div>	  		

		  		<div class="form-group">
		  			<div class="control-label">Title/Name	</div>
		  			<input type="text" id="title" class="form-control input-sm required uppercase">
		  		</div>

		  		<div class="form-group">
		  			<div class="control-label">Address/Location</div>
		  			<input type="text" id="address" class="form-control input-sm required uppercase">
		  		</div>

		  		<div class="form-group">
		  			<div class="control-label">Reference No/Tin No</div>
		  			<input type="text" id="ref" class="form-control input-sm required uppercase" >
		  		</div>
				
		  </div>
		  <div class="form-footer">
				<div class="row">
					<div class="col-md-7"> </div>
					<div class="col-md-5">
						<input id="class_save" class="btn btn-success col-xs-5 pull-right btn-sm" type="submit" value="Save">						
						<input id="class_reset" class="btn btn-link col-xs-5 pull-right btn-sm" type="submit" value="Reset">						
					</div>
				</div>
		   </div>
		</div>
	
	</div>

	<div class="col-md-8">

		<div class="panel panel-default">		
		  <div class="panel-body">		  		
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
	var xhr = "";
	var app_sub_classification ={
		init:function(){

			this.get_classification_setup();
			this.bindEvent();
			this.account_type();

		},get_classification_setup:function(){
			$('.classification_setup_content').content_loader('true');

			$post = {
				type : $('#subsidiary_type option:selected').val(),
			}
			$.post('<?php echo base_url().index_page();?>/setup/subsidiary_setup/get_data',$post,function(response){
				$('.classification_setup_content').html(response);
				$('.classification_table').dataTable(datatable_option);
			});

		},bindEvent:function(){

			$('#class_save').on('click',this.class_save);
			$('#class_reset').on('click',this.class_reset);
			$('body').on('click','.update_class',this.update);
			$('#back_to').on('click',this.back_to);

			$('#txtShortDesc').on('change',this.account_type);

			$('#subsidiary_type').on('change',this.get_classification_setup);



		},class_save:function(){

			if($('.required').required()){
				return false;
			}
						
			$.save({appendTo : '.fancybox-outer'});
						
			$post = {
				subsidiary_type   : $('#subsidiary_type option:selected').val(),
				title             : $('#title').val(),
				address           : $('#address').val(),
				ref               : $('#ref').val(),
				id                : $('#id').val(),
			};

	        if(xhr && xhr.readystate != 4){
	            xhr.abort();
	        }
			xhr = $.post('<?php echo base_url().index_page();?>/setup/subsidiary_setup/save',$post,function(response){
					switch(response){
						case"1": 
							alert('Successfully Save');
							$.save({action : 'success'});
						break;
						default :
							$.save({action : 'hide'});
						break;
					}

				$('.required,.clear').val('');
				app_sub_classification.get_classification_setup();

				$('#class_save').val('Save');

			}).error(function(){
				alert('Service Unavailable');
			});
						
		},update:function(){

			var tr = $(this).closest('tr');


			
			

			var edit = {
				title      : tr.find('td.title').text(),							
				id         : tr.find('td.id').text(),
				address    : tr.find('td.address').text(),
				ref        : tr.find('td.ref_no').text(),
			}

			$.each(edit,function(i,val){
				$('#'+i).val(val);
			});
			
			$('#class_save').val('Update');

		},class_reset:function(){
			$('.required,.clear').val('');
			$('#class_save').val('Save');
		},back_to:function(){
			$.save({appendTo : '.fancybox-outer',loading : 'Processing...'});
			
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

		},account_type:function(){

			$post = {
				account_type : $('#txtShortDesc option:selected').val(),
			};

			$.post('<?php echo base_url().index_page();?>/setup/account_setup/get_classification',$post,function(response){
						$('#cmbClassification').select({
							json : response,
							attr : {
								text : 'full_description',
								value : 'id',
							}
						});			
			},'json');

		}
	};

	$(function(){		
		app_sub_classification.init();
	});
</script>

