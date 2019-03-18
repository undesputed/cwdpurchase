<?php 

	$date = array();
	$data = array();
	foreach($result as $row){
		$d = $this->extra->format_date($row['po_date']);
		$data[$d][] = $row;
	}

?>

<div class="panel panel-default">
  <div class="panel-heading">List of Approved PO</div>		
  <div class="panel-body">
  		<ul class="sidebar">
			<?php foreach($data as $key=>$row): ?>
			<li>
				<span><?php echo $key; ?></span>
				<ul>
				<?php foreach($row as $row1): ?>
					<li><a href="<?php echo base_url().index_page()?>/transaction/purchase_order/<?php echo $row1['po_id']?>/view"/><?php echo $row1['po_number'] ?></a> <span class="pull-right"><?php echo $this->extra->label($row1['po_status']) ?></span></li>							
				<?php endforeach;?>
				</ul>
			</li>
			<?php endforeach; ?>
  		</ul>
  </div>
</div>

