<div class="header">
		<div class="container">
	
		<div class="row">
			<div class="col-md-8">
				<h2>Canvass Sheet <small>Edit</small></h2>			
			</div>
			<div class="col-md-4">				
			</div>
		</div>

	</div>
</div>

<div class="container">
	
	<div class="row">
		<div class="col-md-3">
			<div class="content-title">
				<h3>Project Information</h3>
			</div>

			<div class="panel panel-default">		
			  <div class="panel-body">
					
			  		<div class="form-group">
			  			<div class="control-label">Project</div>
			  			<select name="" id="create_project" class="form-control input-sm"></select>
			  		</div>
					
			  		<div class="form-group">
			  			<div class="control-label">Profit Center</div>
			  			<select name="" id="create_profit_center" class="form-control input-sm"></select>
			  		</div>
			  		
			  </div>	 
			</div>

		</div>
		<div class="col-md-9">
			<div class="content-title">
				<h3>Item Information</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">
			  		<div class="row">
			  			<div class="col-md-4">
				  			<div class="form-group">
				  					<div class="control-label">Select PR No</div>
						  			<select type="text" name="" id="pr_no" class="form-control input-sm " data-placeholder="Select PR">	
						  				<option value=""></option>					  				
						  				<?php foreach($approved_pr as $key => $value): ?>
											<option value="<?php echo $value['pr_id'] ?>"><?php echo $value['PR NO'] ?></option>
						  				<?php endforeach; ?>
						  			</select>
				  			</div>
			  			</div>
			  			<div class="col-md-4">				  			
			  			</div>
			  			<div class="col-md-2">
				  				<div class="form-group">
						  			<div class="control-label">Date</div>
						  			<input type="text" name="" id="date" class="form-control input-sm date" value="<?php echo $main['c_date'] ?>">
					  			</div>
			  			</div>

			  			<div class="col-md-2">
				  				<div class="form-group">
						  			<div class="control-label">Canvass No.</div>
						  			<input type="text" name="" id="canvass_no" class="form-control input-sm" value="<?php echo $main['c_number'] ?>">
					  			</div>
			  			</div>	

			  		</div>
			  		<div class="row">
			  			<div class="col-md-5">
				  			<div class="form-group">
						  			<div class="control-label">Supplier Type</div>
						  			<div class="radio-inline">
						  				<input type="radio" name="supplier_type" value="PERSON" id="person"> <label for='person'>Person</label>
						  			</div>
									
						  			<div class="radio-inline">
						  				<input type="radio" name="supplier_type" value="BUSINESS" id="business" checked> <label for='business'>Business</label>
						  			</div>
									
									<select type="text" name="" id="supplier" class="form-control input-sm"></select>
					  		</div>
			  			</div>
							
			  			<div class="col-md-3">
			  				<button id="add_supplier" class="btn btn-primary nxt-btn">Add Supplier</button>
			  			</div>
						
			  		</div>	  
			  </div>	 
			</div>

		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
				<div class="panel panel-default">
				<div class="table-responsive table-overflow">
					  <table id="pr_details" class="table table-striped">
					  	<thead>
					  		<tr class="tr-title">
					  			<th colspan="3" class="default">Item List</th>
					  		</tr>

					  		<tr class="tr-subtitle">
					  			<th class="default">Item</th>
					  			<th class="default">Item Description</th>
					  			<th class="default">Approved Quantity</th>				  			
					  		</tr>
					  	</thead>
					  	<tbody>

					  	</tbody>
					  </table>
				  </div>		
				</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">		
			  <div class="panel-body">
					<div class="row">
						<div class="col-md-4">

							<div class="form-group">
					  			<div class="control-label">Canvass By</div>
					  			<select name="prepared_by" id="prepared_by" class="form-control input-sm"></select>
					  		</div>

						</div>
						<div class="col-md-4">
						
							<div class="form-group">
					  			<div class="control-label">Approved By</div>
					  			<select name="approved_by" id="approved_by" class="form-control input-sm"></select>
					  		</div>

						</div>
					</div>			  		
			  </div>	 

			  <div class="form-footer">			  	
					<div class="row">
						<div class="col-md-8"> </div>
						<div class="col-md-4">
							<input id="save" class="btn btn-success  col-xs-5 pull-right" type="submit" value="Update">
							<input id="reset" class="btn btn-link  pull-right" type="reset" value="Reset">
						</div>
					</div>					
			  </div>

			</div>
		</div>
	</div>


</div>

