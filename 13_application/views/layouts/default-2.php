<!doctype html>
<html>
<head>
 <title><?php echo $template['title'] ?></title>
<meta charset="utf-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
<meta content="" name="description">
<meta content="" name="author">

<link href="<?php echo base_url();?>asset/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/font-awesome.min.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/jquery-ui.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/datatable-bootstrap.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/chosen.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/prism.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/examples.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/style.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/morris.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/plot.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/jquery-confirm.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/thickbox.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/select2.css" rel="stylesheet" media="screen">

<link href="<?php echo base_url();?>asset/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" media="screen">

<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/chosen.jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/prism.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/fancybox/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/projects_plugin.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/custom.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.nav.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.nicescroll.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.jeditable.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.sieve.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.slimscroll.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery-confirm.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/thickbox-compressed.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.jscroll.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.history.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/select2.js"></script>



<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-70667915-1', 'auto');
  ga('send', 'pageview');

</script>


<script>
	$(function(){

		/*$("html").niceScroll();	*/

	 /*   var searchTemplate = "<div class='search-sieve'><div class='search-group'><input type='text' placeholder='' class='form-control'> <span> <i class='fa fa-search'></i></span></div></div>"
	    $(".table-sieve").sieve({ searchTemplate: searchTemplate }); */
	    $('.tool_tip').tooltip();
        
    });	
</script>

