<div class="content-page">
    <div class="content">


<div class="header">
	<h2>Item Setup</h2>
</div>


<div class="container">

<div class="content-title">
	<h3>Setup Item</h3>	
</div>


<input type="hidden" id="id" value="" class="clear">

<div class="row">
	<div class="col-md-5">

		<div class="panel panel-default">		
		  <div class="panel-body">
		  		
		  		<div class="row">
		  			<div class="col-xs-9">
		  				<div class="form-group">
				  			<div class="control-label">Item Group</div>
				  			<select name="" id="group_id" class="form-control">
				  				
				  			</select>
				  			<?php foreach($item_group as $row): ?>
				  					<!-- <option value="<?php echo $row['group_id']; ?>"><?php echo $row['group_description']; ?> </option> -->
				  			<?php endforeach; ?>
				  		</div>
		  			</div>	
		  			<div class="col-xs-3" style="padding:0px">
		  				
		  					<button id="edit_group" class="btn btn-default btn-sm" style="margin-top:13px;display:inline-block" title="edit"><i title="edit" class="fa fa-edit"></i></button>	
		  					<button id="item_group" class="btn btn-default btn-sm" style="margin-top:13px;display:inline-block" title="add"><i title="edit" class="fa fa-plus"></i></button>		  				
		  				
		  				
		  				
		  			</div>		  			

		  		</div>

		  		<div class="form-group">
		  			<div class="control-label">Item Description</div>
		  			<input type="text" id="item_description" class="form-control input-sm required uppercase">
		  		</div>
		  		<div class="row">
		  			<div class="col-xs-12">
		  				<div class="form-group">
					  			<div class="control-label">Unit Measure</div>
					  			<input type="text" id="unit_measure" class="form-control input-sm required uppercase">
					  	</div>		  				
		  			</div>	
		  			<!-- 
		  			<div class="col-xs-6">
			  			<div class="form-group">
				  			<div class="control-label">Item Quantity</div>
				  			<input type="text" id="item_quantity" class="form-control input-sm required uppercase numbers_only">
				  		</div>
		  			</div> 
		  			-->
		  		</div>
		  		<div class="form-group">
		  			<div class="control-label">Account Classification</div>
		  			<select name="" id="account_classification" class="form-control">
		  				<?php foreach($account_classification as $row): ?>
		  					<?php $selected =  ($row['id']=='1')? 'selected="selected"' : ''; ?>
							<option <?php echo $selected ?> value="<?php echo $row['id']; ?>"><?php echo $row['full_description']; ?></option>
		  				<?php endforeach; ?>
		  			</select>
		  		</div>
		  		<div class="form-group">
		  			<div class="control-label">Account Name</div>
		  			<select name="" id="account_name" class="form-control">
		  				
		  			</select>
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

	<div class="col-md-7">
		<div class="panel panel-default">		
		  <div class="panel-body">		  		
		  </div>	
		  <div class="classification_setup_content">
		 	 <table class="table table-striped">
				<thead>
					<tr>
						<th>Item No</th>
						<th>Item Category</th>
						<th>Item Description</th>
						<th>Unit Measure</th>
						<th>Action</th>
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
	var xhr = '';
	var edit_xhr = '';
	var app_sub_classification ={
		init:function(){
						
			$('#group_id').chosen();

			this.get_classification_setup();
			this.bindEvent();
			this.account_type();
			this.get_items();

		},get_items:function(group_id){
			if(typeof(group_id) == 'undefined'){
				group_id = '';
			}
			$post = {
				group_id : group_id
			}
			$.post('<?php echo base_url().index_page();?>/setup/item_setup/get_group_item',$post,function(response){
				$('#group_id').html(response);
				$("#group_id").trigger("chosen:updated");
			});
		},get_classification_setup:function(){
			$('.classification_setup_content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/setup/item_setup/get_data',function(response){
				$('.classification_setup_content').html(response);
				$('.classification_table').dataTable(datatable_option);
			});
		},bindEvent:function(){

			$('#class_save').on('click',this.class_save);
			$('#class_reset').on('click',this.class_reset);
			$('body').on('click','.update_class',this.update);
			$('body').on('click','.remove_class', this.remove);
			$('#back_to').on('click',this.back_to);
			$('#txtShortDesc').on('change',this.account_type);			
			$('#item_group').on('click',this.item_group);			
			$('#edit_group').on('click',this.edit_group);

			$('#account_classification').on('change',function(){
				$post = {
					class_code : $(this).val(),
				}
				$.post('<?php echo base_url().index_page();?>/setup/item_setup/get_account_setup',$post,function(response){
						var div = "";

						$.each(response,function(i,val){
							var selected = "";
							if(val.account_id == '10'){
								selected = "selected='selected'";
							}
							div +="<option "+selected+" value="+val.account_id+">"+val.account_description+"</option>";
						});
						$('#account_name').html(div);
				},'json');
			});
			$('#account_classification').trigger('change');

		},class_save:function(){

			if($('.required').required()){
				return false;
			}
			
			$.save();


			$post = {
				    id                  : $('#id').val(),
				    group_id            : $('#group_id option:selected').val(),
					description         : $('#item_description').val(),
					quantity            : $('#item_quantity').val(),					
					unit_measure        : $('#unit_measure').val(),
					account_id          : $('#account_name option:selected').val(),
					classification_id    : $('#account_classification option:selected').val(),
			};

			$.post('<?php echo base_url().index_page();?>/setup/item_setup/save_item',$post,function(response){
					switch($.trim(response)){
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
			
			$("#group_id").trigger("chosen:updated");
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

		},item_group:function(){

			$.fancybox.showLoading();
	        if(xhr && xhr.readystate != 4){
	            xhr.abort();
	        }

			xhr = $.post('<?php echo base_url().index_page();?>/setup/item_setup/item_group',function(response){
				$.fancybox(response,{
					width     : 200,
					height    : 200,
					fitToView : false,
					autoSize  : true,
				})
			});

		},edit_group:function(){

			$.fancybox.showLoading();
	        if(edit_xhr && edit_xhr.readystate != 4){
	            edit_xhr.abort();
	        }
	        $post = {
	        	item_group : $('#group_id option:selected').val(),
	        	item_group_name : $('#group_id option:selected').text()
	        }
			edit_xhr = $.post('<?php echo base_url().index_page();?>/setup/item_setup/edit_group',$post,function(response){
				$.fancybox(response,{
					width     : 200,
					height    : 200,
					fitToView : false,
					autoSize  : true,
				})
			});

		},remove:function(){
			var bool = confirm('Are you Sure?');
			if(!bool){
				return false;
		}

		var me = $(this);
		var group_detail_id = me.closest('tr').find('td.id').text();
		var index = me.closest('tr').get(0);

		$post = {
			group_detail_id : group_detail_id
		};

		$.post('<?php echo base_url().index_page();?>/setup/item_setup/delete',$post,function(response){
				switch($.trim(response)){
					case "1":
						alert('Successfully Remove!');
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

