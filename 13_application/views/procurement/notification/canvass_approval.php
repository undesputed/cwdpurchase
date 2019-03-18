

<div class="container">
	<?php if($main_list['approval'] !=0): ?>
		<div class="alert alert-success" role="alert" style="margin-top:5px;">
			<strong>Already Approved!</strong>
		</div>
	<?php endif; ?>

	<div class="row">		
		<div class="col-md-7">
			<h2><?php echo $main_list['c_number']; ?></h2>
			<div class="panel panel-default">
			  <div class="panel-heading">Item List</div>			  
			  <table class="table">
			  		<thead>
			  			<tr>
			  				<th>#</th>
			  				<th>ItemNo</th>
			  				<th>Item Desc</th>
			  				<th>Unit Measure</th>
			  				<th>Request Qty</th>
			  				<th>Approved Qty</th>
			  				<th>Canvass Qty</th>
			  				<th>Total Cost</th>
			  			</tr>
			  		</thead>	
			  		<tbody>
			  			<?php 
			  				$cnt = 0;
			  				foreach($item_list as $row):
			  				$cnt++;
			  			?>
						<tr>							
							<td><?php echo $cnt;?></td>
							<td><?php echo $row['itemNo']; ?></td>
							<td><?php echo $row['DESCRIPTION']; ?></td>
							<td><?php echo $row['UNIT']; ?></td>
							<td><?php echo $row['REQUESTED QTY']; ?></td>
							<td><?php echo $row['APPROVED QTY']; ?></td>
							<td>0</td>
							<td>0</td>							
						</tr>
			  			<?php endforeach;?>
			  		</tbody>
			  </table>
			</div>
			<hr>
			<div class="row">

				<form action="" method="post" id="form">
					<input type="hidden" name="can_id" id="can_id" value="<?php echo $main_list['can_id'] ?>">
					<div class="col-xs-8">
						<div class="form-group">
							<label for="">Approved By : </label>
							<select name="prepared_by" id="prepared_by" class=""></select>
						</div>
					</div>
					<div class="col-xs-4">
						<div class="btn-group pull-right">
							<input type="button"  id="save" value="Save" class="btn btn-primary">
						</div>
					</div>
				</form>

			</div>
		</div>
		<div class="col-md-5">
			<div class="content-title">
				<h3>Canvass Supplier</h3>
			</div>
			
			<?php 
				$supplier_cnt = 0;	
				foreach($canvass_supplier as $key2=>$row): 
			?>
					<?php foreach($row as $key=>$row1):	$supplier_cnt++; ?>
							<div class="panel panel-default">		
							  <div class="panel-heading"><?php echo $supplier_cnt.".) ".$key; ?> <span class="pull-right label label-info"><?php echo $key2; ?></span></div>
							  <table class="table">
								<thead>
									<tr>
										<th>#</th>
										<th>Status</th>
										<th>Item No</th>
										<th>Item Desc</th>
										<th>Unit</th>
										<th>Qty</th>
										<th>Unit Cost</th>
										<th>Total</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php $cnt = 0; foreach($row1 as $row2): $cnt++; ?>
									<?php $class = ($row2['approvedSupplier']=='TRUE')? 'text-success' : 'text-muted'; ?>
									<?php $action = ($row2['approvedSupplier']=='TRUE')? 'Remove' : 'Confirm'; ?>
										<tr class="<?php echo $class; ?>">
											<td class="supplier_id" style="display:none"><?php echo $row2['supplier_id']; ?></td>
											<td><?php echo $cnt; ?></td>
											<td><?php echo $this->extra->label2($row2['approvedSupplier']); ?></td>
											<td class="itemNo"><?php echo $row2['itemNo']; ?></td>
											<td><?php echo $row2['description'] ?></td>
											<td><?php echo $row2['unit_measure']; ?></td>
											<td><?php echo $row2['sup_qty']; ?></td>
											<td><?php echo $row2['supplier_cost']; ?></td>
											<?php $total = $row2['sup_qty'] * $row2['supplier_cost']; ?>
											<td><?php echo $total; ?></td>
											<th><button class="btn-link event" data-status="<?php echo $row2['approvedSupplier'] ?>"><?php echo $action; ?></button></th>											
										</tr>
									<?php endforeach; ?>
								</tbody>							 
							  </table>
							</div>
					<?php endforeach; ?>
			<?php endforeach; ?>
			
						
			
		</div>		
	</div>
</div>


<script>

	$(function(){

		var app = {
			init:function(){
				this.bindEvents();

				$.signatory({
					prepared_by   : 'sesssion',				
				});


			},bindEvents:function(){
				$('.event').on('click',this.change_status);
				$('#save').on('click',this.submit);
			},change_status:function(){
				var me          = $(this);
				var itemNo      = $(this).closest('tr').find('.itemNo').text();
				var can_id      = $('#can_id').val();
				var supplier_id = $(this).closest('tr').find('.supplier_id').text();
				var status      = ($(this).attr('data-status')=='TRUE')? 'FALSE':'TRUE';
				
				$post = {
					item_no     : itemNo,
					can_id      : can_id,
					supplier_id : supplier_id,
					status      : status,
				};	

				$.post('<?php echo base_url().index_page();?>/procurement/canvass_sheet/canvass_detail_status',$post,function(response){

					location.reload('true');

				},'json');				
			},submit:function(){
				var bool = confirm('Are you sure you want to Save?');
				if(!bool){
					return false;
				}
				$('#form').submit();

			}
		}

		app.init();


	});


</script>