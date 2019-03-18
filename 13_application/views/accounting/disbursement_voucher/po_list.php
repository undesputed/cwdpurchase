

<div class="panel panel-default">		
  <div class="panel-body">
  		<table class="table table-hover">
  			<thead>
  				<tr>
  					<th>PO NUMBER</th>
  					<th>PO DATE</th>
  					<th>SUPPLIER</th>
  					<th>PO AMOUNT</th>
  					<th>TERM</th>
  					<th>STATUS</th>  				
  				</tr>
  			</thead>
  			<tbody>
  				<?php if(count($result) > 0): ?>
          <?php foreach($result as $row): ?>
					 <tr>
              <td><a href="<?php echo base_url().index_page()?>/accounting/voucher/<?php echo $row['po_id']; ?>"><?php echo $row['PO NUMBER']; ?></a></td>
              <td><?php echo $row['PO DATE']; ?></td>
              <td><?php echo $row['SUPPLIER']; ?></td>
              <td><?php echo $row['PO AMOUNT']; ?></td>
              <td><?php echo $row['TERM'] ?></td>
              <td><?php echo $row['STATUS'] ?></td>
           </tr>
          <?php endforeach; ?>
  				<?php else: ?>
          <tr>
					   <td colspan="6">Empty List</td>
          </tr>
  				<?php endif; ?>
  			</tbody>

  		</table>
  </div>	 
</div>