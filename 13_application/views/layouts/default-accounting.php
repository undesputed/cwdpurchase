
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
<link href="<?php echo base_url();?>asset/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url();?>asset/css/select2.min.css" rel="stylesheet" media="screen">

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
<script type="text/javascript" src="<?php echo base_url();?>asset/js/jquery.jeditable.datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/select2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/typeahead/dist/typeahead.bundle.min.js"></script>

<script>
	$(function(){
		/* $("html").niceScroll(); */    
	    var searchTemplate = "<div class='search-sieve'><div class='search-group'><input type='text' placeholder='' class='form-control'> <span> <i class='fa fa-search'></i></span></div></div>"
	    /*   $(".table-sieve").sieve({ searchTemplate: searchTemplate }); */
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
                                    <?php $privileges = $this->session->userdata('privileges'); ?>
                                    <?php if($this->lib_auth->restriction('ADMIN') || in_array('voucher',$privileges)): ?>
                                    <li class="">
                                        <a href="<?php echo base_url().index_page(); ?>/transaction_list/purchase_request/incoming"><strong>ERP</strong></a>
                                    </li>
                                    <?php endif; ?>
                                    <li class="">
                                        <a href="javascript:void(0)"><strong class="label label-info">ACCOUNTING</strong></a>
                                    </li>                                   
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
                                            <li><a href="<?php echo base_url().index_page() ?>/transaction/create/pr">Purchase Requisition</a></li>                                         
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
                    
				?>
                
                <div class="left side-menu">
                        <div class="sidebar-inner slimscrollleft">
                            <div id="sidebar-menu" class="">
                                <ul>

                                    <?php if($this->lib_auth->restriction('ACCOUNTANT')): ?>
                                    <li class="">
                                        <a href="javascript:void(0);" class=""><i class="fa fa-gears"></i> <span>Setup</span> <span class="pull-right "><i class="fa fa-angle-down"></i></span></a>
                                            <ul style="">

                                                <li><a class="" href="<?php echo base_url().index_page();?>/setup/chart_of_account"><span>Chart of Account</span></a></li>
                                                <li><a class="" href="<?php echo base_url().index_page();?>/setup/subsidiary_setup"><span>Subsidiary Ledger Setup</span></a></li>
                                                <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/bank_setup"><span>Bank Setup</span></a></li>
                                                <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/customer_setup"><span>Customer Setup</span></a></li>
                                                <!-- <li><a class="" href="<?php echo base_url().index_page();?>/setup/asset_setup"><span>Asset Setup</span></a></li> -->
                                                <!-- <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/check_number"><span>Check No. Setup</span></a></li>
                                                <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/check_voucher"><span>Voucher No. Setup</span></a></li>     -->                                            
                                            </ul>
                                    </li>
                                    <?php endif; ?> 
                                    
                                    <?php if($this->lib_auth->restriction('ACCOUNTANT') || $this->lib_auth->restriction('CANVASS USER')): ?>
                                    <li class="">
                                        <a href="javascript:void(0);" class=""><i class="fa fa-pencil-square-o"></i> <span>Accounting Entry</span> <span class="pull-right "><i class="fa fa-angle-down"></i></span></a>
                                            <ul style="">                                                
                                                <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/journal_entry"><span>Journal Entry</span></a></li>
                                                <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/journal_report"><span>Journal Posting</span></a></li>
                                                <!-- <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/billing_statement"><span>Billing Statement</span></a></li> -->
                                            </ul>
                                    </li>
                                    <?php endif; ?>


                                    <?php if($this->lib_auth->restriction('CANVASS USER') || in_array('voucher',$privileges)): ?>                                    
                                    <li class="">
                                            <a href="javascript:void(0);" class=""><i class="fa fa-exchange"></i> <span>Voucher</span> <span class="pull-right "><i class="fa fa-angle-down"></i></span></a>
                                            <ul style="">
                                              <!--   <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/cash_voucher"><span>Cash Voucher</span></a></li> -->
                                                <li><a class="" href="<?php echo base_url().index_page(); ?>/accounting/voucher"><span>Disbursement Voucher</span></a></li>
                                                <li><a class="" href="<?php echo base_url().index_page(); ?>/accounting/voucher_summary"><span>Voucher Summary</span></a></li>
                                            </ul>
                                    </li>
                                    <?php endif; ?>

                                    <?php if($this->lib_auth->restriction('CANVASS USER')): ?>
                                    <li class="">
                                        <a href="javascript:void(0);" class=""><i class="fa fa-credit-card"></i> <span>Payables</span> <span class="pull-right "><i class="fa fa-angle-down"></i></span></a>
                                        <ul class="">
                                             <!-- <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/payable_entry"><span>Payable Entry</span></a></li> -->
                                             <!-- <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/enter_payable"><span>Enter Payable Account</span></a></li> -->
                                             <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/payable_list"><span>Payables Report</span></a></li>
                                             <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/payable_balance"><span>Payable Balance</span></a></li>
                                             <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/ap_aging"><span>AP Aging</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="">
                                        <a href="javascript:void(0);" class=""><i class="fa fa-credit-card"></i><span>Receivables</span> <span class="pull-right "><i class="fa fa-angle-down"></i></span></a>
                                        <ul class="">
                                             <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/progress_billing"><span>Progress Billing Entry</span></a></li>
                                             <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/Progress_billing_summary"><span>Progress Billing Summary</span></a></li>
                                        </ul>
                                    </li>
                                    <?php endif; ?>  

                                  

        
                                    <?php if($this->lib_auth->restriction('CANVASS USER')): ?>
                                    <li class="">
                                        <a href="<?php echo base_url().index_page();?>/accounting_entry/project_expenses" class=""><i class="fa fa-exchange"></i> <span>Project Expenses</span> <span class="pull-right"></span></a>                                        
                                    </li>
                                    <?php endif; ?>  

                                    <?php if($this->lib_auth->restriction('ACCOUNTANT')): ?>
                                    <li class="">
                                        <a class="" href="<?php echo base_url().index_page();?>/accounting_entry/journal_view"><i class="fa fa-eye"></i><span> View Journal</span></a>                                      
                                    </li>
                                    <?php endif; ?>
                                    <?php if($this->lib_auth->restriction('CANVASS USER')): ?>
                                    <li class="">
                                         <a class="" href="<?php echo base_url().index_page();?>/accounting_entry/payroll_entry"><i class="fa fa-eye"></i><span>Payroll Entry</span></a>
                                    </li>
                                    <?php endif; ?>
                                    <?php  ?>
                                    <?php if($this->lib_auth->restriction('ACCOUNTANT') || $this->lib_auth->restriction('CANVASS USER')): ?>
                                    <li class="">
                                        <a href="javascript:void(0);" class=""><i class="fa fa-gears"></i> <span>Financial Reports</span> <span class="pull-right "><i class="fa fa-angle-down"></i></span></a>
                                            <ul style="">                                              
                                                <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/trial_balance"><span>Trial Balance</span></a></li>
                                                <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/balance_sheet"><span>Balance Sheet</span></a></li>                                                
                                                <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/income_statement"><span>Income Statement</span></a></li>
                                                <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/cashflow"><span>Statement of Cash Flow</span></a></li>
                                                <li><a class="" href="<?php echo base_url().index_page();?>/accounting_entry/change_in_equity"><span>Statement of Equity</span></a></li>                                                
                                            </ul>
                                    </li>
                                    <?php endif; ?>
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
				    }else if($(this).hasClass("subdrop")){
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

        onResize = function(){
            
            /*console.log($(window).height() + "height");*/
            
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

		function toggle_slimscroll(item){
		    if($("#wrapper").hasClass("enlarged")){
		      $(item).css("overflow","inherit").parent().css("overflow","inherit");
		      $(item). siblings(".slimScrollBar").css("visibility","hidden");
		    }else{
		      $(item).css("overflow","hidden").parent().css("overflow","hidden");
		      $(item). siblings(".slimScrollBar").css("visibility","visible");
		    }
		}


</script>
</html>