<base href="<?php echo base_url().index_page();?>">
</head>
<body>
	
      <div id="wrapper">        
        <div class="topbar">
        <div class="navbar navbar-default navbar-static-top navbar-custom">
            <div class="topbar-left">
                <button class="button-menu-mobile open-left">
              	  <i class="fa fa-bars"></i>
                </button>
            </div>

            <div class="navbar-header navbar-title">
                
                <!--  
                <button class="navbar-toggle collapsed" data-target=".navbar-collapse" data-toggle="collapse" type="button">
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                -->
                
                <a class="navbar-brand site-title" href="<?php echo base_url(); ?>">
                    <span class="site-name"><?php echo $this->config->item('site_title') ?></span>
                    <span><?php echo $this->session->userdata('title_project'); ?></span>
                    <span class="sub-site-name"><?php echo $this->session->userdata('title_profitcenter'); ?></span>
                </a>

            </div>

            <div class="topbar-right">
                <button class="button-menu-mobile open-right" data-target=".navbar-collapse" data-toggle="collapse">
                  <i class="fa fa-list"></i>
                </button>
            </div>            
            <div class="navbar-collapse collapse">
                         <div class="navbar-collapse">
                                <ul class="nav navbar-nav navbar-left">
                                    <?php 
                                        
                                        $privileges = $this->session->userdata('privileges');                                        
                                        $projCode   = $this->session->userdata('Proj_Code'); 

                                        if($projCode == 1):
                                    ?>
                                        <?php if($this->lib_auth->restriction('CANVASS USER') || in_array('voucher',$privileges)): ?>
                                        <li class="">
                                            <a href="javascript:void(0)"><strong class="label label-info">ERP</strong></a>
                                        </li>        
                                        <li class="">
                                            <a href="<?php echo base_url(); ?>accounting"><strong>ACCOUNTING</strong></a>
                                        </li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </ul>
                                <ul class="nav navbar-nav navbar-right">            
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->extra->user(); ?> <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo base_url().index_page() ?>/auth/settings">Account Settings</a></li>
                                            <li class="divider"></li>
                                            <li><a href="<?php echo base_url().index_page() ?>/auth/logout">Logout</a></li>
                                        </ul>
                                    </li>
                                </ul>
                                <ul class="nav menu-create-ul navbar-nav navbar-right">
                                    <li class="dropdown notification-drowdown">
                                        <a href="#" id="notification-btn" class="menu-create-button dropdown-toggle" data-toggle="dropdown"><span> <span class="fa fa-plus"></span> CREATE <b class="caret"></b> </span> </a>
                                        <ul class="dropdown-menu">                                          
                                            <li><a href="<?php echo base_url().index_page() ?>/transaction/create/pr">Purchase Request</a></li> 
                                            <li><a href="<?php echo base_url().index_page() ?>/transaction/create/jo">Job Order</a></li>
                                            <li><a href="<?php echo base_url().index_page() ?>/transaction/create/rr">Direct Receiving</a></li> 
                                            <!-- <li><a href="<?php echo base_url().index_page() ?>/transaction/create/dt">Direct Transfer</a></li> -->
                                            <li><a href="<?php echo base_url().index_page() ?>/transaction/create/rx">Return</a></li>                                        
                                        </ul>
                                    </li>
                                </ul>
                         </div>
            </div>

        </div>
        </div>

				<?php 

					$segment3 = $this->uri->segment(3);					
					$segment2 = $this->uri->segment(2);
					$segment1 = $this->uri->segment(1);
                    					
					$active['manage']['a'] = ($segment1 == 'setup')? 'subdrop active' : '';					
					$active['manage']['ul'] = ($segment1 == 'setup')? 'display:block' : '';

					$active['company_setup'] = ($segment2 == 'company_setup')? 'active': '';
					$active['profit_center'] = ($segment2 == 'profit_center')? 'active': '';
					$active['supplier_setup'] = ($segment2 == 'supplier_setup')? 'active': '';
					$active['person_setup'] = ($segment2 == 'person_setup')? 'active': '';
					$active['employee_setup'] = ($segment2 == 'employee_setup')? 'active': '';
					$active['item_setup'] = ($segment2 == 'item_setup')? 'active': '';
                    $active['item_group'] = ($segment2 == 'item_group')? 'active': '';
					$active['user_setup'] = ($segment2 == 'user_setup')? 'active': '';
					$active['beginning'] = ($segment2 == 'beginning')? 'active': '';
                    $active['signatory'] = ($segment2 == 'signatory')? 'active': '';
                    $active['tenant_setup'] = ($segment2 == 'tenant_setup')? 'active': '';
                    
                    
					$active['purchase_request']['a'] = ($segment2 == 'purchase_request')? 'subdrop active' : '';
					$active['purchase_request']['ul'] = ($segment2 == 'purchase_request')? 'display:block' : '';
					$active['incoming'] = ($segment3 == 'incoming')? 'active': '';
					$active['outgoing'] = ($segment3 == 'outgoing')? 'active': '';

					$active['canvass_sheet']['a'] = ($segment2 == 'canvass_sheet')? 'subdrop active' : '';
                    $active['canvass_sheet']['ul'] = ($segment2 == 'canvass_sheet')? 'display:block' : '';
                    $active['for_canvass'] = ($segment3 == 'for_canvass')? 'active': '';
                    if($active['canvass_sheet']['a'] == 'subdrop active'){
                        $active['canvass_for_approval'] = ($segment3 == 'for_approval')? 'active': '';
                    }else{
                        $active['canvass_for_approval'] = "";
                    }                    
                    $active['purchase_order']['a'] = ($segment2 == 'purchase_order')? 'subdrop active' : '';
                    $active['purchase_order']['ul'] = ($segment2 == 'purchase_order')? 'display:block' : '';
                    $active['signatory_print'] = ($segment3 == 'signatory_print')? 'active': '';
                    $active['for_po'] = ($segment3 == 'for_po')? 'active': '';
                    if($active['purchase_order']['a'] == 'subdrop active'){
                        $active['po_for_approval'] = ($segment3 == 'for_approval')? 'active': '';
                    }else{
                        $active['po_for_approval'] = "";
                    }
                    $active['po_for_printing'] = ($segment3 == 'for_printing') ? 'active' : '';   
                                        
					$active['receiving_report'] = ($segment2 == 'receiving_report')? 'active': '';
                    $active['undelivered'] = ($segment2 == 'undelivered')? 'active': '';
                    

					$active['inventory']['a']  =($segment2 == 'inventory')? 'subdrop active': '';
                    $active['inventory']['ul'] = ($segment2 == 'inventory')? 'display:block' : '';


					$active['item_withdrawal']['a'] = ($segment2 == 'item_withdrawal' || $segment2 == 'item_issuance')? 'subdrop active': '';
                    $active['item_withdrawal']['u'] = ($segment2 == 'item_withdrawal' || $segment2 == 'item_issuance')? 'display:block': '';
                    $active['item_withdraw']   = ($segment2 == 'item_withdrawal')? 'active': '';
                    $active['item_issuance']   = ($segment2 == 'item_issuance')? 'active': '';

                    $active['item_transfer']['a'] = ($segment2 == 'item_transfer' || $segment2 == 'item_transfer')? 'subdrop active': '';
                    $active['item_transfer']['u'] = ($segment2 == 'item_transfer' || $segment2 == 'item_transfer')? 'display:block': '';
					$active['item_transfer']['item_request']   = ($segment3 == 'item_request')? 'active': '';
                    $active['item_transfer']['request']   = ($segment3 == 'request')? 'active': '';
                    $active['item_transfer']['for_receiving']   = ($segment3 == 'for_receiving')? 'active': '';

                    $active['material_transfer'] = ($segment2 == 'material_transfer')? 'active': '';
                  
                    $active['direct_receiving'] = ($segment2 == 'direct_receiving')? 'active': '';
                    $active['return'] = ($segment2 == 'return')? 'active': '';

				?>

                <div class="left side-menu">
                        <div class="sidebar-inner slimscrollleft">
                            <div id="sidebar-menu" class="">
                                <ul>   

                                  
                                    <li class="">
                                        <a href="javascript:void(0);" class="<?php echo $active['manage']['a']; ?>"><i class="fa fa-gears"></i> <span>Manage Setup</span> <span class="pull-right "><i class="fa fa-angle-down"></i></span></a>
                                            <ul style="<?php echo $active['manage']['ul']; ?>">
                                                <?php if($this->lib_auth->restriction('ADMIN')): ?>
                                                <li><a class="<?php echo $active['company_setup']; ?>" href="<?php echo base_url().index_page();?>/setup/company_setup"><span>Company Setup</span></a></li>
                                                <li><a class="<?php echo $active['profit_center']; ?>" href="<?php echo base_url().index_page();?>/setup/profit_center"><span>Project Setup</span></a></li>                                            
                                                <li><a class="<?php echo $active['supplier_setup']; ?>" href="<?php echo base_url().index_page();?>/setup/supplier_setup"><span>Supplier Setup</span></a></li>                                            
                                                <?php endif; ?>
                                                <li><a class="<?php echo $active['person_setup']; ?>" href="<?php echo base_url().index_page();?>/setup/person_setup"><span>Person Setup</span></a></li>
                                                <li><a class="<?php echo $active['employee_setup']; ?>" href="<?php echo base_url().index_page();?>/setup/employee_setup"><span>Employee Setup</span></a></li>
                                                <?php if($this->lib_auth->restriction('ADMIN')): ?>
                                                <li><a class="<?php echo $active['user_setup']; ?>" href="<?php echo base_url().index_page();?>/setup/user_setup"><span>User Setup</span></a></li>                                                                                               
                                                <li><a class="<?php echo $active['item_setup']; ?>" href="<?php echo base_url().index_page();?>/setup/item_setup"><span>Item Setup</span></a></li>
                                                <li><a class="<?php echo $active['item_group']; ?>" href="<?php echo base_url().index_page();?>/setup/item_group"><span>Item Grouping Setup</span></a></li>
                                                <?php endif;?>
                                                 
                                                <li><a class="<?php echo $active['signatory']; ?>" href="<?php echo base_url().index_page();?>/setup/signatory"><span>Signatory Setup</span></a></li>
                                                <li><a class="<?php echo $active['signatory_print']; ?>" href="<?php echo base_url().index_page();?>/setup/signatory/signatory_print"><span>Signatory Print Setup</span></a></li>
                                                <?php if($this->lib_auth->restriction('ADMIN')): ?>
                                                <li><a class="<?php echo $active['tenant_setup']; ?>" href="<?php echo base_url().index_page();?>/setup/tenant_setup"><span>Tenant Setup</span></a></li>
                                                <?php endif; ?>
                                                <li><a class="<?php echo $active['beginning']; ?>" href="<?php echo base_url().index_page();?>/setup/beginning"><span>Beginning Inventory</span></a></li>
                                            </ul>
                                    </li>
                                   
                                    <li class="">
                                        <a href="javascript:void(0);" class="<?php echo $active['purchase_request']['a'];  ?>"><i class="fa fa-exchange"></i> <span>Purchase Request</span> <span class="pull-right"><i class="fa fa-angle-down"></i></span></a>
                                        <ul style="<?php echo $active['purchase_request']['ul'];?>">                                       
                                            <li><a class="<?php echo  $active['incoming'] ?>" href="<?php echo base_url().index_page(); ?>/transaction_list/purchase_request/incoming"><span>Incoming Request</span><?php echo $this->lib_transaction->notification('incoming'); ?></a></li>
                                            <li><a class="<?php echo  $active['outgoing'] ?>" href="<?php echo base_url().index_page(); ?>/transaction_list/purchase_request/outgoing"><span>Outgoing Request</span><?php echo $this->lib_transaction->notification('outgoing'); ?></a></li>
                                        </ul>
                                    </li>
                                    <li class="">
                                        <a href="javascript:void(0);" class="<?php echo $active['canvass_sheet']['a'];  ?>">
                                        	<i class="fa fa-tags"></i>
                                            <span>Canvass sheet</span> <span class="pull-right"><i class="fa fa-angle-down"></i></span></a>
                                            <ul style="<?php echo $active['canvass_sheet']['ul'];?>">
                                                <li><a class="<?php echo  $active['for_canvass'] ?>" href="<?php echo base_url().index_page(); ?>/transaction_list/canvass_sheet/for_canvass"><span>For Canvass</span><?php echo $this->lib_transaction->for_canvass_notification(); ?></a></li>
                                                <li><a class="<?php echo  $active['canvass_for_approval'] ?>" href="<?php echo base_url().index_page(); ?>/transaction_list/canvass_sheet/for_approval"><span>For Approval</span> <?php echo $this->lib_transaction->canvass_notification(); ?></a></li>
                                            </ul>
                                    </li>
                                    <li class="">
                                        <a href="javascript:void(0);" class="<?php echo $active['purchase_order']['a'];  ?>">
                                        	<i class="fa fa-shopping-cart"></i>
                                            <span>Purchase Order</span> <span class="pull-right"><i class="fa fa-angle-down"></i></span></a>
                                            <ul style="<?php echo $active['purchase_order']['ul'];?>">
                                                <li><a class="<?php echo  $active['for_po']; ?>" href="<?php echo base_url().index_page(); ?>/transaction_list/purchase_order/for_po"><span>For P.O</span><?php echo $this->lib_transaction->for_po_notification(); ?></a></li>
                                                <li><a class="<?php echo  $active['po_for_approval']; ?>" href="<?php echo base_url().index_page(); ?>/transaction_list/purchase_order/for_approval"><span>For Approval</span><?php echo $this->lib_transaction->po_notification(); ?></a></li>
                                                <li><a class="<?php echo  $active['po_for_printing']; ?>" href="<?php echo base_url().index_page(); ?>/transaction_list/purchase_order/for_printing"><span>For Printing</span><?php //echo $this->lib_transaction->po_woprint_notification(); ?></a></li>
                                            </ul>
                                    </li>
                                    <li class="">
                                        <a class="<?php echo $active['receiving_report']; ?>" href="<?php echo base_url().index_page(); ?>/transaction_list/receiving_report"><i class="fa fa-truck"></i> <span>Receiving Report</span> <?php echo $this->lib_transaction->rr_notification(); ?></a>
                                    </li>

                                    <li class="">
                                        <a class="<?php echo $active['direct_receiving']; ?>" href="<?php echo base_url().index_page(); ?>/transaction_list/direct_receiving"><i class="fa fa-cloud"></i> <span>Direct Receiving List</span></a>
                                    </li>

                                    <li class="">
                                        <a class="<?php echo $active['return']; ?>" href="<?php echo base_url().index_page(); ?>/transaction_list/returns"><i class="fa fa-reply"></i> <span>Return List</span></a>
                                    </li>
                                                                         
                                    <!-- <li class="">
                                         <a class="<?php echo $active['undelivered']; ?>" href="<?php echo base_url().index_page(); ?>/transaction_list/undelivered"><i class="fa fa-truck"></i> <span>Undelivered Items</span> <?php //echo $this->lib_transaction->undelivered_notification(); ?></a>
                                    </li> -->                                
                                    
                                    <li class="">
                                        <a class="<?php echo $active['inventory']['a']; ?>" href="javascript:void(0);">
                                        	<i class="fa fa-inbox"></i>
                                            <span>Inventory</span> <span class="pull-right"><i class="fa fa-angle-down"></i></span></a>
                                            <ul style="<?php echo $active['inventory']['ul']; ?>">
                                                <?php echo $this->lib_transaction->project_site_list2(); ?>    
                                            </ul>
                                    </li>
                                    
                                    <li class="">
                                        <a href="javascript:void(0);" class="<?php echo $active['item_withdrawal']['a']; ?>">
                                            <i class="fa fa-external-link"></i>
                                            <span>Withdraw</span> <span class="pull-right"><i class="fa fa-angle-down"></i></span>
                                        </a>
                                            <ul style="<?php echo $active['item_withdrawal']['u']; ?>">
                                                <li><a class="<?php echo $active['item_withdraw']; ?>" href="<?php echo base_url().index_page() ?>/transaction_list/item_withdrawal"><span>Material Withdrawal</span></a></li>
                                                <li><a class="<?php echo $active['item_issuance']; ?>" href="<?php echo base_url().index_page() ?>/transaction_list/item_issuance"><span>Material Issuance</span></a></li>
                                            </ul>
                                    </li>

                                    <!-- 
                                    <li class="">
                                        <a class="<?php echo $active['material_transfer']; ?>" href="<?php echo base_url().index_page(); ?>/transaction_list/material_transfer"><i class="fa fa-mail-forward"></i> <span>Material Transfer</span> <?php echo $this->lib_transaction->rr_notification(); ?></a>
                                    </li> 
                                    -->
                                    
                                    <li class="">
                                           <a href="javascript:void(0);" class="<?php echo $active['item_transfer']['a']; ?>">
                                               <i class="fa fa-mail-forward"></i>
                                               <span>Item Transfer</span> <span class="pull-right"><i class="fa fa-angle-down"></i></span>
                                           </a>
                                           <ul style="<?php echo $active['item_transfer']['u']; ?>">
                                               <li><a class="<?php echo $active['item_transfer']['item_request']; ?>"  href="<?php echo base_url().index_page() ?>/transaction_list/item_transfer/item_request"><span>Material Request</span></a></li>
                                               <!-- <li><a class="<?php echo $active['item_transfer']['request']; ?>"       href="<?php echo base_url().index_page() ?>/transaction_list/item_transfer/request"><span>Material Transfer</span></a></li>
                                               <li><a class="<?php echo $active['item_transfer']['for_receiving']; ?>" href="<?php echo base_url().index_page() ?>/transaction_list/item_transfer/for_receiving"><span>Material Receiving</span></a></li> -->
                                           </ul>
                                    </li>

                                    <?php if($this->lib_auth->restriction(array('ADMIN'))): ?>
                                    <li>
                                        <a href="javascript:void(0);" class="<?php  ?>">
                                           <i class="fa fa-tags"></i>
                                           <span>Item Monitoring</span> <span class="pull-right"><i class="fa fa-angle-down"></i></span>
                                       </a>
                                       <ul style="<?php  ?>">
                                           <li><a class="<?php  ?>" href="<?php echo base_url().index_page() ?>/item/monitoring"><span>Item Monitoring</span></a></li>
                                       </ul>
                                    </li>
                                <?php endif; ?>
                                <?php if($this->lib_auth->restriction(array('CANVASS USER','BOQ'))): ?>
                                    <!-- <li class="">
                                        <a href="javascript:void(0);" class="<?php  ?>">
                                           <i class="fa fa-clipboard"></i>
                                           <span>BOQ Monitoring</span> <span class="pull-right"><i class="fa fa-angle-down"></i></span>
                                        </a>
                                        <ul style="<?php  ?>">
                                        <?php if($this->lib_auth->restriction(array('CANVASS USER','BOQ'))): ?>
                                           <li><a class="<?php  ?>" href="<?php echo base_url().index_page() ?>/boq/report"><span>BOQ Monitoring</span></a></li>
                                           <li><a class="" href="<?php echo base_url().index_page(); ?>/boq/"><span>BOQ Entry</span></a></li>
                                        <?php endif; ?>
                                           <?php if($this->lib_auth->restriction('ADMIN')): ?>                                           
                                           <li><a class="<?php  ?>" href="<?php echo base_url().index_page() ?>/boq/projects"><span>BOQ Projects</span></a></li>
                                           <li><a class="<?php  ?>" href="<?php echo base_url().index_page() ?>/boq/cost_entry"><span>Cost Entry</span></a></li>
                                           <?php endif; ?>
                                        </ul>
                                    </li> -->
                                <?php endif; ?>
                                    <li class="">
                                        <a href="javascript:void(0);" class="<?php  ?>">
                                           <i class="fa fa-clipboard"></i>
                                           <span>BOQ Monitoring (New)</span> <span class="pull-right"><i class="fa fa-angle-down"></i></span>
                                        </a>
                                        <ul style="<?php  ?>">
                                            <?php
                                                if($this->lib_auth->restriction(array('ADMIN'))){
                                            ?>
                                            <li><a class="<?php  ?>" href="<?php echo base_url().index_page() ?>/boq/upload"><span>BOQ Upload</span></a></li>
                                            <li><a class="<?php  ?>" href="<?php echo base_url().index_page() ?>/boq/projects_new"><span>BOQ Projects (New)</span></a></li>
                                            <?php
                                                }
                                            ?>
                                            <li><a class="<?php  ?>" href="<?php echo base_url().index_page() ?>/boq/boq_view"><span>BOQ View</span></a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url().index_page() ?>/transaction_list/job_order" class="">
                                            <i class="fa fa-comment"></i>
                                            <span>Job Order</span>
                                        </a>
                                    </li>
                                </ul>   
                            </div>
                        </div>
                </div>                
                <?php echo $template['body']; ?> 
    </div>
    <div id="dialog" style="display:none">
        
    </div>
