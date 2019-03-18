<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('item/md_itemmonitoring');
			
	}

	public function index(){
		$this->lib_auth->title = "Item Monitoring";		
		$this->lib_auth->build = "item/monitoring";		
		$data['project'] = $this->md_project->get_profit_center();
		$this->lib_auth->render($data);		
	}
	
	public function get_item(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}



		$arg = $this->input->post();

		$data['pr_items'] = $this->md_itemmonitoring->get_item($arg);
		$this->load->view('item/item_list',$data);

	}

		

}