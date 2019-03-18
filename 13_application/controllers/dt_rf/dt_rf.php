<?php defined('BASEPATH') OR exit('No direct script access allowed');

class dt_rf extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('map/md_map');	
	}

	public function index(){

		$this->lib_auth->title = "Delivery ticket & Rf";		
		$this->lib_auth->build = "dt_rf/index";

		$this->lib_auth->render();
		
	}

	public function get_delivery_tickets(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();

		$result = $this->md_map->get_delivery_ticket($arg);
		$data['data'] = $result;
		$this->load->view('dt_rf/tbL_delivery_ticket',$data);
		
	}

	
}