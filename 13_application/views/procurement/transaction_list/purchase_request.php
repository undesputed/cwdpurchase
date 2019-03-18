
<?php 

$base = base_url().index_page().'/transaction_list/purchase_request';
$sidebar_link = array(
 	  array('title'=>'Outgoing Request','url'=>$base.'/outgoing','outgoing'=>'active','icon'=>'<span class="fa fa-level-up"></span>'),
 	  array('title'=>'Incoming Request','url'=>$base.'/incoming','incoming'=>'active','icon'=>'<span class="fa fa-level-down"></span>'),
 	);
$segment = $this->uri->segment(3);

?>

<div class="container-fluid">

	<div class="wrapper">
		<div class="sidebar-1">
			<ul class="menu-sidebar">
				<li class="menu-sidebar-item head"><span>Purchase Request</span></li>
				<ul>
			  		<?php foreach($sidebar_link as $row): ?>
			  			<li class="sub-menu-sidebar-item <?php  echo (isset($row[$segment]))? 'active': '' ; ?>">
							<a href="<?php echo $row['url']; ?>"><?php echo $row['icon']; ?> <?php echo $row['title'] ?> <span class="badge">1</span></a>
						</li>
			  		<?php endforeach; ?>		
			  	</ul>
			  	<li class="menu-sidebar-item head "><a href="#" class="">Canvass Sheet</a></li>

			  	<li class="menu-sidebar-item head"><a href="#" class="">Purchase Orders</a></li>
			</ul>
		</div>
		<div class="sidebar-main">
			  <div class="row">			  	
			  	<div class="col-xs-6">
			  		  <?php 
				   $segment = $this->uri->segment(3);
				   if($segment == 'outgoing'){
				   		$this->load->view('procurement/transaction_list/purchase_request_outgoing'); 	
				   }else{
						$this->load->view('procurement/transaction_list/purchase_request_incoming'); 					   	
				   }				   
				  ?>	
			  	</div>
			  	<div class="col-xs-6">
			  		
			  	</div>
			  </div>
				

		</div>
	</div>

</div>