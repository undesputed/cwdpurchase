<div class="content-page">
    <div class="content">


<div class="header">
	<h2>Item Grouping</h2>
</div>


<div class="container">

<div class="content-title">
	<h3>Item Grouping</h3>	
</div>


<input type="hidden" id="id" value="" class="clear">

<div class="row">
	<div class="col-md-4">
		<div class="panel panel-default">		
		  <div class="panel-body">
		  	
		  		<div id="item_group_list">
		  			
		  		</div>
		  </div>	 
		</div>
	</div>

	<div class="col-md-4">

		<div class="panel panel-default">		
		  <div class="panel-body">
		  		<div class="form-group">
		  			<div class="control-label">Parent Group Name</div>
		  			<input type="text" id="item_group" class="form-control inline uppercase" style="width:70%">
		  			<button class="btn btn-sm inline" id="save" data-name="">Save</button> <button id="reset" class="btn-link ">Reset</button>
		  		</div>
		  		<h5>List</h5>
		  		<table id="added_list" class="table">
		  			<tbody>
		  				
		  			</tbody>
		  		</table>
		  </div>	 
		</div>
		
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">		
		  <div class="panel-body">
		  		<div class="form-group">
		  			<div class="control-label">Parent Group List</div>		  			
		  		</div>
		  		<div id="parent_group_list">
		  			
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
			$('#item_group').val('');
			this.get_classification_setup();
			this.get_item_group_head();
			this.bindEvent();	
		},get_classification_setup:function(){
			$('.item_group_list').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/setup/item_group/get_item_group',function(response){
				$('#item_group_list').html(response);
				$('.classification_table').dataTable(datatable_option);
			});
		},bindEvent:function(){

			$('#save').on('click',this.save);			
			$('#reset').on('click',function(){
				$('#save').html('Save');
				$('#save').attr('data-name','');
				$('#item_group').val('');
				list = new Array();
				render();
			});
						
		},save:function(){

			if($('#item_group').val()==""){
				alert("Please Enter a Group Name");
				return false;
			}
		
			$post = {
				parent_group : $('#item_group').val(),
				item_list    : list,
				update       : $('#save').attr('data-name'),
			}

			$.post('<?php echo base_url().index_page();?>/setup/item_group/save_group',$post,function(response){
				$('#item_group').val('');
				$('#parent_group_list').html(response);
				alert('Successfully Added');
				$('#item_group').val('');
				$('#save').html('Save');
				$('#save').attr('data-name','');

				list = new Array();
				render();

			});

		},get_item_group_head:function(){

			$.post('<?php echo base_url().index_page();?>/setup/item_group/get_setup_head',function(response){
				$('#parent_group_list').html(response);
			});

		}
	};

	$(function(){		
		app_sub_classification.init();

	});

	var list = new Array();


	var get_headinfo = function(name){

		$post = {
			name : name
		}
		$('#item_group').val(name);
		$.post('<?php echo base_url().index_page();?>/setup/item_group/get_spec_head',$post,function(response){
				list = response;
				render();
				$('#save').html('Update');
				$('#save').attr('data-name',name);
		},'json');

	}

	
	var add_list = function(id,obj){

		var name = $(obj).closest('tr').find('.group_description').text();
		var data = {
			id : id,
			name : name,
		}

		console.log(data);
		list.push(data);

		render();

	}

	var remove_group = function(name){
			var bool = confirm('Are you Sure?');
			if(!bool){				
				return false;
			}
			$post = {
				name : name
			}
			$.post('<?php echo base_url().index_page();?>/setup/item_group/remove_group',$post,function(response){
					$('#parent_group_list').html(response);
			});
			return true
		}


	var render = function(){
		$('#added_list tbody').html(' ');
		if(list.length <= 0)
		{
			return false;
		}
		
		var div = "";
		$.each(list,function(i,val){
			div+="<tr>";
			div+="<td class='id' style='display:none'>"+val.name+"</td>";
			div+="<td>"+val.name+"</td>";
			div+="<td><a href='javascript:void(0)' onclick='remove_list("+i+")'>remove</a></td>";
			div+="</tr>";

		});
		$('#added_list tbody').html(div);
	}

	var remove_list = function(i){
		list.splice(i,1);
		render();
	}

</script>

