<div class="container">
	<h4>PO Summary</h4>

	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<div class="control-label">Date From</div>
				<input type="text" id="datefrom" name="datefrom" class="form-control input-sm datefrom" style="text-align:center;height:30px;" value="<?php if($from <> ''){ echo $from; }else{ echo date('Y-m-d'); }?>" />
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<div class="control-label">Date To</div>
				<input type="text" id="dateto" name="dateto" class="form-control input-sm dateto" style="text-align:center;height:30px;" value="<?php if($to <> ''){ echo $to; }else{ echo date('Y-m-d'); }?>" />
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<div class="control-label">Project Site</div>
				<select name="" id="create_profit_center" class="form-control input-sm" style="height:30px;">
					<option value="">Select Project</option>
					<?php
						if(count($project) > 0){
							foreach($project as $row){
					?>
					<option value="<?php echo $row['project_id'];?>" <?php if($proj <> '' && $row['project_id'] == $proj){ ?>selected="selected"<?php } ?>><?php echo $row['fullname'];?></option>
					<?php
							}
						}
					?>
				</select>
			</div>
		</div>

		<div class="col-md-2">
			<button id="apply_filter" class="btn btn-primary btn-sm">Apply Filter</button>
		</div>
	</div>

	<div class="clearfix"></div>

	<table class="table" id="tbl_po_summary">
		<thead>
			<tr>
				<th>PO.No</th>
				<th>Request From</th>
				<th>Supplier</th>
				<th>Amount</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				if($po_summary != '') :
					foreach($po_summary as $row):  
			?>
				<tr>
					<td><?php echo $row['reference_no']; ?></td>
					<td><?php echo $row['from_projectCodeName']; ?></td>
					<td><?php echo $row['Supplier']; ?></td>
					<td><?php echo $this->extra->number_format($row['total_cost']); ?></td>
					<td><?php echo $row['po_date'];?></td>
				</tr>
			<?php 
					endforeach;
				endif; 
			?>
		</tbody>
		<tfoot>
			<tr>
				<th></th>
				<th></th>
				<th>Total : </th>
				<th></th>
				<th></th>
			</tr>
		</tfoot>
	</table>
</div>
<script>
	
	$(function(){

			var po_summary = {
				init:function(){

					datatable_option.fnFooterCallback = function ( nRow, aaData, iStart, iEnd, aiDisplay )
					{						
						var iTotalMarket = 0;
						for ( var i=0 ; i<aiDisplay.length ; i++ )
						{							

							iTotalMarket += +remove_comma(aaData[aiDisplay[i]][3]);							
						}
							
						nCells = nRow.getElementsByTagName('th');
						nCells[2].innerHTML = "Total : "+ comma(parseInt(iTotalMarket).toFixed(2))
												
						qty = 0;					
						for ( var i=iStart ; i<iEnd ; i++ )
						{	
							var amt = remove_comma(aaData[ aiDisplay[i]][3]);
							qty += +amt;
						}								
						nCells = nRow.getElementsByTagName('th');
						nCells[3].innerHTML =  comma(parseInt(qty).toFixed(2));
					}
					$('#tbl_po_summary').dataTable(datatable_option);

					$('.datefrom').date();
					$('.dateto').date();

				},
			}	

			po_summary.init();	

		$('#apply_filter').on('click',function(){
			$post = {
				datefrom : $('#datefrom').val(),
				dateto : $('#dateto').val(),
				project : $('#create_profit_center option:selected').val()
			}

			console.log($post);

			$.fancybox.showLoading();
			$.post('<?php echo base_url().index_page();?>/transaction/po_summary_override',$post,function(response){
				$.fancybox(response,{
					width     : 1000,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			});
		});
	});

</script>