</body>
<script>	 
        var resizefunc = [];

        $(function(){
                                
                $('#sidebar-btnview').on('click',function(){
                    var project_id  = $('#sidebar-inventory-location option:selected').val();
                    window.location = "<?php echo base_url().index_page(); ?>/transaction_list/inventory/"+project_id+"";
                });

				// LEFT SIDE MAIN NAVIGATION
				$("#sidebar-menu a").on('click',function(e){

				  if(!$("#wrapper").hasClass("enlarged")){
				    if($(this).parent().hasClass("has_sub")){
				      e.preventDefault();
				    }   

				    if(!$(this).hasClass("subdrop")){
				      // hide any open menus and remove all other classes
				      $("ul",$(this).parents("ul:first")).slideUp(350);
				      $("a",$(this).parents("ul:first")).removeClass("subdrop");
				      $("#sidebar-menu .pull-right i").removeClass("fa-angle-up").addClass("fa-angle-down");
				      
				      // open our new menu and add the open class
				      $(this).next("ul").slideDown(350);
				      $(this).addClass("subdrop");
				      $(".pull-right i",$(this).parents(".has_sub:last")).removeClass("fa-angle-down").addClass("fa-angle-up");
				      $(".pull-right i",$(this).siblings("ul")).removeClass("fa-angle-up").addClass("fa-angle-down");
				    }else if($(this).hasClass("subdrop")) {
				      $(this).removeClass("subdrop");
				      $(this).next("ul").slideUp(350);
				      $(".pull-right i",$(this).parent()).removeClass("fa-angle-up").addClass("fa-angle-down");
				      //$(".pull-right i",$(this).parents("ul:eq(1)")).removeClass("fa-chevron-down").addClass("fa-chevron-left");
				    }
				  }

				});

				// NAVIGATION HIGHLIGHT & OPEN PARENT
				$("#sidebar-menu ul li.has_sub a.active").parents("li:last").children("a:first").addClass("active").trigger("click");

				$(".open-left").click(function(e){
					e.stopPropagation();
				    $("#wrapper").toggleClass("enlarged");
				    $("#wrapper").addClass("forced");

				    if($("#wrapper").hasClass("enlarged") && $("body").hasClass("fixed-left")){
				    	$("body").removeClass("fixed-left").addClass("fixed-left-void");
				    }else if(!$("#wrapper").hasClass("enlarged") && $("body").hasClass("fixed-left-void")){
				    	$("body").removeClass("fixed-left-void").addClass("fixed-left");
				    }

				    $(".left ul").removeAttr("style");
				    toggle_slimscroll(".slimscrollleft");
				    $("body").trigger("resize");
				});

                if(jQuery.browser.mobile !== true){
                    //SLIM SCROLL
                    $('.slimscroller').slimscroll({
                      height: 'auto',
                      size: "5px"
                    });
                    
                    $('.slimscrollleft').slimScroll({
                        height: 'auto',
                        position: 'left',
                        size: "5px",
                        color: '#7A868F'
                    });
                }
            onResize();
		});

        

		function toggle_slimscroll(item){
		    if($("#wrapper").hasClass("enlarged")){
		      $(item).css("overflow","inherit").parent().css("overflow","inherit");
		      $(item). siblings(".slimScrollBar").css("visibility","hidden");
		    }else{
		      $(item).css("overflow","hidden").parent().css("overflow","hidden");
		      $(item). siblings(".slimScrollBar").css("visibility","visible");
		    }
		}

        onResize = function(){
                        
            if($(window).width() < 520)
            {
                $('body').addClass('mobile');
                 if($('.right-menu').length > 0)
                {
                    
                    var combine_width = $('.left.side-menu').width() +  $('.center-menu').width() +  $('.right-menu').width();                            
                    var result = ($(window).width() - combine_width) - 10;
                    if(result>0){
                        $('.right-menu').css({'right' :result+'px'});    
                    }
                    
                    if($('.right-menu').html().length > 2){
                        $('.center-menu').css({'display':'none'});
                    }else{
                        $('.center-menu').removeAttr('style');
                    }
                    
                }
            }
                    
        };

        
        $(window).bind('resize',onResize);

</script>

</html>