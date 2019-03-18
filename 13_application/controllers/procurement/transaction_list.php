<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_list extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('procurement/md_purchase_request');
		$this->load->model('procurement/md_transaction_history');
		$this->load->model('procurement/md_purchase_order');
		$this->load->model('procurement/md_canvass_sheet');		
		$this->load->model('procurement/md_stock_availability');
		$this->load->model('inventory/md_stock_withdrawal');
		
		$this->load->model('inventory/md_stock_inventory');

		$this->load->model('procurement/md_item_issuance');
		$this->load->model('procurement/md_item_transfer');
		
		$this->load->model('procurement/md_material_transfer');
		
		$this->load->model('md_project');
		
		$this->load->model('procurement/md_event_logs');
		
	}
		
	public function _remap($method,$arg = array()){		
		if (method_exists($this, $method))
	    {
	        $this->$method($arg);
	    }
	    else
	    {
	        $this->default_method();
	    }
	    
	}

	public function default_method(){		
		redirect(base_url());
	}

	public function purchase_request($arg = array()){

		$data['trans_main'] = '';		

		$action = array(
			'cmd'=>'outgoing',
			'track_no'=>''
		);

		if(isset($arg[0])){
			$action['cmd'] = $arg[0];
		}

		switch($action['cmd']){
			case "outgoing":
				/*$result = $this->md_purchase_request->get_all_pr_out();*/
			break;
			case "incoming":
				/*$result = $this->md_purchase_request->get_all_pr_in();*/
			break;
			default:
				redirect(base_url());
			break;
		}
	

		/*
		$date = array();
		$value = array();
		foreach($result as $row){
			$d = $this->extra->format_date($row['purchaseDate']);
			$value[$d][] = $row;
		}
		$data['pr_list'] = $value;
		*/


		 $segment = $this->uri->segment(3);
		   if($segment == 'outgoing'){
		   	$data['view'] =	$this->load->view('procurement/transaction_list/purchase_request_outgoing','',true); 	
		   }else{
			$data['view'] =	$this->load->view('procurement/transaction_list/purchase_request_incoming','',true);
		   }				   

		$this->lib_auth->title = "Transaction List : Purchase Requst";
		$this->lib_auth->build = "procurement/transaction_list/sidebar";
		$this->lib_auth->render($data);
		
	}


	public function get_pr_incoming(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];
		$data['filter'] = (!isset($_GET['filter'])) ? 'all' : $_GET['filter'];
		$data['search']	= (!isset($_GET['search'])) ? '' : $this->input->get('search');

		$result = $this->md_purchase_request->get_all_pr_in($page,$data);

		$date = array();
		$value = array();
		foreach($result['data'] as $row){
			$d = $this->extra->format_date($row['purchaseDate']);
			$value[$d][] = $row;
		}
		
		$data['pr_list'] = $value;
		$data['next'] = $result['next'];
		$data['search_url']	= (!isset($data['search'])) ? '' : '&search='.$data['search'];
		echo $this->load->view('procurement/scroll_load/pr_incoming',$data,true);
		
	}

	public function get_pr_outgoing(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];
		$data['filter'] = (!isset($_GET['filter'])) ? 'all' : $_GET['filter'];
		$data['search']	= (!isset($_GET['search'])) ? '' : $this->input->get('search');
		$result = $this->md_purchase_request->get_all_pr_out($page,$data);

		$date = array();
		$value = array();
		foreach($result['data'] as $row){
			$d = $this->extra->format_date($row['purchaseDate']);
			$value[$d][] = $row;
		}
		
		$data['pr_list'] = $value;
		$data['next'] = $result['next'];
		$data['search_url']	= (!isset($data['search'])) ? '' : '&search='.$data['search'];
		echo $this->load->view('procurement/scroll_load/pr_outgoing',$data,true);

	}
	

	public function canvass_sheet($arg = array()){

		if(empty($arg)){
			redirect(base_url().index_page().'/transaction_list/canvass_sheet/for_canvass');
		}
		
		$data['_active'] = '';


		switch($arg[0]){
			case "for_canvass":

				/*$result = $this->md_canvass_sheet->for_canvass();*/

				/*$result = $this->md_canvass_sheet->get_canvassMain();*/
				/*$date   = array();
				$value  = array();
				
				foreach($result as $row){
					$d = $this->extra->format_date($row['purchaseDate']);
					$value[$d][] = $row;
				}

				$data['main_list'] = $value;		
				
				if(!empty($arg[1])){
					$data['_active'] = $arg[1];
				}*/

				$data['view'] = $this->load->view("procurement/transaction_list/for_canvass",'',true);

			break;			
			case "for_approval":

				/*	
				$result = $this->md_canvass_sheet->get_canvassMain();		
				$date   = array();
				$value  = array();
				
				foreach($result as $row){
					$d = $this->extra->format_date($row['c_date']);
					$value[$d][] = $row;
				}

				$data['main_list'] = $value;
				
				if(!empty($arg[1])){
					$data['_active'] = $arg[1];
				}*/
				
				$data['view'] = $this->load->view("procurement/transaction_list/canvass_sheet","",true);

			break;
		}

		$this->lib_auth->title = "Transaction List : Canvass Sheet";
		$this->lib_auth->build = "procurement/transaction_list/sidebar";
		$this->lib_auth->render($data);

	}

	public function get_canvass_forcanvass(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];
		/*$result = $this->md_purchase_request->get_all_pr_out($page);*/
		$data['filter'] = (!isset($_GET['filter'])) ? 'all' : $_GET['filter'];
		$data['search']	= (!isset($_GET['search'])) ? '' : $this->input->get('search');

		$result = $this->md_canvass_sheet->for_canvass($page,$data);

		$date   = array();
		$value  = array();
		
		foreach($result['data'] as $row){
			$d = $this->extra->format_date($row['purchaseDate']);
			$value[$d][] = $row;
		}

		$data['main_list'] = $value;		
		$data['next']    = $result['next'];
		$data['search_url']	= (!isset($data['search'])) ? '' : '&search='.$data['search'];
		$this->load->view('procurement/scroll_load/cv_forcanvass',$data);

	}

	public function get_canvass_forapproval(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];
		$data['filter'] = (!isset($_GET['filter'])) ? 'all' : $_GET['filter'];
		$data['search']	= (!isset($_GET['search'])) ? '' : $this->input->get('search');
		
		$result = $this->md_canvass_sheet->get_canvassMain($page,$data);

		$date   = array();
		$value  = array();
		
		foreach($result['data'] as $row){
				$d = $this->extra->format_date($row['c_date']);
				$value[$d][] = $row;
			}

		$data['main_list']  = $value;
		$data['next']       = $result['next'];
		$data['search_url']	= (!isset($data['search'])) ? '' : '&search='.$data['search'];
		echo $this->load->view('procurement/scroll_load/cv_forapproval',$data,true);

	}


	public function purchase_order($arg = array()){
		if(empty($arg)){
			redirect(base_url().index_page().'/transaction_list/purchase_order/for_po');
		}

		$data['_active'] = '';
		if(!empty($arg[1])){
			$data['_active'] = $arg[1];
		}


		switch($arg[0]){

			case "for_po":
					/*
					$result = $this->md_purchase_order->for_po();		
					$date   = array();
					$value  = array();	
					foreach($result as $row){
					
						$d = $this->extra->format_date($row['c_date']);
						$value[$d][] = $row;
					}

					$data['main_list'] = $value;		
					
					if(!empty($arg[1])){
						$data['_active'] = $arg[1];
					}		*/

					$data['view'] = $this->load->view("procurement/transaction_list/for_po","",true);

			break;

			case "for_approval":

					/*
					$result = $this->md_purchase_order->get_all_po2();
					$date = array();
					$value = array();

					foreach($result as $row){
						$d = $this->extra->format_date($row['po_date']);
						$value[$d][] = $row;
					}
					$data['po_list'] = $value;
					*/

					$data['view'] = $this->load->view("procurement/transaction_list/purchase_order",'',true);


			break;

			case "for_printing":
					$data['view'] = $this->load->view("procurement/transaction_list/for_printing",'',true);
			break;

		}

		$this->lib_auth->title = "Transaction List : Purchase Order";
		$this->lib_auth->build = "procurement/transaction_list/sidebar";
		$this->lib_auth->render($data);

	}

	public function get_po_forpo(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];
		$data['filter'] = (!isset($_GET['filter'])) ? 'all' : $_GET['filter'];
		$data['search']	= (!isset($_GET['search'])) ? '' : $this->input->get('search');
		$result = $this->md_purchase_order->for_po($page,$data);	

		$date   = array();
		$value  = array();
		
		foreach($result['data'] as $row){
				$d = $this->extra->format_date($row['c_date']);
				$value[$d][] = $row;
			}

		$data['main_list'] = $value;
		$data['next']    = $result['next'];
		$data['search_url']	= (!isset($data['search'])) ? '' : '&search='.$data['search'];
		echo $this->load->view('procurement/scroll_load/po_forpo',$data,true);
		
	}

	public function get_po_forapproval(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = '1';	

		if(!isset($_GET['p'])){
			$page = '1';
		}else{
			if($_GET['p'] == '1?'){
				$page = '1';
			}else{
				$page = $_GET['p'];
			}
		}
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];

		$data['filter'] = (!isset($_GET['filter'])) ? 'all' : $_GET['filter'];
		$data['search']	= (!isset($_GET['search'])) ? '' : $this->input->get('search');
		$data['search'] = "";		
		$result = $this->md_purchase_order->get_all_po2($page,$data);	

		$date   = array();
		$value  = array();
		
		foreach($result['data'] as $row){
				$d = $this->extra->format_date($row['po_date']);
				$value[$d][] = $row;
		}
		$data['po_list'] = $value;	
		$data['next']    = $result['next'];
		$data['search_url']	= (!isset($data['search'])) ? '' : '&search='.$data['search'];
		echo $this->load->view('procurement/scroll_load/po_forapproval',$data,true);


		
	}

	public function get_po_forprinting(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = '1';	

		if(!isset($_GET['p'])){
			$page = '1';
		}else{
			if($_GET['p'] == '1?'){
				$page = '1';
			}else{
				$page = $_GET['p'];
			}
		}
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];

		$data['filter'] = (!isset($_GET['filter'])) ? 'all' : $_GET['filter'];
		$data['search']	= (!isset($_GET['search'])) ? '' : $this->input->get('search');
		$data['search'] = "";		
		$result = $this->md_purchase_order->get_all_po2($page,$data);	

		$date   = array();
		$value  = array();
		
		foreach($result['data'] as $row){
				$d = $this->extra->format_date($row['po_date']);
				$value[$d][] = $row;
		}
		$data['po_list'] = $value;	
		$data['next']    = $result['next'];
		$data['search_url']	= (!isset($data['search'])) ? '' : '&search='.$data['search'];
		echo $this->load->view('procurement/scroll_load/po_forprinting',$data,true);


		
	}

	public function advance_search(){
		echo $this->load->view('procurement/scroll_load/po_advance_search');
	}

	public function get_po_forapproval_search(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$search = $this->input->post('search');

		$data['search']	= (!isset($search)) ? '' : $search;
		$result = $this->md_purchase_order->get_all_po_search($data);

		$date   = array();
		$value  = array();
		
		foreach($result['data'] as $row){
				$d = $this->extra->format_date($row['po_date']);
				$value[$d][] = $row;
		}
		$data['po_list'] = $value;
		$data['next']    = "";	
		echo $this->load->view('procurement/scroll_load/po_forapproval',$data,true);
	}




	public function receiving_report($arg = array()){

		$data['_active'] = '';
		if(!empty($arg[0])){
			$data['_active'] = $arg[0];
		}
		
		/*	
		$result = $this->md_purchase_order->get_all_approved_po();

				
		$date = array();
		$value = array();
		foreach($result as $row){
			$d = $this->extra->format_date($row['po_date']);
			$value[$d][] = $row;
		}*/		

		$data['view'] = $this->load->view("procurement/transaction_list/receiving_report","",true);
		$this->lib_auth->title = "Transaction List : Receiving Report";
		$this->lib_auth->build = "procurement/transaction_list/sidebar";
		$this->lib_auth->render($data);
		
	}

	public function direct_receiving($arg = array()){

		$data['_active'] = '';
		if(!empty($arg[0])){
			$data['_active'] = $arg[0];
		}	

		$data['view'] = $this->load->view("procurement/transaction_list/direct_receiving","",true);
		$this->lib_auth->title = "Transaction List : Direct Receiving List";
		$this->lib_auth->build = "procurement/transaction_list/sidebar";
		$this->lib_auth->render($data);
		
	}

	public function get_rr_list(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];
		$data['filter'] = (!isset($_GET['filter'])) ? 'all' : $_GET['filter'];
		$data['search']	= (!isset($_GET['search'])) ? '' : $this->input->get('search');
		$result = $this->md_purchase_order->get_all_approved_po($page,$data);

		$date   = array();
		$value  = array();
		
		foreach($result['data'] as $row){
				$d = $this->extra->format_date($row['po_date']);
				$value[$d][] = $row;
		}
		$data['po_list'] = $value;	
		$data['next']    = $result['next'];
		$data['search_url']	= (empty($data['search'])) ? '' : '&search='.$data['search'];
		echo $this->load->view('procurement/scroll_load/rr_list',$data,true);

	}

	public function get_receiving_list(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];
		$data['filter'] = (!isset($_GET['filter'])) ? 'all' : $_GET['filter'];
		$data['search']	= (!isset($_GET['search'])) ? '' : $this->input->get('search');
		$result = $this->md_purchase_request->get_receiving_list($page,$data);

		$date   = array();
		$value  = array();
		
		foreach($result['data'] as $row){
				$d = $this->extra->format_date($row['date_received']);
				$value[$d][] = $row;
		}
		$data['po_list'] = $value;	
		$data['next']    = $result['next'];
		$data['search_url']	= (empty($data['search'])) ? '' : '&search='.$data['search'];
		echo $this->load->view('procurement/scroll_load/directreceiving_list',$data,true);

	}

	public function returns($arg = array()){

		$data['_active'] = '';
		if(!empty($arg[0])){
			$data['_active'] = $arg[0];
		}	

		$data['view'] = $this->load->view("procurement/transaction_list/return","",true);
		$this->lib_auth->title = "Transaction List : Return List";
		$this->lib_auth->build = "procurement/transaction_list/sidebar";
		$this->lib_auth->render($data);
		
	}

	public function get_return_list(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];
		$data['filter'] = (!isset($_GET['filter'])) ? 'all' : $_GET['filter'];
		$data['search']	= (!isset($_GET['search'])) ? '' : $this->input->get('search');
		$result = $this->md_purchase_request->get_return_list($page,$data);

		$date   = array();
		$value  = array();
		
		foreach($result['data'] as $row){
				$d = $this->extra->format_date($row['date_received']);
				$value[$d][] = $row;
		}
		$data['po_list'] = $value;	
		$data['next']    = $result['next'];
		$data['search_url']	= (empty($data['search'])) ? '' : '&search='.$data['search'];
		echo $this->load->view('procurement/scroll_load/return_list',$data,true);

	}

	public function inventory($arg = array()){
		$data['_active'] = '';
		if(!empty($arg[0])){
			$data['_active'] = $arg[0];
		}else{
			$project_code = $this->session->userdata('Proj_Code');

			redirect(base_url().index_page()."/transaction_list/inventory/".$project_code);

			if(!empty($arg[1])){
				redirect(base_url().index_page()."/transaction_list/inventory/".$project_code."/".$arg[1]);
			}
		}


		$data['item_list'] = $this->md_stock_inventory->get_inventory($arg);
		$data['item_category'] = json_encode($this->md_stock_inventory->get_inventory_power($arg));
		$data['main_data'] = '';
		if(!empty($this->uri->segment(4))){
			$data['main_data'] = $this->md_stock_inventory->get_details($this->uri->segment(4),$this->uri->segment(3),'1');
		}

		$data['view'] = $this->load->view("procurement/transaction_list/inventory",$data,true);
		$this->lib_auth->title = "Transaction List : Inventory";
		$this->lib_auth->build = "procurement/transaction_list/sidebar-no-1";
		$this->lib_auth->render($data);
		
	}

	public function get_inventory_item(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['location'] = $this->input->post('location');
		$data['itemNo']   = $this->input->post('item_no');
		$group_id = $this->input->post('group_id');
		$data['item_list'] = $this->md_stock_inventory->get_inventory_items($data['location'],$group_id);

		$this->load->view('procurement/transaction_list/inventory_item',$data);

	}


	public function withdrawal($arg = array()){

		$data['_active'] = '';
		if(!empty($arg[0])){
			$data['_active'] = $arg[0];
		}else{
			$project_code = $this->session->userdata('Proj_Code');
			redirect(base_url().index_page()."/transaction_list/withdrawal/".$project_code);
		}
			
		$data['item_list'] = $this->md_stock_inventory->get_inventory($arg[0]);		
		$data['item_category'] = json_encode($this->md_stock_inventory->get_inventory_power($arg[0]));

		$data['view'] = $this->load->view("procurement/transaction_list/inv_withdrawal_main",$data,true);
		$this->lib_auth->title = "Transaction List : Inventory";
		$this->lib_auth->build = "procurement/transaction_list/sidebar-no";
		$this->lib_auth->render($data);		

	}


	public function issuance($arg = array()){

		$data['_active'] = '';
		if(!empty($arg[0])){
			$data['_active'] = $arg[0];
		}else{
			$project_code = $this->session->userdata('Proj_Code');
			redirect(base_url().index_page()."/transaction_list/issuance/".$project_code);
		}
			
		$data['item_list'] = $this->md_stock_inventory->get_inventory($arg[0]);		
		$data['item_category'] = json_encode($this->md_item_issuance->get_all_item_issuance_power($arg[0]));

		$data['view'] = $this->load->view("procurement/transaction_list/inv_issuance_main",$data,true);
		$this->lib_auth->title = "Transaction List : Inventory";
		$this->lib_auth->build = "procurement/transaction_list/sidebar-no";
		$this->lib_auth->render($data);
		

	}

	public function get_issuance_item(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['location']  = $this->input->post('location');
		$data['itemNo']    = $this->input->post('item_no');
		$group_id = $this->input->post('group_id');
		$data['item_list'] = $this->md_item_issuance->get_all_item_issuance($data['location'],$group_id);
		$this->load->view('procurement/transaction_list/inv_issuance_item',$data);

	}





	public function get_withdrawal_item(){		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['location']  = $this->input->post('location');
		$data['itemNo']    = $this->input->post('item_no');
		$group_id = $this->input->post('group_id');
		$data['item_list'] = $this->md_stock_inventory->get_inventory_items($data['location'],$group_id);
		$this->load->view('procurement/transaction_list/inv_withdrawal_item',$data);

	}

	
	public function item_withdrawal($arg){

		$data['_active'] = '';
		if(!empty($arg[0])){
			$data['_active'] = $arg[0];
		}


		/*
		$result = $this->md_stock_withdrawal->get_withdraw_main();
			
		$date  = array();
		$value = array();
		foreach($result as $row){
			$d = $this->extra->format_date($row['WS DATE']);
			$value[$d][] = $row;
		}

		$data['main_list'] = $value;
		*/

		$data['view'] = $this->load->view("procurement/transaction_list/item_withdrawal",'',true);
		$this->lib_auth->title = "Item Withdrawal : Transaction List";
		$this->lib_auth->build = "procurement/transaction_list/sidebar";
		$this->lib_auth->render($data);
		

	}



	public function get_itemwithdrawal_list(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];		
		$result = $this->md_stock_withdrawal->get_withdraw_main($page);

		$date  = array();
		$value = array();
		foreach($result['data'] as $row){
			$d = $this->extra->format_date($row['WS DATE']);
			$value[$d][] = $row;
		}

		$data['main_list'] = $value;
		$data['next']    = $result['next'];
		echo $this->load->view('procurement/scroll_load/ws_item_withdraw',$data,true);
		
	}



	public function item_issuance(){
		
		$data['_active'] = '';
		if(!empty($arg[0])){
			$data['_active'] = $arg[0];
		}


		/*		
		$result = $this->md_item_issuance->get_issuance_list();
		$date = array();
		$value = array();
		foreach($result as $row){
			$d = $this->extra->format_date($row['date_issued']);
			$value[$d][] = $row;
		}
		$data['main_list'] = $value;
		*/

	
		$data['view'] = $this->load->view("procurement/transaction_list/item_issuance","",true);
		$this->lib_auth->title = "Item Issuance : Transaction List";
		$this->lib_auth->build = "procurement/transaction_list/sidebar";
		$this->lib_auth->render($data);

	}
	
	public function update_issuance(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		$arg = $this->input->post();
		$this->md_item_issuance->update($arg);

		$event['type']    = 'Issuance';
		$event['transaction_no'] = $arg['is_no'];
		$event['transaction_id'] = $arg['is_id'];
		$event['remarks'] = '';
		$event['action']  = 'EDIT';

		echo $this->md_event_logs->create($event);		
	}


	public function get_item_issuance_list(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];

		$result = $this->md_item_issuance->get_issuance_list($page);

				$date = array();
		$value = array();
		foreach($result['data'] as $row){
			$d = $this->extra->format_date($row['date_issued']);
			$value[$d][] = $row;
		}

		$data['main_list'] = $value;		
		$data['next']    = $result['next'];
		echo $this->load->view('procurement/scroll_load/is_item_issuance',$data,true);
		
	}






	public function item_transfer($arg = array()){
		if(empty($arg)){
			redirect(base_url().index_page().'/transaction_list/item_transfer_info');
		}

		$data['_active'] = '';
		if(!empty($arg[1])){
			$data['_active'] = $arg[1];
		}

	/*	$item_list = $this->md_stock_withdrawal->get_item_withdrawal();

		$item_content = array();
		$item_value   = array();
		$cnt = 0;
		
		foreach($item_list as $row){
			$item_value[$row['item_no']]   = $row['item_description'];			
			$item_content[$row['item_no']] = array(
						'item_name'=>$row['item_description'],
						'unit_measure'=>$row['item_measurement'],
						'stocks'=>$row['Quantity at Hand'],
						'item_cost'=>$row['item_cost'],
						'inventory_id'=>$row['inventory_id'],
						);
		}

		$data['item_value']   = json_encode($item_value);
		$data['item_content'] = json_encode($item_content);*/

		
		switch($arg[0]){
			case "item_request":
								
				$result = $this->md_item_request->get_item_request();
				
				$date = array();
				$value = array();
				foreach($result as $row){
					$d = $this->extra->format_date($row['transaction_date']);
					$value[$d][] = $row;
				}

				$data['main_list'] = $value;				
				$data['view'] = $this->load->view("procurement/transaction_list/item_request",$data,true);
				

			break;
			case "request":


				$result = $this->md_item_transfer->get_transfer_list();
				
				$date = array();
				$value = array();
				foreach($result as $row){
					$d = $this->extra->format_date($row['transaction_date']);
					$value[$d][] = $row;
				}

				$data['main_list'] = $value;				
				$data['view'] = $this->load->view("procurement/transaction_list/item_transfer_request",$data,true);
				

			break;

			case "for_receiving":

					$result = $this->md_item_transfer->get_receive_item();
					
					$date = array();
					$value = array();
					foreach($result as $row){
						$d = $this->extra->format_date($row['transaction_date']);
						$value[$d][] = $row;
					}

					$data['main_list'] = $value;
					$data['view'] = $this->load->view("procurement/transaction_list/item_receive",$data,true);


			break;

		}
		
		$this->lib_auth->title = "Item Transfer : Transaction List ";
		$this->lib_auth->build = "procurement/transaction_list/sidebar-no-1";
		$this->lib_auth->render($data);

		

	}

	public function request_list(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$data['result'] = $this->md_item_request->get_list();
		$this->load->view('procurement/transaction_list/request_list',$data);
	}


	public function get_cash_voucher(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];
		$data['filter'] = (!isset($_GET['filter'])) ? 'all' : $_GET['filter'];
		$data['search']	= (!isset($_GET['search'])) ? '' : $this->input->get('search');
		$result = $this->md_purchase_order->get_all_approved_po($page,$data);

		$date   = array();
		$value  = array();
		
		foreach($result['data'] as $row){
				$d = $this->extra->format_date($row['po_date']);
				$value[$d][] = $row;
		}
		$data['po_list'] = $value;	
		$data['next']    = $result['next'];
		$data['search_url']	= (empty($data['search'])) ? '' : '&search='.$data['search'];
		echo $this->load->view('procurement/scroll_load/cash_voucher',$data,true);

	}

	public function undelivered(){

		$data['project']       = $this->md_project->get_profit_center();
		$data['supplier']      = $this->md_project->get_supplier();		
		
		/*
			$data['bank_setup']    = $this->md_payable_list->get_bank_setup();
			$data['get_affiliate'] = $this->md_payable_list->get_affiliate();
		*/		

		$this->lib_auth->title = "Undelivered";
		$this->lib_auth->build = "procurement/undelivered/index";
		$this->lib_auth->render($data);

	}

	public function get_undelivered(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		$data['result'] = $this->md_received_purchase->get_undelivered($arg);
		$this->load->view('procurement/undelivered/cumulative',$data);

	}

	public function details_undelivered(){		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();

		$data['main_data'] = $this->md_purchase_order->get_po_main_po_id($arg['po_id']);
		$data['result']    = $this->md_purchase_order->get_po_details($arg['po_id']);		
		$data['rr_details'] = $this->md_received_purchase->get_po_received_id($arg['po_id']);
		$this->load->view('procurement/undelivered/info',$data);
		
	}

	public function get_voucher_list(){		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];
		$data['filter'] = (!isset($_GET['filter'])) ? 'all' : $_GET['filter'];
		$data['search']	= (!isset($_GET['search'])) ? '' : $this->input->get('search');
		$result = $this->md_voucher->get_voucher_cumulative($page,$data);
		
		$date   = array();
		$value  = array();
		
		foreach($result['data'] as $row){
				$d = $this->extra->format_date($row['voucher_date']);
				$value[$d][] = $row;
		}
		$data['po_list'] = $value;
		$data['next']    = $result['next'];
		$data['search_url']	= (empty($data['search'])) ? '' : '&search='.$data['search'];
		echo $this->load->view('procurement/scroll_load/cash_voucher',$data,true);

	}



	public function info(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->get();		
		switch($arg['method']){			
			case "purchase_request":
					switch($arg['type']){
						case "incoming":							

							$data['type'] = "incoming";
							$this->lib_transaction->pr_info($arg['value'],$data);

						break;
						case"outgoing":
							$data['type'] = "outgoing";
							$this->lib_transaction->pr_info($arg['value'],$data);							
						break;

						case "edit":							
							$this->lib_transaction->pr_info_edit($arg['value']);
						break;
					}
			break;

			case "canvass_sheet":
					switch($arg['type']){
						case "for_canvass":							
							$this->lib_transaction->pr_info_for_canvass($arg['value']);
						break;

						case "for_approval":
							$this->lib_transaction->canvass_info($arg['value']);
						break;						
					}
			break;


			case "purchase_order":
					switch($arg['type']){

						case "for_po":
							$this->lib_transaction->canvass_info_po($arg['value']);
						break;

						case "for_approval":
							$this->lib_transaction->po_info($arg['value']);
						break;

						case "for_printing":
							$this->lib_transaction->po_woprint_info($arg['value']);
						break;

						case "edit":
							$this->lib_transaction->po_info_edit($arg['value']);
						break;

					}

			break;

			case "receiving":
					switch($arg['type']){
						case "main":
							$this->lib_transaction->rr_info($arg['value']);
						break;
						
						case"additional":
							$this->lib_transaction->rr_info($arg['value'],'additional');
						break;

						case"edit":
							$this->lib_transaction->rr_edit($arg['value']);
						break;

						case "direct_receiving":
							$this->lib_transaction->drr_info($arg['value']);
						break;
					}
			break;

			case "item_withdrawal":

				switch($arg['type']){
					case "create":
						$this->lib_transaction->item_withdrawal();
					break;

					case "view":
						$this->lib_transaction->item_withdrawal_no($arg['value']);
					break;					
				}

			break;

			case "issuance":
				switch($arg['type']){
					case "create":
						$this->lib_transaction->item_issuance_create();
					break;
						
					case "view":
						$this->lib_transaction->item_issuance_no($arg['value']);
					break;
					
				}
			break;

			case "cash_voucher":			
				switch($arg['type']){
					case "main":
						$this->lib_transaction->cash_voucher($arg['value']);
					break;

					case"cash_voucher_info":
						$this->lib_transaction->cash_voucher_info($arg['value']);
					break;

					case"journal_voucher":
						$this->lib_transaction->journal_voucher_info($arg['value']);
					break;
						
					case"po_voucher_info":
						$this->lib_transaction->voucher_info($arg['value']);
					break;
					
					case"create":
						$this->lib_transaction->create_voucher();
					break;

					case "edit":
						$this->lib_transaction->edit_voucher($arg['value']);
					break;

					case "po_list":
						$this->lib_transaction->create_voucher();
					break;			
				}
			break;

			case "job_order":
					switch($arg['type']){
						case "job_order":							

							$data['type'] = "job_order";
							$this->lib_transaction->jo_info($arg['value'],$data);

						break;
				
					}
			break;

			case "return":
					switch($arg['type']){
						case "return":
							$this->lib_transaction->rx_info($arg['value']);
						break;
					}
			break;

		}

	}

	public function job_order($arg = array()){

		$data['trans_main'] = '';		


		$data['view'] =	$this->load->view('procurement/transaction_list/job_order','',true);				   

		$this->lib_auth->title = "Transaction List : Job Order";
		$this->lib_auth->build = "procurement/transaction_list/sidebar";
		$this->lib_auth->render($data);
		
	}

	public function get_job_order(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$page = (int) (!isset($_GET['p'])) ? 1 : $_GET['p'];
		$data['filter'] = (!isset($_GET['filter'])) ? 'all' : $_GET['filter'];
		$data['search']	= (!isset($_GET['search'])) ? '' : $this->input->get('search');

		$result = $this->md_purchase_request->get_job_order($page,$data);

		$date = array();
		$value = array();
		foreach($result['data'] as $row){
			$d = $this->extra->format_date($row['job_order_date']);
			$value[$d][] = $row;
		}
		
		$data['list'] = $value;
		$data['next'] = $result['next'];
		$data['search_url']	= (!isset($data['search'])) ? '' : '&search='.$data['search'];
		echo $this->load->view('procurement/scroll_load/job_order',$data,true);
		
	}
	
}
