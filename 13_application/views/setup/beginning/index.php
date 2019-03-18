<div class="content-page">
    <div class="content">

<div class="header">
	<h2>Beginning Inventory Setup</h2>
</div>

<div class="container">

<div class="content-title">
	<h3>Add Item Quantity</h3>	
</div>

<input type="hidden" id="id" value="" class="clear">

<div class="row">
	<div class="col-md-4">

		<div class="panel panel-default">		
		  <div class="panel-body">
		  		
		  		<div class="row">
		  			<div class="col-xs-12">
		  				<div class="form-group">
				  			<div class="control-label">Item Name</div>
				  			<select name="" id="item_list" class="form-control">				  				
				  				<?php foreach($item_list as $row): ?>
				  					<option data-item_no="<?php echo $row['group_detail_id']; ?>" data-item_measurement="<?php echo $row['unit_measure']; ?>" value="<?php echo $row['item_description']; ?>"><?php echo $row['item_description']; ?></option>
				  				<?php endforeach; ?>
				  			</select>
				  		</div>
		  			</div>			  		
		  		</div>
		  	
		  		<div class="row">
		  			<div class="col-xs-12">
			  			<div class="form-group">
				  			<div class="control-label">Item Beginning Quantity</div>
				  			<input type="text" id="item_quantity" class="form-control input-sm required uppercase numbers_only" placeholder="0">
				  		</div>
		  			</div>
		  		</div>

		  </div>

		  <div class="form-footer">
				<div class="row">
					<div class="col-md-7"> </div>
					<div class="col-md-5">
						<input id="class_save" class="btn btn-success col-xs-5 pull-right btn-sm" type="submit" value="Add">						
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
						<th>-</th>						
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

	var data = eval('');
	var app_sub_classification ={
		init:function(){

			$('#item_list').chosen();

			this.get_classification_setup();
			this.bindEvent();				

		},get_classification_setup:function(){
			$('.classification_setup_content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/setup/beginning/get_data',function(response){
				$('.classification_setup_content').html(response);
				$('.classification_table').dataTable(datatable_option);
			});
		},bindEvent:function(){

			$('#class_save').on('click',this.class_save);
			$('#class_reset').on('click',this.class_reset);
			$('body').on('click','.update_class',this.update);
			$('#back_to').on('click',this.back_to);
						
			$('#item_group').on('click',this.item_group);	

			$('body').on('click','.category',this.category);



			$('.editable').editable('<?php echo base_url().index_page(); ?>/ajax/display', {
			     data    : data,
			     type    : 'select',
			     submit  : 'OK',
			     style   : 'inherit',
			     callback: function (value,settings){
			     	$(this).html(item_content[value].item_name);
			     	$(this).closest('tr').find('.item_no').html(value);
			     	$(this).closest('tr').find('.item_cost').html(item_content[value].item_cost)
			     	$(this).closest('tr').find('.unit_measure').html(item_content[value].unit_measure);
			     	$(this).closest('tr').find('.stocks').html(item_content[value].stocks)
			     	$(this).closest('tr').find('.inventory_id').html(item_content[value].inventory_id)
				 }
			});



		},class_save:function(){

			if($('.required').required()){
				return false;
			}
			
			var bool = confirm('Do you want to Proceed?');
			if(!bool){
				return false;
			}

			$.save();

			$post = {
				    item_no             : $('#item_list option:selected').attr('data-item_no'),
				    item_description    : $('#item_list option:selected').val(),
					item_measurement    : $('#item_list option:selected').attr('data-item_measurement'),
					quantity            : $('#item_quantity').val(),					
					
			};

			$.post('<?php echo base_url().index_page();?>/setup/beginning/save_item',$post,function(response){
					switch($.trim(response)){
						case"1": 
							$.save({action : 'success'});
						break;
						default:
							$.save({action : 'hide'});
						break;
					}

				$('.required,.clear').val('');
				app_sub_classification.get_classification_setup();

			}).error(function(){
				alert('Service Unavailable');
			});

			$('#class_save').val('Save');

		},update:function(){

			var tr = $(this).closest('tr');
			var edit = {
				id                : tr.find('td.id').text(),
				group_id          : tr.find('td.group_id').text(),
				item_description  : tr.find('td.description').text(),
				unit_measure      : tr.find('td.unit_measure').text(),
				item_quantity     : tr.find('td.quantity').text(),
			}
			
			$.each(edit,function(i,val){
				$('#'+i).val(val);
			});
			
			$('#class_save').val('Update');

		},class_reset:function(){

			$('.required,.clear').val('');
			$('#class_save').val('Save');

		},category:function(){

		}
	};

	$(function(){		
		app_sub_classification.init();
	});
</script>

