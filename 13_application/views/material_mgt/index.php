
<?php 

echo "<pre>";
print_r($this->session->all_userdata());
echo "</pre>";

?>

<div class="container">

	<?php echo $this->extra->alert(); ?>

	<div class="row" style="margin-top:10px;">
		
		<div class="col-sm-8">
				<div class="panel panel-default">		
				  <div class="panel-heading">
				  	<h3 class="panel-title">Inventory</h3>					  
				  </div>				
				  	<div style="margin-top:5px;"></div>
				  	<table id="tbl_inventory" class="table table-condensed" >
				  		<thead>
				  			<tr>
				  				<th>Item Category</th>
				  				<th>Item No</th>
				  				<th>Description</th>
				  				<th>Unit</th>
				  				<th>Stocks</th>
				  			</tr>
				  		</thead>
						<tbody>
							<?php foreach($items as $row): ?>
								<tr>
									<td><?php echo $row['ITEM CATEGORY']; ?></td>
									<td><?php echo $row['ITEM NO'];?></td>
									<td><?php echo $row['ITEM DESCRIPTION']; ?></td>
									<td><?php echo $row['UNIT MEASURE']; ?></td>
									<td><?php echo $row['qty_onhand']; ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
				  	</table>

				</div>
		</div>
		<div class="col-sm-4">

			<div class="panel panel-default">		
				  <div class="panel-heading">
				  	<h3 class="panel-title">Notification</h3>			  
				  </div>				  
					<div class="list-group list-group-main">
					  <a href="#" class="list-group-item ">
					    <h4 class="list-group-item-heading">List group item heading</h4>
					    <div class="trans-date" >
					    	<span class="date">Oct 23, 2014</span>
					    </div>
					    <p class="list-group-item-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo, voluptatem, blanditiis voluptatum labore voluptas vero quas exercitationem repellat odio distinctio.</p>
					  </a>
					    <a href="#" class="list-group-item ">
					    <h4 class="list-group-item-heading">List group item heading</h4>
					    <div class="trans-date" >
					    	<span class="date">Oct 23, 2014</span>
					    </div>
					    <p class="list-group-item-text">...</p>
					  </a>
					    <a href="#" class="list-group-item ">
					    <h4 class="list-group-item-heading">List group item heading</h4>
					    <div class="trans-date" >
					    	<span class="date">Oct 23, 2014</span>
					    </div>
					    <p class="list-group-item-text">...</p>
					  </a>
					</div>
			</div>
		</div>		
	</div>
</div>

<script>
	
	$(function(){
		
		var app = {
			init:function(){
				this.bindEvents();	
				this.datatable();			
			},bindEvents:function(){


			},datatable:function(){
				$('#tbl_inventory').dataTable(datatable_option);
			}
		}

		app.init();

	});

</script>
