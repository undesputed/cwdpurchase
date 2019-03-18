<style>
	#canvass_table td{
		  white-space: nowrap;
	}
</style>
<input type="hidden" value="" class="date" id="cv_date">
<input type="hidden" value="" class="" id="cv_no">
<input type="hidden" value="<?php echo $pr_main['pr_id']; ?>" id="pr_id">
<input type="hidden" value="<?php echo $pr_main['is_boq_new']; ?>" id="is_boq_new">


<div class="content-title">
	<h3>Create Canvass Sheet</h3>	
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="t-content">
			<div class="t-header">
				<a href="<?php echo base_url().index_page();?>/transaction_list/purchase_request/incoming/<?php echo $pr_main['purchaseNo']; ?>" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span></a>
				<h4 id="cv-no-title">-</h4>
			</div>
			<div class="row">
				<div class="col-xs-6">
					<div class="t-title">
						<div>P.R No :</div>
						<strong><?php echo $pr_main['purchaseNo']; ?></strong>
					</div>
					<div class="t-title">
						<div>P.R Date:</div>
						<strong><?php echo $this->extra->format_date($pr_main['purchaseDate']) ?></strong>
					</div>
					<div class="t-title">
						<div>Created From :</div>
						<strong><?php echo $pr_main['from_projectMainName']; ?></strong>
						<strong><?php echo $pr_main['from_projectCodeName']; ?></strong>
					</div>				
				</div>
				<div class="col-xs-6">
					<div class="control-group">						
						<span class="fa fa-plus text-muted"></span> <a href="javascript:void(0)" id="add-supplier" class="action-status">Add Supplier</a>
					</div>
				</div>
			</div>
			<div class="responsive_table" style="overflow:auto">
				<table id="canvass_table" class="table table-item">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th colspan="4" class="dh head"></th>
							
						</tr>
						<tr>
							<th class="gray">Lookup?</th>
							<th class="gray">Item Name</th>
							<th class="gray">Item No</th>
							<th class="gray"> Unit</th>
							<th class="th-sub-header td-number dh sub-head gray">P.R Qty</th>
							<!--- -->						
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach($pr_details as $row): 
								if($row['qty'] > 0){
						?>
						<tr>
							<td class="gray"><a class="lookup_view" style="cursor:pointer;" data-id="<?php echo $row['itemNo']; ?>">Lookup</a></td>
							<td class="gray"><?php echo $row['itemDesc']; ?></td>
							<td class="gray"><?php echo $row['itemNo']; ?></td>
							<td class="gray"><?php echo $row['unitmeasure']; ?></td>
							<td class="gray td-qty td-number dh"><?php echo $row['qty']; ?></td>
							<!--- -->
						</tr>
						<?php 
								}
							endforeach; 
						?>
					</tbody>
					<tfoot>
						<tr>
							<td class="gray"></td>
							<td class="gray"><?php echo count($pr_details); ?> item(s)</td>
							<td class="gray"></td>
							<td class="gray"></td>
							<td class="gray dh foot"></td>
							<!-- -->												
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="row">
				<div class="col-xs-3">
					<div class="t-title">
						<div>Prepared By :</div>
						<div><select name="" id="prepared_by" class="form-control"></select></div>
					</div>					
				</div>
				<div class="col-xs-3">
					<div class="t-title">
						<div>Approved By :</div>
						<div><select name="" id="approved_by" class="form-control"></select></div>
					</div>
				</div>				
			</div>	
			<div class="row">
				<div class="col-xs-12">
					<button class="pull-right btn btn-primary btn-sm" id="final-save">Save</button>
				</div>
			</div>		
		</div>

	</div>
</div>

