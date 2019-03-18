<?php 

$active = "";

$base = base_url().index_page().'/transaction_list/purchase_request';
$sidebar_link = array(
 	  array('title'=>'Outgoing Request','url'=>$base.'/outgoing','outgoing'=>'active','icon'=>'<span class="fa fa-level-up"></span>','notification'=>'outgoing'),
 	  array('title'=>'Incoming Request','url'=>$base.'/incoming','incoming'=>'active','icon'=>'<span class="fa fa-level-down"></span>','notification'=>'incoming'),
 	);
$segment = $this->uri->segment(3);


$segment2 = $this->uri->segment(2);

$active['canvass_sheet']  =($segment2 == 'canvass_sheet')? 'active': '';
$active['purchase_order'] =($segment2 == 'purchase_order')? 'active': '';
$active['receiving_report'] =($segment2 == 'receiving_report')? 'active': '';
$active['inventory'] =($segment2 == 'inventory')? 'active': '';

?>

<div class="container-fluid">

	<div class="wrapper">
		<div class="sidebar-1">
			<ul class="menu-sidebar">
				<li class="menu-sidebar-item head"><span>Purchase Request</span></li>
				<ul>
			  		<?php foreach($sidebar_link as $row): ?>
			  			<li class="sub-menu-sidebar-item <?php  echo (isset($row[$segment]))? 'active': '' ; ?>">
							<a href="<?php echo $row['url']; ?>"><?php echo $row['icon']; ?> <?php echo $row['title'] ?> <?php echo $this->lib_transaction->notification($row['notification']); ?></a>
						</li>
			  		<?php endforeach; ?>		
			  	</ul>
			  	<li class="menu-sidebar-item head <?php echo $active['canvass_sheet']; ?>"><a href="<?php echo base_url().index_page() ?>/transaction_list/canvass_sheet" class="">Canvass Sheet <?php echo $this->lib_transaction->canvass_notification(); ?></a></li>

			  	<li class="menu-sidebar-item head  <?php echo $active['purchase_order']; ?> " ><a href="<?php echo base_url().index_page() ?>/transaction_list/purchase_order" class="">Purchase Orders <?php echo $this->lib_transaction->po_notification(); ?></a></li>

			  	<li class="menu-sidebar-item head  <?php echo $active['receiving_report']; ?> " ><a href="<?php echo base_url().index_page() ?>/transaction_list/receiving_report" class="">Receiving Report <?php echo $this->lib_transaction->rr_notification(); ?></a></li>

			  	<li class="menu-sidebar-item head  <?php echo $active['inventory']; ?> " ><a href="<?php echo base_url().index_page() ?>/transaction_list/inventory" class="">Inventory</a></li>
			</ul>
		</div>
		<div class="sidebar-main">		
				<div class="row">
					<div class="col-xs-5">
						<?php 
					  	 	echo $view;
					  	 ?>		
					</div>
					<div class="col-xs-7">
						
						<?php echo $this->lib_transaction->transaction_info(); ?>

					</div>
				</div>	  	
			  	 
		</div>
	</div>

</div>