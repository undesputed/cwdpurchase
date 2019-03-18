<div class="content-title">
	<h3>Details</h3>
</div>

<div class="container">	
	<table class="table table-condensed table-striped">
		<thead>
			<tr>
				<th>Item No</th>
				<th>Item Name</th>
				<th>Unit</th>
				<th>Quantity</th>
				<th>Available</th>				
			</tr>
		</thead>
		<tbody>
			<?php foreach($result as $row): ?>
				<tr>
					<td><?php echo $row['itemNo']; ?></td>
					<td><?php echo $row['itemDesc']; ?></td>
					<td><?php echo $row['umsr']; ?></td>
					<td><?php echo $row['qty']; ?></td>
					<td><?php echo $row['available']; ?></td>
							
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div class="row">
		<div class="col-md-5">
			
		</div>
		<div class="col-md-7">
			<button onclick="approved(this,<?php echo $id; ?>)"  class="pull-right btn-sm btn btn-primary ">Approved <span class="fa"></span></button>
		</div>
	</div>
</div>

<script>
	
	var approved = function(dom,id){
		$post = {
			id : id
		};
		$(dom).addClass('disabled');
		$(dom).find('span').addClass('fa-spin fa-spinner');
		$.post('<?php echo base_url().index_page();?>/procurement/material_requisition/approved',$post,function(response){
			if(response == 1){
				alert('Successfully Approved');
				location.reload();
			}
			$(dom).find('span').removeClass('fa-spin fa-spinner');
			$(dom).removeClass('disabled');

		});

	};


</script>