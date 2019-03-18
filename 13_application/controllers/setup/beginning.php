<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Beginning extends CI_Controller {

	public function __construct(){

		parent :: __construct();		
		$this->load->model('setup/md_item_setup');
		$this->load->model('inventory/md_stock_inventory');

	}
	
	public function index(){
				
		$data['item_list']  = $this->md_item_setup->get_items();

		$this->lib_auth->title = "Beginning Inventory";		
		$this->lib_auth->build = "setup/beginning/index";		
		$this->lib_auth->render($data);

	}

	public function get_data(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['inventory_list'] = $this->md_stock_inventory->get_beginning();
		$this->load->view('setup/beginning/cumulative',$data);

	}

	public function save_item(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_item_setup->save_beginning();
	}


	public function item_group(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		$this->load->view('setup/item_setup/item_group');
	}

	public function save_item_group(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		echo $this->md_item_setup->save_item_group($arg);

	}



}