<script type="text/javascript">
	
		
	var table_content = function( data ) {
		var obj = data;
		return {
			'title'    : '<th colspan="5" class="border-left '+obj.id+' ">'+obj.text+'<button id="'+obj.id+'" type="button" class="  pull-right close" aria-hidden="true">&times;</button></th>',
			'subtitle' : '<th class="border-left '+obj.id+'" style="width:14px">Quantity</th><th style="width:100px" class="'+obj.id+'">Unit Cost</th><th class="'+obj.id+'">Total</th><th class="'+obj.id+'">Remarks</th><th class="'+obj.id+'">Terms</th>',
			'row'      : '<td class="'+obj.id+'" style="display:none">'+obj.id+'</td><td class="'+obj.id+'" style="display:none">'+obj.type+'</td><td class="border-left '+obj.id+'"><input type="text" class="form-control input-sm qty numbers_only compute" /></td><td class="'+obj.id+'"><input type="text" class="form-control input-sm unit_cost numbers_only compute" /></td><td class="'+obj.id+'"><input type="text" class="form-control input-sm total_cost" /></td><td class="'+obj.id+'"><input type="text" class="form-control input-sm remarks" /></td><td class="'+obj.id+'"><input type="text" class="form-control input-sm terms" /></td>',
		}
	};

	var edit_table_content = function( data , value ) {
		var obj = data;
		return {
			'title'    : '<th colspan="5" class="border-left '+obj.id+' ">'+obj.text+'<button id="'+obj.id+'" type="button" class="  pull-right close" aria-hidden="true">&times;</button></th>',
			'subtitle' : '<th class="border-left '+obj.id+'" style="width:14px">Quantity</th><th style="width:100px" class="'+obj.id+'">Unit Cost</th><th class="'+obj.id+'">Total</th><th class="'+obj.id+'">Remarks</th><th class="'+obj.id+'">Terms</th>',
			'row'      : '<td class="'+obj.id+'" style="display:none">'+obj.id+'</td><td class="'+obj.id+'" style="display:none">'+obj.type+'</td><td class="border-left '+obj.id+'"><input type="text" class="form-control input-sm qty numbers_only compute" value="'+value.qty+'" /></td><td class="'+obj.id+'"><input type="text" class="form-control input-sm unit_cost numbers_only compute" value="'+value.unit_cost+'" /></td><td class="'+obj.id+'"><input type="text" class="form-control input-sm total_cost" /></td><td class="'+obj.id+'"><input type="text" class="form-control input-sm remarks" value="'+value.remarks+'" /></td><td class="'+obj.id+'"><input type="text" class="form-control input-sm terms" value="'+value.terms+'" /></td>',
		}
	};

	var supplier_list = [];
	var DATA = [];
	var app = {
		init:function(){

			$('.date').date();
			
			var option = {
				profit_center : $('#create_profit_center')
			}			
			$('#create_project').get_projects(option);			
			
			this.bindEvents();
			this.get_item();
			this.get_supplier_type();

			$.signatory({
				prepared_by   : '',
				recommended_by: ["4", "5", "1","2"],
				approved_by   : ["5", "4", "1","2"],
				approved_by_id : "<?php echo $main['approvedBy']; ?>",
				prepared_by_id : "<?php echo $main['preparedBy']; ?>",
			});

			$('#pr_no').chosen({allow_single_deselect: true});

			this.get_supplier_type();
			$('#supplier').chosen();

			app.edit_getDetails();

		},bindEvents:function(){

			$('#item_name').on('change',this.get_category);
			$('#add').on('click',this.add);
			$('#item_add').on('click','.remove',this.remove_data);
			$('#save').on('click',this.save);
			$('#profit_center').on('change',this.location);

			$('input[name="supplier_type"]').on('change',this.get_supplier_type);
			$('#pr_no').on('change',this.get_pr_item);

			$('#add_supplier').on('click',this.add_supplier);

			$('body').on('click','.close',this.remove_supplier);

			$('body').on('blur','.compute',function(){
				var other;
				if($(this).hasClass('unit_cost')){
					other  = $(this).closest('td').prev().find('input').val();
					$(this).closest('td').next().find('input').val(comma(other * $(this).val()));
				}else{
					other  = $(this).closest('td').next().find('input').val();					
					$(this).closest('td').next().next().find('input').val(comma(other * $(this).val()));
				}												
				
			}); 
		},location:function(){
			
			$('#location').val($('#profit_center option:selected').data('location'));
			$('#to').val($('#profit_center option:selected').data('to'));

		},get_item:function(){

			$.get('<?php echo base_url().index_page(); ?>/procurement/purchase_request/get_items',function(response){
				$('#item_name').select({
					json : response,
					attr : {
							text  : 'description',
							value : 'group_id',							
					},
					data : {
						'data-group-detail-id' : 'group_detail_id',						 
						'data-account_id'      : 'account_id',
						'data-unit-cost'	   : 'unit_cost',
					}
				});
				$('#item_name').chosen();
				app.get_category();
			},'json');

		},get_category:function(){
			$post = {
				group_id : $('#item_name option:selected').val(),
			}			
			$.post('<?php echo base_url().index_page();?>/procurement/purchase_request/get_category',$post,function(response){
				 $('#item_category').select({
				 	json : response,
				 	attr : {
				 		   text  : 'group_description',
				 		   value : 'group_id',
				 	}
				 });
			},'json');
		},add:function(){

		},render:function(){
			
		},remove_data:function(){

		},save:function(){

			$.save({appendTo : '.fancybox-outer'});

			if($('#pr_no option:selected').val()==''){
				$pr_id = "<?php echo $main['pr_id'] ?>";
			}else{
				$pr_id = $('#pr_no option:selected').val();
			}

			$post = {
				canvass_no  :$('#canvass_no').val(),
				date        :$('#date').val(),
				pr_id       :$pr_id,
				prepared_by :$('#prepared_by option:selected').val(),
				approved_by :$('#approved_by option:selected').val(),
				title_id    :$('#create_profit_center option:selected').val(),
				details     : app.get_item_details(),
				can_id      : "<?php echo $main['can_id'] ?>",
			};
			

			app.get_item_details();

			$.post('<?php echo base_url().index_page(); ?>/procurement/canvass_sheet/update_canvass',$post, function(response){
				if(response == 1){
					$.save({action : 'success',reload : 'true'});
				}else{
					$.save({action : 'success'});
				}
				
			}).error(function(){
				$.save({action : 'error'});
			});
			
		},get_supplier_type:function(){

			$post = {
				type : $('input[name="supplier_type"]:checked').val(),
			}
			$('#supplier').html('');
			$.post('<?php echo base_url().index_page(); ?>/procurement/canvass_sheet/supplier',$post,function(response){
					$.each(response,function(i,val){
						$('#supplier').append($('<option>').val(val['Supplier ID']).text(val['Supplier']));	
					})					
					$("#supplier").trigger("chosen:updated");
			},'json');

		},get_pr_item:function(){

			$post = {
				pr_id : $('#pr_no option:selected').val(),
			};
			$.post('<?php echo base_url().index_page();?>/procurement/canvass_sheet/get_pr_details',$post,function(response){
				app.render_tableJson(response);
			},'json');

		},render_tableJson:function( json ){
			$('#pr_details tbody').html('');
			if(json.length > 0 ){			
				 var json_length = json.length;
				 var all_row = new Array();					
			        for (var i = 0 ; i < json_length ; i++){

			        	var $row = $('<tr/>');
			        	var td = new Array();			        	
			        	td.push($('<td/>').html(json[i]['unit_cost']).css({'display':'none'}));
						td.push($('<td/>').html(json[i]['itemNo']));
						td.push($('<td/>').html(json[i]['itemDesc']));
						td.push($('<td/>').html(json[i]['qty']));
						td.push($('<td/>').html(json[i]['rem_qty']).css({'display':'none'}));
						$row.append(td);
						all_row.push($row);
			        }
				$('#pr_details tbody').append(all_row);
				

				if(supplier_list.length>0){

					var supplier = {
						text :$('#supplier option:selected').text(),	
						id   :$('#supplier option:selected').val(),
					}					
					$('#pr_details tbody tr').append(table_content(supplier).row);

				}

			}
		},add_supplier:function(){

			var supplier = {
				text :$('#supplier option:selected').text(),	
				id   :$('#supplier option:selected').val(),
				type :$('input[name="supplier_type"]:checked').val(),
			} 
			
			if($.inArray(supplier.id, supplier_list)>= 0){
				console.log(supplier_list);
				alert('Supplier Already Added');
				return false;				
			}
			
			$('#pr_details thead tr.tr-title').append(table_content(supplier).title);
			$('#pr_details thead tr.tr-subtitle').append(table_content(supplier).subtitle);
			$('#pr_details tbody tr').append(table_content(supplier).row);
			
			supplier_list.push(supplier.id);
			console.log(supplier_list);

		},remove_supplier:function(){
			var id = $(this).attr('id');
			$('.'+id).remove();			
			supplier_list.splice($.inArray(id,supplier_list),1);

		},get_item_details:function(){
			var $row_content = new Array();
			$('#pr_details tbody tr').each(function(i,val){
				$row = new Array();
				$(val).find('td').each(function(key,value){
					if($(value).find('input').length>0){
						$row.push($(value).find('input').val());
					}else{
						$row.push($(value).text());	
					}
				});
				$row_content.push($row);
			});
			return $row_content;
		},edit_getDetails:function(){
			var data = JSON.parse('<?php echo $details ?>');
			app.render_tableJson(data);
			app.edit_getSupplier();
		},edit_getSupplier : function(){

			var data = JSON.parse('<?php echo $supplier ?>');			


			$.each(data,function(i,val){

				var supplier = {
					text : val.Supplier,	
					id   : val.supplier_id,
					type : val.supplierType,
				}

				var input_values = {
					qty       : val.sup_qty,
					unit_cost : val.unit_cost,
					remarks   : val.c_remarks,
					terms     : val.c_terms,
				}
				

				if($.inArray(supplier.id, supplier_list)>= 0){
					
					alert('Supplier Already Added');
					return false;				
				}
				
				$('#pr_details thead tr.tr-title').append(table_content(supplier).title);
				$('#pr_details thead tr.tr-subtitle').append(table_content(supplier).subtitle);
				$('#pr_details tbody tr').append(edit_table_content(supplier,input_values).row);

				supplier_list.push(supplier.id);
			});

			



		}
	}

$(function(){
	app.init();
});
</script>