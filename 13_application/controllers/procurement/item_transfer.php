<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Item_transfer extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('procurement/md_item_transfer');			
	}

	public function index(){

		redirect(base_url(),'refresh');
	}

	public function save(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		echo  $this->md_item_transfer->save();

	}
	public function save_request(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		echo  $this->md_item_request->save_request();
	}

	
	public function get_list(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$data['result'] = $this->md_item_request->get_list();
		$this->load->view('procurement/transaction_list/request_list',$data);

	}





}
	