<script>

    var supplier_content = new Array();
    var check_supplier = new Array();
    		
	var xhr = "";

		var canvass = {
			init:function(){				
				$('#cv_date').date({
					url   : 'get_ccv_code',
					dom   : $('#cv_no'),
					div   : $('#cv-no-title'),
					event : 'change',
				});
				var cmbproject = "<?php echo $this->session->userdata('Proj_Code') ?>";

				$.signatory({
					type          : 'cv',
					prepared_by   : '',
					recommended_by: ["4", "5", "1",cmbproject],
					approved_by   : ["5", "4", "1",cmbproject],
				});

				this.bindEvent();
				this.render();

			},bindEvent:function(){

				$('#add-supplier').on('click',this.add_supplier);
				$('#cv-no-title').on('click',this.render);
				$('#final-save').on('click',this.final_save);
				$('body').on('click','.lookup_view',this.lookup);

			},add_supplier:function(){

				$post = {
					pr_id : $('#pr_id').val(),
				};
				
				$.fancybox.showLoading();				
				$.post('<?php echo base_url().index_page();?>/transaction/canvass_supplier',$post,function(response){
					$.fancybox(response,{
						width     : 900,
						height    : 550,
						fitToView : true,
						autoSize  : false,
					})
				});				
			},render:function(){
				

				$('#canvass_table thead tr').find('.dh.head').nextAll('th').remove();
				$('#canvass_table thead tr').find('.dh.sub-head').nextAll('th').remove();
				$('#canvass_table tbody tr').each(function(i,val){
						$(val).find('.dh').nextAll('td').remove();
				});
				$('#canvass_table tfoot tr').find('.dh.foot').nextAll('td').remove();


				$.each(supplier_content,function(i,val){

					$('#canvass_table thead tr').find('.dh.head').after(render_table.header(val.supplier_name,i,val.supplier_remarks));
					$('#canvass_table thead tr').find('.dh.sub-head').after(render_table.sub_head);

					$.each(val.items,function(x,value){
						$('#canvass_table tbody tr').each(function(i,val){
							if(x == i){
								$(val).find('.dh').after(render_table.content(value));	
							}							
						});							
					});					
					$('#canvass_table tfoot tr').find('.dh.foot').after(render_table.footer(val.total,val.discounted_total));					
				});
				
			},final_save:function(){
				if(supplier_content.length == 0){

					alert('Please add supplier');
					return false;
				}

				if($('#cv_no').val()=="")
				{
					alert('No Canvass No. generated');
					return false;
				}

				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}				
				
				$.save({appendTo : 'body'});

				$post = {
					c_number : $('#cv_no').val(),
					c_date   : $('#cv_date').val(),
					pr_id    : $('#pr_id').val(),
					is_boq_new	: $('#is_boq_new').val(),
					approvedBy : $('#approved_by option:selected').val(),
					preparedBy : $('#prepared_by option:selected').val(),
					data       : JSON.stringify(supplier_content),
				};

		        if(xhr && xhr.readystate != 4){
		            xhr.abort();
		        }
				xhr = $.post('<?php echo base_url().index_page();?>/procurement/canvass_sheet/save_canvass2',$post,function(response){
					switch($.trim(response)){
						case "1":
							$.save({action : 'success',reload : 'false'});						
							window.location = "<?php echo base_url().index_page(); ?>/transaction_list/canvass_sheet/for_canvass";
						break;
						default:
							$.save({action : 'error',reload : 'false'});
							alert('Save failed: We are having technical problems, please try again later');
						break;
					}
				}).error(function(x,e) { 
						 if(x.status==0){
			                alert('You are offline!!\n Please Check Your Network.');
			            }else if(x.status==404){
			                alert('Requested URL not found.');
			            }else if(x.status==500){
			                alert('Internel Server Error.');
			            }else if(e=='parsererror'){
			                alert('Error.\nParsing JSON Request failed.');
			            }else if(e=='timeout'){
			                alert('Request Time out.');
			            }else {
			                alert('Unknow Error.\n'+x.responseText);
			            }
					$.save({action : 'error',reload : 'false'});
				});				
			},lookup:function(){
				$post = {
					item_no : $(this).attr('data-id')
				};
				
				$.fancybox.showLoading();				
				$.post('<?php echo base_url().index_page();?>/transaction/lookup',$post,function(response){
					$.fancybox(response,{
						width     : 900,
						height    : 550,
						fitToView : true,
						autoSize  : false,
					})
				});
			}

		}
		


		var render_table = {
			init:function(){
				this.bindEvent();
			},header:function(title,i,remarks){
				var div = "";
					div +='<th colspan="5" class="td-head-border"><button data-id="'+i+'" class="supplier-close close" type="button" ><span aria-hidden="true">&times;</span><span></span></button> '+title+' <div><small>'+remarks+'</small></div></th>';
					return div;					
			},sub_head:function(){
				var div = "";
					div +='<th class="td-number td-border-left">Item Price</th>';
					div +='<th class="td-number">%Less Discount</th>';					
					div +='<th class="td-number ">Discounted Price</th>';
					/*div +='<th class="td-number">Total Discounted Price</th>';*/
					div +='<th class="td-number">Total</th>';
					div +='<th class="td-border-right">Remarks</th>';
					return div;
			},content:function(data){								
				var div = "";					
					div +='<td class="td-number td-qty td-border-left">'+data.unit_cost+'</td>';
					div +='<td class="td-number ">'+data.percentage+'% Discount '+data.discount+'</td>';
					div +='<td class="td-number ">'+data.discounted_price+'</td>';
					div +='<td class="td-number ">'+data.total_discounted_price+'</td>';
					div +='<td class="td-number " style="display:none">'+data.supp_total+'</td>';
					div +='<td class="td-qty td-border-right">'+data.remarks+'</td>';
					return div;
			},footer:function(total,discounted_total){
				var div = "";
					div +='<td class="td-border-left"></td>';
					div +='<td class=""></td>';
					div +='<td class=""></td>';
					div +='<td class="td-number " >Total : '+discounted_total+'</td>';
					/*div +='<td class="td-number " >Total : '+total+'</td>';*/
					div +='<td class="td-border-right"></td>';
					return div;
			},bindEvent:function(){

				$('body').on('click','.supplier-close',function(data){					
					var bool = confirm('Are you sure?');
					if(!bool){
						return false;
					}
					var index = $(this).attr('data-id');
					supplier_content.splice(index,1);
					check_supplier.splice(index,1);
					canvass.render();
				});

			}
		};

	$(function(){
		render_table.init();
		canvass.init();

	});
</script>