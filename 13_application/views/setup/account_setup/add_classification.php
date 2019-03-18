<div class="header">
	<h2>Add Account Classification</h2>
</div>
<div class="container">

<div class="content-title">
	<h3>Classification Form</h3>	
</div>

<span id="back_to" class="pull-right btn-link" style="margin-top:2em;">Back to Account Setup</span>


<?php 
$account_type_1 = array(
				array('title'=>'ASSETS','group'=>'1','cashflow'=>'3'),
				array('title'=>'LIABILITIES','group'=>'2','cashflow'=>'1'),
				array('title'=>'EQUITY','group'=>'3','cashflow'=>'4'),
				array('title'=>'INCOME','group'=>'4','cashflow'=>'5'),
				array('title'=>'EXPENSES','group'=>'5','cashflow'=>'2'),
				); 
?>

<input type="hidden" id="id" value="" class="clear">


<div class="row">
	<div class="col-md-4">

		<div class="panel panel-default">		
		  <div class="panel-body">
		  		<div class="form-group">
		  			<div class="control-label">Classification Code</div>
		  			<input type="text" id="txtClassCode" class="form-control input-sm required">
		  		</div>

		  		<div class="form-group">
		  			<div class="control-label">Class Type</div>
		  			<select name="" id="txtShortDesc" class="form-control input-sm">
				  				<?php foreach($account_type_1 as $row): ?>								
								<option data-group="<?php echo $row['group'] ?>" data-cashflow="<?php echo $row['cashflow'] ?>" value="<?php echo $row['title'] ?>"><?php echo $row['title'] ?></option>
				  				<?php endforeach; ?>
				  	</select>				  	
		  		</div>
		  		<div class="form-group">
		  			<div class="control-label">Classification Name</div>
		  			<input type="text" id="txtClassName" class="form-control input-sm required">
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
						<th>Classification Code</th>
						<th>Short Description</th>
						<th>Classification Name</th>
					</tr>
				</thead>
			 </table>
		  </div> 
		</div>		
	</div>


</div>

</div>

<script>

	var app_classification ={
		init:function(){
			this.get_classification_setup();
			this.bindEvent();
		},get_classification_setup:function(){
			$('.classification_setup_content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/setup/account_setup/get_classification_setup',function(response){
				$('.classification_setup_content').html(response);
				$('.classification_table').dataTable(datatable_option);
			});
		},bindEvent:function(){
			$('#class_save').on('click',this.class_save);
			$('#class_reset').on('click',this.class_reset);
			$('body').on('click','.update_class',this.update);
			$('#back_to').on('click',this.back_to);

		},class_save:function(){

			if($('.required').required()){
				return false;
			}
			
			
			$.save({appendTo : '.fancybox-outer'});
			$post = {
				txtClassCode   : $('#txtClassCode').val(),
				txtShortDesc   : $('#txtShortDesc option:selected').val(), 
				txtClassName   : $('#txtClassName').val(),
				group          : $('#txtShortDesc option:selected').attr('data-group'),
				cashflow       : $('#txtShortDesc option:selected').data('data-cashflow'),
				id             : $('#id').val(),
			};
			$.post('<?php echo base_url().index_page();?>/setup/account_setup/save_classification_setup',$post,function(response){
					switch(response){
						case"1": 
							$.save({action : 'success'});
						break;
						default :
							$.save({action : 'hide'});
						break;
					}

				$('.required,.clear').val('');
				app_classification.get_classification_setup();

			}).error(function(){
				alert('Service Unavailable');
			});

			$('#class_save').val('Save');

		},update:function(){
			var tr = $(this).closest('tr');
			var edit = {
				txtClassCode : tr.find('td.classification_code').text(),
				txtShortDesc : tr.find('td.short_description').text(),
				txtClassName : tr.find('td.classification_name').text(),
				id           : tr.find('td.id').text(),
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

		}
	};

	$(function(){		
		app_classification.init();
	});
</script>

